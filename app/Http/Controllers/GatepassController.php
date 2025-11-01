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
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Mail;

class GatepassController extends Controller
{

    
public function index(){
	      $divisions = Division::all();

        return view('RequestVGatepass',compact('divisions'));
		
        //return view('RequestVGatepass');
    }
    public function indexs(){
         

        return view('vms_safety');
        
        //return view('RequestVGatepass');
    }
public function index1(){
	     // $divisions = Division::all();//
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));
		/*if(Session::get('user_sub_typeSession') == 3){
		$gatepasss = DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
			                   ->where('status','Pending_to_approve')
                              ->orderBy('id', 'DESC')->get();
					}*/
	
	
	
	$gatepasss = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
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
                    ->where('division_id',Session::get('user_DivID_Session'))
			->orwhere('created_by',Session::get('user_idSession'))
            ->where('status','Pending_to_approve')
              ->orderBy('id', 'DESC')->get();	

        $gatepasss_sec_com = DB::table('visitor_gate_pass')->where('status','Completed')
                               ->where('division_id',Session::get('user_DivID_Session'))
							 ->orwhere('status','Rejected')
							   ->where('division_id',Session::get('user_DivID_Session'))
			                 ->orwhere('created_by',Session::get('user_idSession'))
                             ->where('status','issued')
                              ->orderBy('id', 'DESC')->get();	

$gatepasss_requester = DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
            ->where('status','Pending_to_approve')
            ->orderBy('id', 'DESC')->get();   

