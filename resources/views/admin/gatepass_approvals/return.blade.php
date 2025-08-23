<?php 
use App\Department;
use App\UserLogin;
use App\ShutdownChild;
use App\PowerCutting;
use App\PowerGetting;
?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.list_permit.index')}}">List Permit</a></li>
@endsection                        
@section('content')
@if($permit_division_datas[0]->return_status == "Pending" || $permit_division_datas[0]->return_status == "Pending_area")
<div class="form-group-row">
        <div class="col-sm-12">
            <div class="alert alert-danger" style="text-align:center">
                Pending for Return
            </div>
        </div>
    </div>
@endif
@if($permit_division_datas[0]->PermitStatus == "Issued")
<form action="{{ route('admin.printReturn',$id) }}" method="post" enctype="multipart/form-data"> 
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
                <input type="text" class="form-control"  value="{{$permit_division_data->permitOrder}}" readonly><br>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  value="{{$permit_division_data->permitOrderValidity}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Job Description</label>
            <div class="col-sm-10">
                <textarea  class="form-control" readonly="">{{$permit_division_data->JobDescription }}</textarea> 
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Job Location</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$permit_division_data->JobLocation}}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"   autocomplete="off"  value="{{ date('d-m-Y h:i', strtotime($permit_division_data->startDate))  }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  autocomplete="off" value="{{ date('d-m-Y h:i', strtotime($permit_division_data->endDate))}}" readonly>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Job</label>
            <div class="col-sm-10">
                <select class="form-control" id="jobID" readonly="readonly">
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
                <input type="text" class="form-control"  value="{{$job_data->jobSwpNumber}}" readonly> 
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">SWP File</label>
            <div class="col-sm-10">
                @if($swp_files->count() > 0)
                    @foreach($swp_files as $s)
                        <a href="../../{{$swp_files[0]->swp_file}}" target="_blank"><img src="{{ URL::to('public/images/pdf_download.png')}}"></a>
                    @endforeach
                @endif   
            </div>
        </div>
        <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Type of Job</label>
        <div class="col-sm-3">
            <table style="">
                <tr><td><span>(A) Welding/Gas Cutting</span></td>
                    <td><input type="checkbox" name="welding_gas" disabled="" <?php if($permit_division_data->welding_gas == 'on') echo 'checked' ?> ></td></tr>
                <tr><td><span>(B) Riggine / Fittings</span></td>
                    <td><input type="checkbox" name="riggine" disabled="" <?php if($permit_division_data->riggine == 'on') echo 'checked' ?> ></td></tr>
                <tr><td><span>(C) Working at Height</span></td>
                    <td><input type="checkbox" name="working_at_height" disabled="" <?php if($permit_division_data->working_at_height == 'on') echo 'checked' ?> ></td></tr>
            </table>
        </div>
        <div class="col-sm-3">
             <table style="">
                <tr><td><span>(D) Hydraulic/Pneumatic</span></td>
                    <td><input type="checkbox" name="hydraulic_pneumatic" disabled="" <?php if($permit_division_data->hydraulic_pneumatic == 'on') echo 'checked' ?> ></td></tr>
                <tr><td><span>(E)Painting/Cleaning</span></td>
                    <td><input type="checkbox" name="painting_cleaning" disabled="" <?php if($permit_division_data->painting_cleaning == 'on') echo 'checked' ?> ></td></tr>
                <tr><td><span>(F) Gas</span></td>
                    <td><input type="checkbox" name="gas" disabled="" <?php if($permit_division_data->gas == 'on') echo 'checked' ?> ></td></tr>
            </table>
        </div>
        <div class="col-sm-3">
             <table style="">
                <tr><td><span>(G) Others (Specify)</span></td>
                    <td><input type="checkbox" name="others" id="OTHER"  disabled="" <?php if($permit_division_data->others == 'on') echo 'checked' ?> ></td></tr>
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
                        <input type="text" class="form-control"  value="{{$permit_hazards[$key]->dir}}" readonly><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control"  value="{{$permit_hazards[$key]->hazard}}" readonly><br>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control"  value="{{$permit_hazards[$key]->precaution}}" readonly><br>
                    </div>
            </div>
            @endforeach
        @endif
    <!-- 1st -->
    <div class="form-group row remove_hazard1">
            <label for="form-control-label" class="col-sm-2 col-form-label">Six Directional Hazards<br>(Only 20)</label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional1" id="six_directional1" readonly  disabled required>
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
                        <select class="form-control" id="haz1" required readonly disabled onchange="otherFunction1();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both1">
                        <input  placeholder="Add Hazaredas"  readonly class="form-control" type="text">    
                        <input placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre1" readonly disabled>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
                    
    </div>

        <!-- 2nd -->
        <div class="form-group row show_hazard2" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional2"  readonly>
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
                        <select class="form-control" id="haz2"  readonly required onchange="otherFunction2();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both2">
                        <input  placeholder="Add Hazaredas"  readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre2"  readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 3nd -->
        <div class="form-group row show_hazard3" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional3" readonly required>
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
                        <select class="form-control" id="haz3" readonly required onchange="otherFunction3();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both3">
                        <input  placeholder="Add Hazaredas"  readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre3"  readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 4nd -->
        <div class="form-group row show_hazard4" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional4"  readonly required>
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
                        <select class="form-control" id="haz4"  readonly required onchange="otherFunction4();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both4">
                        <input  placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre4" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 5nd -->
        <div class="form-group row show_hazard5" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional5" readonly required>
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
                        <select class="form-control" id="haz5"  readonly required onchange="otherFunction5();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both5">
                        <input  placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre5" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 6nd -->
        <div class="form-group row show_hazard6" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional6" readonly  required>
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
                        <select class="form-control" id="haz6" readonly  required onchange="otherFunction6();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both6">
                        <input placeholder="Add Hazaredas" readonly  class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre6" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        
        <!-- 7nd -->
        <div class="form-group row show_hazard7" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional7" readonly  required>
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
                        <select class="form-control" id="haz7"  readonly required onchange="otherFunction7();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both7">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre7" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 8nd -->
        <div class="form-group row show_hazard8" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional8"  readonly  required>
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
                        <select class="form-control" id="haz8"  readonly required onchange="otherFunction8();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both8">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre8" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 9nd -->
        <div class="form-group row show_hazard9" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional9"   readonly required>
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
                        <select class="form-control" id="haz9" readonly  required onchange="otherFunction9();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both9">
                        <input  placeholder="Add Hazaredas"  readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution"  readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre9" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 10nd -->
        <div class="form-group row show_hazard10" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional10" readonly required>
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
                        <select class="form-control" id="haz10" readonly  required onchange="otherFunction10();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both10">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre10" readonly >
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 11nd -->
        <div class="form-group row show_hazard11" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional11"  readonly required>
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
                        <select class="form-control" id="haz11" readonly  required onchange="otherFunction11();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both11">
                        <input  placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution"readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre11" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 12nd -->
        <div class="form-group row show_hazard12" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional12"  readonly required>
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
                        <select class="form-control" id="haz12" readonly required onchange="otherFunction12();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both12">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre12" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 13nd -->
        <div class="form-group row show_hazard13" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional13"  readonly required>
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
                        <select class="form-control" id="haz13" readonly required onchange="otherFunction13();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both13">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre13" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 14nd -->
        <div class="form-group row show_hazard14" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional14"  readonly required>
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
                        <select class="form-control" id="haz14" readonly required onchange="otherFunction14();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both14">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre14" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 15nd -->
        <div class="form-group row show_hazard15" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional15" id="six_directional" readonly required>
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
                        <select class="form-control" id="haz15"  readonly  required onchange="otherFunction15();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both15">
                        <input  placeholder="Add Hazaredas" readonly  class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre15" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 16nd -->
        <div class="form-group row show_hazard16" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional16" readonly required>
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
                        <select class="form-control" id="haz16" readonly  required onchange="otherFunction16();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both16">
                        <input placeholder="Add Hazaredas" readonly class="form-control" type="text">    
                        <input placeholder="Add Precaution"readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre16" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!--17nd -->
        <div class="form-group row show_hazard17" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional17" id="six_directional" readonly required>
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
                        <select class="form-control" id="haz17" readonly  required onchange="otherFunction17();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both17">
                        <input  placeholder="Add Hazaredas"  readonly  class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre17" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!--18nd -->
        <div class="form-group row show_hazard18" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional18" readonly  required>
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
                        <select class="form-control" id="haz18" readonly required onchange="otherFunction18();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both18">
                        <input  placeholder="Add Hazaredas" readonly  class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre18" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 19nd -->
        <div class="form-group row show_hazard19" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional19"  readonly required>
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
                        <select class="form-control" id="haz19" readonly required onchange="otherFunction19();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both19">
                        <input  placeholder="Add Hazaredas"  readonly class="form-control" type="text">    
                        <input  placeholder="Add Precaution" readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre19" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
        <!-- 20nd -->
        <div class="form-group row show_hazard20" style="display: none">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select class="form-control six_directional20" readonly  required>
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
                        <select class="form-control" id="haz20"  readonly required onchange="otherFunction20();">
                            <option value="null">Select Hazards</option> 
                        </select>
                    </div>
                    <div class="col-sm-2" style="display: none" id="show-both20">
                        <input placeholder="Add Hazaredas"  readonly class="form-control" type="text">    
                        <input placeholder="Add Precaution" readonly  class="form-control" type="text">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" id="pre20" readonly>
                            <option value="null">Select Precaution</option>
                        </select>
                    </div>
        </div>
     
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Executing Agency</label>
            <div class="col-sm-10">
                <select class="form-control" id="issuer_id" readonly>
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
                                    <td><input type="text" class="form-control"  value="{{$gate_pass_details[$key]->employee_name}}"  readonly="readonly"></td>
                                    <td><input type="text" class="form-control"  value="{{$gate_pass_details[$key]->gate_pass_no}}"  readonly="readonly"></td>
                                    <td><input type="text" class="form-control"  value="{{$gate_pass_details[$key]->designation}}" readonly="readonly"></td>
                                    <td><input type="text" class="form-control"  value="{{$gate_pass_details[$key]->age}}" readonly="readonly"></td>
                                    <td><input type="text" class="form-control"  value="{{$gate_pass_details[$key]->expirydate}}" readonly="readonly"></td>

                                </tr>
                            @endforeach
                        @endif    
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
                    and its mitigation plan have also been discussed.</i></b>
            </div>
        </div>
    </div>


<!-- -------------------------------------------------------------------------------------------------------------- -->
    <a name="anchor"></a>  @if($permit_division_data->PermitStatus != 'Requested') <div id="second" style="display:block"> @else <div id="second" style="display:none"> @endif
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Post Site Picture </label>
                <div class="col-sm-3">
                    <input type="file" class="form-control-file" disabled  name="post_site_pic" >    
                    <div class="img-thumbnail">
                        <img src="@if(isset($permit_division_data))
                            {{url('')}}/{{$permit_division_data->PermitSitePic}}
                            @endif"
                        id="imgthumbnail" class="img-fluid" width="100" height="100"/>
                    </div> 
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
                                            <input type="radio" disabled  {{ ($permit_division_data->PermitSafeWork == 'yes') ? 'checked' : '' }} >&nbsp; Yes
                                            
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" disabled  {{ ($permit_division_data->PermitSafeWork == 'no') ? 'checked' : '' }} >&nbsp; No  
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio"  disabled {{ ($permit_division_data->PermitSafeWork == 'na') ? 'checked' : '' }} >&nbsp; NA 
                                        </label>
                                    </td>                        
                                </div>
                            </tr>      
                            <tr>
                                <div class="col-sm-8">
                                    <td><strong>All persons are medically fit and have adequate quality of personal safety appliances. They will use it
                                    compulsorily as per requirement of the job. All person will follow safety norms and conditions of JAMIPOL.</strong></td>
                                </div>
                                <div class="col-sm-4">
                                    <td>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled {{ ($permit_division_data->PermitAll_person == 'yes') ? 'checked' : "" }} >&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled {{ ($permit_division_data->PermitAll_person == 'no') ? 'checked' : "" }} >&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled {{ ($permit_division_data->PermitAll_person == 'na') ? 'checked' : "" }} >&nbsp; NA 
                                    </label>
                                    </td>                        
                                </div>  
                            </tr>      
                            <tr>
                                <div class="col-sm-8">
                                    <td><strong>Worker working on height must have height pass from NTTF/competent authority.</strong></td>
                                </div>
                                <div class="col-sm-4">
                                    <td>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled {{ ($permit_division_data->permitWorkerWorking == 'yes') ? 'checked' : '' }} >&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->permitWorkerWorking == 'no') ? 'checked' : '' }} >&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->permitWorkerWorking == 'na') ? 'checked' : '' }} >&nbsp; NA 
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
                                        <input type="radio" disabled {{ ($permit_division_data->PermitAll_lifting_tools == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled {{ ($permit_division_data->PermitAll_lifting_tools == 'no') ? 'checked' : '' }}>&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->PermitAll_lifting_tools == 'na') ? 'checked' : '' }}>&nbsp; NA 
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
                                        <input type="radio" disabled {{ ($permit_division_data->permitAll_safety_requirement == 'yes') ? 'checked' : ''}} >&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->permitAll_safety_requirement == 'no') ? 'checked' : ''}} >&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->permitAll_safety_requirement == 'na') ? 'checked' : ''}} >&nbsp; NA 
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
                                        <input type="radio" disabled  {{ ($permit_division_data->PermitAll_person_are_trained == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->PermitAll_person_are_trained == 'no') ? 'checked' : '' }}>&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio" disabled {{ ($permit_division_data->PermitAll_person_are_trained == 'na') ? 'checked' : '' }}>&nbsp; NA 
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
                                        <input type="radio"  disabled  {{ ($permit_division_data->permitEnsure_the_appplicablle == 'yes') ? 'checked' : '' }}>&nbsp; Yes
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled  {{ ($permit_division_data->permitEnsure_the_appplicablle == 'no') ? 'checked' : '' }}>&nbsp; No  
                                    </label>
                                    <label class="form-check-label">
                                        <input type="radio"  disabled  {{ ($permit_division_data->permitEnsure_the_appplicablle == 'na') ? 'checked' : '' }}>&nbsp; NA 
                                    </label>
                                    </td>                        
                                </div>
                            </tr>      
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Power Clearance Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Shut Down Required? </label>
                <div class="col-sm-10">
                    <p class="form-control" readonly>
                    {{ ($permit_division_data->PermitPowerClearance == 'on')  ? "Yes" : "No" }} 
                    </p>     
                </div>
            </div>
        
         @if($permit_division_data->PermitPowerClearance == 'on')
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Select Voltage Level </label>
                <div class="col-sm-10">
                    <select class="form-control" id="vlevel" name="vlevel" disabled>
                        <option value="">Select Voltage </option>
                        <option value=".132KV" {{  ($permit_division_data->vlevel === ".132KV") ?  'selected' : '' }}>132KV</option>
                        <option value=".33KV"  {{  ($permit_division_data->vlevel === ".33KV") ? 'selected' : '' }}>33KV</option>
                        <option value=".11KV"  {{  ($permit_division_data->vlevel === ".11KV") ? 'selected' : '' }}>11KV</option>
                        <option value=".LT"    {{   ($permit_division_data->vlevel === ".LT") ? 'selected' : '' }} >LT</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Issuer Name</label>
                    <div class="col-sm-4">
                        <?php 
                            @$iss   = UserLogin::where('id',@$permit_division_data->issuer_power)->first();
                        ?> 
                        <select class="form-control" id="isspower" name="issuer_power" disabled>
                            @if(@$iss)
                            <option value="{{@$iss->id}}">{{@$iss->name}}</option>
                            @endif 
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" id="license_numberISS" name="electrical_license_issuer" disabled>
                            @if(@$permit_division_data->electrical_license_issuer)
                                <option value="{{@$permit_division_data->electrical_license_issuer}}">{{@$permit_division_data->electrical_license_issuer}} </option>
                            @endif 
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" id="validity_dateISS" name="validity_date_issuer" disabled>
                            @if(@$permit_division_data->electrical_license_issuer)
                                <option value="{{@$permit_division_data->validity_date_issuer}}">{{@$permit_division_data->validity_date_issuer}} </option>
                            @endif 
                        </select>
                    </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Receiver Name</label>
                    <div class="col-sm-4">
                        <?php 
                            @$rec   = UserLogin::where('id',@$permit_division_data->rec_power)->first();
                        ?> 
                        <select class="form-control" id="receiverpower" name="rec_power" disabled>
                            @if(@$rec)
                            <option value="{{@$rec->id}}">{{@$rec->name}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" id="license_numberREC" name="electrical_license_rec" disabled>
                            @if(@$permit_division_data->electrical_license_rec)
                                <option value="{{@$permit_division_data->electrical_license_rec}}">{{@$permit_division_data->electrical_license_rec}} </option>
                            @endif 
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" id="validity_dateREC" name="validity_date_rec"  disabled>
                            @if(@$permit_division_data->validity_date_rec)
                                <option value="{{@$permit_division_data->validity_date_rec}}">{{@$permit_division_data->validity_date_rec}} </option>
                            @endif 
                        </select>
                    </div>
            </div>


            <input type="hidden" class="form-control" name="power_cutting_id" value="{{$permit_division_data->pc_id}}" readonly>   
            <input type="hidden" class="form-control" name="" value="{{$id}}" readonly>   
            <div class="form-group row">
                <label for="job_description" class="col-sm-2 col-form-label">Power Cutting Sl. No.</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="power_clearance_number" value="{{$permit_division_data->PermitPower_clearance_number}}" readonly>   
                </div>
            </div>
            <div class="form-group row">
                <label for="job_description" class="col-sm-2 col-form-label">Power Cutting User Remarks</label>
                <div class="col-sm-10">
                    <textarea class="form-control" readonly>{{@$permit_division_data->power_cutting_remarks}}</textarea>   
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearence Details</label>
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
                        @if($power_clearances->count() > 0)
                            @foreach($power_clearances as $key => $value)
                                <tbody id="append_power_clearence">
                                    <tr class="remove_tr_power_cls" id="remove_tr_power_cls">
                                        <input type="hidden" class="form-control"  value="{{$power_clearances[$key]->id}}"  readonly="readonly">
                                        <td><input type="text" class="form-control" value="{{$power_clearances[$key]->equipment}}" readonly="readonly"></td>
                                        <td><input type="text" class="form-control" value="{{$power_clearances[$key]->positive_isolation_no}}"  readonly="readonly"></td>
                                        <td><input type="text" class="form-control" value="{{$power_clearances[$key]->location}}"  readonly="readonly"></td>
                                        <td><input type="text" class="form-control" value="{{$power_clearances[$key]->box_no}}" readonly="readonly"></td>
                                        <td><input type="text" class="form-control" value="{{$power_clearances[$key]->caution_no}}"  readonly="readonly"></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                        
                    </table>
                </div>
            </div>
            <div class="form-group row">
                <label for="job_description" class="col-sm-2 col-form-label"><b>Executing Personal Lock Number</b></label>
                <div class="col-sm-10">
                    <textarea class="form-control" readonly>{{@$permit_division_data->executing_lock}}</textarea>   
                </div>
            </div>
            <div class="form-group row">
                <label for="job_description" class="col-sm-2 col-form-label"><b>Working Personal Lock Number</b></label>
                <div class="col-sm-10">
                    <textarea class="form-control" readonly>{{@$permit_division_data->working_lock}}</textarea>   
                </div>
            </div>
                
            @endif
            <!-- End Power Clearance Details  for fill -->
                        
            <!-- End Power Clearance Details  for fill -->
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Any Other Type Isolation</label>
                <div class="col-sm-10">
                    <select class="form-control" name="other_Isolation" disabled>
                        <option value="">Select </option>
                        <option value="yes"  {{ ($permit_division_data->other_isolation == 'yes') ?  'selected' : '' }} >Yes</option>
                        <option value="no"  {{ ($permit_division_data->other_isolation == 'no')  ? 'selected'  : '' }} >No</option>
                    </select>
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
                                    <tbody >
                                        <tr>
                                            <td><input type="text" readonly class="form-control" name="positive_other[]" value="{{$otherisolation[$key]->positive_other}}"></td>
                                            <td><input type="text" readonly class="form-control" name="equipment_other[]" value="{{$otherisolation[$key]->equipment_other}}"></td>
                                            <td><input type="text" readonly class="form-control" name="location_other[]" value="{{$otherisolation[$key]->location_other}}"></td>
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
                        {{ ($permit_division_data->PermitConfinedSpace == 'on') ?  "Yes" : "No" }} </p>      
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space Details</label>
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
                                        <input type="hidden" class="form-control" name="c_id[]" value="{{$confined_spaces[$key]->id}}" >
                                        <td><input type="text" class="form-control" name="clearance_no[]" value="{{$confined_spaces[$key]->clearance_no}}"   readonly="readonly"></td>
                                        <td><input type="text" class="form-control" name="depth[]"  value="{{$confined_spaces[$key]->depth}}"  readonly="readonly"></td>
                                        <td><input type="text" class="form-control" name="confined_location[]" value="{{$confined_spaces[$key]->location}}"  readonly="readonly"></td>
                                    </tr>
                                </tbody>
                               @endforeach
                            @endif 
                        </table>
                    </div>
            </div>
            <!-- End Power Clearance Details  for fill -->

            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">High Risk Job? </label>
                <div class="col-sm-10">
                    <p class="form-control" readonly>
                        {{ ($permit_division_data->PermitHighRisk == 'on') ? "Yes" : "No" }}
                    </p>       
                </div>
            </div>

            @if($permit_division_data->PermitArea_clearence == "on" && $permit_division_data->PermitArea_clearenceId > 0)
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Name of Owner Agency</label>
                <div class="col-sm-10">
                    <select class="form-control" id="user_id" name="user_id" disabled>
                        <option value="0">Name of Owner Agency</option>
                        @if($users->count() >0)
                            @foreach($users as $user)
                            <option value="{{$user->id}}" {{ ($permit_division_data->PermitArea_clearenceId == $user->id) ? 'selected' : '' }}>{{$user->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Special Instruction</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="s_instruction" readonly="">{{ @$permit_division_data->s_instruction}}</textarea>
                </div>
            </div>
            @endif
            <input type="hidden" name="status" value="{{$permit_division_data->PermitStatus}}">
            

            <!--  ############################ Return From WORKING AGENCY(REQUESTER) $$$$$$$$$$$$$$$$$#$$$$$$$$ -->
            @if($permit_division_datas[0]->return_status != 'Pending' && $permit_division_datas[0]->return_status != 'Pending_area')
                {{--<div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Executing Agency</label>
                        <div class="col-sm-10">
                            <select class="form-control" required name="return_executing_id">
                                <option value="">Name of Executing Agency</option>
                                @if($newExecutingAgencys->count() >0)
                                    @foreach($newExecutingAgencys as $newExecutingAgency)
                                    <option value="{{$newExecutingAgency->id}}">{{$newExecutingAgency->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                </div> --}}
                <?php //echo "REQUESTER BLOCK"; ?>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Completed</label>
                    <div class="col-sm-10">
                        <div class="col-sm-2">
                            <label class="form-check-label">
                                <input type="radio" name="complete1" value="yes" @if($permit_division_datas[0]->complete == "yes") {{"checked"}} @endif>&nbsp; Yes&nbsp;&nbsp;
                                <input type="radio" name="complete1" value="no" @if($permit_division_datas[0]->complete == "no") {{"checked"}} @endif>&nbsp; No
                            </label>       
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Remark of Working Agency </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="requester_remark"></textarea>
                    </div>
                </div>
                @if($permit_division_datas[0]->PermitPowerClearance == "on")
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Power Getting Users</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="power_getting_userid" {{ ($permit_division_datas[0]->PermitPowerClearance == "on") ? 'required' : '' }}>
                                @if($powerGettingUsers->count() > 0)
                                    @foreach($powerGettingUsers as $powerGettingUser)
                                        <option value="{{$powerGettingUser->id}}" <?php if($powerGettingUser->id == $permit_division_datas[0]->ppg_userid) ?>>{{$powerGettingUser->name}}</option>
                                    @endforeach
                                @endif  
                            </select>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <div class="col-sm-8"><td>Men and Material Removed.</td></div>
                                    <div class="col-sm-4">
                                        <td>
                                            <label class="form-check-label"><input type="radio" name="ins1" value="yes" checked>&nbsp; Yes</label>
                                            <label class="form-check-label"><input type="radio" name="ins1" value="no">&nbsp;  No</label>
                                            <label class="form-check-label"><input type="radio" name="ins1" value="na">&nbsp;  NA </label>
                                        </td>                  
                                    </div>
                                </tr>
                                <tr><div class="col-sm-8">
                                    <td>All the Work Permit(in Form EHSMSM/WORKS/446/4007)issued to work 
                                    on the equipment mentioned below have been cancelled prior to filling up this form for getting power.</td>
                                    </div>
                                    <div class="col-sm-4"><td>
                                        <label class="form-check-label"><input type="radio" name="ins2" value="yes" checked>&nbsp; Yes</label>
                                        <label class="form-check-label"><input type="radio" name="ins2" value="no">&nbsp; No  </label>
                                        <label class="form-check-label"><input type="radio" name="ins2" value="na">&nbsp; NA </label></td>                        
                                    </div>
                                </tr>      
                                <tr>
                                    <div class="col-sm-8"><td>Temporary Earthing Removed from all the points.</td></div>
                                    <div class="col-sm-4"><td>
                                        <label class="form-check-label"><input type="radio"  name="ins3" value="yes" checked>&nbsp; Yes</label>
                                        <label class="form-check-label"><input type="radio"  name="ins3" value="no">&nbsp; No </label>
                                        <label class="form-check-label"><input type="radio"  name="ins3" value="na">&nbsp; NA </label></td>                        
                                    </div>
                                </tr>      
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($permit_division_datas[0]->PermitPowerClearance == "on")
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Personal Lock Removed?</label>
                    <div class="col-sm-10">
                        <div class="col-sm-5">
                            <input type="checkbox" class="form-control-check" name="" id="owner_cleck" {{ ($permit_division_datas[0]->PermitPowerClearance == "on") ? "required" : "" }}> (If Removed Check)     
                        </div>
                    </div>
                </div>
                @endif
            @endif
            <!--  ############################ Return From WORKING AGENCY(REQUESTER) END $$$$$$$$$$$$$$$$$#$$$$$$$$ -->



            <!--  ############################ Return From WORKING AGENCY(REQUESTER) END $$$$$$$$$$$$$$$$$#$$$$$$$$ -->
            @if($permit_division_datas[0]->return_status == 'Pending' || $permit_division_datas[0]->return_status == 'Pending_area')
                <?php //echo "BACK TO EXECUTING" ; ?>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Completed </label>
                    <div class="col-sm-10">
                        <div class="col-sm-2">
                            <label class="form-check-label"><input type="radio" disabled {{ (@$permit_division_datas[0]->complete == "yes") ? "checked" : "" }} >&nbsp; Yes</label>       
                            <label class="form-check-label"><input type="radio" disabled {{ (@$permit_division_datas[0]->complete == "no") ?  "checked" : "" }} >&nbsp; No</label>       
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Remark of Working Agency </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="requester_remark" readonly>{{@$permit_division_datas[0]->requester_remark}}</textarea>
                    </div>
                </div>    
                <div class="form-group row">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <div class="col-sm-8"><td>Men and Material Removed.</td></div>
                                    <div class="col-sm-4"><td>{{ucfirst($permit_division_datas[0]->pg_ins1)}}</td></div>
                                </tr>
                                <tr><div class="col-sm-8">
                                    <td>All the Work Permit(in Form EHSMSM/WORKS/446/4007)issued to work 
                                    on the equipment mentioned below have been cancelled prior to filling up this form for getting power.</td>
                                    </div>
                                    <div class="col-sm-4"><td>{{ucfirst($permit_division_datas[0]->pg_ins2)}}</td></div>
                                </tr>      
                                <tr>
                                    <div class="col-sm-8"><td>Temporary Earthing Removed from all the points.</td></div>
                                    <div class="col-sm-4"><td>{{ucfirst($permit_division_datas[0]->pg_ins3)}}</td></div>
                                </tr>      
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($permit_division_datas[0]->PermitPowerClearance == "on")
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Power Getting User Name</label>
                        <div class="col-sm-10">
                            <?php $pguser =  UserLogin::where('id',$permit_division_datas[0]->ppg_userid)->first(); ?>
                            <input type="text" class="form-control" readonly  value="{{@$pguser->name}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Power Getting Sl. NO.</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" readonly  value="{{@$permit_division_datas[0]->pg_number}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Power Getting Remarks</label>
                        <div class="col-sm-10">
                            <?php $pg =  PowerGetting::where('id',$permit_division_datas[0]->pg_id)->first(); ?>
                            <textarea class="form-control"  readonly>{{@$pg->power_cutting_comment}}</textarea>
                        </div>
                    </div>
                   <!--  <div class="form-group row">
                        <label for="form-control-label" class="col-sm-2 col-form-label">Personal Lock Removed </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" readonly value="Yes">
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <div class="col-sm-10"><td>(1) Executing Lock Number Remove or Not.</td></div>
                                        <div class="col-sm-2"><td><input value="{{ ($permit_division_datas[0]->exe_lock == 'on') ?  'Yes' : 'No' }}" readonly="" type="text" class="form-control"></td></div>
                                    </tr>
                                    <tr>
                                        <div class="col-sm-10"><td>(2) Working Lock Number Remove or Not.</td></div>
                                        <div class="col-sm-2"><td><input value="{{ ($permit_division_datas[0]->work_lock == 'on') ?  'Yes' : 'No' }}" readonly="" type="text" class="form-control"></td></div>
                                    </tr>
                                    <tr>
                                        <div class="col-sm-10"><td>(3) Equipment Lock Removed or Not.</td></div>
                                        <div class="col-sm-2"><td><input value="{{ ($permit_division_datas[0]->q1 == 'on') ? 'Yes' : 'No' }}" readonly="" type="text" class="form-control"></td></div>
                                    </tr>
                                    <tr><div class="col-sm-10"><td>(4) Removal of Equipment Lock & Tag.</td></div>
                                        <div class="col-sm-2"><td><input value="{{ ($permit_division_datas[0]->q2 == 'on') ? 'Yes' : 'No' }}" readonly="" type="text" class="form-control"></td></div>
                                    </tr>      
                                    <tr>
                                        <div class="col-sm-10"><td>(5) Power restored.</td></div>
                                        <div class="col-sm-2"><td><input value="{{ ($permit_division_datas[0]->q3 == 'on') ?  'Yes' : 'No' }}" readonly="" type="text" class="form-control"></td></div>
                                    </tr> 
                                    <tr>
                                        <div class="col-sm-10"><td>(6) Others.</td></div>
                                        <div class="col-sm-2"><td><input value="{{ ($permit_division_datas[0]->q4 == 'on') ?  'Yes' : 'No' }}" readonly="" type="text" class="form-control" id="OTHER"></td></div>
                                    </tr>
                                    <tr>
                                        <div class="col-sm-10"><td></td></div>
                                        <?php if($permit_division_datas[0]->q5_others != '') { ?> <td  style="display:block">
                                        <textarea class="form-control" id="specify_others" name="q5_others[]" readonly>{{$permit_division_datas[0]->q5_others}}</textarea>
                                        </td><?php } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif

            @if($permit_division_datas[0]->return_status == 'Pending')
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Owner Agency Required? </label>
                    <div class="col-sm-10">
                        <input type="checkbox" class="form-control-check" name="owner_cleck"  {{ ($permit_division_datas[0]->return_status == "Power_Getting")  ? 'disabled' : ''}} id="owner_cleck">         
                    </div>
                </div>
                {{--<div class="form-group row" style="display:none" id="ownerAgency">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Owner Agency Name: </label>
                        <div class="col-sm-10">
                            <select class="form-control" name="return_owner_id" id="emp_id">
                                @if($newExecutingAgencys->count() >0)
                                    @foreach($newExecutingAgencys as $newExecutingAgency)
                                    <option value="{{$newExecutingAgency->id}}">{{$newExecutingAgency->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                </div>--}}

                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Remark of Executing Agency</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="issuer_remark">{{ $permit_division_datas[0]->issuer_remark }}</textarea>
                    </div>
                </div>
            @endif

            <!-- FOR OWNER POSTING COMMENT -->
            @if($permit_division_datas[0]->return_status == 'Pending_area')
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Remark of Executing Agency</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" readonly>{{ $permit_division_datas[0]->issuer_remark }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="form-control-label" class="col-sm-2 col-form-label">Remark of Owner Agency</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="area_return_remark"></textarea>
                    </div>
                </div>
            @endif


        
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <input type="submit"  name="button" class="btn btn-primary" value="Submit">
                </div>
            </div>
    </div>
</form>
@endif
@endsection


<!-- From JS Started -->
@section('scripts')
<script>
$('#owner_cleck').click(function(){
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
</script>
@endsection