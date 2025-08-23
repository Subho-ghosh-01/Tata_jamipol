
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">2 Factor Authentication</div>
                    <div class="card-body">
                        <?php if(session()->has('message')): ?>
                            <div class="alert alert-danger text-center">
                                <?php echo e(session('message')); ?>

                            </div>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('otpPost')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group row">
                                <label for="enter_otp" class="col-md-12 col-form-label text-md-left">Please enter the OTP
                                    sent to your registered email below:-</label>
                                <div class="col-md-12">
                                    <input id="enter_otp" type="text" class="form-control" name="enter_otp" value="<?php echo e(Session::get('otp')); ?>"
                                        placeholder="Enter OTP" required autofocus>
                                    <div style="margin-top: 10px;">
                                        <?php if($errors->has('enter_otp')): ?>
                                            <?php $__currentLoopData = $errors->get('enter_otp'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p class="alert alert-danger" style="text-align:center;">
                                                    <strong><?php echo e($error); ?></strong>
                                                </p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <?php echo e(__('Login')); ?>

                                    </button>
                        </form>
                        <a href="<?php echo e(route('logout1')); ?>" class="btn btn-danger">
                            <form id="logout-form" action="<?php echo e(route('logout1')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                            <span data-feather=""> </span> Cancel
                        </a>


                    </div>


                </div>
            </div>



        </div>


    </div>
    </div>
    </div>
    </div>
    </div><br>
    <center><img src="<?php echo e(URL::to('/images/footer.png')); ?>"></center>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appCopy', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/otpPage.blade.php ENDPATH**/ ?>