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
use Carbon\Carbon;
class VMSController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'edit', 'update']);
    }



    public function index()
    {
        // $id = request()->get('user_id');
        $id = Session::get('user_idSession');
        $divisions = Division::all();
        return view('admin.vms.index', compact('divisions', 'id'));
    }
    public function getVmsList()
    {
        if (Session::get('user_sub_typeSession') == 3) {
            $vms_lists = DB::table('vehicle_pass')->orderBy('id', 'desc')->get();
        } elseif (Session::get('user_sub_typeSession') == '1') {
            $vms_lists = DB::table('vehicle_pass')
                ->where('created_by', Session::get('user_idSession'))
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
    public function filter(Request $request)
    {
        $query = DB::table('vehicle_pass');

        // ✅ Date filter (created_at range)
        if ($request->from_date && $request->to_date) {
            $from = $request->from_date . ' 00:00:00';
            $to = $request->to_date . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        // ✅ Status filter
        if ($request->status && $request->status !== "All") {
            if (in_array($request->status, ['Pending_with_Safety_Surrender', 'Surrender'])) {
                // Special case: check return_status instead of just status
                if ($request->status == 'Pending_with_Safety_Surrender') {
                    $status = 'pending_with_safety';
                } elseif ($request->status == 'Surrender') {
                    $status = 'approve';
                }
                $query->where('return_status', $status);
            } else {
                $query->where('status', $request->status);
            }
        }

        // ✅ Expiry filters (PUC, Insurance, Registration, License)
        if ($request->expiry_type) {
            $today = now()->format('Y-m-d'); // safer than date()

            switch ($request->expiry_type) {
                case 'puc':
                    $query->whereDate('puc_valid_to', '<=', $today);
                    break;

                case 'insurance':
                    $query->whereDate('insurance_valid_to', '<=', $today);
                    break;

                case 'registration':
                    $query->whereDate('vehicle_registration_date', '<=', $today);
                    break;

                case 'license':
                    $query->whereDate('license_valid_to', '<=', $today);
                    break;
            }
        }

        // ✅ Get data
        $data = $query->get();
        $divisions = Division::all();

        return view('admin.vms.vms_report', compact('divisions', 'data'));
    }

    public function export(Request $request)
    {

        $filters = $request->only(['from_date', 'to_date', 'status', 'expiry_type']);
        $fileName = 'vms_report_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new VMSExport($filters), $fileName);
    }


    // app/Http/Controllers/VMSController.php
    public function filterJson(Request $request)
    {
        $query = DB::table('vehicle_pass');

        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->status && $request->status != 'All') {
            $query->where('status', $request->status);
        }
        if ($request->expiry_type) {
            $today = now()->format('Y-m-d'); // safer than date()

            switch ($request->expiry_type) {
                case 'puc':
                    $query->whereDate('puc_valid_to', '<=', $today);
                    break;

                case 'insurance':
                    $query->whereDate('insurance_valid_to', '<=', $today);
                    break;

                case 'registration':
                    $query->whereDate('vehicle_registration_date', '<=', $today);
                    break;

                case 'license':
                    $query->whereDate('license_valid_to', '<=', $today);
                    break;
            }
        }
        $data = $query->get()->map(function ($row) {
            $createdUser = UserLogin::find($row->created_by);

            // Determine user_type
            if ($createdUser) {
                if ($createdUser->user_type == 1)
                    $user_type = 'Employee';
                elseif ($createdUser->user_type == 2)
                    $user_type = 'Vendor';
                else
                    $user_type = 'N/A';
            } else {
                $user_type = 'N/A';
            }

            return [
                'sl' => $row->sl,
                'full_sl' => $row->full_sl,
                'employee_name' => $row->employee_name,
                'gp' => $row->gp,
                'vehicle_pass_for' => $row->vehicle_pass_for,
                'vehicle_owner_name' => $row->vehicle_owner_name,
                'vehicle_registration_no' => $row->vehicle_registration_no,
                'vehicle_registration_doc' => $row->vehicle_registration_doc,
                'insurance_valid_from' => $row->insurance_valid_from,
                'insurance_valid_to' => $row->insurance_valid_to,
                'insurance_doc' => $row->insurance_doc,
                'vehicle_type' => $row->vehicle_type,
                'vehicle_registration_date' => $row->vehicle_registration_date,
                'puc_valid_from' => $row->puc_valid_from,
                'puc_valid_to' => $row->puc_valid_to,
                'puc_attachment_required' => $row->puc_attachment_required,
                'driven_by' => $row->driven_by,
                'driver_name' => $row->driver_name,
                'driving_license_no' => $row->driving_license_no,
                'driving_license_doc' => $row->driving_license_doc,
                'license_valid_from' => $row->license_valid_from,
                'license_valid_to' => $row->license_valid_to,
                'created_at' => \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s'),
                'created_by' => $createdUser ? $createdUser->name : 'N/A',
                'status' => $row->status,
                'apply_by_type' => $row->apply_by_type,
                'updated_datetime' => $row->updated_datetime,
                'division_id' => $row->division_id,
                'return_status' => $row->return_status,
                'return_datetime' => $row->return_datetime,
                'return_reason' => $row->return_reason,
                'renew_datetime' => $row->renew_datetime,
                'user_type' => $user_type,
                'vendor_code' => $createdUser ? $createdUser->vendor_code : null,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function filterJson_dashboard(Request $request)
    {
        $query = DB::table('vehicle_pass');

        // Date filter
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Status filter
        if ($request->status && $request->status !== "All") {
            $query->where('status', $request->status);
        }

        $vehicles = $query->get();
        $today = Carbon::today();

        // Counters from DB
        $total = $vehicles->count();
        $approved = $vehicles->where('status', 'approve')->whereNull('return_status')->count();
        $surrendered = $vehicles->where('return_status', 'approve')->count();
        $pendingSurrender = $vehicles->where('return_status', 'pending_with_safety')->count();

        $expiredPUC = $vehicles->where('puc_valid_to', '<', $today)->count();
        $expiredInsurance = $vehicles->where('insurance_valid_to', '<', $today)->count();
        $expiredRegistration = $vehicles->where('vehicle_registration_date', '<', $today)->count();
        $expiredLicense = $vehicles->where('license_valid_to', '<', $today)->count();

        return response()->json([
            'status' => true,
            'counts' => [
                'total' => $total,
                'approved' => $approved,
                'surrendered' => $surrendered,
                'pendingSurrender' => $pendingSurrender,
                'expiredPUC' => $expiredPUC,
                'expiredInsurance' => $expiredInsurance,
                'expiredRegistration' => $expiredRegistration,
                'expiredLicense' => $expiredLicense,
            ]
        ]);
    }





    public function create()
    {
        $id = request()->get('user_id');
        date_default_timezone_set('Asia/Kolkata');
        $divisions = Division::all();
        $userdetails = UserLogin::Where('id', $id)->select('user_type')->first();
        $usertype = $userdetails->user_type ?? '';


        return view('admin.vms.create', compact(
            'divisions',
            'id'

        ));
    }



    public function store(Request $request)
    {

    }



    public function show(Department $department)
    {
        //
    }


    public function edit($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();

        return view('admin.vms.edit', compact(
            'vms_details'


        ));

    }

    public function edit_return($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();

        return view('admin.vms.edit_return', compact(
            'vms_details'


        ));

    }
    public function edit_driver_details($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();

        return view('admin.vms.edit_driver_details', compact(
            'vms_details'
        ));

    }
    public function vms_report($vms_report = null)
    {


        $divisions = Division::all();

        return view(
            'admin.vms.vms_report',
            compact(
                'divisions'
            )
        );

    }
    public function vms_dashboard($vms_report = null)
    {


        $divisions = Division::all();

        return view(
            'admin.vms.vms_dashboard',
            compact(
                'divisions'
            )
        );

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
