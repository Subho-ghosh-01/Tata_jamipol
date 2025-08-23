
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.skill.index')); ?>">List of Skill</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Skill</li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    return redirect('admin/dashboard');
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <form action="<?php echo e(route('admin.skill.update', $skill->id)); ?>" method="post" autocomplete="off">
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
                <label for="form-control-label" class="col-sm-2 col-form-label">Skill Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="skill_name" id="" value="<?php echo e($skill->skill_name); ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="form-control-label" class="col-sm-2 col-form-label">Skill Rate</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="skill_rate" id="" value="<?php echo e($skill->skill_rate); ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <input type="submit" name="submit" class="btn btn-primary" value="Update Section">
                </div>
            </div>
        </form>
    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/skill/edit.blade.php ENDPATH**/ ?>