<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use App\UserLogin;
use App\Department;
use App\JobLinking;
//use App\JobLinking;
use Illuminate\Support\Facades\DB;



class DivisionnewController extends Controller
{

    public function index()
    {
        $divisions = DB::table('division_new')->orderBy('id', 'desc')->get();
        return view('admin.division_new.index', compact('divisions'));
    }


    public function create()
    {
        return view('admin.division_new.create');
    }


    public function store(Request $request)
    {
        // dd( $request->all());
        $request->validate([
            'name' => 'required',

        ]);
        //$division = DB::table('users')::create([
        $inserted = DB::table('division_new')->insert([
            'name' => $request->name,

        ]);

        if ($inserted) {
            return back()->with('message', 'Division Added Successfully');
        } else {
            return back()->with('message', 'Error While Addeding Division');

        }
    }


    public function show(Division $division)
    {
    }



    public function edit($divisionIDenc)
    {
        $id = \Crypt::decrypt($divisionIDenc);
        $division = DB::table('division_new')->where('id', $id)->first();
        return view('admin.division_new.edit', compact('division'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $updated = DB::table('division_new')
            ->where('id', $id)
            ->update([
                'name' => $request->name,

            ]);

        if ($updated) {
            return back()->with('message', 'Division Updated Successfully');
        } else {
            return back()->with('message', 'Error While Updating Division');
        }
    }



    public function destroy($id)
    {



        DB::table('division_new')->where('id', $id)->delete();
        return back()->with('message', 'Division Deleted Successfully');

    }




}
