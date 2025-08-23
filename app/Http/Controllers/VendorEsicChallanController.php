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
use Illuminate\Support\Carbon;
class VendorEsicChallanController extends Controller
{

    public function index()
    {
        $divisions = Division::all();
        if (Session::get('user_sub_typeSession') == 3) {
            $esic_lists = DB::table('vendor_esic')->orderBy('id', 'desc')->get();
        } elseif (Session::get('clm_role') == 'hr_dept') {
            $esic_lists = DB::table('vendor_esic')->where('status', 'Pending_with_hr')->orderBy('id', 'desc')->get();
        } else {
            $esic_lists = DB::table('vendor_esic')->where('vendor_id', Session::get('user_idSession'))->orderBy('id', 'desc')->get();
        }
        return view('admin.vendor_esic_challan.index', compact('esic_lists'));
    }


    public function create()
    {
        $divisions = Division::all();
        $currentMonthYear = date('F-Y');
        $currentMonth = date('m', strtotime('-1 month'));
        $currentYear = date('Y');
        $previousMonthYear = date('F-Y', strtotime('-1 month'));


        $vendorName = UserLogin::where('id', Session::get('user_idSession'))
            ->where('user_type', '2')
            ->select('id', 'name')
            ->orderBy('id', 'desc')
            ->first();

        $vendorlist = UserLogin::where('user_type', '2')->select('id', 'name')->get();

        $check_uploaded = DB::table('vendor_esic')
            ->where('vendor_id', Session::get('user_idSession'))
            ->whereMonth('month', $currentMonth)
            ->whereYear('month', $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        $isUploaded = $check_uploaded ? 'yes' : 'no';

        $check_status = DB::table('vendor_esic')->select('status')
            ->where('vendor_id', Session::get('user_idSession'))
            ->whereMonth('month', $currentMonth)
            ->whereYear('month', $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        $setting_date = DB::table('settings')->where('type', 'esic_last_day')->first();
        $today_day = date('d');

        if ($today_day > $setting_date->value) {
            $islast_date = "yes";
        } else {
            $islast_date = "no";
        }



        $form_status = null;
        $color = null;
        if ($check_status) {
            if ($check_status->status == 'Pending_with_hr') {
                $form_status = 'Your Application is pending with HR Team';
                $color = '#cbce28';
            } elseif ($check_status->status == 'completed') {
                $form_status = 'Your Application is Approved by HR Team';
                $color = '#31a11b';
            } elseif ($check_status->status == 'reject') {
                $form_status = 'Your application is rejected. Please re-upload your attachment.';
                $color = '#dc3545'; // Bootstrap red (suggested for errors/rejections)

            }
        }

        return view('admin.vendor_esic_challan.create', compact(
            'divisions',
            'currentMonthYear',
            'vendorName',
            'vendorlist',
            'isUploaded',
            'form_status',
            'color',
            'check_status',
            'islast_date',
            'setting_date',
            'previousMonthYear'

        ));
    }



    public function store(Request $request)
    {
        // Validate inputs
        $request->validate([
            'vendor_id' => 'required|exists:userlogins,id', // Ensure vendor exists in the database
            'document1' => 'required|mimes:pdf,xls,xlsx,jpg,jpeg,png,doc,docx|max:5120', // Added doc and docx
            'document2' => 'required|mimes:pdf,xls,xlsx,jpg,jpeg,png,doc,docx|max:5120' // Added doc and docx
        ], [
            'vendor_id.required' => 'Vendor selection is required.',
            'vendor_id.exists' => 'Selected vendor does not exist.',
            'document1.required' => 'Esic Challan is required.',
            'document1.mimes' => 'Only PDF, Excel, JPG, JPEG, PNG, DOC, DOCX files are allowed.',
            'document1.max' => 'Esic Challan must be less than 5MB.',
            'document2.required' => 'Esic Contribution is required.',
            'document2.mimes' => 'Only PDF, Excel, JPG, JPEG, PNG, DOC, DOCX files are allowed.',
            'document2.max' => 'Esic Contribution must be less than 5MB.'

        ]);



        //check vendor 
        $currentMonth = date('m', strtotime('-1 month'));
        $currentYear = date('Y');

        $check_uploaded = DB::table('vendor_esic')
            ->where('vendor_id', $request->vendor_id)
            ->whereMonth('month', $currentMonth)
            ->whereYear('month', $currentYear)
            ->where('status', '!=', 'reject')
            ->orderBy('id', 'desc')
            ->first();

        $isUploaded = $check_uploaded ? 'yes' : 'no';
        if ($isUploaded == 'yes') {
            return back()->with('message', 'Documents has already been uploaded.');
        }
        // Upload the file



        $allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'image/jpeg',
            'image/png',
        ];

        $location = 'documents/vendor_esic/';
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
                'doc' => 'd0cf11e0a1b11ae1',
                'docx' => '504b0304',
                'xlsx' => '504b0304',
                'xls' => 'd0cf11e0a1b11ae1',
                'jpg' => 'ffd8ff',
                'jpeg' => 'ffd8ff',
                'png' => '89504e47',
            ];

            foreach ($magicNumbers as $type => $magic) {
                if (strpos($hex, $magic) === 0) {
                    switch ($type) {
                        case 'pdf':
                            return 'application/pdf';
                        case 'doc':
                            return 'application/msword';
                        case 'docx':
                        case 'xlsx':
                            return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                        case 'xls':
                            return 'application/vnd.ms-excel';
                        case 'jpg':
                        case 'jpeg':
                            return 'image/jpeg';
                        case 'png':
                            return 'image/png';
                    }
                }
            }

            return 'unknown/unknown';
        }