$gatepasss_requester_returned = DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
            ->where('status','issued')
            ->where('created_by',Session::get('user_idSession'))
            ->orwhere('status','Rejected')
            ->where('created_by',Session::get('user_idSession'))
            ->where('status','Completed')
            ->orderBy('id','DESC')->get();

							  return view('admin.gatepass_approvals.approve',compact('gatepasss','gatepassss','gatepasss_sec','gatepasss_sec_com','gatepasss_requester','gatepasss_requester_returned'));
        //return view('RequestVGatepass');
    }	
    public function indext(){
         // $divisions = Division::all();//
 //$gatepasss = DB::table('visitor_gate_pass')->orderBy('id','desc')->get();
       // return view('admin.gatepass_approvals.approve',compact('gatepasss'));
     /*   if(Session::get('user_sub_typeSession') == 3){
        $gatepasss = DB::table('visitor_gate_pass')
                              ->orderBy('id', 'DESC')->get();
                    }else{
           $gatepasss = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
                                ->where('status','Pending_to_approve')
                              ->orderBy('id', 'DESC')->get();   
                    }   */      
     $gatepasss = DB::table('visitor_gate_pass')->where('approver',Session::get('user_idSession'))
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
                    ->where('division_id',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();   

        $gatepasss_sec_com = DB::table('visitor_gate_pass')->where('status','Completed')
                               ->where('division_id',Session::get('user_DivID_Session'))
                             ->orwhere('status','Rejected')
                               ->where('division_id',Session::get('user_DivID_Session'))
                              ->orderBy('id', 'DESC')->get();   

           $gatepasss_requester = DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
            ->where('status','Pending_to_approve')
            ->orderBy('id', 'DESC')->get();   

          $gatepasss_requester_returned =  DB::table('visitor_gate_pass')->where('created_by',Session::get('user_idSession'))
            ->where('status','issued')
            ->where('created_by',Session::get('user_idSession'))
            ->orwhere('status','Rejected')
            ->where('created_by',Session::get('user_idSession'))
            ->where('status','Completed')
            ->orderBy('id', 'DESC')->get();  

                              return view('admin.gatepass_approvals.approve_t',compact('gatepasss','gatepassss','gatepasss_sec','gatepasss_sec_com','gatepasss_requester','gatepasss_requester_returned'));
        //return view('RequestVGatepass');
    }   

public function report(Request $request){
         
  if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();
        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
          }
        $report = "";
        if ($request->input('divi_id')<>''  || $request->input('dept_id')<>'' || $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {   
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('visitor_gate_pass')->where('division_id',$request->input('divi_id'))
                        ->where('department',$request->input('dept_id'))
                        ->whereBetween('created_datetime',[$start,$end])->get();
        }
        return view('admin.gatepass_approvals.vms_report',compact('report','divisions'));
        
        //return view('RequestVGatepass');
    }

	 public function getDepartment($id){
        $depart = Department::where('division_id',$id)->get();
        return $depart;
    }
	public function getapprover($id){
        $approver = UserLogin::where('department_id',$id)->where('user_type' ,'1')->get();
        return $approver;
    }
	
	
public function GetReport(Request $request){
        // show all the 

        if(Session::get('user_sub_typeSession') == 3){
            $divisions = Division::get();
        }
        else{
            $users       = UserLogin::where('id',Session::get('user_idSession'))->first();
            $divisions   = Division::where('id',@$users->division_id)->get();
        }
            if($request->input('divi_id')<>'' && $request->input('dept_id') == 'ALL' && $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('visitor_gate_pass')->where('division_id',$request->input('divi_id'))->whereBetween('created_datetime',[$start,$end])->get();
            return view('admin.gatepass_approvals.vms_report',compact('report','divisions'));
        }
        elseif($request->input('divi_id')<>'' && $request->input('dept_id') != 'ALL' && $request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('visitor_gate_pass')->where('division_id',$request->input('divi_id'))->where('department',$request->input('dept_id'))->whereBetween('created_datetime',[$start,$end])->get();
            return view('admin.gatepass_approvals.vms_report',compact('report','divisions'));
        }
    
        elseif($request->input('fromdate')<>'' && $request->input('todate')<>'')
        {    
            $start  = Carbon::parse($request->input('fromdate'))->startOfDay()->toDateTimeString();
            $end    = Carbon::parse($request->input('todate'))->endOfDay()->toDateTimeString();
            $report = DB::table('visitor_gate_pass')->whereBetween('created_datetime',[$start,$end])->get();
            return view('admin.gatepass_approvals.vms_report',compact('report','divisions'));
        }
    }	
	
	public function store(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
        // dd( $request->all());
        //$request->validate([
         //   'name'         => 'required',
         //   'abbreviation' => 'required'
       // ]);
	  
	  date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
	  
	    $request->validate([
            'upload_photo' => 'required|file|mimes:jpeg,jpg,png'
        ]);

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
	   

if($request->hasFile('upload_photo')){
            $location = 'documents/clm_pics/';
            $extension = '.'.$request->upload_photo->getClientOriginalExtension();
            $name = basename($request->upload_photo->getClientOriginalName(),$extension).time();
            //$name = $name.$extension;
			$uid = Session::get('user_idSession');
			$datetime = date('Ymd_H_i_s');
			$randn = rand(0000,9999);
			$name = $uid.'_'.$datetime.'_'.$randn.$extension;
            $path = $request->upload_photo->move($location,$name);
            //$name = $location.$name;
            $name=$name;
          
        }else{
            $name='';
        }
		//return $name;
        
     if($request->days1=='Single'){
        $to_date=$request->from_date;
     }else{
        $to_date=$request->to_date;
     }



//$material_name->material_name = json_decode($request->material_name);
    $material_name=$request->material_name;
    $material_idenrification_no=$request->material_idenrification_no;
    $returnable =$request->returnable;
    $purpose_of_material_entry=$request->purpose_of_material_entry;

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
            'to_date' => $to_date,
            'days' => $request->days1,
            'blood_group' => $request->blood_group,
            'upload_photo' =>$name,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'any_material' => $request->any_material,
            'material_name' => serialize($material_name),
            'material_identification_no' => serialize($material_idenrification_no),
            'returnable' => serialize($returnable),
            'propose_of_entry' => serialize($purpose_of_material_entry),
            'visitor_any_vehicle' => $request->any_vehicle,
            'driving_mode' => $request->driving_mode,
            'driver_name' => $request->driver_name,
            'vehicle_no' => $request->vehicle_no,
            'dl_no' => $request->dl_no,
            'id_proof_type' => $request->id_proof_type,
            'id_number' => $request->id_number,
            'status' => 'Pending_to_approve',
			'created_datetime'=>$date,
             'created_by'=>Session::get('user_idSession')
             
        ]);


  $id=DB::getPdo()->lastInsertId();  
       
     
