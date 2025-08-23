<?php
use App\Division;
use App\Department;
use App\UserLogin;

?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.work-order.index')}}">List of WorkOrder</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Work Order</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
@section('content')
<form action="{{route('admin.work-order.update',$id)}}" method="post" enctype="multipart/form-data">
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
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Work Order</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="order_code" value="{{$workOrder->order_code}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code" value="{{$workOrder->vendor_code}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="order_validity" value="{{$workOrder->order_validity}}">
        </div>
    </div>
   <?php $div_id = DB::table('divisions')->where('id',$workOrder->division_id)->first();  ?>
     <?php $deprt_id = DB::table('departments')->where('division_id',$workOrder->division_id)->first();  ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" id="division_id" name="division_id">
                    <option value="">Select Division</option>
                    @if($divisions->count() > 0 )
                        @foreach($divisions as $division)
                            <option value="{{$division->id}}" @if(@$div_id->id == @$division->id) {{ 'selected' }} @endif> {{$division->name}} </option>
                        @endforeach
                    @endif 
                </select>
            </div>  
        </div> 
		<?php $deprt_id = DB::table('departments')->where('id',$workOrder->department_id)->first();  ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
            <div class="col-sm-10">
                <select class="form-control" id="department_id" name="department_id">
                    <option value="">Select Department</option>
                    @if($departments->count() > 0 )
                        @foreach($departments as $department)
                            <option value="{{@$department->id}}" @if(@$deprt_id->id == $department->id) {{'selected'}} @endif> {{@$department->department_name}} </option>
                        @endforeach
                    @endif 
                </select>
            </div>
        </div>
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary"  onclick="return form_validate()" value="Update">
        </div>
    </div>
</form>
@endsection
@endif
@section('scripts')
<script>
    
    // get the Department data
    $('#division_id').on('change',function(){
        var division_ID = $(this).val();
        $("#department_id").html('<option value="">--Select--</option>');
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
</script>
<script>
$('#departmentID').on('change',function(){
        var department_ID = $(this).val();
         //alert(department_ID);
        $("#approverID").html('<option value="null">--Select--</option>');
        
        if(divisionID)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'GET',
                url:"{{route('admin.approverGet')}}/" + department_ID,
                contentType:'application/json',
                dataType:"json",
                success:function(data){
                    console.log(data);
                    for(var i=0;i<data.length;i++){
                        $("#approverID").append('<option value="'+data[i].id+'" >'+data[i].name+'</option>');
                    }
                }
            });
           
        }else{
            $('#approverID').html('<option value="null">Select Department first</option>');
        }
    });
    </script>
@endsection