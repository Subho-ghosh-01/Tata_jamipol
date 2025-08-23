<?php 
use App\Department;
use App\UserLogin;
use App\ShutdownChild;
use App\PowerCutting;
use App\PowerClearence;

?>
 
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.list_permit.index')}}">List Permit</a></li>
@endsection                        
@section('content')

@if($permit_division_datas[0]->PermitStatus != "Issued")
<form action="{{ route('admin.list_permit.update',$id) }}" method="post" enctype="multipart/form-data"> 
    @csrf
    @method('PUT')
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
    <div id="first">
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Divisions</label>
            <div class="col-sm-10">
                <select class="form-control" id="divisionID" readonly="readonly"> 
                        @if($permit_division_datas->count() > 0)
                            @foreach($permit_division_datas as $permit_division_data)
                                <option value="{{$permit_division_data->divisionId}}" {{ 'selected' }}>{{$permit_division_data->divisionName}}</option>
                            @endforeach
                        @endif 
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Department</label>
            <div class="col-sm-10">
            <?php $depart_list = Department::where('id',@$permit_division_datas[0]->department_id)->get(); ?>
                <select class="form-control" id="departmentID" name="department_id" readonly="readonly"> 
                    @if($depart_list->count() > 0)
                        @foreach($depart_list as $depart)
                            <option value="{{$depart->id}}" {{ 'selected' }}>{{$depart->department_name}}</option>
                        @endforeach
                    @endif 
                </select>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Order No</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$permit_division_data->permitOrder}}" readonly><br>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$permit_division_data->permitOrderValidity}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  autocomplete="off"  value="{{ date('Y-m-d h:i', strtotime($permit_division_data->startDate))  }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" autocomplete="off" value="{{ date('Y-m-d h:i', strtotime($permit_division_data->endDate))}}" readonly>   
            </div>
        </div>
        <div class="form-group row">
            <label for="job_description" class="col-sm-2 col-form-label">Job Description</label>
            <div class="col-sm-10">
                <textarea class="form-control"  value="" readonly>{{$permit_division_data->JobDescription}}</textarea>   
            </div>
        </div>
        <div class="form-group row">
            <label for="job_location" class="col-sm-2 col-form-label">Job Location</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  value="{{$permit_division_data->JobLocation}}" readonly>   
            </div>
        </div>

        @if(Session::get('user_typeSession') == 2)
        <div class="form-group row">
            <label for="job_location" class="col-sm-2 col-form-label">Permit Requester Name</label>
            <div class="col-sm-10">
            @php  $super_name = DB::table('vendor_supervisors')->where('id',$permit_division_data->permitRequestname)->get(); @endphp 
                <input type="text" class="form-control"  value="{{@$super_name[0]->supervisor_name}}" readonly>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Job</label>
            <div class="col-sm-10">
                <select class="form-control" id="jobID" readonly>
                    @if($job_datas->count() > 0)
                        @foreach($job_datas as $job_data)
                            <option value="{{$job_data->jobId}}"  {{ 'selected' }}>{{$job_data->jobTitle}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$job_data->jobSwpNumber}}" readonly> 
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SWP File</label>
            <div class="col-sm-10">
                @if($swp_files->count() > 0)
                    @foreach($swp_files as $s)
					
                        <a href="../../../{{$swp_files[0]->swp_file}}" target="_blank"><img src="{{ URL::to('/images/pdf_download.png')}}"></a>
                    @endforeach
                @endif   
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Type of Job</label>
            <div class="col-sm-3">
                <table style="">
                    <tr><td><span>(A) Welding/Gas Cutting</span></td>
                        <td><input type="checkbox" name="welding_gas" disabled="" {{  ($permit_division_data->welding_gas == 'on') ? 'checked' : '' }}></td></tr>
                    <tr><td><span>(B) Riggine / Fittings</span></td>
                        <td><input type="checkbox" name="riggine" disabled="" {{  ($permit_division_data->riggine == 'on') ? 'checked' : ''}}></td></tr>
                    <tr><td><span>(C) Working at Height</span></td>
                        <td><input type="checkbox" name="working_at_height" disabled="" {{  ($permit_division_data->working_at_height == 'on') ? 'checked' : '' }}></td></tr>
                </table>
            </div>
            <div class="col-sm-3">
                 <table style="">
                    <tr><td><span>(D) Hydraulic/Pneumatic</span></td>
                        <td><input type="checkbox" name="hydraulic_pneumatic" disabled="" {{  ($permit_division_data->hydraulic_pneumatic == 'on') ? 'checked' : ''}}></td></tr>
                    <tr><td><span>(E)Painting/Cleaning</span></td>
                        <td><input type="checkbox" name="painting_cleaning" disabled="" {{  ($permit_division_data->painting_cleaning == 'on') ? 'checked' : '' }}></td></tr>
                    <tr><td><span>(F) Gas</span></td>
                        <td><input type="checkbox" name="gas" disabled="" {{  ($permit_division_data->gas == 'on') ? 'checked' : ''}}></td></tr>
                </table>
            </div>
            <div class="col-sm-3">
                 <table style="">
                    <tr><td><span>(G) Others (Specify)</span></td>
                        <td><input type="checkbox" name="others" id="OTHER"  disabled="" {{ ($permit_division_data->others == 'on') ? 'checked' : '' }} ></td></tr>
                    <tr><td id="show_specify" <?php if($permit_division_data->others == 'on') { ?> style="display: block" <?php } ?> >
                            <textarea class="form-control"  id="specify_others" readonly="" name="specify_others">{{$permit_division_data->specify_others}}</textarea>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </div>

        @if($permit_hazards->count() > 0)
            @foreach($permit_hazards as $key => $value)
            <div class="form-group row">
                @if($key == 0)
                    <label for="form-control-label" class="col-sm-2 col-form-label">Six Directional Hazards</label>
                @else
                    <label for="form-control-label" class="col-sm-2 col-form-label"></label>   
                @endif
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="{{$permit_hazards[$key]->dir}}" readonly><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="{{$permit_hazards[$key]->hazard}}" readonly><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="{{$permit_hazards[$key]->precaution}}" readonly><br>
                    </div>
                    <div class="col-sm-1">
                        <a class="btn btn-danger btn-sm" href="{{ route('admin.deletehaz',$permit_hazards[$key]->id) }}">Remove</a>
                    </div>
            </div>
            @endforeach
        @endif
    <!-- 1st -->
    <div class="form-group row remove_hazard1">
            <label for="form-control-label" class="col-sm-2 col-form-label">Six Directional Hazards<br>(Only 20)</label>
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
                            <!-- <option value="null">Select Hazards</option>  -->
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both1">
                        <input name="other_haz1" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional2" name="six_directional2">
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
                        <input name="other_haz2" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional3" name="six_directional3" required>
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
                        <input name="other_haz3" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional4" name="six_directional4" required>
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
                        <input name="other_haz4" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional5" name="six_directional5" required>
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
                        <input name="other_haz5" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional6" name="six_directional6" required>
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
                        <input name="other_haz6" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional7" name="six_directional7" required>
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
                        <input name="other_haz7" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional8" name="six_directional8" required>
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
                        <input name="other_haz8" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional9" name="six_directional9" required>
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
                        <input name="other_haz9" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional10" name="six_directional10" required>
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
                        <input name="other_haz10" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional11" name="six_directional11" required>
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
                        <input name="other_haz11" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional12" name="six_directional12" required>
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
                        <input name="other_haz12" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional13" name="six_directional13" required>
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
                        <input name="other_haz13" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional14" name="six_directional14" required>
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
                        <input name="other_haz14" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <input name="other_haz15" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional16" name="six_directional16" required>
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
                        <input name="other_haz16" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <input name="other_haz17" placeholder="Add Hazaredas" class="form-control" type="text">    
                        <input name="other_pre17" placeholder="Add Precaution" class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre17" name="pre17">
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!--18nd -->
        <div class="form-group row show_hazard18" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional18"  name="six_directional18" required>
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
                        <input name="other_haz18" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional19" name="six_directional19" required>
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
                        <input name="other_haz19" placeholder="Add Hazaredas" class="form-control" type="text">    
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
                        <select class="form-control six_directional20" name="six_directional20" required>
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
                        <input name="other_haz20" placeholder="Add Hazaredas" class="form-control" type="text">    
                        <input name="other_pre20" placeholder="Add Precaution" class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre20" name="pre20">
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
     
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Executing Agency</label>
            <div class="col-sm-10">
                <select class="form-control" id="issuer_id">
                    <?php
                        $get_issuer_id = DB::table('userlogins')->where('id',$permit_division_data->permitIssuerID)->get();
                    ?>
                    <option value="{{@$get_issuer_id[0]->id}}">{{@$get_issuer_id[0]->name}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Gate Pass Details</label>
            <div class="col-sm-10">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>Employee Name</th>
                            <th>Gate Pass No.</th>
                            <th>Designation</th>
                            <th>Age</th>   
                            <th>Expired</th>              
                        </tr>
                    </thead>
                    <tbody>
                        @if($gate_pass_details->count() > 0)
                            @foreach($gate_pass_details as $key => $value)
                                <tr>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->employee_name}}"  readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->gate_pass_no}}"  readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->designation}}" readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->age}}" readonly="readonly"></td>
                                    <td><input type="text" class="form-control"   value="{{$gate_pass_details[$key]->expirydate}}" readonly="readonly"></td>
                                </tr>
                            @endforeach
                        @endif    
                    </tbody>
                </table>
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
                                        <input type="radio"  name="safe_work" value="yes"  disabled  {{ ($permit_division_data->PermitSafeWork == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"  name="safe_work" value="no"  disabled  {{ ($permit_division_data->PermitSafeWork == 'no')  ? 'checked': '' }}>  &nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"   name="safe_work" value="na"  disabled   {{ ($permit_division_data->PermitSafeWork == 'na')  ? 'checked' : '' }}> &nbsp; NA 
                                    </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All persons are medically fit and have adequate quality of personal safety appliances. They will use it
                                compulsorily as per requirement of the job. All person will follow safety norms and conditions of Jamipol.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio"  name="all_person" value="yes" disabled {{ ($permit_division_data->PermitAll_person == 'yes') ?  'checked' : '' }}>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="all_person" value="no" disabled {{ ($permit_division_data->PermitAll_person == 'no')  ?  'checked' : '' }}>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="all_person" value="na" disabled {{ ($permit_division_data->PermitAll_person == 'na')  ?  'checked' : '' }}>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>  
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>Worker working on height must have height pass from NTTF/ Competent authority.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio"  name="worker_working" value="yes" disabled {{ ($permit_division_data->permitWorkerWorking == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"  name="worker_working" value="no" disabled {{  ($permit_division_data->permitWorkerWorking == 'no') ? 'checked' : '' }}>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"  name="worker_working" value="na" disabled {{  ($permit_division_data->permitWorkerWorking == 'na') ? 'checked' : '' }}>&nbsp; NA 
                                </label>
                                </td>                        
                            </div> 
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All lifting tools/Load bearing tools, tackles & safety appliances are in good condition with valid test certificate</strong> </td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio"  name="all_lifting_tools" value="yes" disabled  {{ ($permit_division_data->PermitAll_lifting_tools == 'yes') ? 'checked' : ''  }}>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="all_lifting_tools" value="no" disabled  {{ ($permit_division_data->PermitAll_lifting_tools == 'no') ? 'checked' : ''  }}>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="all_lifting_tools" value="na" disabled  {{ ($permit_division_data->PermitAll_lifting_tools == 'na') ? 'checked' : ''  }}>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td><strong>All safety requirement for working at height will be arranged I made and checked before use as per job
                                requirement ~ Access ladder, rest platform, Extended platform, scaffolding, hand rail, fencing of down area /
                                ground area, Use of full body harness etc.</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio" name="all_safety_requirement" value="yes"  disabled  {{ ($permit_division_data->permitAll_safety_requirement == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"  name="all_safety_requirement" value="no"  disabled  {{ ($permit_division_data->permitAll_safety_requirement == 'no')  ? 'checked' : '' }}>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"  name="all_safety_requirement" value="na"  disabled  {{ ($permit_division_data->permitAll_safety_requirement == 'na')  ? 'checked' : '' }}>&nbsp; NA 
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
                                    <input type="radio"  name="all_person_are_trained" value="yes" disabled  {{ ($permit_division_data->PermitAll_person_are_trained == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="all_person_are_trained" value="no" disabled {{ ($permit_division_data->PermitAll_person_are_trained == 'no') ? 'checked' : '' }}>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="all_person_are_trained" value="na" disabled  {{ ($permit_division_data->PermitAll_person_are_trained == 'na') ? 'checked' : '' }}>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                        <tr>
                            <div class="col-sm-8">
                                <td> <strong>Ensure the applicable activity check list, signed by permit requester, receiver and verified by permit issuer.
                                Checklist shall be attached with permit to work system</strong></td>
                            </div>
                            <div class="col-sm-4">
                                <td>
                                <label class="form-check-label">
                                    <input type="radio"  name="ensure_the_appplicablle" value="yes"  disabled {{ ($permit_division_data->permitEnsure_the_appplicablle == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="ensure_the_appplicablle" value="no" disabled  {{ ($permit_division_data->permitEnsure_the_appplicablle == 'no') ? 'checked' : '' }}>&nbsp; No  
                                </label>
                                <label class="form-check-label">
                                    <input type="radio"   name="ensure_the_appplicablle" value="na" disabled  {{ ($permit_division_data->permitEnsure_the_appplicablle == 'na') ? 'checked' : '' }}>&nbsp; NA 
                                </label>
                                </td>                        
                            </div>
                        </tr>      
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                @if($permit_division_data->PermitStatus != 'Requested')
                <input class="checkbox-inline" type="checkbox" disabled name="agree" id="agree" checked>
                @else 
                <input class="checkbox-inline" type="checkbox" name="agree" id="agree"> 
                @endif &nbsp;
                <b><i>Training imparted by
               
                @if($permit_division_data->PermitStatus != 'Requested') 
                <?php $issuer = DB::table('userlogins')->where('id',$permit_division_datas[0]->permitIssuerID)->get();
                    echo @$issuer[0]->name;
                ?>         
                @else
                me
                @endif
                to all involved in the job and all of their queries have been clarified. Also Risks involved in the job
                and its mitigation plan have also been discussed.I have visited the site and checked. Hence this clearance is being given from site and not from office.</i></b>
            </div>
        </div>

        @if($permit_division_data->PermitStatus == 'Requested')
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <a herf=""> <input type="button" id="next" name="button" class="btn btn-primary" value="Next" onclick="showdown();"> </a> 
            </div>
        </div>
        @endif
    </div>

<!---------------------------------------------------------------------------------------------------------------- -->
    <a name="anchor"></a>  @if($permit_division_data->PermitStatus != 'Requested') <div id="second" style="display:block"> @else <div id="second" style="display:none"> @endif
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Post Site Picture </label>
                <div class="col-sm-3">
                    <input type="file" class="form-control-file" name="post_site_pic">    
                    <div class="img-thumbnail">
                    <img src="@if(isset($permit_division_data))
                                {{url('')}}/{{$permit_division_data->PermitSitePic}}
                            @endif"
                        class="img-fluid" alt=""  width="100" height="100"/>
                    </div> 
                </div>
            </div>
            

            <div class="form-group row" style="display: none">
                <label for="form-control-label" class="col-sm-2 col-form-label">Latitude / Longitude </label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="latlong" value="{{$permit_division_data->PermitlatLong}}">         
                </div>
            </div>

            <!-- Power Clearance Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Shut Down Required? </label>
                <div class="col-sm-10">
                    <p class="form-control" readonly> {{ ($permit_division_data->PermitPowerClearance == 'on') ?  "Yes"  : "No" }}  </p>     
                </div>
            </div>

            @if($permit_division_data->PermitPowerClearance == 'on')
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Cutting User</label>
                <div class="col-sm-10">
                    <select class="form-control" name="power_cutting_userid" {{ ($permit_division_data->PermitPower_clearance_number != "" ) ? 'readonly' : '' }}  @if($permit_division_data->PermitPowerClearance == 'on') required @endif>
                        <option value="">Select Power Cutting user</option>
                        @if($powerCuttingUsers->count() > 0)
                            @foreach($powerCuttingUsers as $powerCuttingUser)
                                <option value="{{$powerCuttingUser->id}}" {{ (@$permit_division_data->ppc_userid == $powerCuttingUser->id) ? "selected" : "" }} >{{$powerCuttingUser->name}}</option>
                            @endforeach
                        @endif  
                    </select>
                </div>
            </div>
            @endif

            @if($permit_division_data->PermitPower_clearance_number != "")
                <!-- Power Clearence number after that cutting user going to flow -->
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearence Number</label>
                    <div class="col-sm-10">
                        <p class="form-control" readonly>
                           {{ ($permit_division_data->PermitPower_clearance_number)}}
                        </p>     
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Select Voltage Level </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" value="<?php echo strtoupper($P->vlevel)?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Issuer Name</label>
                        <div class="col-sm-4">
                            <select class="form-control" readonly>
                            @if($P->issuer_power)
                                <?php @$isspower = UserLogin::where('id',$P->issuer_power)->first(); ?>
                                <option value="{{$isspower->id}}">{{$isspower->name }}</option>
                            @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control"  readonly>
                            @if($P->electrical_license_issuer)
                                <option value="{{ $P->electrical_license_issuer}}"> {{ $P->electrical_license_issuer }}</option>
                            @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control"  readonly>
                            @if($P->validity_date_issuer)
                                <option value="<?= $P->validity_date_issuer ?>"> <?= $P->validity_date_issuer ?></option>
                            @endif
                            </select>
                        </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Receiver Name</label>
                        <div class="col-sm-4">
                            <select class="form-control" readonly>
                            @if($P->rec_power)
                                <?php @$recpower = UserLogin::where('id',$P->rec_power)->first(); ?>
                                <option value="{{$recpower->id}}"> {{$recpower->name}}</option>
                            @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" readonly>
                            @if($P->electrical_license_rec)
                                <option value="{{$P->electrical_license_rec}}">{{$P->electrical_license_rec}}</option>
                            @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" readonly>
                            @if($P->validity_date_rec)
                                <option value="{{$P->validity_date_rec}}"> {{$P->validity_date_rec}}</option>
                            @endif
                            </select>
                        </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearence Details<p><b>(if Not Applicable, Please mention the reason/remarks.)</b></p></label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead>
                                    <tr> 
                                        <th>Name of the Equipment</th>
                                        <th>Equipment Lock No.</th>
                                        <th>Location</th>
                                        <th>Box No</th>   
                                        <th>Caution Tag No</th>   
                                    </tr>
                                </thead>
                                <?php $powerCLS  = PowerClearence::where('permit_id',$P->id)->get(); ?>
                                @if($powerCLS->count() > 0)
                                    @foreach($powerCLS as $key1 => $value1)
                                        <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->equipment }}"></td>
                                                <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->positive_isolation_no }}"></td>
                                                <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->location }}"></td>
                                                <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->box_no }}"></td>
                                                <td><input type="text" class="form-control" readonly value="{{ $powerCLS[$key1]->caution_no }}"></td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Comment of Power Cutting </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" readonly>{{$P->power_cutting_remarks}}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label"><b>Executing Personal Lock Number</b></label>
                    <div class="col-sm-10">
                        <input typr="text" class="form-control" name="excuting_personal_lock" value="{{$permit_division_data->executing_lock}}" {{ ($permit_division_data->PermitPower_clearance_number != "") ? 'required' : '' }} {{ ($permit_division_data->PermitStatus == "Parea") ? "readonly" : "" }}>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label"><b>Working Personal Lock Number</b></label>
                    <div class="col-sm-10">
                        <input typr="text" class="form-control" name="working_personal_lock" value="{{$permit_division_data->working_lock}}" {{ ($permit_division_data->PermitPower_clearance_number != "") ? 'required' : '' }} {{ ($permit_division_data->PermitStatus == "Parea") ? "readonly" : "" }}>
                    </div>
                </div>
            @endif
    

        

            <!-- End Power Clearance Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Any Other Type Isolation</label>
                <div class="col-sm-10">
                    <select class="form-control" name="other_Isolation" onchange="OtherIsolation(this.value)"  {{ ($permit_division_data->PermitStatus == "Parea")  ? 'disabled' : '' }}>
                        <option value="">Select </option>
                        <option value="yes" {{ ($permit_division_data->other_isolation == 'yes') ?  'selected' : ''  }}>Yes</option>
                        <option value="no" {{ ($permit_division_data->other_isolation == 'no')  ? 'selected'  : ''  }}>No</option>
                    </select>
                </div>
            </div>
            <div style="display: none"; id="ShowotherIsolation">
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Fill Other Details</label>
                    <div class="col-sm-9">
                        <table class="table table-bordered">
                                <thead> 
                                    <tr> 
                                        <th>Positive Isolation Lock Number </th>
                                        <th>Equipment</th>
                                        <th>Location</th>      
                                    </tr>
                                </thead> 
                                <tbody id="append_power_clearence_other">
                                    <tr class="remove_tr_power_cls_other">
                                        <td><input type="text" class="form-control" name="positive_other[]"></td>
                                        <td><input type="text" class="form-control" name="equipment_other[]"></td>
                                        <td><input type="text" class="form-control" name="location_other[]"></td>
                                    </tr>
                                </tbody> 
                        </table>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" id="add-power-clearence-other" class="btn btn-primary btn-sm">+</button>&nbsp;
                        <button type="button" id="del-power_clearence_other" class="btn btn-danger btn-sm">-</button>
                    </div>
                </div>
            </div>
            @if($permit_division_data->other_isolation == 'yes')
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Fill Other Details</label>
                    <div class="col-sm-9">
                        <table class="table table-bordered">
                                <thead> 
                                    <tr> 
                                        <th>Positive Isolation Lock Number </th>
                                        <th>Equipment</th>
                                        <th>Location</th>      
                                    </tr>
                                </thead> 
                                @if($otherisolation->count() > 0)
                                    @foreach($otherisolation as $key => $value)
                                    <tbody>
                                        <tr class="">
                                            <td><input type="text" class="form-control" name="positive_other[]" value="{{$otherisolation[$key]->positive_other}}" {{ ($permit_division_data->PermitStatus == "Parea")   ? 'disabled' : '' }} ></td>
                                            <td><input type="text" class="form-control" name="equipment_other[]" value="{{$otherisolation[$key]->equipment_other}}" {{ ($permit_division_data->PermitStatus == "Parea")   ? 'disabled' : '' }} ></td>
                                            <td><input type="text" class="form-control" name="location_other[]" value="{{$otherisolation[$key]->location_other}}" {{ ($permit_division_data->PermitStatus == "Parea")   ? 'disabled' : '' }} ></td>
                                        </tr>
                                    </tbody> 
                                    @endforeach
                                @endif
                        </table>
                    </div>
                </div>
            @endif

            <!-- Confined Space  Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space?</label>
                <div class="col-sm-10">
                    <p class="form-control" readonly>
                        {{ ($permit_division_data->PermitConfinedSpace == 'on') ? "Yes" : "No" }} 
                    </p>      
                </div>
            </div>
        <div <?php if(@$permit_division_data->PermitConfinedSpace == 'off') { ?> style="display: none"; <?php } ?>>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space Details<p><b>(if Not Applicable, Please mention the reason/remarks.)</b></p></label>
                <div class="col-sm-9">
                    <table class="table table-bordered">
                        <thead> 
                            <tr> 
                                <th>Clearance No</th>
                                <th>Depth </th>
                                <th>Location</th>      
                            </tr>
                        </thead>
            
                        @if($confined_spaces->count() > 0)
                            @foreach($confined_spaces as $key => $value) 
                            <tbody id="append_confined_deatils">
                                <tr class="remove_confined" id="remove_confined">
                                    <input type="hidden" class="form-control" name="c_id[]" value="{{$confined_spaces[$key]->id}}">
                                    <td><input type="text" class="form-control" name="clearance_no[]" value="{{$confined_spaces[$key]->clearance_no}}"></td>
                                    <td><input type="text" class="form-control" name="depth[]"  value="{{$confined_spaces[$key]->depth}}"></td>
                                    <td><input type="text" class="form-control" name="confined_location[]" value="{{$confined_spaces[$key]->location}}"></td>
                                </tr>
                            </tbody>
                           @endforeach
                        @else
                            <tbody id="append_confined_deatils">
                                <tr class="remove_confined" id="remove_confined">
                                    <input type="hidden" class="form-control" name="c_id[]">
                                    <td><input type="text" class="form-control" name="clearance_no[]"  {{ ($permit_division_data->PermitConfinedSpace == 'on')   ? 'required' : '' }}></td>
                                    <td><input type="text" class="form-control" name="depth[]"  {{ ($permit_division_data->PermitConfinedSpace == 'on')   ? 'required' : '' }}></td>
                                    <td><input type="text" class="form-control" name="confined_location[]"  {{ ($permit_division_data->PermitConfinedSpace == 'on')   ? 'required' : '' }}></td>
                                </tr>
                            </tbody>
                        @endif 
                    </table>
                </div>
                @if($permit_division_data->PermitStatus != "Prcv")
                <div class="col-sm-1" style="">
                    <button type="button" id="add-confined" class="btn btn-primary btn-sm">+</button>&nbsp;
                    <button type="button" id="del-confined" class="btn btn-danger btn-sm">-</button>
                </div> 
                @endif
            </div>
        </div>
        
        <!-- End Power Clearance Details  for fill -->
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">High Risk Job? </label>
            <div class="col-sm-10">
                <p class="form-control" readonly>
                    {{ ($permit_division_data->PermitHighRisk == 'on') ?  "Yes" : "No" }} 
                </p>       
            </div>
        </div>
        
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Area Clearance Required </label>
            <div class="col-sm-10">
                <input type="checkbox" class="form-control-check" name="area_clearance_req"  id="area_cleck" {{ ($permit_division_data->PermitStatus == "Parea")  ? 'readonly' : '' }} {{ ($permit_division_data->PermitArea_clearence == 'on') ? 'checked' : '' }}>         
            </div>
        </div>
        <div class="form-group row" style="display:none" id="ownerAgency">
            <label for="form-control-label" class="col-sm-2 col-form-label">Owner Agency Name </label>
            <div class="col-sm-10">
                <select class="form-control" name="area_clearence_id" id="emp_id" >
                    <option value="">Select</option>
                    @if($forAreaClearence->count() > 0)
                    <?php $users = UserLogin::where('id',@$permit_division_data->PermitArea_clearenceId)->first(); ?>
                        @foreach($forAreaClearence as $forArea)
                            <option value="{{@$forArea->id}}" {{ (@$forArea->id == @$users->id) ? "selected" : "" }}>{{@$forArea->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        @if($permit_division_data->PermitStatus == "Parea")
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Special Instruction</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="s_instruction"></textarea>
            </div>
        </div>
        @endif
        <input type="hidden" name="status" value="{{$permit_division_data->PermitStatus}}">
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <input type="submit"  name="button" class="btn btn-primary" value="Submit">
            </div>
        </div>

    </div>
</form>
@endif
@endsection
@section('scripts')
<script>
    //click to next page 
    $(document).ready(function(){
        $('#prcvsubmit').prop('disabled', true);
        $('#next').prop('disabled', true);
        $('#agree').click(function(){
            if($(this).is(':checked'))
            {
                $('#next').prop('disabled', false);
            }
            else
            {
                $('#next').prop('disabled', true);
            }
        });
        $('#checktoSubmit').click(function(){
            if($(this).is(':checked'))
            {
                $('#prcvsubmit').prop('disabled', false);
            }
            else
            {
                $('#prcvsubmit').prop('disabled', true);
            }
        });
    });

    //shutdown Clearance Number 
    $("#add-power-clearence").on("click", function (e) {
            var count = $(".remove_tr_power_cls").length + 1;
            // console.log(count);
            $('#append_power_clearence').append(`<tr class="remove_tr_power_cls">
                    <input type="hidden" class="form-control" name="p_id[]"  required>
                    <td><input type="text" class="form-control" name="positive_isolation[]" required></td>
                    <td><input type="text" class="form-control" name="equipment[]" required></td>
                    <td><input type="text" class="form-control" name="power_location[]" required ></td>
                </tr>`);
    });
    //Remove Top Click
    $("#del-power_clearence").on("click", function (e) {
            if($('.remove_tr_power_cls').length > 1){
                $(".remove_tr_power_cls:last").remove();
            }
    });

    //shutdown Clearance Number 
    $("#add-power-clearence-other").on("click", function (e) {
            var count = $(".remove_tr_power_cls_other").length + 1;
            // console.log(count);
            $('#append_power_clearence_other').append(`<tr class="remove_tr_power_cls_other">
                    <td><input type="text" class="form-control" name="positive_other[]"></td>
                    <td><input type="text" class="form-control" name="equipment_other[]"></td>
                    <td><input type="text" class="form-control" name="location_other[]" ></td>
                </tr>`);
    });
    //Remove Top Click
    $("#del-power_clearence_other").on("click", function (e) {
            if($('.remove_tr_power_cls_other').length > 1){
                $(".remove_tr_power_cls_other:last").remove();
            }
    });


    //Confined Clearance Number to add
    $("#add-confined").on("click", function (e) {
            var count = $(".remove_confined").length + 1;
            // console.log(count);
            $('#append_confined_deatils').append(`<tr class="remove_confined">
                    <input type="hidden" class="form-control" name="c_id[]">
                    <td><input type="text" class="form-control" name="clearance_no[]" required></td>
                    <td><input type="text" class="form-control" name="depth[]"  required></td>
                    <td><input type="text" class="form-control" name="confined_location[]" required></td>
                </tr>`);
            });
    //Remove Confined To Click 
    $("#del-confined").on("click", function (e) {
            if($('.remove_confined').length > 1){
                $(".remove_confined:last").remove();
            }
    });

    function scrollToAnchor(aid){
        var aTag = $("a[name='"+ aid +"']");
        $('html,body').animate({scrollTop: aTag.offset().top},'slow');
    }

    function showdown()
    {         
        $("#second").css({'display':'block'});
        scrollToAnchor('anchor');
    }

    //1 st
    $('#six_directional1').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(jobID);
            $('#haz1').html("");
            $('#pre1').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz1").append('<option value="null">Select To Other</option>'); 
                        for(var i=0;i<data.length;i++){
                            $("#haz1").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre1").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                        }   
                        $("#haz1").append('<option value="other1">Other</option>'); 
                    }
                });
            }
    
    });

    //2 st
    $('.six_directional2').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz2').html("");
            $('#pre2').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz2").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz2").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre2").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz2").append('<option value="other2">Other</option>'); 
                    }
                });
            }
    });
    //3 st
    $('.six_directional3').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz3').html("");
            $('#pre3').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz3").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz3").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre3").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz3").append('<option value="other3">Other</option>'); 
                    }
                });
            }
    });
    //4 st
    $('.six_directional4').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz4').html("");
            $('#pre4').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz4").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz4").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre4").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz4").append('<option value="other4">Other</option>'); 
                    }
                });
            }
    });
    //5 st
    $('.six_directional5').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz5').html("");
            $('#pre5').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz5").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz5").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre5").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz5").append('<option value="other5">Other</option>'); 
                    }
                });
            }
    });
    //6 st
    $('.six_directional6').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz6').html("");
            $('#pre6').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz6").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz6").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre6").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz6").append('<option value="other6">Other</option>'); 
                    }
                });
            }
    });
    //7 st
    $('.six_directional7').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz7').html("");
            $('#pre7').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz7").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz7").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre7").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz7").append('<option value="other7">Other</option>'); 
                    }
                });
            }
    });
    //8 st
    $('.six_directional8').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz8').html("");
            $('#pre8').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz8").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz8").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre8").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz8").append('<option value="other8">Other</option>'); 
                    }
                });
            }
    });
    //9 st
    $('.six_directional9').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz9').html("");
            $('#pre9').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz9").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz9").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre9").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz9").append('<option value="other9">Other</option>'); 
                    }
                });
            }
    });
    //10 st
    $('.six_directional10').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz10').html("");
            $('#pre10').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz10").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz10").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre10").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz10").append('<option value="other10">Other</option>'); 
                    }
                });
            }
    });
    //11 st
    $('.six_directional11').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz11').html("");
            $('#pre11').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz11").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz11").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre11").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz11").append('<option value="other11">Other</option>'); 
                    }
                });
            }
    });
    //12 st
    $('.six_directional12').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz12').html("");
            $('#pre12').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz12").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz12").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre12").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz12").append('<option value="other12">Other</option>'); 
                    }
                });
            }
    });
    //13 st
    $('.six_directional13').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz13').html("");
            $('#pre13').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz13").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz13").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre13").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz13").append('<option value="other13">Other</option>'); 
                    }
                });
            }
    });
    //14 st
    $('.six_directional14').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz14').html("");
            $('#pre14').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz14").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz14").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre14").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz14").append('<option value="other14">Other</option>'); 
                    }
                });
            }
    });
    //15 st
    $('.six_directional15').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz15').html("");
            $('#pre15').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz15").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz15").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre15").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz15").append('<option value="other15">Other</option>'); 
                    }
                });
            }
    });
    //16 st
    $('.six_directional16').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz16').html("");
            $('#pre16').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz16").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz16").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre16").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz16").append('<option value="other16">Other</option>'); 
                    }
                });
            }
    });
    //17 st
    $('.six_directional17').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz17').html("");
            $('#pre17').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz17").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz17").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre17").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz17").append('<option value="other17">Other</option>'); 
                    }
                });
            }
    });
    //18 st
    $('.six_directional18').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz18').html("");
            $('#pre18').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz18").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz18").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre18").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz18").append('<option value="other18">Other</option>'); 
                    }
                });
            }
    });
    //19 st
    $('.six_directional19').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz19').html("");
            $('#pre19').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz19").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz19").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre19").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz19").append('<option value="other19">Other</option>'); 
                    }
                });
            }
    });
    //20 st
    $('.six_directional20').on('change',function(){
        var six_directional = $(this).val();
        var jobID = $('#jobID').val();
        // alert(permit_id)
            $('#haz20').html("");
            $('#pre20').html("");
            if(jobID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.get_Hazard')}}/" + jobID  + "/" + six_directional,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        $("#haz20").append('<option value="null">Select To Other</option>');
                        for(var i=0;i<data.length;i++){
                            $("#haz20").append('<option value="'+data[i].hazarde+'" >'+data[i].hazarde+'</option>');
                            $("#pre20").append('<option value="'+data[i].precaution+'" >'+data[i].precaution+'</option>');
                            
                        }
                        $("#haz20").append('<option value="other20">Other</option>'); 
                    }
                });
            }
    });


    //SHOW THE BOTH 1
    function otherFunction1() {
        var selectBox = document.getElementById("haz1");
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
        if (selectedValue=="other1"){
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
        if (selectedValue=="other2"){
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
        if (selectedValue=="other3"){
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
        if (selectedValue=="other4"){
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
        if (selectedValue=="other5"){
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
        if (selectedValue=="other6"){
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
        if (selectedValue=="other7"){
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
        if (selectedValue=="other8"){
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
        if (selectedValue=="other9"){
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
        if (selectedValue=="other10"){
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
        if (selectedValue=="other11"){
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
        if (selectedValue=="other12"){
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
        if (selectedValue=="other13"){
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
        if (selectedValue=="other14"){
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
        if (selectedValue=="other15"){
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
        if (selectedValue=="other16"){
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
        if (selectedValue=="other17"){
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
        if (selectedValue=="other18"){
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
        if (selectedValue=="other19"){
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
        if (selectedValue=="other20"){
            $('#show-both20').show();
            $('#pre20').hide();
        }
        else {
            $('#show-both20').hide();
            $('#pre20').show();
        }
    }

    // Code to add six direection hazard 20
    var button = document.getElementById("add-Hazards"),
    count = 1;
    button.onclick = function() {
        var count2 = $("#count").val();
        if (count2!="20")
        {
            count = Number(count2) + 1;
        }
        add(count);
        $('#count').val(count); 
    };
    function add(id){
        // alert(id)
        $('.show_hazard'+id).show();       
    }

    $("#remove-Hazards").click(function(){
        var removecount = $("#count").val();
        $('.show_hazard'+removecount).hide();   
        if (removecount!="1")
        {
        removecount -=1;    
        }
        $('#count').val(removecount); 
    });

    
    $('#area_cleck').click(function(){
        if($(this).is(':checked'))
        {
            $('#ownerAgency').show();
            $('#emp_id').prop('required', true);
        }
        else
        {
            $('#emp_id').prop('required', false);
            $('#ownerAgency').hide();
        }
    });
    $('#voltagelevel').on('change',function(){ 
        var vlevel = $(this).val();
        // alert(vlevel);
        $('#isspower').html("");
        $('#receiverpower').html("");
        $('#license_numberISS').html("");
        $('#validity_dateISS').html("");
        $('#license_numberREC').html("");
        $('#validity_dateREC').html("");
        // alert(vlevel);
        $.ajax({
            type:'GET',
            url:"{{route('admin.sendvoltagelevel')}}/" + vlevel,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                console.log(data);
                $("#isspower").html('<option value="">--Select--</option>');
                for(var i=0;i<data.length;i++){
                    if (data[i].supervisor_name)
                    {
                        var s1= "(" + data[i].supervisor_name + ")";
                    }
                    else
                    {
                        var s1 ="";
                    }

                    $("#isspower").append("<option value='"+data[i].userid+"'>" + data[i].name + " " + s1 +"</option>");
                    // $("#isspower").append('<option value="'+data[i].pid+'" >'+data[i].name+'</option>');
                }
            }
        });
        $.ajax({
            type:'GET',
            url:"{{route('admin.sendvoltagelevelreciver')}}/" + vlevel,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                console.log(data);
                $("#receiverpower").html('<option value="">--Select--</option>');
                for(var i=0;i<data.length;i++){
                     // console.log(data[i].pid);
                     if (data[i].supervisor_name)
                     {
                        var s= "(" + data[i].supervisor_name + ")";
                     }
                     else
                     {
                        var s ="";
                     }
                    $("#receiverpower").append("<option value='"+data[i].userid+"'>" + data[i].name + " " + s +"</option>");

                }
            }
        });
    });
    $('#isspower').on('change',function(){ 
        var id = $(this).val();
        $('#license_numberISS').html("");
        $('#validity_dateISS').html("");
        // alert(id);
        $.ajax({
            type:'GET',
            url:"{{route('admin.issuer_electrical_license')}}/" + id,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                console.log(data);
                for(var i=0;i<data.length;i++){
                    $("#license_numberISS").append('<option value="'+data[i].electrical_license+'" >'+data[i].electrical_license+'</option>');
                    $("#validity_dateISS").append('<option value="'+data[i].validity_date+'" >'+data[i].validity_date+'</option>');
                }
            }
        });
    });
    $('#receiverpower').on('change',function(){ 
        var id = $(this).val();
        $('#license_numberREC').html("");
        $('#validity_dateREC').html("");
        // alert(id);
        $.ajax({
            type:'GET',
            url:"{{route('admin.recevier_electrical_license')}}/" + id,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                console.log(data);
                for(var i=0;i<data.length;i++){
                    $("#license_numberREC").append('<option value="'+data[i].electrical_license+'" >'+data[i].electrical_license+'</option>');
                    $("#validity_dateREC").append('<option value="'+data[i].validity_date+'" >'+data[i].validity_date+'</option>');
                }
            }
        });
    });


    function OtherIsolation(otheriso) {
        if(otheriso == 'yes'){
             $('#ShowotherIsolation').show();

        }
        else if(otheriso == 'no'){
            $('#ShowotherIsolation').hide();


        }
    }
</script>
@endsection