$getUserUpdateDetails = UserLogin::where('id',$request->approver_id)->first();
                $user = array('name'        => $getUserUpdateDetails->name,
                            'email'         => $getUserUpdateDetails->email,
                            'subject'       => "Pending For Approval [Notification]",
                            'id'            => $id,
                            'visitor_name'  => $request->visitor_name,
                            'visitor_mobile_no'=>$request->visitor_mobile,
                             'visitor_company' => $request->visitor_company,
                             'From_date'  =>  $request->from_date,
                             'To_date' =>$to_date,
                             'from_time'=> $request->from_time,
                             'to_time'=>$request->to_time,
                             'sl'=> $fill_sl

                            
                    );

    

 if($visitor_gatepass){
//$visitor_gatepass->id

 //$count=$request->count;  


//for($i=0;$i<$count;$i++){
//dd($request->all());
  //$vms_question = DB::table('vms_safety_answer_id')->insert([
          //  'answer'=>"$request->question$i",
        //    'vms_gatepass_id'=>'123',
     //       'vms_safety_question_id'=>$request->qid.$i
      //  ]);

//}

             Mail::send('admin.gatepass_approvals.send_pwd_vms',['data' => $user

            ],function($message) use ($user){
                        $message->to($user['email'])
                                ->subject($user['subject']);
                        $message->from('web@jamipol.com');
                    });


$dd0=$request->qid0;
$dd1=$request->qid1;
$dd2=$request->qid2;
$dd3=$request->qid3;
$dd4=$request->qid4;
$dd5=$request->qid5;
$dd6=$request->qid6;
$dd7=$request->qid7;
$dd8=$request->qid8;
//dd($dd3);
//dd($dd0);

//1
        if($dd0 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question0,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid0
        ]);
        }
//2
        if($dd1 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question1,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid1
        ]);
        }
//3
    if($dd2 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question2,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid2
        ]);
        }
//4        
        if($dd3 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question3,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid3
        ]);
        }
//5        
       /* if($dd4 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question4,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid4
        ]);
        }
//6       
        if($dd5 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question5,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid5
        ]);
        }
//7
      if($dd6 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question6,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid6
        ]);
        }    
//8        
        if($dd7 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question7,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid7
        ]);
        }   
//9
        if($dd8 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question8,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid8
        ]);
        }  
//10    
      if($dd9 !="null")
        {
        $vms_question = DB::table('vms_safety_answer_id')->insert([
            'answer'=>$request->question9,
            'vms_gatepass_id'=>$id,
            'vms_safety_question_id'=>$request->qid9
        ]);
        } */    
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
	
	 public function update_vms(Request $request)
    {
		$date =  date('Y-m-d H:i:s');
    if($request->approver_decision=='approve'){
		$status='issued';
	}else{
		$status='Rejected';
	}

if($request->days!='Single'){

    $from_date=$request->edit_from_date;
    $to_date=$request->edit_to_date;
}else{

    $from_date=$request->from_date;
    $to_date=$request->to_date;
}

        $gatepassv1  =  DB::table('visitor_gate_pass')->where('id',$request->id)->update([
                    'approver_decision'           => $request->approver_decision,
                    'approver_remarks'              => $request->approver_remarks,
                    'status'              => $status,
					'approver_datetime'     =>$date,
                    'from_date'           => $from_date,
                    'to_date'                 =>$to_date
                    ]);
        
              
        if($gatepassv1){
			
			if($status=='issued'){
            return back()->with('message',' Approved Successfully');
			}else{
				return back()->with('message',' Reject Successfully');
			}
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
    //public function getDepartment($id){
       // $depart = Department::where('division_id',$id)->get();
       // return $depart;
   //  }

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
}
