<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\Department;
use App\JobLinking;
//use App\JobLinking;



class DivisionController extends Controller
{
    
    public function index()
    {
        $divisions = Division::orderBy('id','desc')->get();
        return view('admin.divisions.index',compact('divisions'));
    }

    
    public function create()
    {
        return view('admin.divisions.create');
    }


    public function store(Request $request)
    {
        // dd( $request->all());
        $request->validate([
            'name'         => 'required',
            'abbreviation' => 'required'
        ]);
        //$division = DB::table('users')::create([
        $division = Division::create([
            'name'         => $request->name,
            'abbreviation' => $request->abbreviation
            
        ]);
      
        if($division){
            return back()->with('message','Division Added Successfully');
        }else{
            return back()->with('message','Error While Addeding Division');

        }
    } 

    
    public function show(Division $division)
    {
    }
    
    

    public function edit($divisionIDenc)
    {
        $id = \Crypt::decrypt($divisionIDenc);
        $division = Division::where('id',$id)->first();
        return view('admin.divisions.edit',compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name'         => 'required',
            'abbreviation' => 'required'
        ]);
        
        $division->name         =  $request->name;
        $division->abbreviation =  $request->abbreviation;

        if($division->save()){
            return back()->with('message','Division Update Successfully');
        }else{
            return back()->with('message','Error While Updateing Division');

        }
    }

    
    public function destroy(Division $division)
    {
        if($division->id){
            $users = UserLogin::where('division_id','=',$division->id)->get();
            $depart = Department::where('division_id','=',$division->id)->get();
            $joblink = JobLinking::where('division_id','=',$division->id)->get();
            // echo @$users[0]->id;
            // echo @$depart[0]->id;
            // exit;
            if(@$users[0]->id != "" || @$depart[0]->id != "" || @$joblink[0]->id){
                // echo "not Del";
                return back()->with('message',"Division Can't be Deleted");
            }
            else{
                // echo  "delete";
               $division->delete();
               return back()->with('message','Division Delete Successfully');
            }
        }
    }
}