        // Handle document1
        if ($request->hasFile('document1')) {
            $doc1 = $request->file('document1');

            // Move temporarily to check magic bytes
            $tmpPath1 = $doc1->getRealPath();

            $realMime1 = getRealMimeType($tmpPath1);

            if (!in_array($realMime1, $allowedMimeTypes)) {
                return back()->withErrors(['document1' => 'Invalid file content type for Esic Challan. Detected: ' . $realMime1]);
            }

            $ext1 = $doc1->getClientOriginalExtension();
            $filename1 = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $ext1;
            $path1 = $doc1->move($location, $filename1);
        } else {
            $path1 = "No file";
        }

        // Handle document2
        if ($request->hasFile('document2')) {
            $doc2 = $request->file('document2');
            $tmpPath2 = $doc2->getRealPath();

            $realMime2 = getRealMimeType($tmpPath2);

            if (!in_array($realMime2, $allowedMimeTypes)) {
                return back()->withErrors(['document2' => 'Invalid file content type for Esic Contribution. Detected: ' . $realMime2]);
            }

            $ext2 = $doc2->getClientOriginalExtension();
            $filename2 = $uid . '_' . $datetime . '_' . rand(1000, 9999) . '.' . $ext2;
            $path2 = $doc2->move($location, $filename2);
        } else {
            $path2 = "No file";
        }



        // Set timezone and get current datetime
        date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('Y-m-d H:i:s');
        $currentDate = date('Y-m-d', strtotime('-1 month'));
        // Insert into database
        $inserted = DB::table('vendor_esic')->insert([
            'vendor_id' => $request->vendor_id,
            'esic_challan' => $path1,
            'esic_contribution' => $path2,
            'month' => $currentDate, // assuming current month
            'created_date' => $currentDateTime,
            'status' => 'Pending_with_hr',
            'division_id' => Session::get('user_DivID_Session') ?? '0'
        ]);


        $vendor_name = UserLogin::where('id', $request->vendor_id)->select('name', 'vendor_code')->first();
        // Convert to array

