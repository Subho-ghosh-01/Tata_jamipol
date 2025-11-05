
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">Import Gate Pass</a></li>
<?php $__env->stopSection(); ?>                        
<?php $__env->startSection('content'); ?>
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
    <form action="<?php echo e(route('admin.gatepass_import')); ?>" method="post" enctype="multipart/form-data"> 
        <?php echo csrf_field(); ?>
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <a herf=""> <input type="file" name="file_datas" required accept=".xlsx,.xls" class="btn btn-primary"> </a><br><br><br><br>
            </div>
            <div class="col-sm-12 text-center">
                <input type="submit" name="button" class="btn btn-primary" value="Submit">
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<!-- From JS Started -->
<?php $__env->startSection('scripts'); ?>
<script>
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/list_permits/gate_pass_view_import.blade.php ENDPATH**/ ?>