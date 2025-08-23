<?php
use App\Division;
use App\Department;
use App\UserLogin;

?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.work-order.index')); ?>">List of WorkOrder</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Work Order</li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 2): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.work-order.update',$id)); ?>" method="post" enctype="multipart/form-data">
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Work Order</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="order_code" value="<?php echo e($workOrder->order_code); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="vendor_code" value="<?php echo e($workOrder->vendor_code); ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" name="order_validity" value="<?php echo e($workOrder->order_validity); ?>">
        </div>
    </div>
   <?php $div_id = DB::table('divisions')->where('id',$workOrder->division_id)->first();  ?>
     <?php $deprt_id = DB::table('departments')->where('division_id',$workOrder->division_id)->first();  ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Division</label>
            <div class="col-sm-10">
                <select class="form-control" id="division_id" name="division_id">
                    <option value="">Select Division</option>
                    <?php if($divisions->count() > 0 ): ?>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($division->id); ?>" <?php if(@$div_id->id == @$division->id): ?> <?php echo e('selected'); ?> <?php endif; ?>> <?php echo e($division->name); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?> 
                </select>
            </div>  
        </div> 
		<?php $deprt_id = DB::table('departments')->where('id',$workOrder->department_id)->first();  ?>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label">Departments</label>
            <div class="col-sm-10">
                <select class="form-control" id="department_id" name="department_id">
                    <option value="">Select Department</option>
                    <?php if($departments->count() > 0 ): ?>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e(@$department->id); ?>" <?php if(@$deprt_id->id == $department->id): ?> <?php echo e('selected'); ?> <?php endif; ?>> <?php echo e(@$department->department_name); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?> 
                </select>
            </div>
        </div>
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary"  onclick="return form_validate()" value="Update">
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
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
                url:"<?php echo e(route('admin.approverGet')); ?>/" + department_ID,
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/orders/edit.blade.php ENDPATH**/ ?>