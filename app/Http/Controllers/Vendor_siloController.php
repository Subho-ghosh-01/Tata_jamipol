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


class Vendor_siloController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'edit', 'update', 'create_ifream']);
    }



    public function index()
    {
        $id = Session::get('user_idSession');
        $divisions = Division::all();

        $query = DB::table('vendor_silo')
            ->leftJoin('divisions', 'vendor_silo.section_id', '=', 'divisions.id')
            ->leftJoin('division_new', 'vendor_silo.division_id', '=', 'division_new.id')
            ->leftJoin('userlogins', 'vendor_silo.vendor_id', '=', 'userlogins.id')
            ->leftJoin('vendor_silo_flow', 'vendor_silo.id', '=', 'vendor_silo_flow.vendor_silo_id')
            ->select(
                'vendor_silo.*',
                'divisions.name as section',
                'division_new.name as division_name',
                'userlogins.name as vendor_name',
                'vendor_silo_flow.department_id'
            )
            ->orderBy('vendor_silo.id', 'desc');

        $active_query = DB::table('vendor_silo')
            ->leftJoin('divisions', 'vendor_silo.section_id', '=', 'divisions.id')
            ->leftJoin('division_new', 'vendor_silo.division_id', '=', 'division_new.id')
            ->leftJoin('userlogins', 'vendor_silo.vendor_id', '=', 'userlogins.id')
            ->leftJoin('vendor_silo_flow', 'vendor_silo.id', '=', 'vendor_silo_flow.vendor_silo_id')
            ->select(
                'vendor_silo.*',
                'divisions.name as section',
                'division_new.name as division_name',
                'userlogins.name as vendor_name',
                'vendor_silo_flow.department_id'
            )
            ->orderBy('vendor_silo.id', 'desc');

        $inactive_query = DB::table('vendor_silo')
            ->leftJoin('divisions', 'vendor_silo.section_id', '=', 'divisions.id')
            ->leftJoin('division_new', 'vendor_silo.division_id', '=', 'division_new.id')
            ->leftJoin('userlogins', 'vendor_silo.vendor_id', '=', 'userlogins.id')
            ->leftJoin('vendor_silo_flow', 'vendor_silo.id', '=', 'vendor_silo_flow.vendor_silo_id')
            ->select(
                'vendor_silo.*',
                'divisions.name as section',
                'division_new.name as division_name',
                'userlogins.name as vendor_name',
                'vendor_silo_flow.department_id'
            )
            ->orderBy('vendor_silo.id', 'desc');
        // Apply filtering based on session conditions
        if (Session::get('user_sub_typeSession') != 3) {
            if (Session::get('clm_role') == 'safety_dept') {
                $query->where('vendor_silo.status', 'Pending_with_safety')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
                $active_query->where('vendor_silo.status', 'approve')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
                $inactive_query->where('vendor_silo.status', 'inactive')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
            } else if (Session::get('inclusion') == 1) {
                $query->where('vendor_silo.status', 'pending_with_inclusion_user')->where('vendor_silo.approval_id', Session::get('user_idSession'));
                $active_query->where('vendor_silo.status', 'approve')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
                $inactive_query->where('vendor_silo.status', 'inactive')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
            } else if (Session::get('silo_role') == 'operation_dept') {
                $query->where('vendor_silo.status', 'pending_with_operation_user')->where('vendor_silo_flow.section_id', Session::get('user_DivID_Session'));
                $active_query->where('vendor_silo.status', 'approve')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
                $inactive_query->where('vendor_silo.status', 'inactive')->where('vendor_silo.section_id', Session::get('user_DivID_Session'));
            } else {
                $query->where('vendor_silo.created_by', Session::get('user_idSession'));
            }
        }

        $vms_lists = $query->get();
        $vms_activelists = $active_query->get();
        $vms_inactivelists = $active_query->get();
        // Encrypt ID for each item
        $vms_lists = $vms_lists->map(function ($item) {
            $item->enc_id = Crypt::encrypt($item->id);
            return $item;
        });

return view('admin.vendor_silo.index', compact(
    'divisions',
    'id',
    'vms_lists',
    'vms_activelists',
    'vms_inactivelists'
));    }



    public function create_ifream($id = null)
    {


        date_default_timezone_set('Asia/Kolkata');
        $divisions = Division::all();
        $userdetails = UserLogin::Where('id', $id)->select('user_type')->first();
        $usertype = $userdetails->user_type ?? '';
        $divs = DB::table('division_new')->get();

        return view('admin.vendor_silo.create_ifream', compact(
            'divisions',
            'id',
            'divs'
        ));
    }
    public function create()
    {

        $id = request()->get('user_id');

        date_default_timezone_set('Asia/Kolkata');
        $divisions = Division::all();
        $userdetails = UserLogin::Where('id', $id)->select('user_type')->first();
        $usertype = $userdetails->user_type ?? '';
        $divs = DB::table('division_new')->get();

        return view('admin.vendor_silo.create', compact(
            'divisions',
            'id',
            'divs'

        ));
    }



    public function store(Request $request)
    {



        // $rules = [
        //     'division' => 'required|integer',
        //     'plant' => 'required|integer',


        // ];

        // for ($i = 1; $i <= 10; $i++) {
        //     $rules["lead{$i}_val"] = 'required|integer|min:0';
        //     $rules["lead{$i}_doc"] = 'nullable|file|mimes:pdf|max:2048';
        // }

        // for ($i = 1; $i <= 5; $i++) {
        //     $rules["lag{$i}_val"] = 'required|integer|min:0';
        //     $rules["lag{$i}_doc"] = 'nullable|file|mimes:pdf|max:2048';
        // }

        // $request->validate($rules);


        // Manual conditional validation (val > 0 => doc required)

        if ($request->status == 'draft') {
            $status = 'draft';
        } elseif ($request->status == 'final') {
            $status = 'pending_with_inclusion_user';
        }



        if ($request->lead7_val > 0 && !$request->hasFile('lead7_doc')) {
            return back()->withErrors(['lead7_doc' => 'Document required for Lead 7'])->withInput();
        }
        if ($request->lead8_val > 0 && !$request->hasFile('lead8_doc')) {
            return back()->withErrors(['lead8_doc' => 'Document required for Lead 8'])->withInput();
        }
        if ($request->lead9_val > 0 && !$request->hasFile('lead9_doc')) {
            return back()->withErrors(['lead9_doc' => 'Document required for Lead 9'])->withInput();
        }
        if ($request->lead10_val > 0 && !$request->hasFile('lead10_doc')) {
            return back()->withErrors(['lead10_doc' => 'Document required for Lead 10'])->withInput();
        }

        $allowedMimeTypes = ['application/pdf'];
        $getMimeType = function ($file) {
            return $file->getMimeType();
        };

        $location = public_path('documents/vendor_silo');
        $uid = $request->uid ?? uniqid();
        $datetime = date('YmdHis');

        // Initialize variables
        $vehicle_registration = $insurance_doc = $fitness_doc = $puc_doc = $road_permit_certificate = null;
        $vessel_certiicate = $pressure_gauge_certificate = $pressure_relief_certificate = null;

        // Helper for moving and setting path
        function handleDoc($request, $field, $index, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, &$errors, $existingFile = null)
        {
            // If no new file uploaded, return existing file path
            if (!$request->hasFile($field)) {
                return $existingFile;
            }

            $file = $request->file($field);
            $mime = $getMimeType($file);

            if (!in_array($mime, $allowedMimeTypes)) {
                $errors[$field] = 'Only PDF files are allowed. Uploaded type: ' . $mime;
                return $existingFile; // Keep old file if validation fails
            }

            $filename = "{$uid}_{$datetime}_{$index}." . $file->getClientOriginalExtension();
            $file->move(public_path('documents/vendor_silo'), $filename);
            return "documents/vendor_silo/" . $filename;

        }

        // Lead documents (1 to 10)
        $vehicle_registration = handleDoc(
            $request,
            'vehicle_reg_file',
            1,
            $uid,
            $datetime,
            $location,
            $allowedMimeTypes,
            $getMimeType,
            $errors,
            $request->input('existing_vehicle_reg_file') // 👈 from form hidden field
        );

        $insurance_doc = handleDoc($request, 'insurance_file', 2, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_insurance_file'));

        $fitness_doc = handleDoc($request, 'fitness_file', 3, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_fitness_file'));

        $puc_doc = handleDoc($request, 'puc_file', 4, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_puc_file'));

        $road_permit_certificate = handleDoc($request, 'road_permit_certificate', 5, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_road_permit_certificate'));

        $vessel_certiicate = handleDoc($request, 'pressure_vessel_file', 6, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_pressure_vessel_file'));

        $pressure_gauge_certificate = handleDoc($request, 'pressure_gauge_file', 7, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_pressure_gauge_file'));

        $pressure_relief_certificate = handleDoc($request, 'pressure_relief_file', 8, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors, $request->input('existing_pressure_relief_file'));

        // $lead9_doc = handleDoc($request, 'lead9_doc', 9, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        // $lead10_doc = handleDoc($request, 'lead10_doc', 10, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);

        // Lag documents (1 to 6)
        // $lag1_doc = handleDoc($request, 'lag1_doc', 11, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        // $lag2_doc = handleDoc($request, 'lag2_doc', 12, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        // $lag3_doc = handleDoc($request, 'lag3_doc', 13, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        // $lag4_doc = handleDoc($request, 'lag4_doc', 14, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        // $lag5_doc = handleDoc($request, 'lag5_doc', 15, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);

        $sl_get = DB::table('vendor_silo')->select('sl', 'full_sl')->first();

        if (empty($request->id)) {
            if (!empty($sl_get->sl)) {
                $sl = $sl_get->sl + 1;
            } else {
                $sl = 1;
            }

            $full_sl = "JAM/Silo Tanker/" . $sl;
        } else {
            $sl = $sl_get->sl;
            $full_sl = $sl_get->full_sl;
        }
        $data = [
            'sl' => $sl,
            'full_sl' => $full_sl,
            'division_id' => $request->division,
            'section_id' => $request->plant,
            'approver_id' => $request->approver,

            'work_order_no' => $request->work_order_no,
            'validity' => $request->validity,

            'vehicle_registration_no' => $request->vehicle_reg_no,
            'registration_doc' => $vehicle_registration, // doc
            'insurance_from' => $request->insurance_from,
            'insurance_to' => $request->insurance_to,
            'insurance_doc' => $insurance_doc, // doc
            'valid_fitness_inspection_date' => $request->fitness_date,
            'vehicle_fitness_due_date' => $request->fitness_due_date,
            'fitness_certificate' => $fitness_doc, // doc
            'puc_inspection_date' => $request->puc_inspection_date,
            'puc_inspection_due_date' => $request->puc_due_date,
            'puc_certificate' => $puc_doc, // doc
            'valid_road_permit_date' => $request->valid_road_permit_date,
            'valid_road_permit_due_date' => $request->valid_road_permit_due_date,
            'road_permit_certificate' => $road_permit_certificate, // doc


            'vehicle_dupted_for' => $request->vehicle_deputed_for,
            'dfms' => $request->dfms_available,
            'hatch_strainers' => $request->hatch_strainers,
            'gps_tracker' => $request->gps_tracker_available,
            'fuel_tank_strainers' => $request->fuel_tank_stainers,
            'battery_placment' => $request->battery_placement,
            'fire_extinguishers' => $request->fire_extinguisher,
            'first_aid_box' => $request->first_aid_box,
            'stepney' => $request->stepney,
            'scoth_block' => $request->scotch_block,
            'earth_chain' => $request->earth_block,

            'vessel_test_date' => $request->pressure_vessel_test_date,
            'vessel_due_date' => $request->pressure_vessel_due_date,
            'vessel_certiicate' => $vessel_certiicate, //doc
            'pressure_gauge_date' => $request->pressure_gauge_date,
            'pressure_gauge_due_date' => $request->pressure_gauge_due_date,
            'pressure_gauge_certificate' => $pressure_gauge_certificate, // doc
            'pressure_relief_test_date' => $request->pressure_relief_test_date,
            'pressure_relief_due_date' => $request->pressure_relief_due_date,
            'pressure_relief_certificate' => $pressure_relief_certificate, // doc



            'created_by' => $uid,
            'created_datetime' => now(),
            'vendor_id' => $uid,
            'status' => $status
        ];



        if ($request->id) {
            $vendor_silo_id = $request->id;
            $vendor_silo = DB::table('vendor_silo')
                ->where('id', $request->id)
                ->update($data);
            DB::table('vendor_silo_desired')->where('vendor_silo_id', $vendor_silo_id)->delete();
            DB::table('vendor_silo_flow')->where('vendor_silo_id', $vendor_silo_id)->delete();
        } else {

            $vendor_silo = $vendor_silo_id = DB::table('vendor_silo')->insertGetId($data);

        }

        // Insert into database


        if ($vendor_silo) {

            $datetime = date('Y-m-d H:i:s');
            // Define approval flow (manually or dynamically from DB)
            $loop = [
                ['type' => 'vendor', 'department_id' => 0, 'level' => 0, 'type_status' => 'New', 'schedule_safety' => 0],
                ['type' => 'inclusion', 'department_id' => $request->approver_id, 'level' => 1, 'type_status' => 'New', 'schedule_safety' => 0], // in department we put user_id for this row only
                ['type' => 'safety', 'department_id' => 2, 'level' => 2, 'type_status' => 'New', 'schedule_safety' => 1],
                ['type' => 'safety', 'department_id' => 2, 'level' => 3, 'type_status' => 'New', 'schedule_safety' => 0],
                ['type' => 'operation_dept', 'department_id' => 3, 'level' => 4, 'type_status' => 'New', 'schedule_safety' => 0]
            ];

            $desiredIds = [];

            foreach ($loop as $item) {
                $desired_id = DB::table('vendor_silo_desired')->insertGetId([
                    'vendor_silo_id' => $vendor_silo_id,
                    'type' => $item['type'],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],
                    'type_status' => 'New'
                ]);

                // Track desired ID by level
                if ($item['level'] != 0 && $item['level'] != 2) {
                    $desiredIds[$item['level']] = $desired_id;
                }
            }

            foreach ($loop as $item) {
                if ($item['level'] != 0 && $item['level'] != 2) {
                    DB::table('vendor_silo_flow')->insert([
                        'vendor_silo_id' => $vendor_silo_id,
                        'desired_id' => $desiredIds[$item['level']],
                        'department_id' => $item['department_id'],
                        'level' => $item['level'],
                        'created_datetime' => $datetime,
                        'status' => 'N',
                        'type' => $item['type'],
                        'schedule' => $item['schedule_safety']


                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully!',
                'id' => $vendor_silo_id
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Not Save!'
            ], 400); // You can also use 422 or 500 depending on the case

        }

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

        $vms_details = DB::table('vendor_silo')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_silo.edit', compact(
            'vms_details',
            'divs'


        ));

    }

    public function edit_ifream($id, $user_id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_silo')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_silo.edit_ifream', compact(
            'vms_details',
            'divs',
            'user_id'


        ));

    }

    public function edit_return($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vehicle_pass')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vms.edit_return', compact(
            'vms_details',
            'divs'


        ));

    }

    public function edit_entry($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_silo')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_silo.edit_entry', compact(
            'vms_details',
            'divs'


        ));

    }

    public function edit_data_ifream($id, $user_id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_silo')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_silo.edit_data_ifream', compact(
            'vms_details',
            'divs',
            'user_id'


        ));

    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'action' => 'required',
                'remarks' => 'required',
            ]);


            date_default_timezone_set('Asia/Kolkata');

            // if ($request->lag6_val > 0 && !$request->hasFile('lag6_doc')) {
            //     return response()->json([
            //         'errors' => [
            //             'lag6_doc' => ['Document required for No of Severity 4&5 Violation Reported']
            //         ]
            //     ], 422);

            // }


            if ($request->type == 'New') {
                $decision = $request->action;
                $remarks = $request->remarks;
                $flow_id = $request->flow_id;
                $schedule_date = $request->schedule_date ?? '';
                $datetime = date('Y-m-d H:i:s');



                DB::table('vendor_silo_flow')->where('id', $flow_id)->update([
                    'status' => 'Y',
                    'decision' => $decision,
                    'remarks' => $remarks,
                    'remarks_datetime' => $datetime

                ]);

                if ($decision == 'return') {
                    $flow = DB::table('vendor_silo_flow')->where('id', $flow_id)->where('type', 'New')->select('level')->first();
                    $level = $flow->level - 1;

                    $find_desired = DB::table('vendor_silo_desired')->where('level', $level)->where('vendor_silo_id', $id)->where('type_status', 'New')->first();

                    DB::table('vendor_silo_flow')->insert([
                        'vendor_silo_id' => $id,
                        'desired_id' => $find_desired->id,
                        'department_id' => $find_desired->department_id,
                        'level' => $find_desired->level,
                        'created_datetime' => $datetime,
                        'status' => 'N',
                        'type' => 'New',

                    ]);
                }

                if ($decision == 'approve') {
                    $flow = DB::table('vendor_silo_flow')->where('id', $flow_id)->where('type', 'New')->select('level')->first();
                    $level = $flow->level + 1;
                    $find_desired = DB::table('vendor_silo_desired')->where('level', $level)->where('type_status', 'New')->where('vendor_silo_id', $id)->first();
                    if (!empty($find_desired->id)) {

                        DB::table('vendor_silo_flow')->insert([
                            'vendor_silo_id' => $id,
                            'desired_id' => $find_desired->id,
                            'department_id' => $find_desired->department_id,
                            'level' => $find_desired->level,
                            'created_datetime' => $datetime,
                            'status' => 'N',
                            'type' => 'New',
                            'schedule_date' => $schedule_date
                        ]);

                        $check_current = DB::table('vendor_silo')->where('id', $id)->select('status')->first();
                        if ($check_current->status == 'pending_with_inclusion_user') {
                            $status = 'pending_with_safety_training';
                        } elseif ($check_current->status == 'pending_with_safety_training') {
                            $status = 'pending_with_operation_dept';
                        } elseif ($check_current->status == 'pending_with_operation_dept') {
                            $status = 'approve';
                        }

                        DB::table('vendor_silo')->where('id', $id)->update([
                            'status' => $status,
                        ]);
                    } else {
                        DB::table('vendor_silo')->where('id', $id)->update([
                            'status' => $decision,
                        ]);
                    }


                }

            } elseif ($request->type == 'Return') {

                $decision = $request->action;
                $remarks = $request->remarks;
                $flow_id = $request->flow_id;
                $datetime = date('Y-m-d H:i:s');



                DB::table('vendor_silo_flow')->where('id', $flow_id)->update([
                    'status' => 'Y',
                    'decision' => $decision,
                    'remarks' => $remarks,
                    'remarks_datetime' => $datetime
                ]);

                if ($decision == 'return') {
                    $flow = DB::table('vendor_silo_flow')->where('id', $flow_id)->where('type', 'Return')->select('level')->first();
                    $level = $flow->level - 1;

                    $find_desired = DB::table('vendor_silo_desired')->where('level', $level)->where('vendor_silo_id', $id)->where('type_status', 'Return')->first();

                    DB::table('vendor_silo_flow')->insert([
                        'vendor_silo_id' => $id,
                        'desired_id' => $find_desired->id,
                        'department_id' => $find_desired->department_id,
                        'level' => $find_desired->level,
                        'created_datetime' => $datetime,
                        'status' => 'N',
                        'type' => 'Return'
                    ]);
                }

                if ($decision == 'approve') {
                    $flow = DB::table('vendor_silo_flow')->where('id', $flow_id)->where('type', 'Return')->select('level')->first();
                    $level = $flow->level + 1;
                    $find_desired = DB::table('vendor_silo_desired')->where('level', $level)->where('vendor_silo_id', $id)->where('type_status', 'Return')->first();
                    if (!empty($find_desired->id)) {

                        DB::table('vendor_silo_flow')->insert([
                            'vendor_silo_id' => $id,
                            'desired_id' => $find_desired->id,
                            'department_id' => $find_desired->department_id,
                            'level' => $find_desired->level,
                            'created_datetime' => $datetime,
                            'status' => 'N',
                            'type' => 'Return'
                        ]);

                        $check_current = DB::table('vendor_silo')->where('id', $id)->select('return_status')->first();
                        if ($check_current->return_status == 'pending_with_inclusion_user') {
                            $status = 'pending_with_safety';
                        } elseif ($check_current->return_status == 'pending_with_safety') {
                            $status = 'approve';
                        }

                        DB::table('vendor_silo')->where('id', $id)->update([
                            'return_status' => $status,
                        ]);
                    } else {
                        DB::table('vendor_silo')->where('id', $id)->update([
                            'return_status' => $decision,
                        ]);
                    }


                }
            }

            return response()->json([
                'message' => 'Update processed successfully!',
                'data' => $id
            ]);

        } catch (\Exception $e) {
            // Optional: log the error
            //Log::error('Error in vendor MIS flow: ' . $e->getMessage());

            // Return error response
            return response()->json([
                'error' => true,
                'message' => 'An error occurred: ' . $e->getMessage(), // you can also return $e->getTraceAsString() for full trace
            ], 500);
        }

    }

    public function update_data(Request $request)
    {


        $id = $request->vendor_mis_id; // assuming the record id is passed

        // Fetch existing DB record
        $existing = DB::table('vendor_silo')->where('id', $id)->first();

        if (!$existing) {
            return back()->withErrors(['id' => 'Record not found']);
        }

        $request->validate([
            'division' => 'required|integer',
            'plant' => 'required|integer',
            'department' => 'required|integer',
            'month' => 'required',


        ]);
        $allowedMimeTypes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.ms-excel' // .xls
        ];

        $allowedMimeTypes = ['application/pdf'];
        $getMimeType = fn($file) => $file->getMimeType();
        $location = public_path('documents/vendor_mis');
        $uid = $request->uid ?? uniqid();
        $datetime = date('YmdHis');

        // Helper to process file upload based on value + existing db
        function handleUpdateDoc($request, $field_val, $field_doc, $index, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $existing)
        {
            if ($request->hasFile($field_doc)) {
                $mime = $getMimeType($request->file($field_doc));
                if (!in_array($mime, $allowedMimeTypes)) {
                    throw new \Exception("Invalid file type: $mime");
                }
                $filename = "{$uid}_{$datetime}_{$index}." . $request->file($field_doc)->getClientOriginalExtension();
                $request->file($field_doc)->move($location, $filename);
                return "documents/vendor_mis/" . $filename;
            }

            $val = $request->$field_val;
            $existing_doc = $existing->$field_doc;

            if ($val > 0 && !$existing_doc) {
                throw new \Exception("Document required for " . ucfirst(str_replace('_', ' ', $field_doc)));
            }

            return $existing_doc; // retain old file if exists
        }

        try {
            // Step 1: Prepare common update fields
            $updatedData = [
                'plant_id' => $request->plant,
                'division_id' => $request->division,
                'department_id' => $request->department,
                'month' => $request->month,
                'status' => 'pending_with_safety',
                'updated_datetime' => now()
            ];

            // Step 2: Handle all lead documents and values (1 to 10)
            for ($i = 1; $i <= 10; $i++) {
                $valField = "lead{$i}_val";
                $docField = "lead{$i}_doc";
                $updatedData[$valField] = $request->$valField;
                $updatedData[$docField] = handleUpdateDoc(
                    $request,
                    $valField,
                    $docField,
                    $i,
                    $uid,
                    $datetime,
                    $location,
                    $allowedMimeTypes,
                    $getMimeType,
                    $existing
                );
            }

            // Step 3: Handle all lag documents and values (1 to 6)
            for ($i = 1; $i <= 5; $i++) {
                $valField = "lag{$i}_val";
                $docField = "lag{$i}_doc";
                $updatedData[$valField] = $request->$valField;
                $updatedData[$docField] = handleUpdateDoc(
                    $request,
                    $valField,
                    $docField,
                    10 + $i,
                    $uid,
                    $datetime,
                    $location,
                    $allowedMimeTypes,
                    $getMimeType,
                    $existing
                );
            }

            // Step 4: Update main record
            DB::table('vendor_mis')->where('id', $id)->update($updatedData);

            // Step 5: Mark current flow as complete
            $flow = DB::table('vendor_mis_flow')->where('status', 'N')->where('vendor_mis_id', $id)->first();
            if ($flow) {
                $level = $flow->level + 1;

                DB::table('vendor_mis_flow')->where('id', $flow->id)->update([
                    'status' => 'Y',
                    'remarks' => $request->remarks ?? 'DONE',
                    'remarks_datetime' => now()
                ]);

                // Step 6: Insert next level flow if exists
                $find_desired = DB::table('vendor_mis_desired')
                    ->where('level', $level)
                    ->where('vendor_mis_id', $id)
                    ->first();

                if ($find_desired) {
                    DB::table('vendor_mis_flow')->insert([
                        'vendor_mis_id' => $id,
                        'desired_id' => $find_desired->id,
                        'department_id' => $find_desired->department_id,
                        'level' => $find_desired->level,
                        'created_datetime' => now(),
                        'status' => 'N',
                    ]);
                }
            }

            return response()->json([
                'message' => 'Updated successfully!',
                'message_type' => 'success'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'message_type' => 'error'
            ], 500);
        }




    }

    public function edit_data_update(Request $request)
    {


        $id = $request->vendor_mis_id; // assuming the record id is passed

        // Fetch existing DB record
        $existing = DB::table('vendor_mis')->where('id', $id)->first();

        if (!$existing) {
            return back()->withErrors(['id' => 'Record not found']);
        }

        $request->validate([
            'division' => 'required|integer',
            'plant' => 'required|integer',
            'department' => 'required|integer',
            'month' => 'required',


        ]);
        $allowedMimeTypes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.ms-excel' // .xls
        ];

        $allowedMimeTypes = ['application/pdf'];
        $getMimeType = fn($file) => $file->getMimeType();
        $location = public_path('documents/vendor_mis');
        $uid = $request->uid ?? uniqid();
        $datetime = date('YmdHis');

        // Helper to process file upload based on value + existing db
        function handleUpdateDoc($request, $field_val, $field_doc, $index, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $existing)
        {
            if ($request->hasFile($field_doc)) {
                $mime = $getMimeType($request->file($field_doc));
                if (!in_array($mime, $allowedMimeTypes)) {
                    throw new \Exception("Invalid file type: $mime");
                }
                $filename = "{$uid}_{$datetime}_{$index}." . $request->file($field_doc)->getClientOriginalExtension();
                $request->file($field_doc)->move($location, $filename);
                return "documents/vendor_mis/" . $filename;
            }

            $val = $request->$field_val;
            $existing_doc = $existing->$field_doc;

            if ($val > 0 && !$existing_doc) {
                throw new \Exception("Document required for " . ucfirst(str_replace('_', ' ', $field_doc)));
            }

            return $existing_doc; // retain old file if exists
        }

        try {
            // Step 1: Prepare common update fields
            $updatedData = [
                'plant_id' => $request->plant,
                'division_id' => $request->division,
                'department_id' => $request->department,
                'month' => $request->month,

                'updated_datetime' => now()
            ];

            // Step 2: Handle all lead documents and values (1 to 10)
            for ($i = 1; $i <= 10; $i++) {
                $valField = "lead{$i}_val";
                $docField = "lead{$i}_doc";
                $updatedData[$valField] = $request->$valField;
                $updatedData[$docField] = handleUpdateDoc(
                    $request,
                    $valField,
                    $docField,
                    $i,
                    $uid,
                    $datetime,
                    $location,
                    $allowedMimeTypes,
                    $getMimeType,
                    $existing
                );
            }

            // Step 3: Handle all lag documents and values (1 to 6)
            for ($i = 1; $i <= 5; $i++) {
                $valField = "lag{$i}_val";
                $docField = "lag{$i}_doc";
                $updatedData[$valField] = $request->$valField;
                $updatedData[$docField] = handleUpdateDoc(
                    $request,
                    $valField,
                    $docField,
                    10 + $i,
                    $uid,
                    $datetime,
                    $location,
                    $allowedMimeTypes,
                    $getMimeType,
                    $existing
                );
            }

            // Step 4: Update main record
            DB::table('vendor_mis')->where('id', $id)->update($updatedData);



            return response()->json([
                'message' => 'Updated successfully!',
                'message_type' => 'success'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'message_type' => 'error'
            ], 500);
        }




    }

    public function return_silo(Request $request)
    {
        $id = $request->id;
        $reason = $request->reason;
        $datetime = date('Y-m-d H:i:s');
        // Define approval flow (manually or dynamically from DB)
        $loop = [
            ['type' => 'vendor', 'department_id' => 0, 'level' => 0, 'type_status' => 'Return'],
            ['type' => 'inclusion', 'department_id' => $request->approver_id, 'level' => 1, 'type_status' => 'Return'], // in department we put user_id for this row only
            ['type' => 'safety', 'department_id' => 2, 'level' => 2, 'type_status' => 'Return']
        ];

        $desiredIds = [];

        foreach ($loop as $item) {
            $desired_id = DB::table('vendor_silo_desired')->insertGetId([
                'vendor_silo_id' => $id,
                'type' => $item['type'],
                'department_id' => $item['department_id'],
                'level' => $item['level'],
                'type_status' => 'Return'
            ]);

            // Track desired ID by level
            if ($item['level'] != 0 && $item['level'] != 2) {
                $desiredIds[$item['level']] = $desired_id;
            }
        }

        foreach ($loop as $item) {
            if ($item['level'] != 0 && $item['level'] != 2) {
                DB::table('vendor_silo_flow')->insert([
                    'vendor_silo_id' => $id,
                    'desired_id' => $desiredIds[$item['level']],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],
                    'created_datetime' => $datetime,
                    'status' => 'N',
                    'type' => 'Return'

                ]);
            }
        }


        $update = DB::table('vendor_silo')->where('id', $id)->update([
            'return_status' => 'pending_with_inclusion_user',
            'return_datetime' => $datetime,
            'return_reason' => $reason
        ]);
        if ($update) {
            // ✅ Return JSON for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Tanker excluded successfully!',

            ]);
        } else {
            // ✅ Return JSON for AJAX
            return response()->json([
                'success' => false,
                'message' => 'Tanker excluded Not successfully!',

            ]);
        }
    }
    public function getDepartment($id)
    {


        $divisions = DB::table('divisions')
            ->where('div_id', $id)
            ->select('id', 'name')
            ->get();

        return $divisions;
    }
    public function getPlant($id)
    {


        $department = DB::table('departments')
            ->where('division_id', $id)
            ->select('id', 'department_name')
            ->get();

        return $department;
    }

    public function autocomplete_silo(Request $request)
    {


        $data = DB::table('work_order')->select('order_code', 'id')
            ->where('order_code', 'LIKE', '%' . $request->get('search') . '%')
            // ->where('division_id', Session::get('user_DivID_Session'))
            ->get();

        return response()->json($data);



        //echo json_encode($return_arr);
        //  $da=json_decode($return_arr);
        // return response()->json($da[0]->order_code);
        // return response()->json($da[0]->order_code);
    }
    public function autoworkorder_silo($id)
    {
        $order_validity = DB::table('work_order')
            ->select('order_validity')
            ->where('order_code', $id)
            // ->where('division_id', Session::get('user_DivID_Session'))
            ->first(); // use ->first() instead of ->get() for single row

        if ($order_validity && isset($order_validity->order_validity)) {
            return response()->json($order_validity->order_validity);
        } else {
            return response()->json(['error' => 'No order found or order_validity missing'], 404);
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
