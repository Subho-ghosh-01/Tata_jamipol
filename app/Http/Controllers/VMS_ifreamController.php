<?php

namespace App\Http\Controllers;

use App\Department;
use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\JobLinking;
use DB;
use Psy\Readline\Userland;
use Session;
use Mail;
use Illuminate\Support\Facades\Crypt; // ✅ This is important

class VMS_ifreamController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'edit', 'update', 'edit_return']);
    }



    public function index()
    {
        // $id = request()->get('user_id');
        $id = Session::get('user_idSession');
        $divisions = Division::all();
        return view('admin.vms_ifream.index', compact('divisions', 'id'));
    }
    public function getVmsList()
    {
        if (Session::get('user_sub_typeSession') == 3) {
            $vms_lists = DB::table('vehicle_pass')->orderBy('id', 'desc')->get();
        } elseif (Session::get('clm_role') == 'hr_dept') {
            $vms_lists = DB::table('vehicle_pass')
                ->where('status', 'Pending_with_hr')
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $vms_lists = DB::table('vehicle_pass')
                ->where('created_by', Session::get('user_idSession'))
                ->orderBy('id', 'desc')
                ->get();
        }

        // Add encrypted ID to each item
        $vms_lists = $vms_lists->map(function ($item) {
            $item->enc_id = Crypt::encrypt($item->id);
            return $item;
        });

        return response()->json([
            'status' => 'ok',
            'data' => $vms_lists
        ]);
    }


    public function create()
    {
        $id = request()->get('user_id');
        date_default_timezone_set('Asia/Kolkata');
        $divisions = Division::all();
        $userdetails = UserLogin::Where('id', $id)->select('user_type')->first();
        $usertype = $userdetails->user_type;


        return view('admin.vms_ifream.create', compact(
            'divisions',
            'usertype',
            'id'

        ));
    }



    public function store(Request $request)
    {
        // Validate inputs
        $isEmployee = $request->utype == '1'; // assuming you use this to check

        $rules = [
            'vehicle_type_required' => 'required|in:two_wheeler,four_wheeler',
            'owner_name' => 'required|string|max:100',
            'registration_no' => 'required|string|max:20',
            'rc_attachment' => 'required|file|mimes:pdf|max:5120',
            'insurance_from' => 'required|date',
            'insurance_to' => 'required|date|after_or_equal:insurance_from',
            'insurance_attachment' => 'required|file|mimes:pdf|max:5120',
            'vehicle_category' => 'required|in:Petrol,Diesel,CNG,EV,Hybrid',
            'registration_date' => 'required|date',
            'puc_from' => 'nullable|date',
            'puc_to' => 'nullable|date|after_or_equal:puc_from',
            'puc_attachment' => 'nullable|file|mimes:pdf|max:5120',
            'driver_type' => 'nullable|in:self,driver',
            'driver_name' => 'nullable|string|max:100|required_if:driver_type,driver',
            'license_no' => 'nullable|string|max:50',
            'license_valid_from' => 'required|date',
            'license_valid_to' => 'required|date|after_or_equal:license_valid_from',
            'license_attachment' => 'required|file|mimes:pdf|max:5120',
        ];
        // Apply additional validation if not Employee
        if (!$isEmployee) {
            $rules['emp_name'] = 'required|string|max:100';
            $rules['gp'] = 'required|string|max:100';
        }
        $validated = $request->validate($rules);




        if ($isEmployee) {
            $apply_by = 1;
        } else {
            $apply_by = 2;
        }


        $allowedMimeTypes = [
            'application/pdf'
        ];

        $location = 'documents/vehicle_pass/';
        $uid = Session::get('user_idSession');
        $datetime = date('Ymd_H_i_s');

        function getRealMimeType($filePath)
        {
            $file = fopen($filePath, 'rb');
            $bytes = fread($file, 12);
            fclose($file);

            $hex = bin2hex($bytes);

            $magicNumbers = [
                'pdf' => '25504446',
            ];

            foreach ($magicNumbers as $type => $magic) {
                if (strpos($hex, $magic) === 0) {
                    switch ($type) {
                        case 'pdf':
                            return 'application/pdf';
                    }
                }
            }

            return 'unknown/unknown';
        }
        // RC
        if ($request->hasFile('rc_attachment')) {
            $doc1 = $request->file('rc_attachment');
            $realMime1 = getRealMimeType($doc1->getRealPath());
            if (!in_array($realMime1, $allowedMimeTypes)) {
                return back()->withErrors(['rc_attachment' => 'Invalid file content type for rc_attachment. Detected: ' . $realMime1]);
            }
            $filename1 = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $doc1->getClientOriginalExtension();
            $rcPath = $doc1->move($location, $filename1);
        } else {
            $rcPath = null;
        }

        // Insurance
        if ($request->hasFile('insurance_attachment')) {
            $doc2 = $request->file('insurance_attachment');
            $realMime2 = getRealMimeType($doc2->getRealPath());
            if (!in_array($realMime2, $allowedMimeTypes)) {
                return back()->withErrors(['insurance_attachment' => 'Invalid file content type for insurance_attachment. Detected: ' . $realMime2]);
            }
            $filename2 = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $doc2->getClientOriginalExtension();
            $insurancePath = $doc2->move($location, $filename2);
        } else {
            $insurancePath = null;
        }

        // PUC
        if ($request->hasFile('puc_attachment')) {
            $doc3 = $request->file('puc_attachment');
            $realMime3 = getRealMimeType($doc3->getRealPath());
            if (!in_array($realMime3, $allowedMimeTypes)) {
                return back()->withErrors(['puc_attachment' => 'Invalid file content type for puc_attachment. Detected: ' . $realMime3]);
            }
            $filename3 = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $doc3->getClientOriginalExtension();
            $pucPath = $doc3->move($location, $filename3);
        } else {
            $pucPath = null;
        }

        // License
        if ($request->hasFile('license_attachment')) {
            $doc4 = $request->file('license_attachment');
            $realMime4 = getRealMimeType($doc4->getRealPath());
            if (!in_array($realMime4, $allowedMimeTypes)) {
                return back()->withErrors(['license_attachment' => 'Invalid file content type for license_attachment. Detected: ' . $realMime4]);
            }
            $filename4 = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $doc4->getClientOriginalExtension();
            $licensePath = $doc4->move($location, $filename4);
        } else {
            $licensePath = null;
        }


        $find_user = UserLogin::Where('id', $request->uid)->select('division_id', 'vendor_code', 'name')->first();

        $division = Division::Where('id', $find_user->division_id)->first();

        $find_sl = DB::table('vehicle_pass')->where('vehicle_pass_for', $request->vehicle_type_required)->orderBy('id', 'desc')->first();
        if (!empty($find_sl->sl)) {
            $sl = $find_sl->sl + 1;
        } else {
            $sl = 1;
        }
        $my = date('m-Y');
        $formatted_sl = str_pad($sl, 3, '0', STR_PAD_LEFT);
        if ($request->vehicle_type_required == 'two_wheeler') {
            $vtype = 'TW';
        } else if ($request->vehicle_type_required == 'four_wheeler') {
            $vtype = "CAR";
        }

        $full_sl = 'JAM/' . 'DC JSR' . '/VEP' . '/' . $vtype . '/' . $formatted_sl;



        // Set timezone and get current datetime
        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('Y-m-d H:i:s');
        $currentDate = date('Y-m-d', strtotime('-1 month'));
        // Insert into database
        $inserted = DB::table('vehicle_pass')->insert([
            'vehicle_pass_for' => $request->vehicle_type_required ?? '',
            'employee_name' => $request->emp_name ?? 'NA',
            'gp' => $request->gp ?? 'NA',
            'vehicle_owner_name' => $request->owner_name ?? 'NA',
            'vehicle_registration_no' => $request->registration_no ?? 'NA',
            'vehicle_registration_doc' => $rcPath,
            'insurance_valid_from' => $request->insurance_from,
            'insurance_valid_to' => $request->insurance_to,
            'insurance_doc' => $insurancePath,
            'vehicle_type' => $request->vehicle_category,
            'vehicle_registration_date' => $request->registration_date,
            'puc_valid_from' => $request->puc_from,
            'puc_valid_to' => $request->puc_to,
            'puc_attachment_required' => $pucPath,
            'driving_license_no' => $request->license_no,
            'license_valid_from' => $request->license_valid_from,
            'license_valid_to' => $request->license_valid_to,
            'driving_license_doc' => $licensePath,
            'driven_by' => $request->driver_type ?? 'driver',
            'driver_name' => $request->driver_name,
            'apply_by_type' => $apply_by,
            'status' => 'pending_with_safety',
            'created_at' => $currentDateTime,
            'created_by' => $request->uid,

            'division_id' => $find_user->division_id,
            'sl' => $sl,
            'full_sl' => $full_sl

        ]);

        if ($inserted) {
            // Get last inserted vehicle_pass_id
            $lastId = DB::getPdo()->lastInsertId();

            // Define approval flow (manually or dynamically from DB)
            $loop = [
                ['type' => 'vendor', 'department_id' => 0, 'level' => 0, 'type_status' => 'New'],
                ['type' => 'safety', 'department_id' => 1, 'level' => 1, 'type_status' => 'New']
            ];

            $desiredIds = [];

            foreach ($loop as $item) {
                $desired_id = DB::table('vehicle_pass_desired_flow')->insertGetId([
                    'vehicle_pass_id' => $lastId,
                    'type' => $item['type'],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],
                    'type_status' => $item['type_status']
                ]);

                // Track desired ID by level
                if ($item['level'] != 0) {
                    $desiredIds[$item['level']] = $desired_id;
                }
            }

            foreach ($loop as $item) {
                if ($item['level'] != 0) {
                    DB::table('vehicle_pass_flow')->insert([
                        'vehicle_pass_id' => $lastId,
                        'desired_id' => $desiredIds[$item['level']],
                        'department_id' => $item['department_id'],
                        'level' => $item['level'],
                        'created_datetime' => $currentDateTime,
                        'status' => 'N',
                        'type_status' => $item['type_status']
                    ]);
                }
            }
            $recipients = UserLogin::where('clm_role', 'safety_dept')
                ->where('active', 'Yes')
                ->pluck('email') // Only get emails
                ->toArray();     // Convert to array

            if ($apply_by == 1) {
                $subject = "Request for Vehicle Entry Pass for $find_user->name, Vehicle Number - $request->registration_no";
                $user = [
                    'name' => 'Safety Department',
                    'email' => $recipients,
                    'subject' => $subject,
                    'vendor_name' => $find_user->name ?? 'NA',
                    'employee_name' => $request->emp_name ?? 'NA',
                    'vehicle_registration_no' => $request->registration_no ?? 'NA',
                    'vehicle_type' => $request->vehicle_type_required,
                    'doc_status' => 'Pending With Safety Department',
                    'send_to' => "Emp_to_safety",
                    'vendor_code' => $find_user->vendor_code
                ];
            } else {
                $subject = "Request for Vehicle Entry Pass for $request->emp_name, Vendor Name - $find_user->name,Vehicle Number - $request->registration_no";
                $user = [
                    'name' => 'Safety Department',
                    'email' => $recipients,
                    'subject' => $subject,
                    'vendor_name' => $find_user->name ?? 'NA',
                    'employee_name' => $request->emp_name ?? 'NA',
                    'vehicle_registration_no' => $request->registration_no ?? 'NA',
                    'vehicle_type' => $request->vehicle_type_required,
                    'doc_status' => 'Pending With Safety Department',
                    'send_to' => "Vendor_to_safety",
                    'vendor_code' => $find_user->vendor_code
                ];
            }





            // Send email to each user
            Mail::send('admin.vms_ifream.send_mail', [
                'data' => $user
            ], function ($message) use ($user) {
                $message->to($user['email']) // This is an array now
                    ->subject($user['subject']);
                $message->from('web@jamipol.com');
            });



            return response()->json([
                'status' => 'ok',
                'message' => 'Vehicle pass application submitted successfully.'
            ]);
        }

    }



    public function show(Department $department)
    {
        //
    }


    public function edit($id, $user_id)
    {




        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();

        return view('admin.vms_ifream.edit', compact(
            'vms_details',
            'user_id'
        ));

    }
    public function edit_return($id, $user_id)
    {




        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();

        return view('admin.vms_ifream.edit_return', compact(
            'vms_details',
            'user_id'


        ));

    }
    public function edit_driver_details($id, $user_id)
    {




        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();

        return view('admin.vms_ifream.edit_driver_details', compact(
            'vms_details',
            'user_id'


        ));

    }
    public function update(Request $request, $vms_ifream)
    {

        $decision = $request->action;
        $remarks = $request->remarks;
        $flow_id = $request->flow_id;
        $datetime = date('Y-m-d H:i:s');

        $vms_flow = DB::table('vehicle_pass_flow')->where('id', $flow_id)->where('type_status', 'New')->update([
            'status' => 'Y',
            'desion' => $decision,
            'remarks' => $remarks,
            'remarks_datetime' => $datetime
        ]);

        if ($decision == 'return') {

            $flow = DB::table('vehicle_pass_flow')->where('id', $flow_id)->where('type_status', 'New')->select('level')->first();

            $level = $flow->level - 1;

            $find_desired = DB::table('vehicle_pass_desired_flow')->where('level', $level)->where('vehicle_pass_id', $vms_ifream)->where('type_status', 'New')->first();

            $vms_insert = DB::table('vehicle_pass_flow')->insert([
                'vehicle_pass_id' => $vms_ifream,
                'desired_id' => $find_desired->id,
                'department_id' => $find_desired->department_id,
                'level' => $find_desired->level,
                'created_datetime' => $datetime,
                'status' => 'N',
                'type_status' => 'New'
            ]);
        }

        $vms = DB::table('vehicle_pass')->where('id', $vms_ifream)->update([
            'status' => $decision,
        ]);

        $find_vid = DB::table('vehicle_pass')->where('id', $vms_ifream)->select('created_by', 'employee_name', 'vehicle_registration_no', 'vehicle_pass_for', 'driver_name', 'return_datetime', 'return_reason', 'apply_by_type', 'id')->first();
        $recipients = UserLogin::where('id', $find_vid->created_by)
            ->where('active', 'Yes')
            ->pluck('email') // Only get emails
            ->toArray();     // Convert to array


        $find_user = UserLogin::Where('id', $find_vid->created_by)->select('division_id', 'vendor_code', 'name', 'department_id')->first();
        $department = Department::Where('id', $find_user->department_id)->select('department_name')->first();

        $apply_by = $find_vid->apply_by_type;
        if ($apply_by == 1) {
            $subject = "Vehicle Entry Pass Approved- Vehicle Number $find_vid->vehicle_registration_no | $find_user->name";
            $user = [
                'name' => 'Safety Department',
                'email' => $recipients,
                'subject' => $subject,
                'department' => $department->department_name,
                'surrender_date' => $find_vid->return_datetime,
                'vendor_name' => $find_user->name ?? 'NA',
                'employee_name' => $find_vid->employee_name ?? 'NA',
                'vehicle_registration_no' => $find_vid->vehicle_registration_no ?? 'NA',
                'vehicle_type' => $find_vid->vehicle_pass_for,
                'doc_status' => 'Approved',
                'send_to' => "Safety_to_emp_approve",
                'vendor_code' => $find_user->vendor_code
            ];
        } else {
            $subject = "Vehicle Entry Pass Approved - Vehicle Number $find_vid->vehicle_registration_no , $find_vid->employee_name , $find_vid->vehicle_registration_no";
            $user = [
                'name' => 'Safety Department',
                'email' => $recipients,
                'subject' => $subject,
                'department' => $department->department_name ?? 'NA',
                'reason' => $find_vid->return_reason,
                'surrender_date' => $find_vid->return_datetime,
                'vendor_name' => $find_user->name ?? 'NA',
                'employee_name' => $find_vid->employee_name ?? 'NA',
                'driver_name' => $find_vid->driver_name ?? 'NA',
                'vehicle_registration_no' => $find_vid->vehicle_registration_no ?? 'NA',
                'vehicle_type' => $find_vid->vehicle_pass_for,
                'doc_status' => 'Approved',
                'send_to' => "Safety_to_vendor_approve",
                'vendor_code' => $find_user->vendor_code
            ];
        }





        // Send email to each user
        Mail::send('admin.vms_ifream.send_mail', [
            'data' => $user
        ], function ($message) use ($user) {
            $message->to($user['email']) // This is an array now
                ->subject($user['subject']);
            $message->from('web@jamipol.com');
        });

        return response()->json([
            'message' => 'Update processed successfully!',
            'data' => $vms_ifream // full joined data
        ]);
    }



    public function update_return_approval(Request $request, $vms_ifream)
    {

        $decision = $request->action;
        $remarks = $request->remarks;
        $flow_id = $request->flow_id;
        $datetime = date('Y-m-d H:i:s');

        $vms_flow = DB::table('vehicle_pass_flow')->where('id', $flow_id)->where('type_status', 'Return')->update([
            'status' => 'Y',
            'desion' => $decision,
            'remarks' => $remarks,
            'remarks_datetime' => $datetime
        ]);

        if ($decision == 'return') {

            $flow = DB::table('vehicle_pass_flow')->where('id', $flow_id)->where('type_status', 'Return')->select('level')->first();

            $level = $flow->level - 1;

            $find_desired = DB::table('vehicle_pass_desired_flow')->where('level', $level)->where('vehicle_pass_id', $vms_ifream)->where('type_status', 'Return')->first();

            $vms_insert = DB::table('vehicle_pass_flow')->insert([
                'vehicle_pass_id' => $vms_ifream,
                'desired_id' => $find_desired->id,
                'department_id' => $find_desired->department_id,
                'level' => $find_desired->level,
                'created_datetime' => $datetime,
                'status' => 'N',
                'type_status' => 'Return'
            ]);
        }

        $vms = DB::table('vehicle_pass')->where('id', $vms_ifream)->update([
            'return_status' => $decision,
        ]);


        $find_vid = DB::table('vehicle_pass')->where('id', $vms_ifream)->select('created_by', 'employee_name', 'vehicle_registration_no', 'vehicle_pass_for', 'driver_name', 'return_datetime', 'return_reason', 'apply_by_type', 'id')->first();
        $recipients = UserLogin::where('id', $find_vid->created_by)
            ->where('active', 'Yes')
            ->pluck('email') // Only get emails
            ->toArray();     // Convert to array


        $find_user = UserLogin::Where('id', $find_vid->created_by)->select('division_id', 'vendor_code', 'name', 'department_id')->first();
        $department = Department::Where('id', $find_user->department_id)->select('department_name')->first();

        $apply_by = $find_vid->apply_by_type;
        if ($apply_by == 1) {
            $subject = "Acknowledgment: Vehicle Entry Pass Surrender Approved , Vehicle Number - $find_vid->vehicle_registration_no";
            $user = [
                'name' => 'Safety Department',
                'email' => $recipients,
                'subject' => $subject,
                'department' => $department->department_name,
                'surrender_date' => $find_vid->return_datetime,
                'vendor_name' => $find_user->name ?? 'NA',
                'employee_name' => $find_vid->employee_name ?? 'NA',
                'vehicle_registration_no' => $find_vid->vehicle_registration_no ?? 'NA',
                'vehicle_type' => $find_vid->vehicle_pass_for,
                'doc_status' => 'Approved',
                'send_to' => "Safety_to_emp_surrendor",
                'vendor_code' => $find_user->vendor_code
            ];
        } else {
            $subject = "Acknowledgment: Vehicle Entry Pass Surrender Approved - Vehicle Number $find_vid->vehicle_registration_no , $find_vid->employee_name";
            $user = [
                'name' => 'Safety Department',
                'email' => $recipients,
                'subject' => $subject,
                'department' => $department->department_name ?? 'NA',
                'reason' => $find_vid->return_reason,
                'surrender_date' => $find_vid->return_datetime,
                'vendor_name' => $find_user->name ?? 'NA',
                'employee_name' => $find_vid->employee_name ?? 'NA',
                'driver_name' => $find_vid->driver_name ?? 'NA',
                'vehicle_registration_no' => $find_vid->vehicle_registration_no ?? 'NA',
                'vehicle_type' => $find_vid->vehicle_pass_for,
                'doc_status' => 'Approved',
                'send_to' => "Safety_to_vendor_surrendor",
                'vendor_code' => $find_user->vendor_code
            ];
        }





        // Send email to each user
        Mail::send('admin.vms_ifream.send_mail', [
            'data' => $user
        ], function ($message) use ($user) {
            $message->to($user['email']) // This is an array now
                ->subject($user['subject']);
            $message->from('web@jamipol.com');
        });

        return response()->json([
            'message' => 'Update processed successfully!',
            'data' => $vms_ifream // full joined data
        ]);
    }


    public function update_driver_details(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'vms_id' => 'required|integer',
            'driver_type' => 'required|string',
            'license_no' => 'required|string|max:50',
            'license_valid_from' => 'required|date',
            'license_valid_to' => 'required|date|after:license_valid_from',
            'driver_name' => 'nullable|string|max:100',
            'license_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $datetime = now()->format('Ymd_His');
            $uid = 1; // Replace with actual user ID logic
            $location = 'documents/vehicle_pass/';
            $licensePath = null;

            // Handle file upload if present
            if ($request->hasFile('license_attachment')) {
                $doc = $request->file('license_attachment');

                // Verify MIME type
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                $realMime = $doc->getMimeType();

                if (!in_array($realMime, $allowedMimeTypes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid file type. Allowed types: JPEG, PNG, PDF'
                    ], 422);
                }

                // Generate unique filename
                $filename = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $doc->getClientOriginalExtension();

                // Store file using Storage facade
                $licensePath = $doc->storeAs($location, $filename, 'public');
            }

            // Update database record
            $updateData = [
                'status' => 'pending_with_safety',
                'driven_by' => $request->driver_type,
                'driver_name' => $request->driver_name,
                'driving_license_no' => $request->license_no,
                'license_valid_from' => $request->license_valid_from,
                'license_valid_to' => $request->license_valid_to,
                'renew_datetime' => now()
            ];

            // Only update document path if new file was uploaded
            if ($licensePath) {
                $updateData['driving_license_doc'] = $licensePath;
            }

            $vms = DB::table('vehicle_pass')
                ->where('id', $request->vms_id)
                ->update($updateData);





            $find_desired = DB::table('vehicle_pass_desired_flow')->where('level', '!=', '0')->where('vehicle_pass_id', $request->vms_id)->where('type_status', 'New')->first();
            $date_time = date('Y-m-d H:i:s');
            $vms_insert = DB::table('vehicle_pass_flow')->insert([
                'vehicle_pass_id' => $request->vms_id,
                'desired_id' => $find_desired->id,
                'department_id' => $find_desired->department_id,
                'level' => $find_desired->level,
                'created_datetime' => $date_time,
                'status' => 'N',
                'type_status' => 'New'
            ]);

            // Uncomment and adjust email sending logic as needed
            /*
            $find_vid = DB::table('vehicle_pass')
                ->where('id', $request->vms_id)
                ->select('created_by', 'employee_name', 'vehicle_registration_no', 
                        'vehicle_pass_for', 'driver_name', 'return_datetime', 
                        'return_reason', 'apply_by_type', 'id')
                ->first();

            // Your email sending logic here...
            */

            return response()->json([
                'success' => true,
                'message' => 'Driver details updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating driver details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_return(Request $request)
    {



        // Validate inputs
        $isEmployee = session('user_typeSession') == '1'; // assuming you use this to check

        $rules = [
            'vehicle_type_required' => 'required|in:two_wheeler,four_wheeler',
            'owner_name' => 'required|string|max:100',
            'registration_no' => 'required|string|max:20',
            'rc_attachment' => 'required|file|mimes:pdf|max:5120',
            'insurance_from' => 'required|date',
            'insurance_to' => 'required|date|after_or_equal:insurance_from',
            'insurance_attachment' => 'required|file|mimes:pdf|max:5120',
            'vehicle_category' => 'required|in:Petrol,Diesel,CNG,EV,Hybrid',
            'registration_date' => 'required|date',
            'puc_from' => 'nullable|date',
            'puc_to' => 'nullable|date|after_or_equal:puc_from',
            'puc_attachment' => 'nullable|file|mimes:pdf|max:5120',
            'driver_type' => 'nullable|in:self,driver',
            'driver_name' => 'nullable|string|max:100|required_if:driver_type,driver',
            'license_no' => 'nullable|string|max:50',
            'license_valid_from' => 'required|date',
            'license_valid_to' => 'required|date|after_or_equal:license_valid_from',
            'license_attachment' => 'required|file|mimes:pdf|max:5120',
        ];


        // Apply additional validation if not Employee
        if (!$isEmployee) {
            $rules['emp_name'] = 'required|string|max:100';
            $rules['gp'] = 'required|string|max:100';
        }

        //$validated = $request->validate($rules);

        // dd('fs');


        if ($isEmployee) {
            $apply_by = 1;
        } else {
            $apply_by = 2;
        }


        $allowedMimeTypes = [
            'application/pdf'
        ];

        $location = 'documents/vehicle_pass/';
        $uid = 1;
        $datetime = date('Ymd_H_i_s');

        function getRealMimeType($filePath)
        {
            $file = fopen($filePath, 'rb');
            $bytes = fread($file, 12);
            fclose($file);

            $hex = bin2hex($bytes);

            $magicNumbers = [
                'pdf' => '25504446',
            ];

            foreach ($magicNumbers as $type => $magic) {
                if (strpos($hex, $magic) === 0) {
                    switch ($type) {
                        case 'pdf':
                            return 'application/pdf';
                    }
                }
            }

            return 'unknown/unknown';
        }
        $existing = DB::table('vehicle_pass')->where('id', $request->vehicle_id)->first();

        // 1. RC Attachment
        $rcPath = $existing->vehicle_registration_doc;
        if ($request->hasFile('rc_attachment')) {
            $doc = $request->file('rc_attachment');
            $realMime = getRealMimeType($doc->getRealPath());
            if (!in_array($realMime, $allowedMimeTypes)) {
                return back()->withErrors(['rc_attachment' => 'Invalid RC file content type.']);
            }
            $filename = $uid . '_' . $datetime . '_rc.' . $doc->getClientOriginalExtension();
            $rcPath = $doc->move($location, $filename);
        }

        // 2. Insurance Attachment
        $insurancePath = $existing->insurance_doc;
        if ($request->hasFile('insurance_attachment')) {
            $doc = $request->file('insurance_attachment');
            $realMime = getRealMimeType($doc->getRealPath());
            if (!in_array($realMime, $allowedMimeTypes)) {
                return back()->withErrors(['insurance_attachment' => 'Invalid insurance file content type.']);
            }
            $filename = $uid . '_' . $datetime . '_insurance.' . $doc->getClientOriginalExtension();
            $insurancePath = $doc->move($location, $filename);
        }

        // 3. PUC Attachment
        $pucPath = $existing->puc_attachment_required;
        if ($request->hasFile('puc_attachment')) {
            $doc = $request->file('puc_attachment');
            $realMime = getRealMimeType($doc->getRealPath());
            if (!in_array($realMime, $allowedMimeTypes)) {
                return back()->withErrors(['puc_attachment' => 'Invalid PUC file content type.']);
            }
            $filename = $uid . '_' . $datetime . '_puc.' . $doc->getClientOriginalExtension();
            $pucPath = $doc->move($location, $filename);
        }

        // 4. License Attachment
        $licensePath = $existing->driving_license_doc;
        if ($request->hasFile('license_attachment')) {
            $doc = $request->file('license_attachment');
            $realMime = getRealMimeType($doc->getRealPath());
            if (!in_array($realMime, $allowedMimeTypes)) {
                return back()->withErrors(['license_attachment' => 'Invalid license file content type.']);
            }
            $filename = $uid . '_' . $datetime . '_license.' . $doc->getClientOriginalExtension();
            $licensePath = $doc->move($location, $filename);
        }

        // Update DB record
        $update_return = DB::table('vehicle_pass')->where('id', $request->vehicle_id)->update([
            'vehicle_pass_for' => $request->vehicle_type_required ?? '',
            'employee_name' => $request->emp_name ?? 'NA',
            'gp' => $request->gp ?? 'NA',
            'vehicle_owner_name' => $request->owner_name ?? 'NA',
            'vehicle_registration_no' => $request->registration_no ?? 'NA',
            'vehicle_registration_doc' => $rcPath,
            'insurance_valid_from' => $request->insurance_from,
            'insurance_valid_to' => $request->insurance_to,
            'insurance_doc' => $insurancePath,
            'vehicle_type' => $request->vehicle_category,
            'vehicle_registration_date' => $request->registration_date,
            'puc_valid_from' => $request->puc_from,
            'puc_valid_to' => $request->puc_to,
            'puc_attachment_required' => $pucPath,
            'driving_license_no' => $request->license_no,
            'license_valid_from' => $request->license_valid_from,
            'license_valid_to' => $request->license_valid_to,
            'driving_license_doc' => $licensePath,
            'driven_by' => $request->driver_type,
            'driver_name' => $request->driver_name,
            'updated_datetime' => now()

        ]);







        if ($update_return) {
            // Get last inserted vehicle_pass_id

            $flow = DB::table('vehicle_pass_flow')->where('status', 'N')->where('type_status', 'New')->select('level', 'id')->first();

            $level = $flow->level + 1;


            $vms_flow = DB::table('vehicle_pass_flow')->where('id', $flow->id)->where('type_status', 'New')->update([
                'status' => 'Y',
                'remarks' => $request->remarks ?? 'DONE',
                'remarks_datetime' => now()
            ]);

            $find_desired = DB::table('vehicle_pass_desired_flow')->where('level', $level)->where('vehicle_pass_id', $request->vehicle_id)->where('type_status', 'New')->first();



            $vms_insert = DB::table('vehicle_pass_flow')->insert([
                'vehicle_pass_id' => $request->vehicle_id,
                'desired_id' => $find_desired->id,
                'department_id' => $find_desired->department_id,
                'level' => $find_desired->level,
                'created_datetime' => now(),
                'status' => 'N',
                'type_status' => 'New'
            ]);



            $vms = DB::table('vehicle_pass')->where('id', $request->vehicle_id)->update([
                'status' => 'pending_with_safety',
                'updated_datetime' => now()
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Vehicle pass application Updated successfully.'
            ]);
        }

    }

    // In VmsController.php
    public function update_surrender(Request $request)
    {
        $request->validate([
            'pass_id' => 'required|integer|exists:vehicle_pass,id',
        ]);
        $datetime = date('Y-m-d H:i:s');

        $vms_update = DB::table('vehicle_pass')->where('id', $request->pass_id)->update(['return_status' => 'pending_with_safety', 'return_datetime' => $datetime, 'return_reason' => $request->surrender_reason]);




        $lastId = $request->pass_id;

        // Define approval flow (manually or dynamically from DB)
        $loop = [
            ['type' => 'vendor', 'department_id' => 0, 'level' => 0, 'type_status' => 'Return'],
            ['type' => 'safety', 'department_id' => 1, 'level' => 1, 'type_status' => 'Return']
        ];

        $desiredIds = [];

        foreach ($loop as $item) {
            $desired_id = DB::table('vehicle_pass_desired_flow')->insertGetId([
                'vehicle_pass_id' => $lastId,
                'type' => $item['type'],
                'department_id' => $item['department_id'],
                'level' => $item['level'],
                'type_status' => $item['type_status']
            ]);

            // Track desired ID by level
            if ($item['level'] != 0) {
                $desiredIds[$item['level']] = $desired_id;
            }
        }

        foreach ($loop as $item) {
            if ($item['level'] != 0) {
                DB::table('vehicle_pass_flow')->insert([
                    'vehicle_pass_id' => $lastId,
                    'desired_id' => $desiredIds[$item['level']],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],
                    'created_datetime' => $datetime,
                    'status' => 'N',
                    'type_status' => $item['type_status']
                ]);
            }
        }

        //Mail
        $recipients = UserLogin::where('clm_role', 'safety_dept')
            ->where('active', 'Yes')
            ->pluck('email') // Only get emails
            ->toArray();     // Convert to array

        $find_vid = DB::table('vehicle_pass')->where('id', $request->pass_id)->select('created_by', 'employee_name', 'vehicle_registration_no', 'vehicle_pass_for', 'driver_name', 'apply_by_type')->first();
        $find_user = UserLogin::Where('id', $find_vid->created_by)->select('division_id', 'vendor_code', 'name', 'department_id')->first();
        $department = Department::Where('id', $find_user->department_id)->select('department_name')->first();

        $apply_by = $find_vid->apply_by_type;
        if ($apply_by == 1) {
            $subject = "Vehicle Entry Pass Surrender  for $find_user->name, Vehicle Number - $find_vid->vehicle_registration_no";
            $user = [
                'name' => 'Safety Department',
                'email' => $recipients,
                'subject' => $subject,
                'department' => $department->department_name,
                'reason' => $request->surrender_reason,
                'surrender_date' => $datetime,
                'vendor_name' => $find_user->name ?? 'NA',
                'employee_name' => $find_vid->employee_name ?? 'NA',
                'vehicle_registration_no' => $find_vid->vehicle_registration_no ?? 'NA',
                'vehicle_type' => $find_vid->vehicle_pass_for,
                'doc_status' => 'Pending With Safety Department',
                'send_to' => "Emp_to_safety_surander",
                'vendor_code' => $find_user->vendor_code
            ];
        } else {
            $subject = "Vehicle Entry Pass Surrender for $find_vid->employee_name, Vendor Name - $find_user->name,Vehicle Number - $find_vid->vehicle_registration_no";
            $user = [
                'name' => 'Safety Department',
                'email' => $recipients,
                'subject' => $subject,
                'department' => $department->department_name ?? 'NA',
                'reason' => $request->surrender_reason,
                'surrender_date' => $datetime,
                'vendor_name' => $find_user->name ?? 'NA',
                'employee_name' => $find_vid->employee_name ?? 'NA',
                'driver_name' => $find_vid->driver_name ?? 'NA',
                'vehicle_registration_no' => $find_vid->vehicle_registration_no ?? 'NA',
                'vehicle_type' => $find_vid->vehicle_pass_for,
                'doc_status' => 'Pending With Safety Department',
                'send_to' => "vendor_to_safety_surander",
                'vendor_code' => $find_user->vendor_code
            ];
        }





        // Send email to each user
        Mail::send('admin.vms_ifream.send_mail', [
            'data' => $user
        ], function ($message) use ($user) {
            $message->to($user['email']) // This is an array now
                ->subject($user['subject']);
            $message->from('web@jamipol.com');
        });



        return response()->json([
            'status' => 'ok',
            'message' => 'Vehicle pass application updated successfully.'
        ]);
    }




    public function destroy($skill)
    {
        date_default_timezone_set('Asia/Kolkata'); // or your preferred timezone
        $date = date('Y-m-d H:i:s');
        if ($skill) {

            $skill1 = DB::table('skill_clms')->where('id', $skill)->update([
                'is_deleted' => 'Y',
                'updated_by' => Session::get('user_idSession'),
                'updated_date' => $date
            ]);
            if ($skill1) {
                return back()->with('message', 'Skill Delete Successfully');
            } else {
                return back()->with('message', 'Error While Skill Delete');

            }
        }

    }

}
