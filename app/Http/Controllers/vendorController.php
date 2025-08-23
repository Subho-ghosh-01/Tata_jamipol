<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserLogin;
use Session;
use App\Division;
use App\Department;
use App\VendorSupervisor;
use Str;
use Hash;
use App\VendorEmployeeDetails;
use App\ShutdownChild;
use App\Permit;
use Mail;

use Auth;

use App\RenewPermit;

use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
class vendorController extends Controller
{
    public function edit1($idenc)
    {

        $id = \Crypt::decrypt($idenc);
        //Unique Record
        $user = UserLogin::where('id', $id)->first();
        return view('admin.users.vendor_approval', compact('id', 'user'));
    }


    public function store(Request $request)
    {

        if (($request->location == 'Jharkhand' && $request->lobour_capacity > '9')) {

            $request->validate([
                'lobour_license_no' => 'required',
            ], [
                'lobour_license_no.' . 'required' => 'Labour License No & Labour License Document is required',
            ]);
        } else if ($request->location == 'Karnataka' && $request->lobour_capacity > '19') {
            $request->validate([
                'lobour_license_no' => 'required',
            ], [
                'lobour_license_no.' . 'required' => 'Labour License No & Labour License Document is required',
            ]);
        }
        /*  $request->validate([

       'mobile_no' => 'required|min:10|max:10',

          ]); */

        if ($request->hasFile('esic_document')) {
            $location4 = 'public/documents/clm_pics/';
            $extension4 = '.' . $request->esic_document->getClientOriginalExtension();
            $name4 = basename($request->esic_document->getClientOriginalName(), $extension4) . time();
            $name4 = $name4 . $extension4;
            $path4 = $request->esic_document->move($location4, $name4);
            $name4 = $name4;

        }
        if ($request->hasFile('lobour_license_document')) {
            $location5 = 'public/documents/clm_pics/';
            $extension5 = '.' . $request->lobour_license_document->getClientOriginalExtension();
            $name5 = basename($request->lobour_license_document->getClientOriginalName(), $extension5) . time();
            $name5 = $name5 . $extension5;
            $path5 = $request->lobour_license_document->move($location5, $name5);
            $name5 = $name5;

        } else {
            $name5 = "";
        }

        if ($request->hasFile('ec_policy_document')) {
            $location6 = 'public/documents/clm_pics/';
            $extension6 = '.' . $request->ec_policy_document->getClientOriginalExtension();
            $name6 = basename($request->ec_policy_document->getClientOriginalName(), $extension6) . time();
            $name6 = $name6 . $extension6;
            $path6 = $request->ec_policy_document->move($location6, $name6);
            $name6 = $name6;

        } else {
            $name6 = "";
        }
        if ($request->hasFile('po_number_document')) {
            $location7 = 'public/documents/clm_pics/';
            $extension7 = '.' . $request->po_number_document->getClientOriginalExtension();
            $name7 = basename($request->po_number_document->getClientOriginalName(), $extension7) . time();
            $name7 = $name7 . $extension7;
            $path7 = $request->po_number_document->move($location7, $name7);
            $name7 = $name7;

        } else {
            $name7 = "";
        }


        if ($request->hasFile('wcp_document')) {
            $location8 = 'public/documents/clm_pics/';
            $extension8 = '.' . $request->wcp_document->getClientOriginalExtension();
            $name8 = basename($request->wcp_document->getClientOriginalName(), $extension8) . time();
            $name8 = $name8 . $extension8;
            $path8 = $request->wcp_document->move($location8, $name8);
            $name8 = $name8;

        } else {
            $name8 = "";
        }


        $vendor = UserLogin::where('id', $request->id)->update([
            'company_name' => $request->company_name,
            'mobile_no' => $request->mobile_no,
            'landing_no' => $request->landing_no,
            'md_name' => $request->md_name,
            'GSTN' => $request->GSTN,
            'pan_of_the_orgination' => $request->pan_of_the_orgination,
            'epf_code' => $request->epf_code,
            'esci_code' => $request->esci_code,
            'esci_document' => $name4,
            'location' => $request->location,
            'lobour_capacity' => $request->lobour_capacity,
            'contract_type' => $request->contract_type,
            'nature_of_work' => $request->nature_of_work,
            'lobour_license_no' => $request->lobour_license_no,
            'lobour_license_document' => $name5,
            'ec_policy' => $request->ec_policy,
            'ec_document' => $name6,
            'po_number' => $request->po_number,
            'po_document' => $name7,
            'emergency_contact_no' => $request->emergency_mobile_no,
            'labour_license_validity' => $request->labour_license_validity,
            'wcp_no' => $request->wcp_no,
            'wcp_validity' => $request->wcp_validity,
            'wcp_doc' => $name8,
            'status' => 'Pending_for_hr'
        ]);

        if ($vendor) {
            return back()->with('message', 'Vendor Update Suceessfully');
        } else {
            return back()->with('message', 'Ooops... Error While Adding user');
        }

    }

    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $date = date('Y-m-d H:i:s');

