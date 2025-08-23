
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.department.index')); ?>">List of Department</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Department</li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.department.store')); ?>" method="post"  autocomplete="off">
    <?php echo csrf_field(); ?>
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Department Name<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="department_name" id="" value="">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Divisions<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-5" id="add_new">
            <select class="form-control appendrow"  name="division_id[]">
                <option value="">Select Division</option>
                <?php if($divisions->count() > 0): ?>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?> 
            </select>
        </div>
        <div class="col-sm-1">
            <button type="button" id="btn-add" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
  
    
    
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" class="btn btn-primary" value="Add Department">
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
<script>
   //Append code
    $("#btn-add").on("click", function (e) {
    var datas='';
        datas +='&nbsp;<select class="form-control appendrow" name="division_id[]"><option value="">Select Divisions</option>';
            <?php if($divisions->count() > 0): ?>
                <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    datas +='<option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>';
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?> 
        datas +='</select>';
    $('#add_new').append(datas);

    });

    //Remove 
    $("#btn-remove").on("click", function (e) {
        if($('.appendrow').length > 1){
            $(".appendrow:last").remove();
        }
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/departments/create.blade.php ENDPATH**/ ?>