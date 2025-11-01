<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\UserLogin;
use Session;
use App\Permit;
use App\Job;
use App\Division;
use Auth;
use App\Department;
use App\RenewPermit;
use Mail;
use Illuminate\Support\Facades\Crypt; // ✅ This is important

class SiloDailyInspectionController extends Controller
{


    public function index()
    {
        $fields = DB::table('silo_master')
            ->where('isactive', 1)
            ->orderBy('displayorder')
            ->get();
        $divs = DB::table('division_new')->get();
        $active_list = DB::table('vendor_silo')->where('status', 'approve')->where('return_status', 'approve')->get();
        return view('silo_daily_inspection', compact('fields', 'divs', 'active_list'));
    }
    public function index_silo_daily()
    {
        $id = Session::get('user_idSession');
        $divisions = Division::all();

        $query = DB::table('silo_inspection')
            ->leftJoin('divisions', 'silo_inspection.section_id', '=', 'divisions.id')
            ->leftJoin('division_new', 'silo_inspection.division_id', '=', 'division_new.id')

            ->select(
                'silo_inspection.*',
                'divisions.name as section',
                'division_new.name as division_name',
            )
            ->orderBy('silo_inspection.id', 'desc');

        // Apply filtering based on session conditions


        // $query->where('vendor_silo.created_by', Session::get('user_idSession'));


        $vms_lists = $query->get();

        // Encrypt ID for each item
        $vms_lists = $vms_lists->map(function ($item) {
            $item->enc_id = Crypt::encrypt($item->id);
            return $item;
        });

        return view('admin.vendor_silo.index_silo_daily', compact('divisions', 'id', 'vms_lists'));

    }

    public function store(Request $request)
    {
        $id = $request->id; // if present, update; else insert

        // Ensure folder exists
        $folder = public_path('documents/remarks_voice');
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $uid = 6; // TODO: replace with Auth::id() or Session user
        $datetime = date('YmdHis');

        // Fetch existing record if updating
        $existing = $id ? DB::table('silo_inspection')->where('id', $id)->first() : null;

        // Handle remarks_voice upload
        $remarks_voice = $existing->remarks_voice ?? null;
        if ($request->hasFile('remarks_voice')) {
            $file = $request->file('remarks_voice');
            $extension = strtolower($file->getClientOriginalExtension());
            $mime = $file->getMimeType();
            $allowedExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'];

            if (strpos($mime, 'audio/') === 0 || in_array($extension, $allowedExtensions)) {
                $filename = "{$uid}_{$datetime}_voice." . $extension;
                $file->move($folder, $filename);
                $remarks_voice = "documents/remarks_voice/" . $filename;
            } else {
                return back()->with('error', 'Invalid audio file type.');
            }
        }

        // Handle photo upload
        $photo = $existing->image ?? null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = strtolower($file->getClientOriginalExtension());
            $mime = $file->getMimeType();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (strpos($mime, 'image/') === 0 || in_array($extension, $allowedExtensions)) {
                $filename = "{$uid}_{$datetime}_photo." . $extension;
                $file->move($folder, $filename);
                $photo = "documents/remarks_voice/" . $filename;
            } else {
                return back()->with('error', 'Invalid image file type.');
            }
        }

        // Static fields
        $staticData = [
            'division_id' => $request->input('division_id'),
            'section_id' => $request->input('section_id'),
            'silo_tanke_id' => $request->input('silo_id'),
            'image' => $photo,
            'remarks_local' => $request->input('remarks_local'),
            'remarks_english' => $request->input('remarks_english'),
            'remarks_voice' => $remarks_voice,
            'updated_at' => now(),
            'status' => 'pending_with_vendor_supervisor'
        ];

        // Dynamic fields (everything except fixed ones)
        $dynamicData = $request->except([
            '_token',
            'division_id',
            'section_id',
            'silo_id',
            'remarks_local',
            'remarks_english',
            'remarks_voice',
            'photo',
            'id',
            'image',
            'status'
        ]);

        // Merge with existing dynamic JSON if updating
        if ($existing) {
            $oldDynamic = json_decode($existing->form_data, true) ?? [];
            $dynamicData = array_merge($oldDynamic, $dynamicData);
        }

        $staticData['form_data'] = json_encode($dynamicData, JSON_UNESCAPED_UNICODE);