        if ($inserted) {
            $crm = date('F-Y', strtotime('-1 month'));
            $recipients = UserLogin::where('clm_role', 'hr_dept')
                ->where('active', 'Yes')
                ->pluck('email') // Only get emails
                ->toArray();     // Convert to array
            $user = [
                'name' => 'HR Department',
                'email' => $recipients,
                'subject' => "Vendor ESIC Challan & Contribution Uploaded - Pending Approval ($crm)",
                'Month' => $crm,
                'vendor_name' => $vendor_name->name ?? 'NA',
                'vendor_code' => $vendor_name->vendor_code ?? 'NA',
                'doc_status' => 'Pending With HR Department',
                'send_to' => "Vendor_to_hr"
            ];




            // Send email to each user
            Mail::send('admin.vendor_esic_challan.send_mail', [
                'data' => $user
            ], function ($message) use ($user) {
                $message->to($user['email']) // This is an array now
                    ->subject($user['subject']);
                $message->from('web@jamipol.com');
            });



            return back()->with('message', 'Esic uploaded successfully.');
        } else {
            return back()->with('message', 'Error!');
        }
    }



    public function show(Department $department)
    {
        //
    }


    public function edit($attendanceIDenc)
    {
        $id = \Crypt::decrypt($attendanceIDenc);
        $vendor_attendance = DB::table('vendor_esic')->where('id', $id)->first();

        if (Session::get('user_sub_typeSession') == 2) {
            $lastMonth = Carbon::now()->subMonth();
            $check_uploaded_reject = DB::table('vendor_esic')
                ->where('vendor_id', Session::get('user_idSession'))
                ->whereMonth('month', $lastMonth->month)
                ->whereYear('month', $lastMonth->year)
                ->where('status', 'reject')
                ->orderBy('id', 'desc')
                ->count();
            if ($check_uploaded_reject != 0) {
                $inform_message = 'You have already submitted your details <strong>' . $check_uploaded_reject . ' times</strong>, and all 
have been <span class="text-danger fw-bold">Rejected</span>. Please review the <strong>remarks section</strong> carefully 
and ensure your information is accurate before re-uploading. 
<a href="' . route('admin.vendor_esic_details.create') . '" class="text-primary fw-bold ms-1">Click here to re-upload your details</a>.';

            } else {
                $inform_message = '';
            }
        } else {
            $check_uploaded_reject = '';
            $inform_message = '';
        }

        // return $departments[0]->division_id; 
        return view('admin.vendor_esic_challan.edit', compact('vendor_attendance', 'check_uploaded_reject', 'inform_message'));

    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'required',
            'remarks' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $now = now();

        $record = DB::table('vendor_esic')->where('id', $id)->orderBy('id', 'desc')->first();

        if (!$record) {
            return back()->with('message', 'Record not found.');
        }

        $userId = Session::get('user_idSession');
        $updated = false;

        if ($record->status === 'Pending_with_hr') {
            if ($request->action == 'approve') {
                $status = "completed";
            } elseif ($request->action == 'reject') {
                $status = "reject";
            } else {
                $status = "";
            }
            $updated = DB::table('vendor_esic')->where('id', $id)->update([
                'hr_by' => $userId,
                'hr_remarks' => $request->remarks,
                'hr_decision' => $request->action,
                'hr_decision_datetime' => $now,
                'status' => $status,
            ]);

            $vendor_name = UserLogin::where('id', $record->vendor_id)->select('name', 'vendor_code', 'email')->first();
            // Convert to array


            $crm = date('F-Y', strtotime('-1 month'));
            if ($request->action == 'approve') {
                $recipients = UserLogin::where('clm_role', 'Account_dept')
                    ->where('active', 'Yes')
                    ->pluck('email') // Only get emails
                    ->toArray();     // Convert to array
                $user = [
                    'name' => $vendor_name->name ?? 'NA',
                    'email' => $recipients,
                    'subject' => "Vendor ESIC Challan & Contribution Uploaded - Approved ($crm)",
                    'Month' => $crm,
                    'vendor_name' => $vendor_name->name ?? 'NA',
                    'vendor_code' => $vendor_name->vendor_code ?? 'NA',
                    'doc_status' => 'Completed',
                    'send_to' => "hr_to_vendor",
                    'remarks' => $request->remarks
                ];
            } elseif ($request->action == 'reject') {

                $user = [
                    'name' => $vendor_name->name ?? 'NA',
                    'email' => $vendor_name->email,
                    'subject' => "Vendor ESIC Challan & Contribution - Rejected ($crm)",
                    'Month' => $crm,
                    'vendor_name' => $vendor_name->name ?? 'NA',
                    'vendor_code' => $vendor_name->vendor_code ?? 'NA',
                    'doc_status' => 'Rejected',
                    'send_to' => "hr_to_vendor_cancel",
                    'remarks' => $request->remarks
                ];
            }





        } else {


            $icons = [
                'success' => 'fas fa-check-circle',
                'info' => 'fas fa-info-circle',
                'warning' => 'fas fa-exclamation-circle',
                'danger' => 'fas fa-exclamation-triangle',
            ];

            return back()->with([
                'message' => 'Invalid status for update.',
                'message_type' => 'danger',
                'message_icon' => $icons['danger']
            ]);


        }

        if ($updated) {
            // Send email to each user
            Mail::send('admin.vendor_esic_challan.send_mail', [
                'data' => $user
            ], function ($message) use ($user) {
                $message->to($user['email']) // This is an array now
                    ->subject($user['subject']);
                $message->from('web@jamipol.com');
            });
            return back()->with([
                'message' => 'Updated Successfully.',
                'message_type' => 'success',
                'message_icon' => 'fas fa-check-circle'
            ]);
        } else {
            return back()->with([
                'message' => 'Oops... Something went wrong while updating.',
                'message_type' => 'danger',
                'message_icon' => 'fas fa-exclamation-triangle'
            ]);
        }

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