        if ($request->status == 'Pending_for_hr') {

            if ($request->approver_decision == 'approve') {
                $status = 'pending_for_safety';
            } else {
                $status = 'pending_clms_vendor';
            }
            $vendor_update = UserLogin::where('id', $request->id)->update([
                'hr_remarks' => $request->approver_remarks,
                'hr_decision' => $request->approver_decision,
                'status' => $status,
                'hr_by' => Session::get('user_idSession'),
                'hr_datetime' => $date
            ]);


        } else if ($request->status == 'pending_for_safety') {

            if ($request->approver_decision == 'approve') {
                $status = '';
            } else {
                $status = 'pending_clms_vendor';
            }
            $vendor_update = UserLogin::where('id', $request->id)->update([


                'safety_remarks' => $request->approver_remarks,
                'safety_decision' => $request->approver_decision,
                'status' => $status,
                'safety_by' => Session::get('user_idSession'),
                'safety_datetime' => $date
            ]);
        }
        if ($vendor_update) {
            if ($request->approver_decision == 'approve') {
                return back()->with('message', 'Approve Successfully');
            } else {
                return back()->with('message', 'Reject Successfully');
            }
        } else {
            return back()->with('message', 'Ooops... Error While Adding user');
        }

    }

    public function vendor_clms_pending_list(Request $request)
    {

        $divisions = Division::all();
        if (Session::get('user_sub_typeSession') == 3) {
            $vendors_approvals = UserLogin::where('user_type', 2)->where('status', 'pending_clms_vendor')->orwhere('status', 'Pending_for_hr')->orwhere('status', 'Pending_for_safety')->orderBy('id', 'desc')->get();
        } elseif (Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1) {


            if (Session::get('clm_role') == 'hr_dept') {
                $vendors_approvals = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))->where('status', 'Pending_for_hr')->orderBy('id', 'desc')->get();
            } elseif (Session::get('clm_role') == 'Safety_dept') {
                $vendors_approvals = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))->where('status', 'Pending_for_safety')->orderBy('id', 'desc')->get();
            } else {
                $vendors_approvals = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))
                    ->where('id', Session::get('user_idSession'))->orderBy('id', 'desc')->get();
            }
        } else {
            /*$vendors_approvals = UserLogin::where('user_type',2)->where(['division_id' => Session::get('user_DivID_Session')])
             ->where('id','!=',Session::get('user_idSession'))->where('status','pending_clms_vendor')->orwhere('status','Pending_for_hr')->orwhere('status','Pending_for_safety')->orderBy('id','desc')->get();*/

            $vendors_approvals = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))
                ->where('id', Session::get('user_idSession'))->orderBy('id', 'desc')->get();
        }
        return view('admin.vendor_clms_pending_list', compact('divisions', 'vendors_approvals'));
    }
}