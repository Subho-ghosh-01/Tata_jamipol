<?php

namespace App\Http\Controllers;

use App\AreaClearence;
use Illuminate\Http\Request;
use App\Job;
use Session;
use App\Division;
use App\UserLogin;
use App\Department;
use App\Section;

class AreaClearenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $area_clearences = AreaClearence::orderBy('id','desc')->get();
        // return  $area_clearences;
        return view('admin.area-clearence.index',compact('area_clearences'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $jobs = Job::get();
        $divisions = Division::all();
        return view('admin.area-clearence.create',compact('jobs','divisions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'division_id'   => 'required|numeric',  
            'department_id' => 'required|numeric',  
            'section_id'    => 'required|numeric',  
            'job_id'        => 'required|numeric',  
            'user_id'       => 'required|numeric',  
        ]);

        $create = AreaClearence::create([
            "division_id"       => $request->division_id,
            "department_id"     => $request->department_id, 
            "section_id"        => $request->section_id, 
            "job_id"            => $request->job_id,
            "user_id"           => $request->user_id 
        ]);
        if($create){
            return back()->with('message','Your Arae Clearence Created');
        }
        else{
            return back()->with('message','Ooops Error While Create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AreaClearence  $areaClearence
     * @return \Illuminate\Http\Response
     */
    public function show(AreaClearence $areaClearence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AreaClearence  $areaClearence
     * @return \Illuminate\Http\Response
     */
    public function edit($idenc)
    {
        $id = \Crypt::decrypt($idenc);
        $GetAreaClearence = AreaClearence::where('id',$id)->get();
        $jobs = Job::get();
        $divisions   = Division::all();
        $departments = Department::all();
        $sections    = Section::all();
        // return $GetAreaClearence[0]->id;
        return view('admin.area-clearence.edit',compact('GetAreaClearence','jobs','divisions',
        'departments','sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AreaClearence  $areaClearence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($id);
        $request->validate([
            'division_id'   => 'required|numeric',  
            'department_id' => 'required|numeric',  
            'section_id'    => 'required|numeric',  
            'job_id'        => 'required|numeric',  
            'user_id'       => 'required|numeric',  
        ]);
        $areaClearence = AreaClearence::where('id',$id)->update([

            'division_id'     =>  $request->division_id,   
            'department_id'   =>  $request->department_id,   
            'section_id'      =>  $request->section_id,   
            'job_id'          =>  $request->job_id,  
            'user_id'         =>  $request->user_id,   
        ]);

        if($areaClearence){          
            return back()->with('message','Area Clearence Update Suceessfully');
        }
        else{
             return back()->with('message','OOPs... Something Wrong while Area Clearence Adding');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AreaClearence  $areaClearence
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id != 0)
        {
            $destroy = AreaClearence::where('id',$id)->delete();  
            return back()->with('message' ,'Recored Deleted.');
        }
        else{
            return back()->with('message' ,'Oops Something Worng......');
        }
    }
    public function getvalidEmployee($emp_code)
    {
        $toReturn = UserLogin::where('vendor_code',$emp_code)->get();
        return $toReturn;
    }
}
