
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <?php if(session()->has('message')): ?>
                        <div class="alert alert-danger text-center">
                            <?php echo e(session('message')); ?>

                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('loginPost')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group row">
                            <label for="vendor_code" class="col-md-4 col-form-label text-md-right">Employee P.No./Vendor User Name</label>
                            <div class="col-md-6">
                                <input id="vendor_code" type="text" class="form-control" name="vendor_code" value="" placeholder="Employee Personal No./Vendor User Name" required autofocus>
                                <div style="margin-top: 10px;"> 
                                    <?php if($errors->has('vendor_code')): ?>
                                        <?php $__currentLoopData = $errors->get('vendor_code'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="alert alert-danger" style="text-align:center;">
                                            <strong><?php echo e($error); ?></strong>
                                        </p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                    
                                    <div style="margin-top: 10px;"> 
                                        <?php if($errors->has('password')): ?>
                                            <?php $__currentLoopData = $errors->get('password'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p class="alert alert-danger">
                                                <strong><?php echo e($error); ?></strong>
                                            </p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                               <div class="g-recaptcha" data-sitekey="<?php echo e(env('GOOGLE_RECAPTCHA_KEY')); ?>" required></div>
                            </div>
                        </div>

                        

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo e(__('Login')); ?>

                                </button>

                                 
                            </div>
                        </div> 
						
				
                    </form>
                   <br><center><a href="<?php echo e(URL::to('forgotPage')); ?>">Forgot Password?</a>/<a href="<?php echo e(URL::to('RegisterGatepass')); ?>">Register</a></center><br>
				<!-- <center> <a href="<?php echo e(URL::to('RegisterGatepass')); ?>"><button type="submit" class="btn btn-primary">Register</button></a></center><br>-->
				
                </div>
            </div>
        </div>
    </div>
</div><br>
<center><img src="<?php echo e(asset('images/footer.png')); ?>"></center>
<?php $__env->stopSection(); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>

<?php echo $__env->make('layouts.appCopy', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/loginPage.blade.php ENDPATH**/ ?>