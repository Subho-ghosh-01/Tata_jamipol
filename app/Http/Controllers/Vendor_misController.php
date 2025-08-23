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


class Vendor_misController extends Controller
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
        return view('admin.vendor_mis.index', compact('divisions', 'id'));
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

        return view('admin.vendor_mis.create_ifream', compact(
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

        return view('admin.vendor_mis.create', compact(
            'divisions',
            'id',
            'divs'

        ));
    }



    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'division' => 'required|integer',
            'plant' => 'required|integer',
            'department' => 'required|integer',
            'report_month' => 'required',
        ];
        $request->validate($rules);

        $allowedMimeTypes = ['application/pdf'];
        $location = public_path('documents/vendor_mis');
        $uid = $request->uid; // logged-in user id
        $datetime = date('YmdHis');
        $errors = [];

        // Helper: handle multiple uploaded files
        $handleDocs = function ($field) use ($request, $location, $uid, $datetime, $allowedMimeTypes, &$errors) {
            $paths = [];
            if (!$request->hasFile($field))
                return [];

            $files = is_array($request->file($field)) ? $request->file($field) : [$request->file($field)];
            foreach ($files as $index => $file) {
                $mime = $file->getMimeType();
                if (!in_array($mime, $allowedMimeTypes)) {
                    $errors[$field][] = 'Only PDF files allowed. Uploaded: ' . $mime;
                    continue;
                }
                $filename = "{$uid}_{$datetime}_{$field}_{$index}." . $file->getClientOriginalExtension();
                $file->move($location, $filename);
                $paths[] = "documents/vendor_mis/" . $filename;
            }
            return $paths;
        };

        // Lead docs 1-10
        $leadDocs = [];
        for ($i = 1; $i <= 10; $i++) {
            $field = "lead{$i}_doc";
            $valField = "lead{$i}_val";
            $val = $request->$valField ?? 0;

            // Get existing docs from hidden inputs
            $existingDocs = $request->input("lead{$i}_existing", []); // array of paths

            // Handle uploaded files
            $uploadedDocs = $handleDocs($field);

            // Merge existing + uploaded
            $allDocs = array_merge($existingDocs, $uploadedDocs);

            $isOptional = ($i === 1); // Lead 1 optional
            if (!$isOptional && $val > 0 && count($allDocs) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Document required for Lead $i"
                ], 422);
            }

            $leadDocs[$field] = $allDocs;
        }

        // Lag docs 1-6
        $lagDocs = [];
        for ($i = 1; $i <= 6; $i++) {
            $field = "lag{$i}_doc";
            $valField = "lag{$i}_val";
            $val = $request->$valField ?? 0;

            $existingDocs = $request->input("lag{$i}_existing", []);
            $uploadedDocs = $handleDocs($field);
            $allDocs = array_merge($existingDocs, $uploadedDocs);

            if ($val > 0 && count($allDocs) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Document required for Lag $i"
                ], 422);
            }

            $lagDocs[$field] = $allDocs;
        }

        // Determine status
        if ($request->input('status') == 'draft') {
            $draft = 'Y';
            $status_main = 'draft';
        } elseif ($request->input('status') == 'final') {
            $draft = 'N';
            $status_main = 'pending_with_safety';
        }

        // Prepare main table data
        $data = [
            'plant_id' => $request->plant,
            'division_id' => $request->division,
            'department_id' => $request->department,
            'month' => $request->report_month,
            'created_by' => $uid,
            'vendor_id' => $uid,
            'created_datetime' => now(),
            'status' => $status_main,
            'draft' => $draft
        ];

        // Add lead values/docs
        for ($i = 1; $i <= 10; $i++) {
            $data["lead{$i}_val"] = $request->input("lead{$i}_val", 0);
            $data["lead{$i}_doc"] = json_encode($leadDocs["lead{$i}_doc"]);
        }

        // Add lag values/docs
        for ($i = 1; $i <= 6; $i++) {
            $data["lag{$i}_val"] = $request->input("lag{$i}_val", 0);
            $data["lag{$i}_doc"] = json_encode($lagDocs["lag{$i}_doc"]);
        }

        // Insert or Update
        if ($request->filled('id')) {
            $vendor_mis_id = $request->id;
            DB::table('vendor_mis')->where('id', $vendor_mis_id)->update($data);

            // Delete old child rows
            DB::table('vendor_mis_desired')->where('vendor_mis_id', $vendor_mis_id)->delete();
            DB::table('vendor_mis_flow')->where('vendor_mis_id', $vendor_mis_id)->delete();
        } else {
            $vendor_mis_id = DB::table('vendor_mis')->insertGetId($data);
        }

        // Approval flow (child tables)
        $datetimeNow = now();
        $loop = [
            ['type' => 'vendor', 'department_id' => 0, 'level' => 0],
            ['type' => 'safety', 'department_id' => 1, 'level' => 1]
        ];

        $desiredIds = [];
        foreach ($loop as $item) {
            $desired_id = DB::table('vendor_mis_desired')->insertGetId([
                'vendor_mis_id' => $vendor_mis_id,
                'type' => $item['type'],
                'department_id' => $item['department_id'],
                'level' => $item['level'],
            ]);
            if ($item['level'] != 0)
                $desiredIds[$item['level']] = $desired_id;
        }

        foreach ($loop as $item) {
            if ($item['level'] != 0) {
                DB::table('vendor_mis_flow')->insert([
                    'vendor_mis_id' => $vendor_mis_id,
                    'desired_id' => $desiredIds[$item['level']],
                    'department_id' => $item['department_id'],
                    'level' => $item['level'],
                    'created_datetime' => $datetimeNow,
                    'status' => 'N',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'id' => $vendor_mis_id,
            'message' => 'Data saved successfully!'
        ]);
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
