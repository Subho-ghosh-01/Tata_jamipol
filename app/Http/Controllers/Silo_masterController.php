<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\Department;
use App\JobLinking;
//use App\JobLinking;
use Illuminate\Support\Facades\DB;
use Session;


class Silo_masterController extends Controller
{

    public function index()
    {
        $silo_master = DB::table('silo_master')->orderBy('id', 'desc')->get();
        return view('admin.silo_master.index', compact('silo_master'));
    }


    public function create()
    {
        $divs = DB::table('division_new')->get();
        return view('admin.silo_master.create', compact('divs'));
    }


    // public function store11(Request $request)
    // {
    //     // dd( $request->all());
    //     $request->validate([
    //         'name' => 'required',
    //         'abbreviation' => 'required'
    //     ]);
    //     //$division = DB::table('users')::create([
    //     $division = Division::create([
    //         'name' => $request->name,
    //         'abbreviation' => $request->abbreviation,
    //         'div_id' => $request->division_id

    //     ]);

    //     if ($division) {
    //         return back()->with('message', 'Division Added Successfully');
    //     } else {
    //         return back()->with('message', 'Error While Addeding Division');

    //     }
    // }
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:silo_master,name',
            'label' => 'required|string|max:200',
            'type' => 'required|string|max:50',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:200',
            'required' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
            'multiple' => 'nullable|boolean', // ✅ new
        ]);

        // Normalize data
        $options = null;
        if (in_array($request->type, ['select', 'radio', 'checkbox'])) {
            $options = json_encode(array_filter($request->options ?? []));
        }

        $data = [
            'name' => strtolower(trim($request->name)),
            'label' => $request->label,
            'type' => $request->type,
            'options' => $options,
            'ismultiple' => ($request->type === 'select' && $request->has('multiple')) ? 1 : 0, // ✅ handle multiple
            'isrequired' => $request->has('required') ? 1 : 0,
            'displayorder' => $request->order ?? 0,
            'isactive' => $request->has('active') ? 1 : 0,
            'createdat' => now(),
            'createdby' => Session::get('user_idSession'),
        ];

        // Insert into DB
        DB::table('silo_master')->insert($data);

        return redirect()
            ->route('admin.silo_master.index')
            ->with('message', 'Silo field created successfully!');
    }


    public function show(Division $division)
    {
    }



    public function edit($divisionIDenc)
    {
        $id = \Crypt::decrypt($divisionIDenc);
        $silo = DB::table('silo_master')->where('id', $id)->first();
        return view('admin.silo_master.edit', compact('silo'));
    }

    public function update1(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required',
            'abbreviation' => 'required'
        ]);

        $division->name = $request->name;
        $division->abbreviation = $request->abbreviation;

        if ($division->save()) {
            return back()->with('message', 'Division Update Successfully');
        } else {
            return back()->with('message', 'Error While Updateing Division');

        }
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:silo_master,name,' . $id,
            'label' => 'required|string|max:200',
            'type' => 'required|string|max:50',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:200',
            'required' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
            'ismultiple' => 'nullable|boolean',
        ]);

        // Normalize options for select/radio/checkbox
        $options = null;
        if (in_array($request->type, ['select', 'radio', 'checkbox'])) {
            $options = json_encode(array_filter($request->options ?? []));
        }

        // Prepare data for update
        $data = [
            'name' => strtolower(trim($request->name)),
            'label' => $request->label,
            'type' => $request->type,
            'options' => $options,
            'isrequired' => $request->has('required') ? 1 : 0,
            'ismultiple' => $request->has('ismultiple') ? 1 : 0,
            'displayorder' => $request->order ?? 0,
            'isactive' => $request->has('active') ? 1 : 0,
            'updatedat' => now(),
            'updatedby' => Session::get('user_idSession'),
        ];

        // Update record in DB
        DB::table('silo_master')->where('id', $id)->update($data);

        // Redirect with success message
        return redirect()
            ->route('admin.silo_master.index')
            ->with('message', 'Silo field updated successfully!');
    }



    public function destroy($id)
    {
        // Get the field definition
        $field = DB::table('silo_master')->where('id', $id)->first();

        if (!$field) {
            return back()->with('error', 'Field not found.');
        }

        $hasData = false;

        try {
            // ✅ Check inside silo_data table (not silo_master)
            $hasData = DB::table('silo_data')
                ->whereRaw("JSON_VALUE(data, '$.\"{$field->name}\"') IS NOT NULL")
                ->exists();
        } catch (\Exception $e) {
            // Fallback if JSON_VALUE not available
            $allData = DB::table('silo_data')->pluck('data');

            $hasData = $allData->contains(function ($json) use ($field) {
                $decoded = json_decode($json, true);
                return !empty($decoded[$field->name]);
            });
        }

        if ($hasData) {
            return back()->with('error', '❌ Cannot delete. This field already has user data!');
        }

        // ✅ Safe to delete
        DB::table('silo_master')->where('id', $id)->delete();

        return redirect()
            ->route('admin.silo_master.index')
            ->with('message', '✅ Field deleted successfully!');
    }


}
