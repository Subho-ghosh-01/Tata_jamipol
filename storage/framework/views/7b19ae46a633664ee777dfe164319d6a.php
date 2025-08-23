<?php
use App\Division;
use App\Department;
use App\UserLogin;

?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.user.index')); ?>">List of Users</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 2): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<!-- start content Section-->
<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.user.update',$id)); ?>" method="post"  autocomplete="off">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="form-group-row">
        <div class="col-sm-12">
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            <?php if(session()->has('message')): ?>
            <div class="alert alert-success">
                <?php echo e(session('message')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Name<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" value="<?php echo e($user->name); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Employee P.No./Vendor User Name<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code"  value="<?php echo e($user->vendor_code); ?>" >
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Email<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" name="email" value="<?php echo e($user->email); ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Wps<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control"  name="wps_user">
                <option value="Yes" <?php if($user->wps == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Yes</option>
                <option value="No" <?php if($user->wps == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>No</option>
             </select>
        </div>  
    </div>


    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">User Type<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control"  name="user_type">
            <?php if($user->user_type == 1): ?><option value="1" <?php if($user->user_type == 1 ): ?> <?php echo e('selected'); ?> <?php endif; ?> >Employee</option> <?php endif; ?>
            <?php if($user->user_type == 2): ?><option value="2" <?php if($user->user_type == 2 ): ?> <?php echo e('selected'); ?> <?php endif; ?> >Vendor</option> <?php endif; ?>
            </select>
        </div>
    </div>
    
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">User Sub-Type</label>
        <div class="col-sm-10">
            <select class="form-control"  name="user_sub_type">
                <option value="1" <?php if($user->user_sub_type == 1 ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Admin</option>
                <option value="2" <?php if($user->user_sub_type == 2 ): ?> <?php echo e('selected'); ?> <?php endif; ?>>User</option>
              
                <?php if(Session::get('user_sub_typeSession') == 3) { ?>
                    <option value="3" <?php if($user->user_sub_type == 3 ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Super Admin</option>
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
                <option value="Yes" <?php if($user->vms == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Yes</option>
                <option value="No" <?php if($user->vms == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>No</option>
            </select>
        </div>  
</div>
<link href="<?php echo e(URL::to('public/css/sweetalert.css')); ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/app.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/sweetalert.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('node_modules/jquery-datetimepicker/jquery.datetimepicker.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/app.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/dataTables.buttons.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/jszip.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/buttons.html5.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(URL::to('public/js/all.js')); ?>"> </script>
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
                 <option value="Approver" <?php if($user->vms_roll == "Approver" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Approver</option>
                <option value="Security" <?php if($user->vms_roll == "Security" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Security</option>
                <option value="Requester" <?php if($user->vms_roll == "Requester" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Requester</option>
            </select>
        </div>
    </div>
    <div class="form-group row" id="vms_admin" >
        <label for="form-control-label" class="col-sm-2 col-form-label">VMS Admin<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="vms_admin">
            <option value="">--Select--</option>
            <option value="Yes" <?php if($user->vms_admin == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Yes</option>
             <option value="No" <?php if($user->vms_admin == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>No</option>
           
            </select>
        </div>
    </div>
	
	 <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Safety<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec" name="safety" id="safety_id">
            <option value="">--Select--</option>
             <option value="No" <?php if($user->safety == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> No</option>
             <option value="Yes" <?php if($user->safety == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Yes</option>
           
            </select>
        </div>
    </div>
<div class="form-group row" id="safety_role_id" style="display:none;">
        <label for="form-control-label" class="col-sm-2 col-form-label">Safety Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="safety_role" >
            <option value="">--Select--</option>
                <option value="data_entry" <?php if($user->safety_role == "data_entry" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Data Entry</option>
                <option value="data_view" <?php if($user->safety_role == "data_view" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Data View</option>
            
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
            <option value="No" <?php if($user->safety_admin == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> No</option>
             <option value="Yes" <?php if($user->safety_admin == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Yes</option>
            
            
            </select>
        </div>
    </div>
	<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Labour Capacity</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="labour_capacity" value="<?php echo e($user->lobour_capacity); ?>"
            

        </div>  
    </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor ABB</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_abb" value="<?php echo e($user->vendor_abb); ?>"
            

        </div>  
    </div>
    </div>
    
	<?php if($user->user_type == 1): ?>
    
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS</label>
        <div class="col-sm-10">
            <select class="form-control rec" name="clms" id="clms">
                <option value="Yes" <?php if($user->clm == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Yes</option>
                <option value="No" <?php if($user->clm == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>No</option>
            </select>

        </div>  
    </div>
	
    <div class="form-group row" id="CLMS_role" >
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="clms_role">

            <option value="">--Select--</option>
             <option value="Shift_incharge" <?php if($user->clm_role == "Shift_incharge" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Shift Incharge</option>
           <option value="hr_dept" <?php if($user->clm_role == "hr_dept" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>HR Dept</option>
            <option value="Safety_dept" <?php if($user->clm_role == "Safety_dept" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Safety Dept</option>
           <option value="plant_head" <?php if($user->clm_role == "plant_head" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Plant Head</option>
           <option value="security" <?php if($user->clm_role == "security" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Security</option>
        <option value="Executing_agency" <?php if($user->clm_role == "Executing_agency" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Executing Agency</option>
        <option value="Account_dept" <?php if($user->clm_role == "Account_dept"): ?> <?php echo e('selected'); ?> <?php endif; ?>> Account Dept</option>
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
            <option value="Yes" <?php if($user->clms_admin == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Yes</option>
            <option value="No" <?php if($user->clms_admin == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> No</option>
             </select>
        </div>
    </div>

  
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Power Cutting<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec"  name="power_cutting">
                <option value="">--Select--</option>
                <option  <?php if($user->power_cutting == "Yes"): ?> <?php echo e('selected'); ?> <?php endif; ?> value="Yes">Yes</option>
                <option  <?php if($user->power_cutting == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?> value="No">No</option>
            </select>
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Power Getting<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec"  name="power_getting">
                <option value="">--Select--</option>
                <option  <?php if($user->power_getting == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?> value="Yes">Yes</option>
                <option  <?php if($user->power_getting == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?> value="No">No</option>
            </select>
        </div>  
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Authority to Issue Confined Space<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec"  name="confined_space">
                <option value="">--Select--</option>
                <option  <?php if($user->confined_space == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?> value="Yes">Yes</option>
                <option  <?php if($user->confined_space == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?> value="No">No</option>
            </select>
        </div>  
    </div>
    <?php endif; ?>
<?php if($user->user_type == 1): ?>
    <?php if(Session::get('user_sub_typeSession') == 3): ?>
    <?php $div_id = DB::table('divisions')->where('id',$user->division_id)->first();  ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" id="division_id" name="division_id">
                    <option value="null">Select Division</option>
                    <?php if($divisions->count() > 0 ): ?>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($division->id); ?>" <?php if(@$div_id->id == @$division->id): ?> <?php echo e('selected'); ?> <?php endif; ?>> <?php echo e($division->name); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?> 
                </select>
            </div>  
        </div> 
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
            <div class="col-sm-10">
                <select class="form-control" id="department_id" name="department_id">
                    <option value="null">Select Department</option>
                    <?php if($departments->count() > 0 ): ?>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e(@$department->id); ?>" <?php if($user->department_id == $department->id): ?> <?php echo e('selected'); ?> <?php endif; ?>> <?php echo e(@$department->department_name); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?> 
                </select>
            </div>
        </div>
    <?php else: ?>
    <?php   $division = Division::where('id',Session::get('user_DivID_Session'))->get();   
            $department = Department::where('division_id',$division[0]->id)->get();
    ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" name="division_id">
                    <option value="<?php echo e($division[0]->id); ?>"><?php echo e($division[0]->name); ?></option>            
                </select>
            </div>  
        </div> 
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
            <div class="col-sm-10">
                <select class="form-control"  name="department_id">
                    <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php if(@$user->department_id == @$depar->id): ?> <?php echo e('selected'); ?> <?php endif; ?>  value="<?php echo e(@$depar->id); ?>"><?php echo e(@$depar->department_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Active <span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control rec" name="active">
                <option value="">Select</option>
                <option value="Yes" <?php if($user->active == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Yes</option>
                <option value="No"  <?php if($user->active == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>No</option>
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
                <input type="text" class="form-control" name="electrical_license_emp" value="<?php echo e(@$powershutdown[0]->electrical_license); ?>">&nbsp;
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">License Number Validity date<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input type="date" class="form-control" name="license_validity_emp" id="edate" value="<?php echo e(@$powershutdown[0]->validity_date); ?>">&nbsp;
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
<?php endif; ?>

<?php if($user->user_type == 2): ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <input  type="text" class="form-control" id="" name="vendor_name_code" value="<?php echo e($user->vendor_name_code); ?>">                    
            </div>  
        </div>
	<div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS</label>
        <div class="col-sm-10">
            <select class="form-control rec" name="clms" id="clms">
				<option>--Select--</option>
                <option value="Yes" <?php if($user->clm == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Yes</option>
                <option value="No" <?php if($user->clm == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>No</option>
            </select>

        </div>  
    </div>
	
	<div class="form-group row" id="CLMS_role1" style="display:none">
        <label for="form-control-label" class="col-sm-2 col-form-label">CLMS Role<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <select class="form-control " name="clms_role">

            <option value="">--Select--</option>
             <option value="Shift_incharge" <?php if($user->clm_role == "Shift_incharge" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>Shift Incharge</option>
           <option value="hr_dept" <?php if($user->clm_role == "hr_dept" ): ?> <?php echo e('selected'); ?> <?php endif; ?>>HR Dept</option>
            <option value="Safety_dept" <?php if($user->clm_role == "Safety_dept" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Safety Dept</option>
           <option value="plant_head" <?php if($user->clm_role == "plant_head" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Plant Head</option>
           <option value="security" <?php if($user->clm_role == "security" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Security</option>
        <option value="Executing_agency" <?php if($user->clm_role == "Executing_agency" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Executing Agency</option>
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
            <option value="Yes" <?php if($user->clms_admin == "Yes" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> Yes</option>
            <option value="No" <?php if($user->clms_admin == "No" ): ?> <?php echo e('selected'); ?> <?php endif; ?>> No</option>
             </select>
        </div>
    </div>
	
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division<span style="color:red;font-size: 20px;">*</span></label>
            <div class="col-sm-10">
                <?php $div_id = DB::table('divisions')->where('id',$user->division_id)->first();  ?>
                <select class="form-control"  name="vendor_division_id">
                    <option value="">Select The Division</option>
                    <?php if($divisions->count() > 0): ?>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if(@$div_id->id == @$division->id): ?> <?php echo e('selected'); ?> <?php endif; ?> value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>  
        </div>
        <div class="form-group row" style="" id="sup">
            <label for="form-control-label" class="col-sm-2 col-form-label">Supervisor Name</label>
            <div class="col-sm-7" id="append_sup">
                <?php if($get_supervisors->count() > 0 ): ?>
                    <?php $__currentLoopData = $get_supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                        <input type="text" class="form-control" name="supervisor[]" id="supervisor_id" value="<?php echo e(@$get_supervisors[$key]->supervisor_name); ?>">&nbsp;
                        <a href="<?php echo e(route('admin.delsuper',$get_supervisors[$key]->id)); ?>"  style="margin:-100px 2px 8px 540px"  class="btn btn-danger btn-sm">-</a>
                        <input type="hidden" name="uni_sup[]" value="<?php echo e($get_supervisors[$key]->id); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
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
                        <?php if($gatepass->count() > 0 ): ?>
                            <?php $__currentLoopData = $gatepass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                <tr class="gatepass" id="gatepass">
                                    <input type="hidden" name="oldgatepassid[]" value="<?php echo e($gatepass[$key]->id); ?>">
                                    <td><input type="text" class="form-control" name="employee[]"  value="<?php echo e($gatepass[$key]->employee); ?>"></td>
                                    <td><input type="text" class="form-control" name="gatepass[]"  value="<?php echo e($gatepass[$key]->gatepass); ?>"></td>
                                    <td><input type="text" class="form-control" name="designation[]"  value="<?php echo e($gatepass[$key]->designation); ?>"></td>
                                    <td><input type="text" class="form-control" name="age[]"  value="<?php echo e($gatepass[$key]->age); ?>"></td>
                                    <td><input type="date" class="form-control" name="expirydate[]"  value="<?php echo e($gatepass[$key]->expiry); ?>"></td>
                                    
                                    <td><a href="<?php echo e(route('admin.delgatepass',$gatepass[$key]->id)); ?>" class="btn btn-danger btn-sm">-</a></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

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
                        <?php if($powershutdown->count() > 0 ): ?>
                            <?php $__currentLoopData = $powershutdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <?php $identify = $key;  ?>
                                <input type="hidden" name="uni_id[]" value="<?php echo e($powershutdown[$key]->id); ?>">&nbsp;
                                <tr class="appendrow">
                                    <td><input type="text" class="form-control" name="supervisor_ven[]"  value="<?php echo e($powershutdown[$key]->supervisor_name); ?>"></td>
                                    <td><input type="text" class="form-control" name="electrical_license_ven[]" value="<?php echo e($powershutdown[$key]->electrical_license); ?>"></td>
                                    <td><input type="date" class="form-control" name="license_validity_ven[]" value="<?php echo e($powershutdown[$key]->validity_date); ?>"></td>
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
                                    <td><a href="<?php echo e(route('admin.shutdown',$powershutdown[$key]->id)); ?>" class="btn btn-danger btn-sm">-</a></td>
                                </tr>                                                                   
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-1" style="">
                <input type="hidden" id="increment" value="<?php echo e($identify); ?>">
                <button type="button" id="btn-add-vendor" class="btn btn-primary btn-sm">+</button>&nbsp;
                <button type="button" id="btn-remove-vendor" class="btn btn-danger btn-sm">-</button>
            </div> 
        </div>
    <?php endif; ?>

    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Update User" onclick="return form_validate();">
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<!-- END Content Section -->
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
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
                url:"<?php echo e(route('admin.user.department')); ?>/" + division_ID,
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
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>