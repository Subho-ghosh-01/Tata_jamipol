<?php

namespace App\Http\Controllers;

use App\Department;
use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\JobLinking;
use DB;
use Session;

class SkillController extends Controller
{

    public function index()
    {
        $divisions = Division::all();
        $skills = DB::table('skill_clms')->Where('is_deleted', 'N')->orderBy('id', 'desc')->get();
        return view('admin.skill.index', compact('skills'));
    }


    public function create()
    {
        $divisions = Division::all();
        return view('admin.skill.create', compact('divisions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'skill_name' => 'required',
            'skill_rate' => 'required'
        ], [
            'skill_name.required' => 'Skill Name Required',
            'skill_rate.required' => 'Skill Rate is Required'
        ]);
        date_default_timezone_set('Asia/Kolkata'); // or your preferred timezone
        $date = date('Y-m-d H:i:s');
        // dd($request->all());
        $skill = DB::table('skill_clms')->insert([
            'skill_name' => $request->skill_name,
            'skill_rate' => $request->skill_rate,
            'created_by' => Session::get('user_idSession'),
            'created_date' => $date,
            'is_deleted' => 'N'
        ]);

        if ($skill) {
            return back()->with('message', 'Skill Added Successfully');
        } else {
            return back()->with('message', 'Error While Adding Skill');

        }

    }


    public function show(Department $department)
    {
        //
    }


    public function edit($skillIDenc)
    {
        $id = \Crypt::decrypt($skillIDenc);
        $skill = DB::table('skill_clms')->where('id', $id)->first();



        // return $departments[0]->division_id; 
        return view('admin.skill.edit', compact('skill'));

    }


    public function update(Request $request, $skill)
    {

        // dd($request->all());
        $request->validate([
            'skill_name' => 'required',
            'skill_rate' => 'required'

        ]);
        date_default_timezone_set('Asia/Kolkata'); // or your preferred timezone
        $date = date('Y-m-d H:i:s');
        $skill1 = DB::table('skill_clms')->where('id', $skill)->update([
            'skill_name' => $request->skill_name,
            'skill_rate' => $request->skill_rate,
            'updated_by' => Session::get('user_idSession'),
            'updated_date' => $date
        ]);

        if ($skill1) {
            return back()->with('message', 'SKill Update Suceessfully');
        } else {
            return back()->with('message', 'OOPs... Something Wrong Skill');
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
