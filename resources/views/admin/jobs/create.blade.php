<?php
use App\Division;
use App\Department;
use App\Section;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.job.index')}}">List of Job Category</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Job Category</li>
@endsection

@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
@section('content')
<form action="{{route('admin.job.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Job Category Name<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control rec" name="job_title">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP No<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control rec" name="swp_number">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">SWP/SOP File<br> (File Size 2MB)<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-10">
            <input type="file" class="form-control-file rec" name="swp_file[]" multiple>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">High Risk Activity?</label>
        <div class="form-check col-sm-10">
            <input type="radio"  name="high_risk"  checked value="on">&nbsp; Yes
            <input type="radio"  name="high_risk" value="off">&nbsp; No  
            <!-- <input type="checkbox" class="form-check-label" name="high_risk"  id="high_risk">&nbsp;Yes -->
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Power Clearance Required?</label>
        <div class="form-check col-sm-10">
            <input type="radio"  name="power_clearance"  checked value="on">&nbsp; Yes
            <input type="radio"  name="power_clearance" value="off">&nbsp; No 
            <!-- <input type="checkbox" class="form-check-label" name="power_clearance"  id="power_clearance">&nbsp;Yes -->
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Confined Space Job?</label>
        <div class="form-check col-sm-10">
            <input type="radio"  name="confined_space"  checked value="on">&nbsp; Yes
            <input type="radio"  name="confined_space" value="off">&nbsp; No 
            <!-- <input type="checkbox" class="form-check-lable" name="confined_space"  id="confined_space">&nbsp;Yes -->
        </div>
    </div>
    <!-- <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Issuer 2?</label>
        <div class="form-check col-sm-10">
            <input type="checkbox" class="form-check-lable" name="issuer2"  id="">&nbsp;Yes
        </div>
    </div>   -->
    <!-- North -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">North<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="north-append">
                    <tr class="north" id="">
                        <td><input type="text" class="form-control rec" name="north_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="north_precaution[]"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-north" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove-north" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
    <!-- North end -->

    <!-- south -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">South<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="south-append">
                    <tr class="south" id="">
                        <td><input type="text" class="form-control rec" name="south_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="south_precaution[]"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-south" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove-south" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
    <!-- South  End -->

    <!-- East -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">East<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="east-append">
                    <tr class="east" id="">
                        <td><input type="text" class="form-control rec" name="east_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="east_precaution[]"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-east" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove-east" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
    <!-- East  End -->

    <!-- West -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">West<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="west-append">
                    <tr class="west" id="">
                        <td><input type="text" class="form-control rec" name="west_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="west_precaution[]"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-west" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove-west" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
    <!-- Wast  End -->

    <!-- Top -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Top<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="top-append">
                    <tr class="top" id="">
                        <td><input type="text" class="form-control rec" name="top_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="top_precaution[]"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-top" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove-top" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
    <!-- Top   End -->

    <!-- Buttom -->
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Buttom<span style="color:red;font-size: 22px;">*</span></label>
        <div class="col-sm-9">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Hazards</th>
                        <th>Precautions</th>
                    </tr>
                </thead>
                <tbody id="buttom-append">
                    <tr class="buttom" id="">
                        <td><input type="text" class="form-control rec" name="buttom_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="buttom_precaution[]"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1" style="">
            <button type="button" id="btn-add-buttom" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove-buttom" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
    <!-- Top Buttom -->

    <!-------------------- Code for Multiple Division,Department,Section Buttom ------------------------->
        <input type="hidden" id="increment" name="increment" value="0">
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Choose Multiple Details<span style="color:red;font-size: 22px;">*</span></label>
            <div class="col-sm-10">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>#</th>
                            <th>Division Name</th>
                            <th>Department Name</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody class="items_list" id="items_list">
                        <tr>
                            <td class="sl">1</td>
                            <td><select class="form-control rec checkValue" id="division_id" name="division_id[]"  onchange="getDepartment(this,this.value,0)">
                                    <option value="">Select Division</option>
                                    @if($divisions->count() > 0)
                                        @foreach($divisions as $division)
                                            <option value="{{@$division->id}}">{{@$division->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td><select class="form-control rec department_id0" id="department_id" name="department_id[]" >
                                <option value="">Select Department</option>

                                </select>
                            </td>
                            <td>
                                <a href="javascript:void(0)" title="ADD" onclick="addItems(this)" class="tab-index"><i class="fa fa-plus-circle remove fa-2x"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <!--------------------End Code for Multiple Division,Department,Section Buttom ------------------------->

    <!-- HIT Insert -->
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" onclick="return form_validate()" class="btn btn-primary" value="Add Job">
        </div>
    </div>
</form>
@endsection
@endif
@section('scripts')
    <script>
        //Append North Click
        $("#btn-add-north").on("click", function (e) {
            var count = $(".north").length + 1;
            $('#north-append').append(`<tr class="north">
                        <td><input type="text" class="form-control rec" name="north_hazarde[]"></td>
                        <td><input type="text" class="form-control rec" name="north_precaution[]"></td>
                        </tr>`);
        });
        //Remove North click
        $("#btn-remove-north").on("click", function (e) {
            if($('.north').length > 1){
                $(".north:last").remove();
            }
        });

        //Append South Click
        $("#btn-add-south").on("click", function (e) {
            var count = $(".south").length + 1;
            $('#south-append').append(`<tr class="south">
                        <td><input type="text" class="form-control rec" name="south_hazarde[]"   id="" value=""></td>
                        <td><input type="text" class="form-control rec" name="south_precaution[]"   id="" value=""></td>
                    </tr>`);
        });
        //Remove South Click
        $("#btn-remove-south").on("click", function (e) {
            if($('.south').length > 1){
                $(".south:last").remove();
            }
        });

        //Append East Click
        $("#btn-add-east").on("click", function (e) {
            var count = $(".east").length + 1;
            $('#east-append').append(`<tr class="east">
                        <td><input type="text" class="form-control rec" name="east_hazarde[]"   id="" value=""></td>
                        <td><input type="text" class="form-control rec" name="east_precaution[]"   id="" value=""></td>
                    </tr>`);
        });
        //Remove east Click
        $("#btn-remove-east").on("click", function (e) {
            if($('.east').length > 1){
                $(".east:last").remove();
            }
        });

        //Append West Click
        $("#btn-add-west").on("click", function (e) {
            var count = $(".west").length + 1;
            $('#west-append').append(` <tr class="west">
                        <td><input type="text" class="form-control rec" name="west_hazarde[]"    id="" value=""></td>
                        <td><input type="text" class="form-control rec" name="west_precaution[]"    id="" value=""></td>
                    </tr>`);
        });
        //Remove West Click
        $("#btn-remove-west").on("click", function (e) {
            if($('.west').length > 1){
                $(".west:last").remove();
            }
        });

        //Append Top Click
        $("#btn-add-top").on("click", function (e) {
            var count = $(".top").length + 1;
            $('#top-append').append(`<tr class="top">
                        <td><input type="text" class="form-control rec" name="top_hazarde[]"  id="" value=""></td>
                        <td><input type="text" class="form-control rec" name="top_precaution[]"  id="" value=""></td>
                    </tr>`);
        });
        //Remove Top Click
        $("#btn-remove-top").on("click", function (e) {
            if($('.top').length > 1){
                $(".top:last").remove();
            }
        });


        //Append Buttom Click
        $("#btn-add-buttom").on("click", function (e) {
            var count = $(".buttom").length + 1;
            $('#buttom-append').append(`<tr class="buttom">
                        <td><input type="text" class="form-control rec" name="buttom_hazarde[]"   id="" value=""></td>
                        <td><input type="text" class="form-control rec" name="buttom_precaution[]"   id="" value=""></td>
                    </tr>`);
        });
        //Remove Buttom Click
        $("#btn-remove-buttom").on("click", function (e) {
            if($('.buttom').length > 1){
                $(".buttom:last").remove();
            }
        });

    function addItems(th)
    {  
        var incrementjquery = $("#increment").val();
        incrementjquery++;
        $("#increment").val(incrementjquery);
        var datas ='';
        var datas='<tr><td class="sl">1</td>';
                datas +='<td><select class="form-control rec checkValue" id="division_id" name="division_id[]" onchange="getDepartment(this,this.value,'+incrementjquery+')"><option value="">Select Division</option>';
                            @if($divisions->count() > 0)
                                 @foreach($divisions as $division)
                            datas +='<option value="{{@$division->id}}">{{@$division->name}}</option>';
                                 @endforeach
                            @endif
                    datas +='</select></td>';
                datas +='<td><select class="form-control rec department_id'+incrementjquery+'" id="" name="department_id[]"><option value="">Select Department</option>';
                    datas +='</select></td>';
                    datas +='<td><a href="javascript:void(0)" title="ADD" onclick="addItems(this)"  class="tab-index"><i class="fa fa-plus-circle fa-2x"></i></a></td></tr>';
    
        $(th).find('> i').addClass('fa-plus-circle');
        $(th).find('> i').removeClass('remove');
        $(th).attr('onclick','$(this).closest("tr").remove();var incrementjquery = $("#increment").val();incrementjquery--;$("#increment").val(incrementjquery);setSl();');        
        $(th).attr('title','DELETE'); 
        $("#items_list").append(datas);                                  
        setSl();
    }
    function setSl()
    {
        var i=0;
        $(".sl").each(function(e){
            i++;
            $(this).html(i);
        })
    }

    function getDepartment(th,divisionID,unique) {
        if(divisionID!="")
        {
            var c=0;
            $(".checkValue").each(function(e){
                if($(this).val()==divisionID)
                {
                    c++;
                }
            })
            if(c==1)
            {
                $(".department_id"+unique).html('<option value="">--Select--</option>');
                if(divisionID)
                {
                    $.ajaxSetup({
                        headers:{
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type:'GET',
                        url:"{{route('admin.job.department')}}/" + divisionID,
                        contentType:'application/json',
                        dataType:"json",
                        success:function(data){
                            console.log(data);
                            for(var i=0;i<data.length;i++){
                                $(th).closest('tr').find('.department_id'+unique).append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                            }
                        }
                    });
                }else{
                    $('.department_id'+unique).html('<option value="">Select Department</option>');
                }
            }
            else{
                alert("This Division Already Added.");
                $(th).val("");
                $(th).closest('tr').find('.department_id'+unique).val("");
            }
        }
    }

    
</script>

@endsection