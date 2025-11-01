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

        $user = UserLogin::select('user_sub_type')->where('id', $uid)->first();
        $usertype = $user->user_sub_type ?? null;
        // Determine status
        if ($request->input('status') == 'draft') {
            $draft = 'Y';
            $status_main = 'draft';
        } elseif ($request->input('status') == 'final') {
            $draft = 'N';
            if ($usertype == 3) {
                $status_main = 'approve';
            } else {
                $status_main = 'pending_with_safety';

            }

        }



        // Insert or Update
        if ($request->filled('id')) {
            $updateData = [
                'plant_id' => $request->plant,
                'division_id' => $request->division,
                'department_id' => $request->department,
                'month' => $request->report_month,
                'updated_by' => $uid,
                'updated_datetime' => now(),
                'status' => $status_main,
                'draft' => $draft
            ];

            // Add lead values/docs to update data
            for ($i = 1; $i <= 10; $i++) {
                $updateData["lead{$i}_val"] = $request->input("lead{$i}_val", 0);
                $updateData["lead{$i}_doc"] = json_encode($leadDocs["lead{$i}_doc"]);
            }

            // Add lag values/docs to update data
            for ($i = 1; $i <= 6; $i++) {
                $updateData["lag{$i}_val"] = $request->input("lag{$i}_val", 0);
                $updateData["lag{$i}_doc"] = json_encode($lagDocs["lag{$i}_doc"]);
            }

            $vendor_mis_id = $request->id;
            DB::table('vendor_mis')->where('id', $vendor_mis_id)->update($updateData);

            // Delete old child rows
            DB::table('vendor_mis_desired')->where('vendor_mis_id', $vendor_mis_id)->delete();
            DB::table('vendor_mis_flow')->where('vendor_mis_id', $vendor_mis_id)->delete();
        } else {
            $insertData = [
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

            // Add lead values/docs to insert data
            for ($i = 1; $i <= 10; $i++) {
                $insertData["lead{$i}_val"] = $request->input("lead{$i}_val", 0);
                $insertData["lead{$i}_doc"] = json_encode($leadDocs["lead{$i}_doc"]);
            }

            // Add lag values/docs to insert data
            for ($i = 1; $i <= 6; $i++) {
                $insertData["lag{$i}_val"] = $request->input("lag{$i}_val", 0);
                $insertData["lag{$i}_doc"] = json_encode($lagDocs["lag{$i}_doc"]);
            }

            $vendor_mis_id = DB::table('vendor_mis')->insertGetId($insertData);
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


    public function filterJson(Request $request)
    {
        $query = DB::table('vendor_mis');

        // Date filters
        if ($request->from_date) {
            // convert '2025-09' → '2025-09-01'
            $fromDate = $request->from_date . '-01';
            $query->whereDate('created_datetime', '>=', $fromDate);
        }

        if ($request->to_date) {
            // convert '2025-09' → '2025-09-30' (last day of month)
            $toDate = date('Y-m-t', strtotime($request->to_date . '-01'));
            $query->whereDate('created_datetime', '<=', $toDate);
        }


        $query->where('status', 'approve');

        if ($request->vid) {
            $query->where('vendor_id', $request->vid);
        }

        // Division filter
        if ($request->division) {
            $query->where('division_id', $request->division);
        }

        // Plant filter
        if ($request->plant) {
            $query->where('plant_id', $request->plant);
        }

        // Department filter
        if ($request->department) {
            $query->where('department_id', $request->department);
        }

        $query->where('status', 'approve');

        // Draft filter


        $data = $query->get()->map(function ($row) {
            $createdUser = UserLogin::find($row->created_by);

            // Careful: Your mapping of division/plant was swapped in old code
            $divisionName = Division::find($row->plant_id);
            $plantName = DB::table('division_new')->find($row->division_id);
            $departmentName = Department::find($row->department_id);

            return [
                'division_id' => $divisionName ? $divisionName->name : '',
                'plant_id' => $plantName ? $plantName->name : '',
                'department_id' => $departmentName ? $departmentName->department_name : '',
                'month' => $row->month,

                'lead1_val' => $row->lead1_val,
                'lead2_val' => $row->lead2_val,
                'lead3_val' => $row->lead3_val,
                'lead4_val' => $row->lead4_val,
                'lead5_val' => $row->lead5_val,
                'lead6_val' => $row->lead6_val,
                'lead7_val' => $row->lead7_val,
                'lead8_val' => $row->lead8_val,
                'lead9_val' => $row->lead9_val,
                'lead10_val' => $row->lead10_val,

                'lag1_val' => $row->lag1_val,
                'lag2_val' => $row->lag2_val,
                'lag3_val' => $row->lag3_val,
                'lag4_val' => $row->lag4_val,
                'lag5_val' => $row->lag5_val,
                'lag6_val' => $row->lag6_val,

                'vendor_id' => $row->vendor_id,
                'created_by' => $createdUser ? $createdUser->name : 'N/A',
                'created_datetime' => $row->created_datetime,
                'updated_by' => $row->updated_by,
                'updated_datetime' => $row->updated_datetime,
                'status' => $row->status,
                'draft' => $row->draft,

                'vendor_code' => $createdUser ? $createdUser->vendor_code : null,
            ];
        });

        return response()->json(['data' => $data]);
    }



    public function filterJson_dashboard(Request $request)
    {
        $query = DB::table('vendor_mis');

        // Date filter
        if ($request->from_date) {
            $fromDate = $request->from_date . '-01';
            $query->whereDate('created_datetime', '>=', $fromDate);
        }
        if ($request->to_date) {
            $toDate = date('Y-m-t', strtotime($request->to_date . '-01'));
            $query->whereDate('created_datetime', '<=', $toDate);
        }

        // Vendor filter
        if ($request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Status filter
        if ($request->status && $request->status !== "All") {
            $query->where('status', $request->status);
        }

        // Draft filter
        if (!is_null($request->draft) && $request->draft !== '') {
            $query->where('draft', $request->draft);
        }

        if ($request->division) {
            $query->where('division_id', $request->division);
        }
        if ($request->plant) {
            $query->where('plant_id', $request->plant);
        }
        if ($request->department) {
            $query->where('department_id', $request->department);
        }

        if (Session::get('user_typeSession') == 1 && Session::get(key: 'user_sub_typeSession') == 3) {

        } else if (Session::get('user_typeSession') == 1) {
            $query->where('plant_id', Session::get('user_DivID_Session'));
        } else {
            $query->where('plant_id', Session::get('user_DivID_Session'));

        }
        $query->where('status', 'approve');
        $data = $query->get();

        // Handle vendor comparison request
        if ($request->vendor_comparison) {
            $vendorData = $data->groupBy('vendor_id')->map(function ($vendorRecords, $vendorId) {
                $vendor = UserLogin::find($vendorId);
                $totals = ['vendor_id' => $vendorId, 'vendor_name' => $vendor ? $vendor->name : 'Unknown'];

                for ($i = 1; $i <= 10; $i++) {
                    $totals['lead' . $i . '_val'] = $vendorRecords->sum("lead{$i}_val");
                }

                for ($i = 1; $i <= 6; $i++) {
                    $totals['lag' . $i . '_val'] = $vendorRecords->sum("lag{$i}_val");
                }

                return $totals;
            })->values();

            return response()->json([
                'status' => true,
                'vendor_data' => $vendorData
            ]);
        }

        // Total records
        $total = $data->count();

        // Status wise counts
        $approved = $data->where('status', 'approve')->count();
        $rejected = $data->where('status', 'reject')->count();
        $pending = $data->whereIn('status', ['pending', 'pending_with_safety'])->count();

        // Draft counts
        $drafts = $data->where('draft', 'Y')->count();
        $nonDrafts = $data->where('draft', 'N')->count();

        // Initialize Lead & Lag arrays
        $leadCounts = [];
        $lagCounts = [];

        for ($i = 1; $i <= 10; $i++) {
            $leadCounts['lead' . $i . '_val'] = $data->sum("lead{$i}_val");
        }

        for ($i = 1; $i <= 6; $i++) {
            $lagCounts['lag' . $i . '_val'] = $data->sum("lag{$i}_val");
        }

        return response()->json([
            'status' => true,
            'counts' => array_merge([
                'total' => $total,
                'approved' => $approved,
                'rejected' => $rejected,
                'pending' => $pending,
                'drafts' => $drafts,
                'nonDrafts' => $nonDrafts
            ], $leadCounts, $lagCounts)
        ]);
    }



    public function mis_report($mis9_report = null)
    {


        $divisions = DB::table('division_new')->get();
        if (Session::get('user_sub_typeSession') == 3 && Session::get('user_typeSession') == 1) {
            $vendors = UserLogin::where('user_type', 2)->get();
        } else if (Session::get('user_typeSession') == 1) {
            $vendors = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))->get();
        } else {
            $vendors = UserLogin::Where('id', Session::get('user_idSession'))->get();
        }
        return view(
            'admin.vendor_mis.mis_report',
            compact(
                'divisions',
                'vendors'

            )
        );

    }

    public function mis_dashboard($mis_report = null)
    {
        //   $divisions = Division::all();

        if (Session::get('user_sub_typeSession') == 3 && Session::get('user_typeSession') == 1) {
            $vendors = UserLogin::where('user_type', 2)->get();
        } else if (Session::get('user_typeSession') == 1) {
            $vendors = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))->get();
        } else {
            $vendors = UserLogin::Where('id', Session::get('user_idSession'))->get();
        }
        $divisions = DB::table('division_new')->get();
        return view(
            'admin.vendor_mis.mis_dashboard',
            compact(
                'divisions',
                'vendors'
            )
        );
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

    public function edit_ifream($id, $user_id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit_ifream', compact(
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

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit_entry', compact(
            'vms_details',
            'divs'


        ));

    }

    public function edit_data_ifream($id, $user_id)
    {


        if (!$id) {
            return redirect()->back()->with('error', 'No ID provided');
        }

        $vms_details = DB::table('vendor_mis')->where('id', $id)->orderBy('id', 'desc')->first();
        $divs = DB::table('division_new')->get();
        return view('admin.vendor_mis.edit_data_ifream', compact(
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


        $divisions = Division::
            where('div_id', $id)
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

    public function getvendor($id)
    {

        if (Session::get('user_sub_typeSession') == 3 && Session::get('user_typeSession') == 1) {
            $vendors = UserLogin::where('user_type', 2)->get();
        } else if (Session::get('user_typeSession') == 1) {
            $vendors = UserLogin::where('user_type', 2)->where('division_id', Session::get('user_DivID_Session'))->get();
        } else {
            $vendors = UserLogin::Where('id', Session::get('user_idSession'))->get();
        }

        //$vendors = UserLogin::where('user_type', 2)->get();
        return $vendors;
    }

    public function getVendorsByPlant($plantId)
    {
        $vendors = DB::table('userlogins')->where('division_id', $plantId)->where('user_type', 2)->get();

        return response()->json($vendors);
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
