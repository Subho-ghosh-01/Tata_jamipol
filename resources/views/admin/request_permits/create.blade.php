<?php 
use App\Division;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.request_permit.create')}}">Request Permit</a></li>
@endsection
@section('content')

    <form action="{{route('admin.request_permit.store')}}" method="post" autocomplete="off">
        @csrf
        <div class="form-group-row">
            <div class="col-sm-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="form-group-row">
            <div class="col-sm-12" style="text-align:center;">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message')}}
                    </div>
                @endif
            </div>
        </div>


        @if(Session::get('user_typeSession') == 1)
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Divisions<span
                        style="color:red;font-size: 20px;"> *</span></label>
                <div class="col-sm-10">
                    <select class="form-control division_id" id="divisionID" name="division_id" required>
                        <option value=""> Select Division</option>
                        @if($divisions->count() > 0)
                            @foreach($divisions as $division)
                                <option value="{{$division->id}}">{{$division->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        @endif
        @if(Session::get('user_typeSession') == 2)
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Divisions<span
                        style="color:red;font-size: 20px;"> *</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="divisionID" name="division_id" required>
                        <?php    $div = Division::where('id', Session::get('user_DivID_Session'))->first();
                     ?>
                        <option value=""> Select Division</option>
                        <?php    if ($div == "") { ?>
                        <option value="">=========================Please Contact the Admin To Add
                            Division=========================</option>
                        <?php    } else {  ?>
                        <option value="{{$div->id}}">{{$div->name}}</option>

                        <?php    } ?>
                    </select>

                </div>
            </div>
        @endif
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Department<span
                    style="color:red;font-size: 20px;"> *</span></label>
            <div class="col-sm-10">
                <select class="form-control" id="departmentID" name="department_id" required>
                    <option value=""> Select Department</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="order_no" class="col-sm-2 col-form-label">Order No<span style="color:red;font-size: 20px;">
                    *</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="order_no" id="order_no" value="" <?php if (Session::get('user_typeSession') == 2) { ?> required <?php } ?>>
            </div>
            <div class="col-sm-2"><a class="btn btn-info btn-xm" id="getvalidity">Check Validity</a>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" readonly name="order_validity" id="order_validity">
            </div>
        </div>
        <div class="form-group row">
            <label for="start_date" class="col-sm-2 col-form-label">Start Date<span style="color:red;font-size: 20px;">
                    *</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="start_date" id="start_date" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="end_date" class="col-sm-2 col-form-label">End Date<span style="color:red;font-size: 20px;">
                    *</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="end_date" id="end_date" value="" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="job_description" class="col-sm-2 col-form-label">Job Description<span
                    style="color:red;font-size: 20px;"> *</span></label>
            <div class="col-sm-10">
                <textarea class="form-control" name="job_description" id="job_description" value="" required></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="job_location" class="col-sm-2 col-form-label">Job Location<span style="color:red;font-size: 20px;">
                    *</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="job_location" id="job_location" value="" required>
            </div>
        </div>

        @if(Session::get('user_typeSession') == 2)
            <div class="form-group row">
                <label for="p_req_name" class="col-sm-2 col-form-label">Permit Requester Name<span
                        style="color:red;font-size: 20px;">*</span></label>
                <div class="col-sm-10">
                    <select class="form-control" id="p_req_name" name="supervisor_name" required>
                        @if($vendor_supervisors->count() > 0)
                            <option value="null">Select Requester </option>
                            @foreach($vendor_supervisors as $vendor_supervisor)
                                <option value="{{$vendor_supervisor->id}}">{{$vendor_supervisor->supervisor_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Job Category<span
                    style="color:red;font-size: 20px;"> *</span></label>
            <div class="col-sm-10">
                <select class="form-control" id="jobID" name="job_id" required>
                    <option value="null"> Select Job Category</option>
                </select>
            </div>
        </div>
        {{-- @if($jobs->count() > 0)
        @foreach($jobs as $job)
        <option value="{{$job->id}}">{{$job->job_title}}</option>
        @endforeach
        @endif --}}
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP</label>
            <div class="col-sm-10">
                <div id="swp_no">

                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SwP File</label>
            <div class="col-sm-10">
                <div id="swp_file">

                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Type of Job</label>
            <div class="col-sm-3">
                <table style="">
                    <tr>
                        <td><span>(A) Welding/Gas Cutting</span></td>
                        <td><input type="checkbox" name="welding_gas"></td>
                    </tr>
                    <tr>
                        <td><span>(B) Riggine / Fittings</span></td>
                        <td><input type="checkbox" name="riggine"></td>
                    </tr>
                    <tr>
                        <td><span>(C) Working at Height</span></td>
                        <td><input type="checkbox" name="working_at_height"></td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <table style="">
                    <tr>
                        <td><span>(D) Hydraulic/Pneumatic</span></td>
                        <td><input type="checkbox" name="hydraulic_pneumatic"></td>
                    </tr>
                    <tr>
                        <td><span>(E)Painting/Cleaning</span></td>
                        <td><input type="checkbox" name="painting_cleaning"></td>
                    </tr>
                    <tr>
                        <td><span>(F) Gas</span></td>
                        <td><input type="checkbox" name="gas"></td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-3">
                <table style="">
                    <tr>
                        <td><span>(G) Others (Specify)</span></td>
                        <td><input type="checkbox" name="others" id="OTHER"></td>
                    </tr>
                    <tr>
                        <td id="show_specify" style="display: none">
                            <textarea class="form-control" id="specify_others" name="specify_others"></textarea>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        <!-- 1st -->
        <div class="form-group row remove_hazard1">
            <label for="form-control-label" class="col-sm-2 col-form-label">Six Directional Hazards<span
                    style="color:red;font-size: 20px;">*<br></span>(Only 20)</label>
            <div class="col-sm-2">
                <select class="form-control six_directional1" id="six_directional1" name="six_directional1" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz1" name="haz1" required onchange="otherFunction1();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both1">
                <input name="other_haz1" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre1" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre1" name="pre1">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
            <div class="col-sm-1" style="">
                <button type="button" id="add-Hazards" class="btn btn-primary btn-sm">+</button>&nbsp;
                <button type="button" id="remove-Hazards" class="btn btn-danger btn-sm">-</button>
            </div>
        </div>
        <input type="hidden" id="count" name="count" value="1">
        <!-- 2nd -->
        <div class="form-group row show_hazard2" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional2" id="" name="six_directional2" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz2" name="haz2" required onchange="otherFunction2();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both2">
                <input name="other_haz2" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre2" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre2" name="pre2">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 3nd -->
        <div class="form-group row show_hazard3" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional3" id="" name="six_directional3" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz3" name="haz3" required onchange="otherFunction3();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both3">
                <input name="other_haz3" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre3" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre3" name="pre3">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 4nd -->
        <div class="form-group row show_hazard4" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional4" id="" name="six_directional4" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz4" name="haz4" required onchange="otherFunction4();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both4">
                <input name="other_haz4" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre4" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre4" name="pre4">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 5nd -->
        <div class="form-group row show_hazard5" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional5" id="" name="six_directional5" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz5" name="haz5" required onchange="otherFunction5();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both5">
                <input name="other_haz5" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre5" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre5" name="pre5">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 6nd -->
        <div class="form-group row show_hazard6" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional6" id="" name="six_directional6" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz6" name="haz6" required onchange="otherFunction6();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both6">
                <input name="other_haz6" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre6" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre6" name="pre6">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>

        <!-- 7nd -->
        <div class="form-group row show_hazard7" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional7" id="" name="six_directional7" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz7" name="haz7" required onchange="otherFunction7();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both7">
                <input name="other_haz7" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre7" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre7" name="pre7">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 8nd -->
        <div class="form-group row show_hazard8" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional8" id="" name="six_directional8" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz8" name="haz8" required onchange="otherFunction8();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both8">
                <input name="other_haz8" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre8" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre8" name="pre8">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 9nd -->
        <div class="form-group row show_hazard9" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional9" id="" name="six_directional9" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz9" name="haz9" required onchange="otherFunction9();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both9">
                <input name="other_haz9" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre9" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre9" name="pre9">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 10nd -->
        <div class="form-group row show_hazard10" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional10" id="" name="six_directional10" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz10" name="haz10" required onchange="otherFunction10();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both10">
                <input name="other_haz10" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre10" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre10" name="pre10">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 11nd -->
        <div class="form-group row show_hazard11" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional11" id="" name="six_directional11" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz11" name="haz11" required onchange="otherFunction11();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both11">
                <input name="other_haz11" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre11" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre11" name="pre11">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 12nd -->
        <div class="form-group row show_hazard12" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional12" id="" name="six_directional12" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz12" name="haz12" required onchange="otherFunction12();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both12">
                <input name="other_haz12" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre12" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre12" name="pre12">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 13nd -->
        <div class="form-group row show_hazard13" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional13" id="" name="six_directional13" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz13" name="haz13" required onchange="otherFunction13();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both13">
                <input name="other_haz13" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre13" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre13" name="pre13">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 14nd -->
        <div class="form-group row show_hazard14" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional14" id="" name="six_directional14" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz14" name="haz14" required onchange="otherFunction14();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both14">
                <input name="other_haz14" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre14" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre14" name="pre14">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 15nd -->
        <div class="form-group row show_hazard15" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional15" id="six_directional" name="six_directional15" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz15" name="haz15" required onchange="otherFunction15();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both15">
                <input name="other_haz15" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre15" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre15" name="pre15">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 16nd -->
        <div class="form-group row show_hazard16" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional16" id="" name="six_directional16" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz16" name="haz16" required onchange="otherFunction16();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both16">
                <input name="other_haz16" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre16" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre16" name="pre16">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!--17nd -->
        <div class="form-group row show_hazard17" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional17" id="six_directional" name="six_directional17" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz17" name="haz17" required onchange="otherFunction17();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both17">
                <input name="other_haz17" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre17" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre17" name="pre17">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!---18nd -->
        <div class="form-group row show_hazard18" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional18" id="" name="six_directional18" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz18" name="haz18" required onchange="otherFunction18();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both18">
                <input name="other_haz18" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre18" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre18" name="pre18">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 19nd -->
        <div class="form-group row show_hazard19" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional19" id="" name="six_directional19" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz19" name="haz19" required onchange="otherFunction19();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both19">
                <input name="other_haz19" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre19" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre19" name="pre19">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>
        <!-- 20nd -->
        <div class="form-group row show_hazard20" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-2">
                <select class="form-control six_directional20" id="" name="six_directional20" required>
                    <option value="null">Select Direction</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Top">Top</option>
                    <option value="Bottom">Bottom</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="haz20" name="haz20" required onchange="otherFunction20();">
                    <option value="null">Select Hazards</option>
                </select>
            </div>
            <div class="col-sm-2" style="display: none" id="show-both20">
                <input name="other_haz20" placeholder="Add Hazards" class="form-control" type="text">
                <input name="other_pre20" placeholder="Add Precaution" class="form-control" type="text">
            </div>
            <div class="col-sm-2">
                <select class="form-control" id="pre20" name="pre20">
                    <option value="null">Select Precaution</option>
                </select>
            </div>
        </div>


        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Executing Agency<span
                    style="color:red;font-size: 20px;"> *</span></label>
            <div class="col-sm-10">
                <select class="form-control" id="issuer_id" name="issuer_id" required>
                    <option value="null">Select Executing Agency</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Gate Pass Details</label>
            <div class="col-sm-9">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Gate Pass No.</th>
                            <th>Designation</th>
                            <th>Age</th>
                            <th>Gatepass Expiry</th>
                            <th>Intime</th>
                        </tr>
                    </thead>
                    <!-- FOR Vendor already insert their Employee Details -->
                    <tbody id="append_gatepass">
                        <tr class='tr_input'>
                            <td><input type='text' name="employee_name[]" required="" class='username form-control'
                                    id='username_1' placeholder='Enter Employee'></td>
                            <td><input type='text' name="gate_pass_no[]" required="" class='gatepass form-control'
                                    id='gatepass_1'></td>
                            <td><input type='text' name="designation[]" required="" class='desig form-control' id='desig_1'>
                            </td>
                            <td><input type='text' name="age[]" required="" class='age form-control' id='age_1'></td>
                            <td><input type='date' name="expiry_date[]" required="" class='exp form-control' id='exp_1'
                                    readonly></td>
                            <td><input type='time' name="intime[]" required="" class='exp form-control' id='intime_1'></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-1" style="">
                <button type="button" id="btn-add" class="btn btn-primary btn-sm">+</button>&nbsp;
                <button type="button" id="btn-remove" class="btn btn-danger btn-sm">-</button>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Access</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>Safe Work Procedure has been made & approved.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="safe_work" value="yes" checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="safe_work" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="safe_work" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All persons are medically fit and have adequate quality of personal safety
                                        appliances. They will use it
                                        compulsorily as per requirement of the job. All person will follow safety norms and
                                        conditions of JAMIPOL.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_person" value="yes" checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_person" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_person" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>Worker working on height must have height pass from NTTF/ Competent
                                        authority.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="worker_working" value="yes" checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="worker_working" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="worker_working" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All lifting tools/Load bearing tools, tackles & safety appliances are in good
                                        condition with valid test certificate</strong> </td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_lifting_tools" value="yes" checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_lifting_tools" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_lifting_tools" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All safety requirement for working at height will be arranged I made and checked
                                        before use as per job
                                        requirement ~ Access ladder, rest platform, Extended platform, scaffolding, hand
                                        rail, fencing of down area /
                                        ground area, Use of full body harness etc.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_safety_requirement" value="yes"
                                            checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_safety_requirement" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_safety_requirement" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div class="col-sm-8">
                                <td> <strong>All persons are trained on Safe Working Procedure (SWP)</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_person_are_trained" value="yes"
                                            checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_person_are_trained" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="all_person_are_trained" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                        <tr>
                            <div class="col-sm-8">
                                <td> <strong>Ensure the applicable activity check list, signed by permit requester, receiver
                                        and verified by permit issuer.
                                        Checklist shall be attached with permit to work system</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="ensure_the_appplicablle" value="yes"
                                            checked>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="ensure_the_appplicablle" value="no">&nbsp; No
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" class="" name="ensure_the_appplicablle" value="na">&nbsp; NA
                                    </label>
                                </td>
                            </div>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <input type="submit" name="submit" class="btn btn-primary" id="submitbtn" value="Add">
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
        integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        $("#getvalidity").on("click", function (e) {
            var o_ID = $("#order_no").val();
            // alert(o_ID);
            $.ajax({
                type: 'GET',
                url: "{{route('admin.getvalidity')}}/" + o_ID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    // console.log(data);                   
                    $("#order_validity").val(data.date);
                }
            });
        });

        // get the department data
        $('#divisionID').on('change', function () {
            var division_ID = $(this).val();
            // alert(division_ID);
            $("#departmentID").html('<option value="null">--Select--</option>');
            $("#issuer_id").html('<option value="null">--Select--</option>');
            $("#jobID").html('<option value="null">--Select--</option>');
            $("#swp_no").html("");
            $("#swp_file").html("");
            if (divisionID) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.departmentGet')}}/" + division_ID,
                    contentType: 'application/json',
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        for (var i = 0; i < data.length; i++) {
                            $("#departmentID").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                        }
                    }
                });

            } else {
                $('#departmentID').html('<option value="null">Select Division first</option>');
            }
        });

        // get the Section and Issuer  data
        $('#departmentID').on('change', function () {
            var Div_ID = $("#divisionID").val();
            // console.log(Div_ID);
            var Dept_ID = $(this).val();
            $("#jobID").html('<option value="">--Select--</option>');
            $("#issuer_id").html('<option value="null">--Select--</option>');
            $("#swp_no").html("");
            $("#swp_file").html("");
            if (Dept_ID) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.getjob')}}/" + Div_ID + "/" + Dept_ID,
                    contentType: 'application/json',
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        for (var i = 0; i < data.length; i++) {
                            $("#jobID").append('<option value="' + data[i].id + '" >' + data[i].job_title + '</option>');
                        }
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.getIssuer')}}/" + Div_ID,
                    contentType: 'application/json',
                    dataType: "json",
                    success: function (user) {
                        // console.log(user);
                        for (var i = 0; i < user.length; i++) {
                            $("#issuer_id").append('<option value="' + user[i].id + '" >' + user[i].name + '</option>');
                        }
                    }
                });

            }
        });

        //1time
        $('#six_directional1').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz1').html("");
                $('#pre1').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            // console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz1").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre1").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz1").append('<option value="other1">Other</option>');
                        }
                    });
                }
            }
        });

        //2 time
        $('.six_directional2').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz2').html("");
                $('#pre2').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz2").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre2").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz2").append('<option value="other2">Other</option>');
                        }
                    });
                }
            }
        });

        //3 time
        $('.six_directional3').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz3').html("");
                $('#pre3').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz3").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre3").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz3").append('<option value="other3">Other</option>');
                        }
                    });
                }
            }
        });
        //4 time
        $('.six_directional4').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz4').html("");
                $('#pre4').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz4").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre4").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz4").append('<option value="other4">Other</option>');
                        }
                    });
                }
            }
        });
        //5 time
        $('.six_directional5').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz5').html("");
                $('#pre5').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz5").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre5").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz5").append('<option value="other5">Other</option>');
                        }
                    });
                }
            }
        });
        //6 time
        $('.six_directional6').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz6').html("");
                $('#pre6').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz6").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre6").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz6").append('<option value="other6">Other</option>');
                        }
                    });
                }
            }
        });
        //7 time
        $('.six_directional7').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category Category First');
            }
            else {
                $('#haz7').html("");
                $('#pre7').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz7").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre7").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz7").append('<option value="other7">Other</option>');
                        }
                    });
                }
            }
        });
        //8 time
        $('.six_directional8').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz8').html("");
                $('#pre8').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz8").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre8").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz8").append('<option value="other8">Other</option>');
                        }
                    });
                }
            }
        });
        //9 time
        $('.six_directional9').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz9').html("");
                $('#pre9').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz9").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre9").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz9").append('<option value="other9">Other</option>');
                        }
                    });
                }
            }
        });
        //10 time
        $('.six_directional10').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz10').html("");
                $('#pre10').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz10").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre10").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz10").append('<option value="other10">Other</option>');
                        }
                    });
                }
            }
        });
        //11 time
        $('.six_directional11').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz11').html("");
                $('#pre11').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz11").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre11").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz11").append('<option value="other11">Other</option>');
                        }
                    });
                }
            }
        });
        //12 time
        $('.six_directional12').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz12').html("");
                $('#pre12').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz12").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre12").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz12").append('<option value="other12">Other</option>');
                        }
                    });
                }
            }
        });
        //13 time
        $('.six_directional13').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz13').html("");
                $('#pre13').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz13").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre13").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz13").append('<option value="other13">Other</option>');
                        }
                    });
                }
            }
        });
        //14 time
        $('.six_directional14').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz14').html("");
                $('#pre14').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz14").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre14").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz14").append('<option value="other14">Other</option>');
                        }
                    });
                }
            }
        });
        //15 time
        $('.six_directional15').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz15').html("");
                $('#pre15').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz15").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre15").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz15").append('<option value="other15">Other</option>');
                        }
                    });
                }
            }
        });
        //16 time
        $('.six_directional16').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz16').html("");
                $('#pre16').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz16").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre16").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz16").append('<option value="other16">Other</option>');
                        }
                    });
                }
            }
        });
        //17 time
        $('.six_directional17').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz17').html("");
                $('#pre17').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz17").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre17").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz17").append('<option value="other17">Other</option>');
                        }
                    });
                }
            }
        });
        //18 time
        $('.six_directional18').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz18').html("");
                $('#pre18').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz18").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre18").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz18").append('<option value="other18">Other</option>');
                        }
                    });
                }
            }
        });
        //19time
        $('.six_directional19').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz19').html("");
                $('#pre19').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz19").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre19").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz19").append('<option value="other19">Other</option>');
                        }
                    });
                }
            }
        });
        //20 time
        $('.six_directional20').on('change', function () {
            var jobID = $('#jobID').val();
            var six_directional = $(this).val();
            if (jobID == "0") {
                alert('Select Job Category First');
            }
            else {
                $('#haz20').html("");
                $('#pre20').html("");
                if (jobID) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'GET',
                        url: "{{route('admin.getHaz')}}/" + jobID + "/" + six_directional,
                        contentType: 'application/json',
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            for (var i = 0; i < data.length; i++) {
                                $("#haz20").append('<option value="' + data[i].hazarde + '" >' + data[i].hazarde + '</option>');
                                $("#pre20").append('<option value="' + data[i].precaution + '" >' + data[i].precaution + '</option>');

                            }
                            $("#haz20").append('<option value="other20">Other</option>');
                        }
                    });
                }
            }
        });

        //SHOW THE BOTH 1
        function otherFunction1() {
            var selectBox = document.getElementById("haz1");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other1") {
                $('#show-both1').show();
                $('#pre1').hide();
            }
            else {
                $('#show-both1').hide();
                $('#pre1').show();
            }
        }
        //SHOW THE BOTH 2
        function otherFunction2() {
            var selectBox = document.getElementById("haz2");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other2") {
                $('#show-both2').show();
                $('#pre2').hide();
            }
            else {
                $('#show-both2').hide();
                $('#pre2').show();
            }
        }
        //SHOW THE BOTH 3
        function otherFunction3() {
            var selectBox = document.getElementById("haz3");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other3") {
                $('#show-both3').show();
                $('#pre3').hide();
            }
            else {
                $('#show-both3').hide();
                $('#pre3').show();
            }
        }
        //SHOW THE BOTH 4
        function otherFunction4() {
            var selectBox = document.getElementById("haz4");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other4") {
                $('#show-both4').show();
                $('#pre4').hide();
            }
            else {
                $('#show-both4').hide();
                $('#pre4').show();
            }
        }
        //SHOW THE BOTH 5
        function otherFunction5() {
            var selectBox = document.getElementById("haz5");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other5") {
                $('#show-both5').show();
                $('#pre5').hide();
            }
            else {
                $('#show-both5').hide();
                $('#pre5').show();
            }
        }
        //SHOW THE BOTH 6
        function otherFunction6() {
            var selectBox = document.getElementById("haz6");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other6") {
                $('#show-both6').show();
                $('#pre6').hide();
            }
            else {
                $('#show-both6').hide();
                $('#pre6').show();
            }
        }
        //SHOW THE BOTH 7
        function otherFunction7() {
            var selectBox = document.getElementById("haz7");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other7") {
                $('#show-both7').show();
                $('#pre7').hide();
            }
            else {
                $('#show-both7').hide();
                $('#pre7').show();
            }
        }
        //SHOW THE BOTH 8
        function otherFunction8() {
            var selectBox = document.getElementById("haz8");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other8") {
                $('#show-both8').show();
                $('#pre8').hide();
            }
            else {
                $('#show-both8').hide();
                $('#pre8').show();
            }
        }
        //SHOW THE BOTH 9
        function otherFunction9() {
            var selectBox = document.getElementById("haz9");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other9") {
                $('#show-both9').show();
                $('#pre9').hide();
            }
            else {
                $('#show-both9').hide();
                $('#pre9').show();
            }
        }
        //SHOW THE BOTH 10
        function otherFunction10() {
            var selectBox = document.getElementById("haz10");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other10") {
                $('#show-both10').show();
                $('#pre10').hide();
            }
            else {
                $('#show-both10').hide();
                $('#pre10').show();
            }
        }
        //SHOW THE BOTH 11
        function otherFunction11() {
            var selectBox = document.getElementById("haz11");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other11") {
                $('#show-both11').show();
                $('#pre11').hide();
            }
            else {
                $('#show-both11').hide();
                $('#pre11').show();
            }
        }
        //SHOW THE BOTH 12
        function otherFunction12() {
            var selectBox = document.getElementById("haz12");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other12") {
                $('#show-both12').show();
                $('#pre12').hide();
            }
            else {
                $('#show-both12').hide();
                $('#pre12').show();
            }
        }
        //SHOW THE BOTH 13
        function otherFunction13() {
            var selectBox = document.getElementById("haz13");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other13") {
                $('#show-both13').show();
                $('#pre13').hide();
            }
            else {
                $('#show-both13').hide();
                $('#pre13').show();
            }
        }
        //SHOW THE BOTH 14
        function otherFunction14() {
            var selectBox = document.getElementById("haz14");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other14") {
                $('#show-both14').show();
                $('#pre14').hide();
            }
            else {
                $('#show-both14').hide();
                $('#pre14').show();
            }
        }
        //SHOW THE BOTH 15
        function otherFunction15() {
            var selectBox = document.getElementById("haz15");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other15") {
                $('#show-both15').show();
                $('#pre15').hide();
            }
            else {
                $('#show-both15').hide();
                $('#pre15').show();
            }
        }
        //SHOW THE BOTH 16
        function otherFunction16() {
            var selectBox = document.getElementById("haz16");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other16") {
                $('#show-both16').show();
                $('#pre16').hide();
            }
            else {
                $('#show-both16').hide();
                $('#pre16').show();
            }
        }
        //SHOW THE BOTH 17
        function otherFunction17() {
            var selectBox = document.getElementById("haz17");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other17") {
                $('#show-both17').show();
                $('#pre17').hide();
            }
            else {
                $('#show-both17').hide();
                $('#pre17').show();
            }
        }
        //SHOW THE BOTH 18
        function otherFunction18() {
            var selectBox = document.getElementById("haz18");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other18") {
                $('#show-both18').show();
                $('#pre18').hide();
            }
            else {
                $('#show-both18').hide();
                $('#pre18').show();
            }
        }
        //SHOW THE BOTH 19
        function otherFunction19() {
            var selectBox = document.getElementById("haz19");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other19") {
                $('#show-both19').show();
                $('#pre19').hide();
            }
            else {
                $('#show-both19').hide();
                $('#pre19').show();
            }
        }
        //SHOW THE BOTH 20
        function otherFunction20() {
            var selectBox = document.getElementById("haz20");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            if (selectedValue == "other20") {
                $('#show-both20').show();
                $('#pre20').hide();
            }
            else {
                $('#show-both20').hide();
                $('#pre20').show();
            }
        }

        //get the swp number & file
        $('#jobID').on('change', function () {
            var job_ID = $(this).val();
            $('#swp_no').html("");
            $('#swp_file').html("");
            if (jobID) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "{{route('admin.getSwpNumber')}}/" + job_ID,
                    contentType: 'application/json',
                    dataType: "json",
                    success: function (data) {
                        // console.log(data);
                        $("#swp_no").html(data.swp_num);
                        // $("#swp_file").append('<a href="../../'+data.swp_file+'" target="_blank"><img src="{{ URL::to('/images/pdf_download.png')}}"></a>');
                        for (var i = 0; i < data.swp_f.length; i++) {
                            var link = data.swp_f[i].swp_file;
                            let result = link.replace("public/", '');
                            $("#swp_file").append('<a href="../../' + result + '" target="_blank"><img src="{{ URL::to('/images/pdf_download.png')}}"></a>');
                        }
                    }
                });
            }
        });

        //Remove Top Click
        // $("#btn-remove").on("click", function (e) {
        //     if($('.remove_tr').length > 1){
        //         $(".remove_tr:last").remove();
        //     }
        // });

        // Code to add six direection hazard 20
        var button = document.getElementById("add-Hazards"),
            count = 1;
        button.onclick = function () {
            var count2 = $("#count").val();
            if (count2 != "20") {
                count = Number(count2) + 1;
            }
            add(count);
            $('#count').val(count);
        };
        function add(id) {
            // alert(id)
            $('.show_hazard' + id).show();
        }

        $("#remove-Hazards").click(function () {
            var removecount = $("#count").val();
            $('.show_hazard' + removecount).hide();
            if (removecount != "1") {
                removecount -= 1;
            }
            $('#count').val(removecount);
        });

        $('#start_date').datetimepicker();
        $('#end_date').datetimepicker();

        $(document).ready(function () {
            <?php 
        if (Session::get('user_DivID_Session') == 2) {
        ?>
            $(document).on('keydown', '.username', function () {
                var id = this.id;
                var valname = $(this).val();
                // console.log(valname);
                var splitid = id.split('_');
                var index = splitid[1];
                //alert(index);
                // Initialize jQuery UI autocomplete
                $('#' + id).autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{route('admin.autocomplete_gate_pass')}}/" + <?php    echo Session::get('user_idSession') ?> + "/" + valname,
                            type: 'get',
                            dataType: "json",
                            success: function (data) {
                                var array = data.error ? [] : $.map(data.list, function (m) {
                                    return {
                                        label: m.name,
                                        gatepass: m.full_sl,
                                        des: m.job_role,
                                        age: m.date_of_birth,
                                        expi: m.valid_till
                                    };
                                });
                                response(array);
                            }

                        });
                    },
                    select: function (event, ui) {
                        $('#' + id).val(ui.item.label);
                        $('#' + id).closest('tr').find('.gatepass').val(ui.item.gatepass);
                        $('#' + id).closest('tr').find('.desig').val(ui.item.des);
                        $('#' + id).closest('tr').find('.age').val(ui.item.age);
                        $('#' + id).closest('tr').find('.exp').val(ui.item.expi);
                        // $('#'+id).closest('tr').find('.intime').val(ui.item.intime);

                    }
                });
            });
            <?php } else {
            ?>
            $(document).on('keydown', '.username', function () {
                var id = this.id;
                var valname = $(this).val();
                // console.log(valname);
                var splitid = id.split('_');
                var index = splitid[1];
                //alert(index);
                // Initialize jQuery UI autocomplete
                $('#' + id).autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{route('admin.autocomplete_gate_pass')}}/" + <?php    echo Session::get('user_idSession') ?> + "/" + valname,
                            type: 'get',
                            dataType: "json",
                            success: function (data) {
                                var array = data.error ? [] : $.map(data.list, function (m) {
                                    return {
                                        label: m.employee,
                                        gatepass: m.gatepass,
                                        des: m.designation,
                                        age: m.age,
                                        expi: m.expiry
                                    };
                                });
                                response(array);
                            }

                        });
                    },
                    select: function (event, ui) {
                        $('#' + id).val(ui.item.label);
                        $('#' + id).closest('tr').find('.gatepass').val(ui.item.gatepass);
                        $('#' + id).closest('tr').find('.desig').val(ui.item.des);
                        $('#' + id).closest('tr').find('.age').val(ui.item.age);
                        $('#' + id).closest('tr').find('.exp').val(ui.item.expi);
                        // $('#'+id).closest('tr').find('.intime').val(ui.item.intime);

                    }
                });
            });
            <?php
    }?>

            // Add more
            $('#btn-add').click(function () {
                var count = $(".tr_input").length + 1;
                // Get last id 
                var lastname_id = $('.tr_input input[type=text]:nth-child(1)').last().attr('id');
                var split_id = lastname_id.split('_');
                // New index
                var index = Number(split_id[1]) + 1;
                // Create row with input elements
                var html = "<tr class='tr_input'>";
                html += "<td><input type='text'  name='employee_name[]' class='username form-control' id='username_" + index + "' placeholder='Enter Employee'></td>";
                html += "<td><input type='text' name='gate_pass_no[]' class='gatepass form-control' id='gatepass_" + index + "'></td>";
                html += "<td><input type='text' name='designation[]' class='desig form-control' id='desig_" + index + "'></td>";
                html += "<td><input type='text' name='age[]' class='age form-control' id='age_" + index + "'></td>";
                html += "<td><input type='date' readonly name='expiry_date[]' class='exp form-control' id='exp_" + index + "'></td>";

                html += "<td><input type='time' name='intime[]' class='exp form-control' id='intime_" + index + "'></td></tr>";
                $('#append_gatepass').append(html);
            });
            $("#btn-remove").on("click", function (e) {
                if ($('.tr_input').length > 1) {
                    $(".tr_input:last").remove();
                }
            });
        });

        $('#OTHER').click(function () {
            if ($(this).is(':checked')) {
                $('#show_specify').show();
                $('#specify_others').prop('required', true);
            }
            else {
                $('#specify_others').prop('required', false);
                $('#show_specify').hide();
            }
        });


    </script>


    <!-- jQuery library -->

@endsection