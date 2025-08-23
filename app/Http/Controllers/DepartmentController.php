<?php

namespace App\Http\Controllers;

use App\Department;
use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\JobLinking;

class DepartmentController extends Controller
{

    public function index()
    {
        $divisions = Division::all();
        $departments = Department::orderBy('id', 'desc')->get();
        return view('admin.departments.index', compact('departments', 'divisions'));
    }


    public function create()
    {
        $divisions = Division::all();
        return view('admin.departments.create', compact('divisions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'division_id.*' => 'required'
        ], [
            'department_name.required' => 'Department Name Required',
            'division_id.*' . 'required' => 'Division is Required'
        ]);


        // dd($request->all());
        foreach ($request->division_id as $key => $value) {
            $department = Department::create([
                'department_name' => $request->department_name,
                'division_id' => $request->division_id[$key]
            ]);
        }

        if ($department) {
            return back()->with('message', 'Department Added Successfully');
        } else {
            return back()->with('message', 'Error While Adding Department');

        }

    }


    public function show(Department $department)
    {
        //
    }


    public function edit($departmentIDenc)
    {
        $id = \Crypt::decrypt($departmentIDenc);
        $department = Department::where('id', $id)->first();
        $divisions = Division::get();
        $departments = Division::where('departments.id', $department->id)
            ->leftjoin('departments', 'divisions.id', '=', 'departments.division_id')
            ->select('departments.*', 'divisions.name as division_name')->get();
        // return $departments[0]->division_id; 
        return view('admin.departments.edit', compact('department', 'departments', 'divisions'));

    }


    public function update(Request $request, Department $department)
    {
        // dd($request->all());
        $request->validate([
            'department_name' => 'required',
            'division_id' => 'required'

        ]);

        $department->department_name = $request->department_name;
        $department->division_id = $request->division_id;

        if ($department->save()) {
            return back()->with('message', 'Department Update Suceessfully');
        } else {
            return back()->with('message', 'OOPs... Something Wrong Department');
        }

    }

    public function destroy(Department $department)
    {
        if ($department->id != 0) {
            $users = UserLogin::where('department_id', '=', $department->id)->get();
            $joblink = JobLinking::where('department_id', '=', $department->id)->get();
            if (@$users[0]->id != "" || @$joblink[0]->id) {
                return back()->with('message', "Department Can't be Deleted");
            } else {
                $department->delete();
                return back()->with('message', 'Department Delete Successfully');
            }
        }

    }
    public function DepartmentFilter(Request $request)
    {
        // dd($request->all());

        if ($request->input('division_id') <> '') {
            $divisions = Division::all();
            $departments = Department::where('division_id', $request->division_id)->get();


        }
        return view('admin.departments.index', compact('departments', 'divisions'));
    }
}
