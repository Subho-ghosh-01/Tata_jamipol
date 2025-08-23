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
use Excel;
use App\Imports\Importholidaylist;


class VendorholidayController extends Controller
{

    public function index()
    {
        $divisions = Division::all();
        if (Session::get('user_sub_typeSession') == 3) {
            $holiday_lists = DB::table('vendor_holiday_list')->orderBy('id', 'desc')->get();
        } elseif (Session::get('clm_role') == 'hr_dept') {
            $holiday_lists = DB::table('vendor_holiday_list')->orderBy('id', 'desc')->get();
        } else {
            $holiday_lists = DB::table('vendor_holiday_list ')->where('vendor_id', Session::get('user_idSession'))->orderBy('id', 'desc')->get();
        }
        return view('admin.vendor_holiday.index', compact('holiday_lists'));
    }


    public function create()
    {
        return view('admin.vendor_holiday.create');
    }



    public function store(Request $request)
    {
        Excel::import(new Importholidaylist, $request->file('file_datas'));




        return back()->with('message', 'File Imported Successfully');
    }



    public function show(Department $department)
    {
        //
    }


    public function edit($attendanceIDenc)
    {
        $id = \Crypt::decrypt($attendanceIDenc);
        $vendor_attendance = DB::table('vendor_hyr')->where('id', $id)->first();

        if (Session::get('user_sub_typeSession') == 2) {
            $lastMonth = now()->subMonth();

            $check_uploaded_reject = DB::table('vendor_ecm')
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
<a href="' . route('admin.vendor_ecm.create') . '" class="text-primary fw-bold ms-1">Click here to re-upload your details</a>.';

            } else {
                $inform_message = '';
            }
        } else {
            $check_uploaded_reject = '';
            $inform_message = '';
        }

        // return $departments[0]->division_id; 
        return view('admin.vendor_hyr.edit', compact('vendor_attendance', 'check_uploaded_reject', 'inform_message'));

    }


    public function update(Request $request, $id)
    {

        //strtotime('-1 month')
        $request->validate([
            'action' => 'required',
            'remarks' => 'required',
        ]);

        date_default_timezone_set('Asia/Kolkata');
        $now = now();

        $record = DB::table('vendor_hyr')->where('id', $id)->orderBy('id', 'desc')->first();

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
            $updated = DB::table('vendor_hyr')->where('id', $id)->update([
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
                    'subject' => "Vendor Half Yearly Return Uploaded - Approved ($crm)",
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
                    'subject' => "Vendor Half Yearly Return - Rejected ($crm)",
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
            Mail::send('admin.vendor_hyr.send_mail', [
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
