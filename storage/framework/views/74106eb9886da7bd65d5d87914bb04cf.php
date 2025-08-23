<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'JAMIPOL Work Permit System')); ?></title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/> -->
    <!-- Styles -->
    <link href="<?php echo e(asset('public/css/app.css')); ?>" rel="stylesheet" >
    <link href="<?php echo e(asset('public/css/jquery.dataTables.min.css')); ?>" rel="stylesheet" >
    <link href="<?php echo e(asset('public/css/buttons.dataTables.min.css')); ?>" rel="stylesheet" >
    <link href="<?php echo e(asset('public/css/sweetalert.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('public/css/admin.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('public/css/fontawesome-free/css/all.min.css')); ?>">
    
	
	
	
    <!-- <link href="<?php echo e(asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet"> -->
</head>
<body>

    
                <div class="col-md-12">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
           
    <!-- Scripts -->
      
    <script type="text/javascript" src="<?php echo e(asset('public/js/app.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('public/js/sweetalert.js')); ?>"> </script>
    
    <script type="text/javascript" src="<?php echo e(asset('public/js/jquery.dataTables.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('public/js/dataTables.buttons.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('public/js/jszip.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('public/js/buttons.html5.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('public/js/all.js')); ?>"> </script>

    <!-- <script type="text/javascript" src="<?php echo e(asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"> </script> --> 
    
    
</body>
</html><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/app2.blade.php ENDPATH**/ ?>