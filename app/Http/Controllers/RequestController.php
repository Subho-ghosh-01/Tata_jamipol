<?php

namespace App\Http\Controllers;

use App\Request;

use App\Division;
use App\Section;
use App\Job;
use App\UserLogin;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $divisions = Division::all();
        $users = UserLogin::where('user_sub_type', 2)->get();
        $jobs = Job::all();
        return view('admin.request_permits.create', compact('divisions', 'jobs', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }


    //get Section Details Ajax call
    public function getSectionDetails(Request $request, $id)
    {
        if ($request->ajax()) {
            return Section::where('division_id', $id)->get();
        }
    }


    public function getSwpNumber(Request $request, $id)
    {
        $toReturn = Job::where('id', $id)->get();
        return $toReturn;

    }

    public function getSixDirectionalView(Request $request, $id)
    {

        $hazared_all = Job::where('jobs.id', $id)
            ->leftjoin('hazardes', 'jobs.id', '=', 'hazardes.job_id')
            ->select('jobs.*', 'hazardes.*')->get();

        $toReturn = "";
        $toReturn .= "<table class='table'>";
        $toReturn .= "<tbody>";
        $toReturn .= "<tr>";
        $toReturn .= "<th>Direction</th>";
        $toReturn .= "<th>Hazareds</th>";
        $toReturn .= "<th>Precaution</th>";
        $toReturn .= "</tr>";

        foreach ($hazared_all as $key => $value) {
            if ($hazared_all[$key]->direction == 'North') {
                $toReturn .= "<tr>";
                $toReturn .= "<td>North</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->hazarde . "</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->precaution . "</td>";
                $toReturn .= "</tr>";
            }
        }
        foreach ($hazared_all as $key => $value) {
            if ($hazared_all[$key]->direction == 'South') {
                $toReturn .= "<tr>";
                $toReturn .= "<td>South</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->hazarde . "</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->precaution . "</td>";
                $toReturn .= "</tr>";
            }
        }
        foreach ($hazared_all as $key => $value) {
            if ($hazared_all[$key]->direction == 'East') {
                $toReturn .= "<tr>";
                $toReturn .= "<td>East</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->hazarde . "</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->precaution . "</td>";
                $toReturn .= "</tr>";
            }
        }
        foreach ($hazared_all as $key => $value) {
            if ($hazared_all[$key]->direction == 'West') {
                $toReturn .= "<tr>";
                $toReturn .= "<td>West</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->hazarde . "</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->precaution . "</td>";
                $toReturn .= "</tr>";
            }
        }
        foreach ($hazared_all as $key => $value) {
            if ($hazared_all[$key]->direction == 'Top') {
                $toReturn .= "<tr>";
                $toReturn .= "<td>Top</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->hazarde . "</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->precaution . "</td>";
                $toReturn .= "</tr>";
            }
        }
        foreach ($hazared_all as $key => $value) {
            if ($hazared_all[$key]->direction == 'Buttom') {
                $toReturn .= "<tr>";
                $toReturn .= "<td>Buttom</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->hazarde . "</td>";
                $toReturn .= "<td>" . $hazared_all[$key]->precaution . "</td>";
                $toReturn .= "</tr>";
            }
        }

        $toReturn .= "</tbody>";
        $toReturn .= "</table>";
        return $toReturn;
    }

}
