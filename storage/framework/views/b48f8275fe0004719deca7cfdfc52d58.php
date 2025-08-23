
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Change Password Section</li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('admin.pwd_upd')); ?>" method="post" autocomplete="off">
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
        <?php if(session()->has('message')): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '<?php echo e(session('message')); ?>',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        background: 'linear-gradient(to right, #e0f7f1, #ccfbf1)',
                        iconColor: '#16a34a',
                        customClass: {
                            popup: 'rounded-4 shadow-lg border-0 px-4 py-3',
                            title: 'fw-bold text-dark fs-6'
                        },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                });
            </script>
        <?php endif; ?>



        <div class="form-group row">
            <label for="old_pwd" class="col-sm-2 col-form-label">Old Password</label>
            <div class="col-sm-3">
                <input type="password" class="form-control" name="old_pwd" id="old_pwd" required autocomplete="off">
            </div>
        </div>

        <div class="form-group row">
            <label for="new_pwd" class="col-sm-2 col-form-label">NeW Password</label>
            <div class="col-sm-3">
                <input type="password" class="form-control" name="new_pwd" id="new_pwd" required autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label for="confirm_pwd" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-3">
                <input type="password" class="form-control" name="confirm_pwd" id="confirm_pwd" required autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label for="form-control-label" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-3">
                <input type="submit" name="submit" class="btn btn-primary" value="Change Password">
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/users/show_password.blade.php ENDPATH**/ ?>