        if ($id) {

            // ✅ Update record (include photo + voice if changed)
            DB::table('silo_inspection')
                ->where('id', $id)
                ->update($staticData);

            $message = 'Record updated successfully!';
        } else {
            // ✅ Insert new record
            $staticData['created_at'] = now();
            $inspection_id = DB::table('silo_inspection')->insertGetId($staticData);



            $datetime = date('Y-m-d H:i:s');
            // Define approval flow (manually or dynamically from DB)
            $loop = [
                ['type' => 'vendor', 'department_id' => 0, 'level' => 0],
                ['type' => 'vendor_supervisor', 'department_id' => 1, 'level' => 1], // in department we put user_id for this row only
                ['type' => 'safety', 'department_id' => 2, 'level' => 2]
            ];

            $desiredIds = [];

            foreach ($loop as $item) {
                $desired_id = DB::table('vendor_silo_inspection_desired')->insertGetId([
                    'vendor_silo_inspection_id' => $inspection_id,
                    'type' => $item['type'],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],

                ]);

                // Track desired ID by level
                if ($item['level'] != 0 && $item['level'] != 2) {
                    $desiredIds[$item['level']] = $desired_id;
                }
            }

            foreach ($loop as $item) {
                if ($item['level'] != 0 && $item['level'] != 2) {
                    DB::table('vendor_silo_inspection_flow')->insert([
                        'vendor_silo_inspection_id' => $inspection_id,
                        'desired_id' => $desiredIds[$item['level']],
                        'department_id' => $item['department_id'],
                        'level' => $item['level'],
                        'created_datetime' => $datetime,
                        'status' => 'N',
                    ]);
                }
            }
            $message = 'Form submitted successfully!';
        }

        return back()->with('success', $message);
    }



    public function edit_silo_daily($id)
    {
        $silo_details = DB::table('silo_inspection')->where('id', $id)->first();
        return view('admin.vendor_silo.edit_silo_daily', compact('silo_details'));
    }


    public function edit_data_ifream_silo_daily($id, $user_id)
    {
        $silo_details = DB::table('silo_inspection')->where('id', $id)->first();
        $divs = DB::table('division_new')->get();
        $active_list = DB::table('vendor_silo')->where('status', 'approve')->where('return_status', 'approve')->get();
        $fields = DB::table('silo_master')
            ->where('isactive', 1)
            ->orderBy('displayorder')
            ->get();

        $formData = $silo_details->form_data ? json_decode($silo_details->form_data, true) : [];
        $fieldMasters = DB::table('silo_master')
            ->where('isactive', 1)
            ->orderBy('displayorder')
            ->get();
        return view('admin.vendor_silo.edit_data_ifream_silo_daily', compact('silo_details', 'user_id', 'divs', 'active_list', 'fields', 'formData', 'fieldMasters'));
    }
    public function update(Request $request, $id)
    {


        $request->validate([
            'action' => 'required',
            'remarks' => 'required',
        ]);
        date_default_timezone_set('Asia/Kolkata');

        $decision = $request->action;
        $remarks = $request->remarks;
        $flow_id = $request->flow_id;
        $datetime = date('Y-m-d H:i:s');



        DB::table('vendor_silo_inspection_flow')->where('id', $flow_id)->update([
            'status' => 'Y',
            'decision' => $decision,
            'remarks' => $remarks,
            'remarks_datetime' => $datetime
        ]);

        if ($decision == 'return') {
            $flow = DB::table('vendor_silo_inspection_flow')->where('id', $flow_id)->select('level')->first();
            $level = $flow->level - 1;

            $find_desired = DB::table('vendor_silo_inspection_desired')->where('level', $level)->where('vendor_silo_inspection_id', $id)->first();

            DB::table('vendor_silo_inspection_flow')->insert([
                'vendor_silo_inspection_id' => $id,
                'desired_id' => $find_desired->id,
                'department_id' => $find_desired->department_id,
                'level' => $find_desired->level,
                'created_datetime' => $datetime,
                'status' => 'N'

            ]);
        }

        if ($decision == 'approve') {

            $flow = DB::table('vendor_silo_inspection_flow')->where('id', $flow_id)->select('level')->first();
            $level = $flow->level + 1;
            $find_desired = DB::table('vendor_silo_inspection_desired')->where('level', $level)->where('vendor_silo_inspection_id', $id)->first();
            if (!empty($find_desired->id)) {

                DB::table('vendor_silo_inspection_flow')->insert([
                    'vendor_silo_inspection_id' => $id,
                    'desired_id' => $find_desired->id,
                    'department_id' => $find_desired->department_id,
                    'level' => $find_desired->level,
                    'created_datetime' => $datetime,
                    'status' => 'N'

                ]);

                $check_current = DB::table('silo_inspection')->where('id', $id)->select('status')->first();
                if ($check_current->status == 'pending_with_vendor_supervisor') {
                    $status = 'pending_with_safety';
                } elseif ($check_current->status == 'pending_with_safety') {
                    $status = 'approve';
                }

                DB::table('silo_inspection')->where('id', $id)->update([
                    'status' => $status,
                ]);
            } else {
                DB::table('silo_inspection')->where('id', $id)->update([
                    'status' => $decision,
                ]);
            }


        }
        return response()->json([
            'message' => 'Update processed successfully!',
            'data' => $id
        ]);
    }


    //for validate email and otp
    public function check_otp2(Request $request)
    {
        $date = date('Y-m-d H:i:s');
        $transdate = date('Y-m-d');
        $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year = date('Y', strtotime($transdate));
        $request->validate([
            'g-recaptcha-response' =>
                ['required']
        ]);

        $seed = str_split('abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . '0123456789!@#$%^&*()'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 10) as $k)
            $rand .= $seed[$k];
        $password = $rand;

        $getUserDetails = UserLogin::where('email', $request->email)->first();
        if (!$getUserDetails) {
            $otp = rand(0000, 9999);
            $user = array(
                'email' => $request->email,
                'name' => $request->name,
                'otp' => $otp,
                'subject' => "OTP For Sign Up (JAMIPOL)"
            );

            Mail::send('send_otp_register', ['data' => $user], function ($message) use ($user) {
                $message->to($user['email'])
                    ->subject($user['subject']);
                $message->from('web@jamipol.com');
            });
            $session_data = array(
                'name' => $request->name,
                'email' => $request->email,
                'otp' => $otp
            );
            Session::put($session_data);
            return back()->with('message', 'OTP Send in Your Email Id !!!');
        } else {
            return back()->with('message2', 'Your Email Id Already Register !!!');
        }








    }
}
/*public function index1(){
         // $divisions = Division::all();
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));

        $gatepasss = DB::table('visitor_gate_pass')->orwhere('approver',Session::get('user_idSession'))
                                ->where('status','Pending_to_approve')
                              ->orderBy('id', 'DESC')->get();

        $gatepassss = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
                              ->where('status','issued')
                              ->orwhere('status','Rejected')
                              ->where('approver',Session::get('user_idSession'))
                               ->orwhere('status','Completed')
                              ->where('approver',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->get();	
        $gatepasss_sec = DB::table('visitor_gate_pass')->where('status','issued')
                              ->orderBy('id', 'DESC')->get();	

        $gatepasss_sec_com = DB::table('visitor_gate_pass')->where('status','Completed')
                             ->where('security_print_id',Session::get('user_idSession'))
                             ->orwhere('status','Rejected')
                              ->where('security_print_id',Session::get('user_idSession'))
                              ->orderBy('id', 'DESC')->get();	


                              return view('admin.gatepass_approvals.approve',compact('gatepasss','gatepassss','gatepasss_sec','gatepasss_sec_com'));
        //return view('RequestVGatepass');
    }	
     public function getDepartment($id){
        $depart = Department::where('division_id',$id)->get();
        return $depart;
    }
    public function getapprover($id){
        $approver = UserLogin::where('department_id',$id)->get();
        return $approver;
    }


    //public function create()
 //   {
      //  return view('admin.visitor_gate_pass.create');
   // }

    public function store(Request $request)
    {
        $date =  date('Y-m-d H:i:s');
        // dd( $request->all());
        //$request->validate([
         //   'name'         => 'required',
         //   'abbreviation' => 'required'
       // ]);


       $transdate = date('Y-m-d');
       $transdate1 = date('m-d');
        $month = date('m', strtotime($transdate));
        $year  = date('Y', strtotime($transdate));

      //  $users = DB::table('users')->get();


        $divv = DB::table('visitor_gate_pass')->where('division_id',$request->division_id)
                ->whereYear('created_datetime', '=', $year)
                ->whereMonth('created_datetime', '=', $month)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->sl;
            $v++;
            $serial_no=$v;
        }
        else{
            $serial_no="1"; 
        }

         $divv = Division::where('id',$request->division_id)
                ->orderBy('id', 'DESC')->first();
        if ($divv)
        {
            $v=$divv->abbreviation;

            $abb=$v;
        }

   $fill_sl='VGP'.'/'.$abb.'/'.$transdate1.'/'.$serial_no;

        $visitor_gatepass = DB::table('visitor_gate_pass')->insert([
        //$division = Division::create([
            'sl'       =>$serial_no,
            'full_sl'       =>$fill_sl,
            'visitor_mobile_no'=> $request->visitor_mobile,
            'visitor_name' => $request->visitor_name,
            'visitor_company' => $request->visitor_company,
            'visitor_email' => $request->visitor_email,
            'visitor_emergency_contact_no' => $request->visitor_emergency_contact_no,
            'division_id' => $request->division_id,
            'department' => $request->department_id,
            'approver' => $request->approver_id,
            'to_meet' => $request->to_meet,
            'from_date' => $request->from_date,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'any_material' => $request->any_material,
            'material_name' => $request->material_name,
            'material_identification_no' => $request->material_idenrification_no,
            'returnable' => $request->returnable,
            'propose_of_entry' => $request->purpose_of_material_entry,
            'visitor_any_vehicle' => $request->any_vehicle,
            'driving_mode' => $request->driving_mode,
            'driver_name' => $request->driver_name,
            'vehicle_no' => $request->vehicle_no,
            'dl_no' => $request->dl_no,
            'status' => 'Pending_to_approve',
            'created_datetime'=>$date

        ]);

        if($visitor_gatepass){
            return back()->with('message','Visitor Gatepass Requested Successfully! VGP No :-'.$fill_sl);
        }else{
            return back()->with('message','Error!');

        }
    } 
    public function edit($vgpid)
    {
        $id = \Crypt::decrypt($vgpid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
        $gatepass  =DB::table('visitor_gate_pass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();

        return view('admin.gatepass_approvals.edit',compact('id','gatepass','divisions','department'));
    }

    public function printg($pgid)
    {
        $id = \Crypt::decrypt($pgid);
        //Unique Record
        //$gatepass = DB::table('visitor_gate_pass');
       $gatepass  =DB::table('visitor_gate_pass')->where('id',$id)->get();
        $divisions   = Division::all();
        $department   = Department::all();

        return view('admin.gatepass_approvals.printg',compact('id','gatepass','divisions','department'));
    }

     public function update(Request $request)
    {
        $date =  date('Y-m-d H:i:s');
    if($request->approver_decision=='approve'){
        $status='issued';
    }else{
        $status='Rejected';
    }
        $gatepassv  =  DB::table('visitor_gate_pass')->where('id',$request->id)->update([

                      'approver_decision'           => $request->approver_decision,
                    'approver_remarks'              => $request->approver_remarks,
                    'status'              => $status,
                    'approver_datetime'=>$date


                ]);


        if($gatepassv){
            return back()->with('message',' Approved Successfully');
        }else{
            return back()->with('message','Error While Approve');

        }
    }
    public function update_security(Request $request)
    {
        $date =  date('Y-m-d H:i:s');

        $gatepassv =  DB::table('visitor_gate_pass')->where('id',$request->ida)->update([

                   'security_print_id'            =>Session::get('user_idSession'),
                    'security_print_remarks'       =>$request->security_remarks,
                    'security_print_datetime'      =>$date,
                    'status'                       =>'Completed'
                  ]);

        if($gatepassv){
            return back()->with('message',' Returned Successfully');
        }else{
            return back()->with('message','Error While Returned');

        }
    }
    public function RequestVGatepassPost(Request $request){
//echo $request->visitor_mobile;
//echo $request->name;
//exit;
        return view('RequestVGatepass');
    } 
    //public function RequestVGatepassPostup(Request $request){
//echo $request->approval_remarks;
//echo $request->name;
//exit;
        //return view('admin.gatepass_approvals.edit');
    //} 
    //public function approvePost(Request $request){
//echo $request->visitor_mobile;
//echo $request->name;
//exit;
       // return view('approve');
    //}
}*/
