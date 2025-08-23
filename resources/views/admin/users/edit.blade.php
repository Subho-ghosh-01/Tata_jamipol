<?php
use App\Division;
use App\Department;
use App\UserLogin;

?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.user.index')}}">List of Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
@endsection
@if(Session::get('user_sub_typeSession') == 2)
    return redirect('admin/dashboard');
@else
<!-- start content Section-->
@section('content')
<form action="{{route('admin.user.update',$id)}}" method="post"  autocomplete="off">
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Name<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" value="{{$user->name}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Employee P.No./Vendor User Name<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code"  value="{{$user->vendor_code}}" >
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Email<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="{{$user->email}}">
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Wps<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control"  name="wps_user">
                <option value="Yes" @if($user->wps == "Yes" ) {{  'selected' }} @endif>Yes</option>
                <option value="No" @if($user->wps == "No" ) {{  'selected' }} @endif>No</option>
             </select>
        </div>  
    </div>


    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">User Type<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control"  name="user_type">
            @if($user->user_type == 1)<option value="1" @if($user->user_type == 1 ) {{ 'selected' }} @endif >Employee</option> @endif
            @if($user->user_type == 2)<option value="2" @if($user->user_type == 2 ) {{ 'selected' }} @endif >Vendor</option> @endif
            </select>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">User Sub-Type</label>
        <div class="col-sm-10">
            <select class="form-control"  name="user_sub_type">
                <option value="1" @if($user->user_sub_type == 1 ) {{  'selected' }} @endif>Admin</option>
                <option value="2" @if($user->user_sub_type == 2 ) {{  'selected' }} @endif>User</option>
              
                <?php if(Session::get('user_sub_typeSession') == 3) { ?>
                    <option value="3" @if($user->user_sub_type == 3 ) {{  'selected' }} @endif>Super Admin</option>
                <?php 
                } 
                ?>
            </select>
        </div>  
    </div>

<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">VMS</label>
        <div class="col-sm-10">
            <select class="form-control"  name="vms" id="VMS">
                <option value="Yes" @if($user->vms == "Yes" ) {{  'selected' }} @endif>Yes</option>
                <option value="No" @if($user->vms == "No" ) {{  'selected' }} @endif>No</option>
            </select>
        </div>  
</div>
<link href="{{URL::to('public/css/sweetalert.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('public/js/sweetalert.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('node_modules/jquery-datetimepicker/jquery.datetimepicker.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('public/js/app.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('public/js/dataTables.buttons.min.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('public/js/jszip.min.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('public/js/buttons.html5.min.js')}}"> </script>
    <script type="text/javascript" src="{{URL::to('public/js/all.js')}}"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    $("#VMS").on('change',function (){
      var modeval =$(this).val();
      if(modeval == 'Yes'){
            $('#VMS_role').show();
            $('#vms_admin').show(); 
             }
      else {
             $('#VMS_role').hide();
             $('#vms_admin').hide();    
           }
   }); 
</script>


<div class="form-group row" id="VMS_role" >
        <label for="form-control-label" class="col-sm-2 col-form-label">VMS Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="vms_role">
            <option value="">--Select--</option>
                 <option value="Approver" @if($user->vms_roll == "Approver" ) {{  'selected' }} @endif>Approver</option>
                <option value="Security" @if($user->vms_roll == "Security" ) {{  'selected' }} @endif>Security</option>
                <option value="Requester" @if($user->vms_roll == "Requester" ) {{  'selected' }} @endif>Requester</option>
            </select>
        </div>
    </div>
    <div class="form-group row" id="vms_admin" >
        <label for="form-control-label" class="col-sm-2 col-form-label">VMS Admin<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="vms_admin">
            <option value="">--Select--</option>
            <option value="Yes" @if($user->vms_admin == "Yes" ) {{  'selected' }} @endif>Yes</option>
             <option value="No" @if($user->vms_admin == "No" ) {{  'selected' }} @endif>No</option>
           
            </select>
        </div>
    </div>
	
	 <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Safety<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec" name="safety" id="safety_id">
            <option value="">--Select--</option>
             <option value="No" @if($user->safety == "No" ) {{  'selected' }} @endif> No</option>
             <option value="Yes" @if($user->safety == "Yes" ) {{  'selected' }} @endif> Yes</option>
           
            </select>
        </div>
    </div>
<div class="form-group row" id="safety_role_id" style="display:none;">
        <label for="form-control-label" class="col-sm-2 col-form-label">Safety Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="safety_role" >
            <option value="">--Select--</option>
                <option value="data_entry" @if($user->safety_role == "data_entry" ) {{  'selected' }} @endif> Data Entry</option>
                <option value="data_view" @if($user->safety_role == "data_view" ) {{  'selected' }} @endif> Data View</option>
            
            </select>
        </div>
    </div>

    <script type="text/javascript">
    $("#safety_id").on('change',function (){
      var modeval =$(this).val();
      if(modeval == 'Yes'){
            $('#safety_role_id').show();
            $('#safety_admin').show(); 
             }
      else {
             $('#safety_role_id').hide();
             $('#safety_admin').hide();    
           }
   }); 
</script>
<div class="form-group row" id="safety_admin" style="display:none;">
        <label for="form-control-label" class="col-sm-2 col-form-label">Safety Admin<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="safety_admin">
            <option value="">--Select--</option>
            <option value="No" @if($user->safety_admin == "No" ) {{  'selected' }} @endif> No</option>
             <option value="Yes" @if($user->safety_admin == "Yes" ) {{  'selected' }} @endif> Yes</option>
            
            
            </select>
        </div>
    </div>
	<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Labour Capacity</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="labour_capacity" value="{{$user->lobour_capacity}}"
            

        </div>  
    </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor ABB</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_abb" value="{{$user->vendor_abb}}"
            

        </div>  
    </div>
    </div>
    
	@if($user->user_type == 1)
    
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS</label>
        <div class="col-sm-10">
            <select class="form-control rec" name="clms" id="clms">
                <option value="Yes" @if($user->clm == "Yes" ) {{  'selected' }} @endif>Yes</option>
                <option value="No" @if($user->clm == "No" ) {{  'selected' }} @endif>No</option>
            </select>

        </div>  
    </div>
	
    <div class="form-group row" id="CLMS_role" >
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="clms_role">

            <option value="">--Select--</option>
             <option value="Shift_incharge" @if($user->clm_role == "Shift_incharge" ) {{  'selected' }} @endif>Shift Incharge</option>
           <option value="hr_dept" @if($user->clm_role == "hr_dept" ) {{  'selected' }} @endif>HR Dept</option>
            <option value="Safety_dept" @if($user->clm_role == "Safety_dept" ) {{  'selected' }} @endif> Safety Dept</option>
           <option value="plant_head" @if($user->clm_role == "plant_head" ) {{  'selected' }} @endif> Plant Head</option>
           <option value="security" @if($user->clm_role == "security" ) {{  'selected' }} @endif> Security</option>
        <option value="Executing_agency" @if($user->clm_role == "Executing_agency" ) {{  'selected' }} @endif> Executing Agency</option>
        <option value="Account_dept" @if($user->clm_role == "Account_dept") {{  'selected' }} @endif> Account Dept</option>
         </select>
        </div>
    </div>
	
<script type="text/javascript">
    $("#clms").on('change',function (){
      var modeval =$(this).val();
      if(modeval == 'Yes'){
            $('#CLMS_role').show();
             $('#clms_admin').show();
             }
      else {
             $('#CLMS_role').hide();
              $('#clms_admin').hide();  
           }
   }); 
   
   $( document ).ready(function() {
	   var modeval =$('#clms').val();
    if(modeval == 'Yes'){
            $('#CLMS_role').show();
             $('#clms_admin').show();
             }
      else {
             $('#CLMS_role').hide();
              $('#clms_admin').hide();  
           }
});
</script>
<div class="form-group row" id="clms_admin" >
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Admin<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="clms_admin">
            <option value="">--Select--</option>
            <option value="Yes" @if($user->clms_admin == "Yes" ) {{  'selected' }} @endif> Yes</option>
            <option value="No" @if($user->clms_admin == "No" ) {{  'selected' }} @endif> No</option>
             </select>
        </div>
    </div>

  
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Power Cutting<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec"  name="power_cutting">
                <option value="">--Select--</option>
                <option  @if($user->power_cutting == "Yes") {{  'selected' }} @endif value="Yes">Yes</option>
                <option  @if($user->power_cutting == "No" ) {{  'selected' }} @endif value="No">No</option>
            </select>
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Power Getting<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec"  name="power_getting">
                <option value="">--Select--</option>
                <option  @if($user->power_getting == "Yes" ) {{  'selected' }} @endif value="Yes">Yes</option>
                <option  @if($user->power_getting == "No" ) {{  'selected' }} @endif value="No">No</option>
            </select>
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Confined Space<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec"  name="confined_space">
                <option value="">--Select--</option>
                <option  @if($user->confined_space == "Yes" ) {{  'selected' }} @endif value="Yes">Yes</option>
                <option  @if($user->confined_space == "No" ) {{  'selected' }} @endif value="No">No</option>
            </select>
        </div>  
    </div>
    @endif
@if($user->user_type == 1)
    @if(Session::get('user_sub_typeSession') == 3)
    <?php $div_id = DB::table('divisions')->where('id',$user->division_id)->first();  ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" id="division_id" name="division_id">
                    <option value="null">Select Division</option>
                    @if($divisions->count() > 0 )
                        @foreach($divisions as $division)
                            <option value="{{$division->id}}" @if(@$div_id->id == @$division->id) {{ 'selected' }} @endif> {{$division->name}} </option>
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
                    @if($departments->count() > 0 )
                        @foreach($departments as $department)
                            <option value="{{@$department->id}}" @if($user->department_id == $department->id) {{'selected'}} @endif> {{@$department->department_name}} </option>
                        @endforeach
                    @endif 
                </select>
            </div>
        </div>
    @else
    <?php   $division = Division::where('id',Session::get('user_DivID_Session'))->get();   
            $department = Department::where('division_id',$division[0]->id)->get();
    ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" name="division_id">
                    <option value="{{$division[0]->id}}">{{$division[0]->name}}</option>            
                </select>
            </div>  
        </div> 
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
            <div class="col-sm-10">
                <select class="form-control"  name="department_id">
                    @foreach($department as $depar)
                        <option @if(@$user->department_id == @$depar->id) {{'selected'}} @endif  value="{{@$depar->id}}">{{@$depar->department_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Active <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec" name="active">
                <option value="">Select</option>
                <option value="Yes" @if($user->active == "Yes" ) {{  'selected' }} @endif>Yes</option>
                <option value="No"  @if($user->active == "No" ) {{  'selected' }} @endif>No</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory?<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec" name="ElectricalSup" onChange="ElectricalSupervisoryEmployee(this.value)">
                <option value="">Select</option>
                <option value="yes" <?php if(@$powershutdown[0]->electrical_license) echo "selected"; ?> >Yes</option>
                <option value="no">No</option>
            </select>
        </div>
    </div>
    <div 
    <?php if(@$powershutdown[0]->electrical_license) { ?> style="display: block"; <?php } else { ?> style="display: none"; <?php }  ?> id="Electrical_Yes">
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Electrical Supervisory License number<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="electrical_license_emp" value="{{@$powershutdown[0]->electrical_license}}">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">License Number Validity date<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control" name="license_validity_emp" id="edate" value="{{@$powershutdown[0]->validity_date}}">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Competent for Voltage Level<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10"> 
                <table style="width: 150px;">
                    <tr><td><span>132KV</span></td>
                        <td><input type="radio" name="v133kv_emp" <?php if(@$powershutdown[0]->KV132 == 'yes') {  echo 'checked'; } ?>  value="yes">&nbsp; Yes
                            <input type="radio" name="v133kv_emp" <?php if(@$powershutdown[0]->KV132 == 'no')  {  echo 'checked'; } ?>  value="no">&nbsp; No
                        </td>
                    <tr>
                    <tr><td><span>33KV</span></td>
                        <td><input type="radio" name="v33kv_emp" <?php if(@$powershutdown[0]->KV33 == 'yes') {  echo 'checked'; } ?>  value="yes">&nbsp; Yes
                            <input type="radio" name="v33kv_emp" <?php if(@$powershutdown[0]->KV33 == 'no') {  echo 'checked'; } ?>  value="no">&nbsp; No
                        </td>
                    </tr>
                    <tr><td><span>11KV</span></td>
                        <td><input type="radio" name="v11kv_emp"  <?php if(@$powershutdown[0]->KV11 == 'yes') {  echo 'checked'; } ?> value="yes">&nbsp; Yes
                            <input type="radio"  name="v11kv_emp" <?php if(@$powershutdown[0]->KV11 == 'no') {  echo 'checked'; } ?>  value="no">&nbsp; No
                        </td>
                    </tr>
                    <tr><td><span>LT</span></td>
                        <td><input type="radio"  name="vlt_emp" <?php if(@$powershutdown[0]->LT == 'yes') {  echo 'checked'; } ?> value="yes">&nbsp; Yes
                            <input type="radio" name="vlt_emp"  <?php if(@$powershutdown[0]->LT == 'no') {  echo 'checked'; } ?>  value="no">&nbsp; No
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Issue Power Clearance<span style="color:red;font-size: 20px;">*</span></label>
            <div class="form-check col-sm-10">
                <input type="radio" class="" name="issue_power" <?php if(@$powershutdown[0]->issue_power == 'yes') {  echo 'checked'; } ?> value="yes">&nbsp; Yes
                <input type="radio" class=""  name="issue_power" <?php if(@$powershutdown[0]->issue_power == 'no') {  echo 'checked'; } ?> value="no">&nbsp; No
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Receive Power Clearance<span style="color:red;font-size: 20px;">*</span></label>
            <div class="form-check col-sm-10">
                <input type="radio" class="" name="rec_power" <?php if(@$powershutdown[0]->receive_power == 'yes') {  echo 'checked'; } ?> value="yes">&nbsp; Yes
                <input type="radio" class=""  name="rec_power" <?php if(@$powershutdown[0]->receive_power == 'no') {  echo 'checked'; } ?> value="no">&nbsp; No  
            </div>
        </div>
    </div>
@endif

@if($user->user_type == 2)
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input  type="text" class="form-control" id="" name="vendor_name_code" value="{{$user->vendor_name_code}}">                    
            </div>  
        </div>
	<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS</label>
        <div class="col-sm-10">
            <select class="form-control rec" name="clms" id="clms">
				<option>--Select--</option>
                <option value="Yes" @if($user->clm == "Yes" ) {{  'selected' }} @endif>Yes</option>
                <option value="No" @if($user->clm == "No" ) {{  'selected' }} @endif>No</option>
            </select>

        </div>  
    </div>
	
	<div class="form-group row" id="CLMS_role1" style="display:none">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="clms_role">

            <option value="">--Select--</option>
             <option value="Shift_incharge" @if($user->clm_role == "Shift_incharge" ) {{  'selected' }} @endif>Shift Incharge</option>
           <option value="hr_dept" @if($user->clm_role == "hr_dept" ) {{  'selected' }} @endif>HR Dept</option>
            <option value="Safety_dept" @if($user->clm_role == "Safety_dept" ) {{  'selected' }} @endif> Safety Dept</option>
           <option value="plant_head" @if($user->clm_role == "plant_head" ) {{  'selected' }} @endif> Plant Head</option>
           <option value="security" @if($user->clm_role == "security" ) {{  'selected' }} @endif> Security</option>
        <option value="Executing_agency" @if($user->clm_role == "Executing_agency" ) {{  'selected' }} @endif> Executing Agency</option>
         </select>
        </div>
    </div>
<script type="text/javascript">
    $("#clms").on('change',function (){
      var modeval =$(this).val();
      if(modeval == 'Yes'){
            $('#CLMS_role').show();
             $('#clms_admin').show();
             }
      else {
             $('#CLMS_role').hide();
              $('#clms_admin').hide();  
           }
   }); 
</script>
<div class="form-group row" id="clms_admin1" style="display:none">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Admin<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="clms_admin">
            <option value="">--Select--</option>
            <option value="Yes" @if($user->clms_admin == "Yes" ) {{  'selected' }} @endif> Yes</option>
            <option value="No" @if($user->clms_admin == "No" ) {{  'selected' }} @endif> No</option>
             </select>
        </div>
    </div>
	
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <?php $div_id = DB::table('divisions')->where('id',$user->division_id)->first();  ?>
                <select class="form-control"  name="vendor_division_id">
                    <option value="">Select The Division</option>
                    @if($divisions->count() > 0)
                        @foreach($divisions as $division)
                            <option @if(@$div_id->id == @$division->id) {{ 'selected' }} @endif value="{{$division->id}}">{{$division->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>  
        </div>
        <div class="form-group row" style="" id="sup">
            <label for="form-control-label" class="col-sm-2 col-form-label">Supervisor Name</label>
            <div class="col-sm-7" id="append_sup">
                @if($get_supervisors->count() > 0 )
                    @foreach($get_supervisors as $key => $value)  
                        <input type="text" class="form-control" name="supervisor[]" id="supervisor_id" value="{{@$get_supervisors[$key]->supervisor_name}}">&nbsp;
                        <a href="{{ route('admin.delsuper',$get_supervisors[$key]->id) }}"  style="margin:-100px 2px 8px 540px"  class="btn btn-danger btn-sm">-</a>
                        <input type="hidden" name="uni_sup[]" value="{{$get_supervisors[$key]->id}}">
                    @endforeach
                @endif
            </div>
            <div class="col-sm-3">
                <button type="button" style="margin:0px 15px 7px 85px" id="add_sup" class="btn btn-primary btn-sm">+</button>
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Gate Pass Details<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-9">
                <table class="table table-bordered">
                    <thead> 
                        <tr> 
                            <th>Employee Name</th>
                            <th>Gate Pass No.</th>
                            <th>Designation</th>
                            <th>Age</th>
                            <th>Expiry Date</th>
                            <th>#</th>              
                        </tr>
                    </thead>
                    <tbody id="append_gatepass">
                        @if($gatepass->count() > 0 )
                            @foreach($gatepass as $key => $value) 
                                <tr class="gatepass" id="gatepass">
                                    <input type="hidden" name="oldgatepassid[]" value="{{$gatepass[$key]->id}}">
                                    <td><input type="text" class="form-control" name="employee[]"  value="{{$gatepass[$key]->employee}}"></td>
                                    <td><input type="text" class="form-control" name="gatepass[]"  value="{{$gatepass[$key]->gatepass}}"></td>
                                    <td><input type="text" class="form-control" name="designation[]"  value="{{$gatepass[$key]->designation}}"></td>
                                    <td><input type="text" class="form-control" name="age[]"  value="{{$gatepass[$key]->age}}"></td>
                                    <td><input type="date" class="form-control" name="expirydate[]"  value="{{$gatepass[$key]->expiry}}"></td>
                                    
                                    <td><a href="{{ route('admin.delgatepass',$gatepass[$key]->id) }}" class="btn btn-danger btn-sm">-</a></td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
            <div class="col-sm-1" style="">
                <button type="button" id="btn-add" class="btn btn-primary btn-sm">+</button>&nbsp;
                <button type="button" id="btn-remove" class="btn btn-danger btn-sm">-</button>
            </div> 
        </div>
        <!-- Supervisor details -->
        <div class="form-group row">
            <!-- <input type="text" id="unique" value="0" name=""> -->
            <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Power Shutdowns:<span style="color:red;font-size: 20px;">*</span></label>
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
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody id="dataview">
                        <?php $identify = 0; ?>
                        @if($powershutdown->count() > 0 )
                            @foreach($powershutdown as $key => $value) 
                            <?php $identify = $key;  ?>
                                <input type="hidden" name="uni_id[]" value="{{$powershutdown[$key]->id}}">&nbsp;
                                <tr class="appendrow">
                                    <td><input type="text" class="form-control" name="supervisor_ven[]"  value="{{$powershutdown[$key]->supervisor_name}}"></td>
                                    <td><input type="text" class="form-control" name="electrical_license_ven[]" value="{{$powershutdown[$key]->electrical_license}}"></td>
                                    <td><input type="date" class="form-control" name="license_validity_ven[]" value="{{$powershutdown[$key]->validity_date}}"></td>
                                    <td><table style="width: 180px;">
                                            <tr><td><span>132KV</span></td>
                                                <td>
                                                    <input type="radio" name="v132kv_ven[<?= $identify ?>]"  <?php if($powershutdown[$key]->KV132 == 'no'){ echo 'checked';  }  ?> value="no">&nbsp; No
                                                    <input type="radio" name="v132kv_ven[<?= $identify ?>]"  <?php if($powershutdown[$key]->KV132 == 'yes'){ echo 'checked'; }  ?> value="yes">&nbsp; Yes
                                                </td>
                                            <tr>
                                            <tr><td><span>33KV</span></td>
                                                <td>
                                                    <input type="radio" name="v33kv_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->KV33 == 'no'){ echo 'checked';  }  ?> value="no">&nbsp; No
                                                    <input type="radio" name="v33kv_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->KV33 == 'yes'){ echo 'checked';  }  ?> value="yes">&nbsp; Yes
                                                </td>
                                            <tr><td><span>11KV</span></td>
                                                <td>
                                                    <input type="radio" name="v11kv_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->KV11 == 'no'){ echo 'checked';  }  ?> value="no">&nbsp; No
                                                    <input type="radio" name="v11kv_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->KV11 == 'yes'){ echo 'checked';  }  ?> value="yes">&nbsp; Yes
                                                </td>
                                            <tr><td><span>LT</span></td>
                                                <td>
                                                    <input type="radio" name="vlt_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->LT == 'no'){ echo 'checked';  }  ?> value="no">&nbsp; No
                                                    <input type="radio" name="vlt_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->LT == 'yes'){ echo 'checked';  }  ?> value="yes">&nbsp; Yes
                                                </td>
                                            <tr>
                                        </table>
                                    </td>
                                    <td>
                                        <label class="form-check-label">
                                            <input type="radio" name="issue_power_ven[<?= $identify ?>]"   <?php if($powershutdown[$key]->issue_power == 'yes'){ echo 'checked';  }  ?> value="yes">&nbsp; Yes
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio"  name="issue_power_ven[<?= $identify ?>]"  <?php if($powershutdown[$key]->issue_power == 'no'){ echo 'checked';  }  ?> value="no">&nbsp; No  
                                        </label>
                                    </td>
                                    <td>
                                        <label class="form-check-label">
                                            <input type="radio" class="" name="rec_power_ven[<?= $identify ?>]"  <?php if($powershutdown[$key]->receive_power == 'yes'){ echo 'checked';  }  ?> value="yes">&nbsp; Yes
                                        </label>
                                        <label class="form-check-label">
                                            <input type="radio" class=""  name="rec_power_ven[<?= $identify ?>]" <?php if($powershutdown[$key]->receive_power == 'no'){ echo 'checked';  }  ?>  value="no">&nbsp; No  
                                        </label>
                                    </td>
                                    <td><a href="{{ route('admin.shutdown',$powershutdown[$key]->id) }}" class="btn btn-danger btn-sm">-</a></td>
                                </tr>                                                                   
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-sm-1" style="">
                <input type="hidden" id="increment" value="{{$identify}}">
                <button type="button" id="btn-add-vendor" class="btn btn-primary btn-sm">+</button>&nbsp;
                <button type="button" id="btn-remove-vendor" class="btn btn-danger btn-sm">-</button>
            </div> 
        </div>
    @endif

    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Update User" onclick="return form_validate();">
        </div>
    </div>
</form>
@endsection
<!-- END Content Section -->
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

    
    $("#add_sup").on("click", function (e) {
        var count = $(".remove_tr").length + 1;
        $('#append_sup').append(`<input type="hidden" name="uni_sup[]"><input type="text" class="form-control" name="supervisor[]" id="supervisor_id">&nbsp;`);
    });
    
    //Append code
    $("#btn-add-vendor").on("click", function (e) {
    var incrementjquery = $("#increment").val();
    incrementjquery++;
    $("#increment").val(incrementjquery);
    console.log(incrementjquery);
    var datas='';
        datas +='<tr class="appendrow">';
        datas +='<td><input type="text" class="form-control" name="supervisor_ven[]"> <input type="hidden" name="uni_id[]"></td>';
        datas +='<td><input type="text" class="form-control" name="electrical_license_ven[]"></td>';
        datas +='<td><input type="date" class="form-control" name="license_validity_ven[]"></td>';
        datas +='<td><table style="width: 180px;">';
                    datas +='<tr><td><span>132KV</span></td>';
                        datas +='<td><input type="radio" name="v132kv_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="v132kv_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr>';
                    datas +='<tr><td><span>33KV</span></td>';
                        datas +='<td><input type="radio" name="v33kv_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="v33kv_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr><td><span>11KV</span></td>';
                        datas +='<td><input type="radio" name="v11kv_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="v11kv_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr><td><span>LT</span></td>';
                        datas +='<td><input type="radio" name="vlt_ven['+incrementjquery+']" checked value="no">&nbsp; No';
                        datas +='<input type="radio" name="vlt_ven['+incrementjquery+']" value="yes">&nbsp; Yes';
                        datas +='</td>';
                    datas +='<tr>';
                datas +='</table>';
            datas +='</td>';
            datas +='<td>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class="" name="issue_power_ven['+incrementjquery+']"  checked value="yes">&nbsp; Yes';
                datas +='</label>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class=""  name="issue_power_ven['+incrementjquery+']" value="no">&nbsp; No';  
                datas +='</label>';
            datas +='</td>';
            datas +='<td>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class="" name="rec_power_ven['+incrementjquery+']" checked value="yes">&nbsp; Yes';
                datas +='</label>';
                datas +='<label class="form-check-label">';
                    datas +='<input type="radio" class=""  name="rec_power_ven['+incrementjquery+']" value="no">&nbsp; No '; 
                datas +='</label>';
            datas +='</td><td></td>';
    datas +='</tr>';
    $('#dataview').append(datas);

    });

    //Remove 
    $("#btn-remove-vendor").on("click", function (e) {
        if($('.appendrow').length > 1){
            $(".appendrow:last").remove();
            var incrementjquery = $("#increment").val();
            incrementjquery--;
            $("#increment").val(incrementjquery);
        }
    });


    //gate pass Details to add
    $("#btn-add").on("click", function (e) {
            var count = $(".remove_tr").length + 1;
            // console.log(count);
            $('#append_gatepass').append(`<tr class="gatepass">
                    <td><input type="hidden" name="oldgatepassid[]"><input type="text" class="form-control" name="employee[]"></td>
                    <td><input type="text" class="form-control" name="gatepass[]"></td>
                    <td><input type="text" class="form-control" name="designation[]"></td>
                    <td><input type="text" class="form-control" name="age[]"></td>      
                    <td><input type="date" class="form-control" name="expirydate[]"></td><td></td>       

                </tr>`);
    });

    //Remove Top Click
    $("#btn-remove").on("click", function (e) {
        if($('.gatepass').length > 1){
            $(".gatepass:last").remove();
        }
    });
    function ElectricalSupervisoryEmployee(items){
        if(items!="")
        {
            if(items == 'yes'){
                $("#Electrical_Yes").show();
            }
            else if(items == 'no')   
            {
                $("#Electrical_Yes").hide();
            }
        }
    }
  
</script>
@endsection


