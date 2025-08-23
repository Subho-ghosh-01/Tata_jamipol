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
        // $id = request()->get('user_id');
        $id = Session::get('user_idSession');
        $divisions = Division::all();
        return view('admin.vendor_silo.index', compact('divisions', 'id'));
    }
    public function getVmsList()
    {
        $query = DB::table('vendor_mis')
            ->leftJoin('divisions', 'vendor_mis.division_id', '=', 'divisions.id')
            ->leftJoin('departments', 'vendor_mis.department_id', '=', 'departments.id')
            ->leftJoin('userlogins', 'vendor_mis.vendor_id', '=', 'userlogins.id')
            ->select(
                'vendor_mis.*',
                'divisions.name as division_name',
                'departments.department_name',
                'userlogins.name as vendor_name'
            )
            ->orderBy('vendor_mis.id', 'desc');
        // Apply filtering based on session conditions
        if (Session::get('user_sub_typeSession') != 3) {
            if (Session::get('clm_role') == 'safety_dept') {
                $query->where('vendor_mis.status', 'Pending_with_safety');
            } else {
                $query->where('vendor_mis.created_by', Session::get('user_idSession'));
            }
        }

        $vms_lists = $query->get();

        // Encrypt ID for each item
        $vms_lists = $vms_lists->map(function ($item) {
            $item->enc_id = Crypt::encrypt($item->id);
            return $item;
        });

        return response()->json([
            'status' => 'ok',
            'data' => $vms_lists
        ]);
    }


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


        $rules = [
            'division' => 'required|integer',
            'plant' => 'required|integer',
            'department' => 'required|integer',
            'month' => 'required',
        ];

        // for ($i = 1; $i <= 10; $i++) {
        //     $rules["lead{$i}_val"] = 'required|integer|min:0';
        //     $rules["lead{$i}_doc"] = 'nullable|file|mimes:pdf|max:2048';
        // }

        // for ($i = 1; $i <= 5; $i++) {
        //     $rules["lag{$i}_val"] = 'required|integer|min:0';
        //     $rules["lag{$i}_doc"] = 'nullable|file|mimes:pdf|max:2048';
        // }

        $request->validate($rules);


        // Manual conditional validation (val > 0 => doc required)
        if ($request->lead1_val > 0 && !$request->hasFile('lead1_doc')) {
            return back()->withErrors(['lead1_doc' => 'Document required for Lead 1'])->withInput();
        }
        if ($request->lead2_val > 0 && !$request->hasFile('lead2_doc')) {
            return back()->withErrors(['lead2_doc' => 'Document required for Lead 2'])->withInput();
        }
        if ($request->lead3_val > 0 && !$request->hasFile('lead3_doc')) {
            return back()->withErrors(['lead3_doc' => 'Document required for Lead 3'])->withInput();
        }
        if ($request->lead4_val > 0 && !$request->hasFile('lead4_doc')) {
            return back()->withErrors(['lead4_doc' => 'Document required for Lead 4'])->withInput();
        }
        if ($request->lead5_val > 0 && !$request->hasFile('lead5_doc')) {
            return back()->withErrors(['lead5_doc' => 'Document required for Lead 5'])->withInput();
        }
        if ($request->lead6_val > 0 && !$request->hasFile('lead6_doc')) {
            return back()->withErrors(['lead6_doc' => 'Document required for Lead 6'])->withInput();
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

        $location = public_path('documents/vendor_mis');
        $uid = $request->uid ?? uniqid();
        $datetime = date('YmdHis');

        // Initialize variables
        $lead1_doc = $lead2_doc = $lead3_doc = $lead4_doc = $lead5_doc = null;
        $lead6_doc = $lead7_doc = $lead8_doc = $lead9_doc = $lead10_doc = null;
        $lag1_doc = $lag2_doc = $lag3_doc = $lag4_doc = $lag5_doc = null;

        // Helper for moving and setting path
        function handleDoc($request, $field, $index, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, &$errors)
        {
            if (empty($field) || !$request->hasFile($field)) {
                return null;
            }

            $file = $request->file($field);
            $mime = $getMimeType($file);

            if (!in_array($mime, $allowedMimeTypes)) {
                $errors[$field] = 'Only PDF files are allowed. Uploaded type: ' . $mime;
                return null; // Return nothing, but log error
            }

            $filename = "{$uid}_{$datetime}_{$index}." . $file->getClientOriginalExtension();
            $file->move($location, $filename);
            return "documents/vendor_mis/" . $filename;
        }
        // Lead documents (1 to 10)
        $lead1_doc = handleDoc($request, 'lead1_doc', 1, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead2_doc = handleDoc($request, 'lead2_doc', 2, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead3_doc = handleDoc($request, 'lead3_doc', 3, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead4_doc = handleDoc($request, 'lead4_doc', 4, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead5_doc = handleDoc($request, 'lead5_doc', 5, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead6_doc = handleDoc($request, 'lead6_doc', 6, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead7_doc = handleDoc($request, 'lead7_doc', 7, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead8_doc = handleDoc($request, 'lead8_doc', 8, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead9_doc = handleDoc($request, 'lead9_doc', 9, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lead10_doc = handleDoc($request, 'lead10_doc', 10, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);

        // Lag documents (1 to 6)
        $lag1_doc = handleDoc($request, 'lag1_doc', 11, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lag2_doc = handleDoc($request, 'lag2_doc', 12, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lag3_doc = handleDoc($request, 'lag3_doc', 13, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lag4_doc = handleDoc($request, 'lag4_doc', 14, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);
        $lag5_doc = handleDoc($request, 'lag5_doc', 15, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType, $errors);



        // Insert into database
        $vendor_mis = DB::table('vendor_mis')->insert([
            'plant_id' => $request->plant,
            'division_id' => $request->division,
            'department_id' => $request->department,
            'month' => $request->month,

            'lead1_val' => $request->lead1_val,
            'lead1_doc' => $lead1_doc,
            'lead2_val' => $request->lead2_val,
            'lead2_doc' => $lead2_doc,
            'lead3_val' => $request->lead3_val,
            'lead3_doc' => $lead3_doc,
            'lead4_val' => $request->lead4_val,
            'lead4_doc' => $lead4_doc,
            'lead5_val' => $request->lead5_val,
            'lead5_doc' => $lead5_doc,
            'lead6_val' => $request->lead6_val,
            'lead6_doc' => $lead6_doc,
            'lead7_val' => $request->lead7_val,
            'lead7_doc' => $lead7_doc,
            'lead8_val' => $request->lead8_val,
            'lead8_doc' => $lead8_doc,
            'lead9_val' => $request->lead9_val,
            'lead9_doc' => $lead9_doc,
            'lead10_val' => $request->lead10_val,
            'lead10_doc' => $lead10_doc,
            'lag1_val' => $request->lag1_val,
            'lag1_doc' => $lag1_doc,
            'lag2_val' => $request->lag2_val,
            'lag2_doc' => $lag2_doc,
            'lag3_val' => $request->lag3_val,
            'lag3_doc' => $lag3_doc,
            'lag4_val' => $request->lag4_val,
            'lag4_doc' => $lag4_doc,
            'lag5_val' => $request->lag5_val,
            'lag5_doc' => $lag5_doc,


            'created_by' => $uid,
            'created_datetime' => now(),
            'vendor_id' => $uid,
            'status' => 'pending_with_safety'
        ]);

        if ($vendor_mis) {
            $lastId = DB::getPdo()->lastInsertId();
            $datetime = date('Y-m-d H:i:s');
            // Define approval flow (manually or dynamically from DB)
            $loop = [
                ['type' => 'vendor', 'department_id' => 0, 'level' => 0],
                ['type' => 'safety', 'department_id' => 1, 'level' => 1]
            ];

            $desiredIds = [];

            foreach ($loop as $item) {
                $desired_id = DB::table('vendor_mis_desired')->insertGetId([
                    'vendor_mis_id' => $lastId,
                    'type' => $item['type'],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],
                ]);

                // Track desired ID by level
                if ($item['level'] != 0) {
                    $desiredIds[$item['level']] = $desired_id;
                }
            }

            foreach ($loop as $item) {
                if ($item['level'] != 0) {
                    DB::table('vendor_mis_flow')->insert([
                        'vendor_mis_id' => $lastId,
                        'desired_id' => $desiredIds[$item['level']],
                        'department_id' => $item['department_id'],
                        'level' => $item['level'],
                        'created_datetime' => $datetime,
                        'status' => 'N',

                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully!'
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

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit', compact(
            'vms_details',
            'divs'


        ));

    }

    public function edit_ifream($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit_ifream', compact(
            'vms_details',
            'divs'


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

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit_entry', compact(
            'vms_details',
            'divs'


        ));

    }

    public function edit_data_ifream($id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit_data_ifream', compact(
            'vms_details',
            'divs'


        ));

    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'action' => 'required',
                'remarks' => 'required',
                'lag6_doc' => 'nullable|file|mimes:pdf|max:2048',
            ]);


            date_default_timezone_set('Asia/Kolkata');
            $now = now();
            $allowedMimeTypes = ['application/pdf'];
            $getMimeType = function ($file) {
                return $file->getMimeType();
            };

            $location = public_path('documents/vendor_mis');
            $uid = uniqid();
            $datetime = date('YmdHis');

            // if ($request->lag6_val > 0 && !$request->hasFile('lag6_doc')) {
            //     return response()->json([
            //         'errors' => [
            //             'lag6_doc' => ['Document required for No of Severity 4&5 Violation Reported']
            //         ]
            //     ], 422);

            // }

            $lag6_doc = null;
            function handleDoc($request, $field, $index, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType)
            {
                // ✅ Check if field is null or empty string
                if (empty($field) || !$request->hasFile($field)) {
                    return null; // No file uploaded or invalid field
                }

                $file = $request->file($field);

                // ✅ Check MIME type
                $mime = $getMimeType($file);
                if (!in_array($mime, $allowedMimeTypes)) {
                    throw new \Exception("Invalid type: $mime");
                }

                // ✅ Generate filename and move file
                $filename = "{$uid}_{$datetime}_{$index}." . $file->getClientOriginalExtension();
                $file->move($location, $filename);

                return "documents/vendor_mis/" . $filename;
            }

            $lag6_doc = handleDoc($request, 'lag6_doc', 16, $uid, $datetime, $location, $allowedMimeTypes, $getMimeType);
            $decision = $request->action;
            $remarks = $request->remarks;
            $flow_id = $request->flow_id;
            $datetime = date('Y-m-d H:i:s');

            DB::table('vendor_mis_flow')->where('id', $flow_id)->update([
                'status' => 'Y',
                'decision' => $decision,
                'remarks' => $remarks,
                'remarks_datetime' => $datetime
            ]);

            if ($decision == 'return') {
                $flow = DB::table('vendor_mis_flow')->where('id', $flow_id)->select('level')->first();
                $level = $flow->level - 1;

                $find_desired = DB::table('vendor_mis_desired')->where('level', $level)->where('vendor_mis_id', $id)->first();

                DB::table('vendor_mis_flow')->insert([
                    'vendor_mis_id' => $id,
                    'desired_id' => $find_desired->id,
                    'department_id' => $find_desired->department_id,
                    'level' => $find_desired->level,
                    'created_datetime' => $datetime,
                    'status' => 'N'
                ]);
            }

            DB::table('vendor_mis')->where('id', $id)->update([
                'status' => $decision,
                'lag6_val' => $request->lag6_val,
                'lag6_doc' => $lag6_doc
            ]);

            return response()->json([
                'message' => 'Update processed successfully!',
                'data' => $id
            ]);

        } catch (\Exception $e) {
            // Optional: log the error
            Log::error('Error in vendor MIS flow: ' . $e->getMessage());

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
