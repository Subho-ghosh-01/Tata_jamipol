<?php
use App\Division;
use App\Department;
use App\Section;
use App\UserLogin;
use App\AreaClearence;

?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.area_cls.index')}}">Area Clearence</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Clearence</li>
@endsection
@section('content')
<form action="{{ route('admin.area_cls.update',$GetAreaClearence[0]->id) }}" method="post"  autocomplete="off">
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
    <?php 
        $area_cls   = AreaClearence::where('id',$GetAreaClearence[0]->id)->first();
        $get_user   = UserLogin::where('id',@$area_cls->user_id)->first();
    ?>

    @if(Session::get('user_sub_typeSession') == 3)
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Divisions</label>
        <div class="col-sm-10">
            <select class="form-control" id="division_id" name="division_id">
                <option value="null">Select Division</option>
                @if($divisions->count() > 0)
                    @foreach($divisions as $division)
                        <option value="{{$division->id}}" @if(@$GetAreaClearence[0]->division_id == $division->id) {{'Selected'}}@endif>{{$division->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
        <div class="col-sm-10">
            <select class="form-control" id="department_id" name="department_id">
                <option value="null">Select Department</option>
                @if($departments->count() > 0)
                    @foreach($departments as $department)
                      <option value="{{$department->id}}"  @if($GetAreaClearence[0]->department_id == $department->id) {{'Selected'}} @endif>{{$department->department_name}}</option>
                    @endforeach
                @endif 
            </select> 
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Sections</label>
        <div class="col-sm-10">
            <select class="form-control" id="section_id" name="section_id">
                <option value="null">Select Section</option>
                @if($sections->count() > 0)
                    @foreach($sections as $section)
                      <option value="{{$section->id}}"  @if($GetAreaClearence[0]->section_id == $section->id) {{'Selected'}} @endif>{{$section->name}}</option>
                    @endforeach
                @endif 
                
            </select>
        </div>
    </div>
    @else
    <?php 
        $division   = Division::where('id',Session::get('user_DivID_Session'))->get();   
        $department = Department::where('id',Session::get('user_DeptID_Session'))->get();   
        $section    = Section::where('department_id',Session::get('user_DeptID_Session'))->get(); 
        // echo $get_user;
    ?>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="division_id"> 
                <option value="{{@$division[0]->id}}">{{$division[0]->name}}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Department</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="department_id">
                <option value="{{@$department[0]->id}}">{{@$department[0]->department_name}}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Section</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="section_id">
                @if($section->count() > 0)
                    @foreach($section as $sec)
                      <option value="{{@$sec->id}}">{{$sec->name}}</option>
                    @endforeach
                @endif   
            </select>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Job Category</label>
        <div class="col-sm-10">
            <select class="form-control" id="" name="job_id">
                @if($jobs->count() > 0)
                    @foreach($jobs as $job)
                      <option value="{{@$job->id}}" @if($GetAreaClearence[0]->job_id == $job->id) {{'Selected'}} @endif>{{$job->job_title}}</option>
                    @endforeach
                @endif  
                             
            </select>
        </div>
    </div> 
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Area Clearance Auth Person</label>
        <div class="col-sm-8">
            <input type="text" name="area_clearence" class="form-control" id="area_cls" @if(@$get_user->id == 0) {{'required'}} @endif>
        </div>
        <div class="col-sm-2">
            <a class="btn btn-info btn-xm" id="getvalidity">Check</a>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Employee Details</label>
        <div class="col-sm-10">
            <select class="form-control" id="emp_id" name="user_id" required>
                <option value="{{@$get_user->id}}">{{@$get_user->name}}</option>                    
            </select>
        </div>
    </div> 
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Edit Area Clearence">
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
// get the Department data
    $('#division_id').on('change',function(){
            var division_ID = $(this).val();
                // alert(division_ID);
            $("#department_id").html('<option value="">--Select--</option>');
            $("#section_id").html('<option value="">--Select--</option>');
            if(division_ID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.job.department')}}/" + division_ID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        // $("#sectionID").html('<option value="0">--Select--</option>');
                        for(var i=0;i<data.length;i++){
                            $("#department_id").append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                        }
                    }
                });
            }else{
                $('#department_id').html('<option value="null">Select Department</option>');
            }
    });

    // get the Section data
    $('#department_id').on('change',function(){
            var department_ID = $(this).val();
                // alert(department_ID);
            $("#section_id").html('<option value="">--Select--</option>');
            if(department_ID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"{{route('admin.job.section')}}/" + department_ID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        // $("#sectionID").html('<option value="0">--Select--</option>');
                        for(var i=0;i<data.length;i++){
                            $("#section_id").append('<option value="'+data[i].id+'" >'+data[i].name+'</option>');
                        }
                    }
                });
            }else{
                $('#section_id').html('<option value="null">Select Department</option>');
            }
    });

    $("#getvalidity").on("click", function (e)
    {
        var area_clear = $("#area_cls").val();
        $.ajax({
            type:'GET',
            url:"{{route('admin.getvalid_emp')}}/" + area_clear,
            contentType:'application/json',
            dataType:"json",
            success:function(data){
                console.log(data);
                $("#emp_id").html("");
                for(var i=0;i<data.length;i++){
                    $("#emp_id").append('<option value="'+data[i].id+'" >'+data[i].name+'</option>');
                }
                
            }
        });
    });
</script>
@endsection