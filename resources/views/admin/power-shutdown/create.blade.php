<?php use App\Division;
use App\Department;
use App\Section; ?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.power_shutdown.index')}}">List of Power Shotdown</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Power Shutdown</li>
@endsection
@section('content')
<form action="{{route('admin.power_shutdown.store')}}" method="post"  autocomplete="off">
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
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">User Type </label>
        <div class="col-sm-10">
            <select class="form-control" id="u_type" name="user_type">
                <option value="1">Employee</option>
                <option value="2">Vendor</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Employee P.No./Vendor Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code" required  id="" autocomplete="off" >
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id=""  required value="" >
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" required id="" >
        </div>
    </div>

    <div id="Employee">
        @if(Session::get('user_sub_typeSession') == 3)
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" id="division_id" name="division_id">
                    <option value="null">Select The Division</option>
                    @if($divisions->count() > 0)
                        @foreach($divisions as $division)
                            <option value="{{$division->id}}">{{$division->name}}</option>
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
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Section</label>
            <div class="col-sm-10">
                <select class="form-control" id="section_id" name="section_id">
                    <option value="null">Select Section</option>
                    
                </select>
            </div>
        </div>
        @else
        <?php 
            $division   = Division::where('id',Session::get('user_DivID_Session'))->get();   
            $department = Department::where('id',Session::get('user_DeptID_Session'))->get();   
            $section    = Section::where('department_id',Session::get('user_DeptID_Session'))->get();   
        ?>   
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" id="" name="division_id">
                    @if($division->count() > 0)
                        @foreach($division as $div)
                            <option value="{{$div->id}}">{{$div->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>  
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
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
                        <option value="{{$sec->id}}">{{$sec->name}}</option>
                        @endforeach
                    @endif   
                </select>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory License number</label>
            <div class="col-sm-10" id="">
                <input type="text" class="form-control" name="electrical_license_emp" id="">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">License Number Validity date</label>
            <div class="col-sm-10" id="">
                <input type="date" class="form-control" name="license_validity_emp" id="edate">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Competent for Voltage Level</label>
            <div class="col-sm-10"> 
                <table style="width: 150px;">
                    <tr><td><span>132KV</span></td>
                        <td><input type="radio" name="v133kv_emp"  checked value="yes">&nbsp; Yes
                        <input type="radio" name="v133kv_emp" value="no">&nbsp; No</td>
                    <tr>
                    <tr><td><span>33KV</span></td>
                        <td><input type="radio" name="v33kv_emp"  checked value="yes">&nbsp; Yes
                        <input type="radio" name="v33kv_emp" value="no">&nbsp; No</td>
                    <tr><td><span>11KV</span></td>
                        <td><input type="radio" name="v11kv_emp"  checked value="yes">&nbsp; Yes
                        <input type="radio"  name="v11kv_emp" value="no">&nbsp; No</td>
                    <tr><td><span>LT</span></td>
                        <td><input type="radio"  name="vlt_emp"  checked value="yes">&nbsp; Yes
                        <input type="radio" name="vlt_emp" value="no">&nbsp; No</td>
                    <tr>
                </table>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Issue Power Clearance</label>
            <div class="form-check col-sm-10">
                    <input type="radio" class="" name="issue_power"  checked value="yes">&nbsp; Yes
                    <input type="radio" class=""  name="issue_power" value="no">&nbsp; No
                <!-- <input type="checkbox" class="form-check-label" name="issue_power"  id="">&nbsp;Yes -->
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Receive Power Clearance</label>
            <div class="form-check col-sm-10">
                <input type="radio" class="" name="rec_power" checked value="yes">&nbsp; Yes
                <input type="radio" class=""  name="rec_power" value="no">&nbsp; No  
                <!-- <input type="checkbox" class="form-check-lable" name="rec_power"  id="">&nbsp;Yes -->
            </div>
        </div>
    </div>

    <!-- Supervisor details -->
    <div class="form-group row" style="display:none;" id="sup">
        <!-- <label for="form-control-label" class="col-sm-2 col-form-label">Enter Details</label> -->
        <div class="col-sm-11">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Supervisor Name</th>
                        <th>Electrical Supervisory License number</th>
                        <th>License Number Validity date</th>
                        <th>Competent for Voltage level</th>
                        <th>Issue Power Clearance</th>
                        <th>Receive Power Clearance</th>
                    </tr>
                </thead>
                <tbody id="dataview">
                    <tr class="appendrow" id="">
                        <td><input type="text" class="form-control" name="supervisor_ven[]"></td>
                        <td><input type="text" class="form-control" name="electrical_license_ven[]"></td>
                        <td><input type="date" class="form-control" name="license_validity_ven[]"></td>
                        <td><table style="width: 180px;">
                                <tr><td><span>132KV</span></td>
                                    <td><input type="radio" name="v132kv_ven[0]" checked value="yes">&nbsp; Yes
                                    <input type="radio" name="v132kv_ven[0]" value="no">&nbsp; No
                                    </td>
                                <tr>
                                <tr><td><span>33KV</span></td>
                                    <td><input type="radio" name="v33kv_ven[0]" checked value="yes">&nbsp; Yes
                                    <input type="radio" name="v33kv_ven[0]" value="no">&nbsp; No
                                    </td>
                                <tr><td><span>11KV</span></td>
                                    <td><input type="radio" name="v11kv_ven[0]" checked value="yes">&nbsp; Yes
                                    <input type="radio" name="v11kv_ven[0]" value="no">&nbsp; No
                                    </td>
                                <tr><td><span>LT</span></td>
                                    <td><input type="radio" name="vlt_ven[0]" checked value="yes">&nbsp; Yes
                                    <input type="radio" name="vlt_ven[0]" value="no">&nbsp; No
                                    </td>
                                <tr>
                            </table>
                        </td>
                        <td>
                            <label class="form-check-label">
                                <input type="radio" class="" name="issue_power_ven[0]"  checked value="yes">&nbsp; Yes
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class=""  name="issue_power_ven[0]" value="no">&nbsp; No  
                            </label>
                        </td>
                        <td>
                            <label class="form-check-label">
                                <input type="radio" class="" name="rec_power_ven[0]" checked value="yes">&nbsp; Yes
                            </label>
                            <label class="form-check-label">
                                <input type="radio" class=""  name="rec_power_ven[0]" value="no">&nbsp; No  
                            </label>
                        </td>
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
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
    $(function() {
        $('#Employee').show(); 
        $('#u_type').change(function(){
            if($('#u_type').val() == 1) {
                $('#Employee').show(); 
                $('#sup').hide(); 
            } else {
                $('#Employee').hide(); 
                $('#sup').show(); 
            } 
        });
    });

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
                url:"{{route('admin.user.department')}}/" + division_ID,
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
                url:"{{route('admin.user.section')}}/" + department_ID,
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


    //Append code
    $("#btn-add").on("click", function (e) {
    var count = $(".appendrow").length + 1;
    count  = count - 1;
    // i++;
    // console.log(count);
    $('#dataview').append(`<tr class="appendrow" id="">
            <td><input type="text" class="form-control" name="supervisor_ven[]"></td>
            <td><input type="text" class="form-control" name="electrical_license_ven[]"></td>
            <td><input type="date" class="form-control" name="license_validity_ven[]"></td>
            <td><table style="width: 180px;">
                    <tr><td><span>132KV</span></td>
                        <td><input type="radio" name="v132kv_ven[`+count+`]" checked value="yes">&nbsp; Yes
                        <input type="radio" name="v132kv_ven[`+count+`]" value="no">&nbsp; No
                        </td>
                    <tr>
                    <tr><td><span>33KV</span></td>
                        <td><input type="radio" name="v33kv_ven[`+count+`]" checked value="yes">&nbsp; Yes
                        <input type="radio" name="v33kv_ven[`+count+`]" value="no">&nbsp; No
                        </td>
                    <tr><td><span>11KV</span></td>
                        <td><input type="radio" name="v11kv_ven[`+count+`]" checked value="yes">&nbsp; Yes
                        <input type="radio" name="v11kv_ven[`+count+`]" value="no">&nbsp; No
                        </td>
                    <tr><td><span>LT</span></td>
                        <td><input type="radio" name="vlt_ven[`+count+`]" checked value="yes">&nbsp; Yes
                        <input type="radio" name="vlt_ven[`+count+`]" value="no">&nbsp; No
                        </td>
                    <tr>
                </table>
            </td>
            <td>
                <label class="form-check-label">
                    <input type="radio" class="" name="issue_power_ven[`+count+`]"  checked value="yes">&nbsp; Yes
                </label>
                <label class="form-check-label">
                    <input type="radio" class=""  name="issue_power_ven[`+count+`]" value="no">&nbsp; No  
                </label>
            </td>
            <td>
                <label class="form-check-label">
                    <input type="radio" class="" name="rec_power_ven[`+count+`]" checked value="yes">&nbsp; Yes
                </label>
                <label class="form-check-label">
                    <input type="radio" class=""  name="rec_power_ven[`+count+`]" value="no">&nbsp; No  
                </label>
            </td>
        </tr>`);
    });

    //Remove 
    $("#btn-remove").on("click", function (e) {
        if($('.appendrow').length > 1){
            $(".appendrow:last").remove();
        }
    });
</script>

@endsection