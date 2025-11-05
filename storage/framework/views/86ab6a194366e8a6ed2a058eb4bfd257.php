<?php
use App\Division;
use App\Department;
use App\Section;
?>



<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.work-order.index')); ?>">List of Work Orders</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Work Orders</li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 2): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.work-order.store')); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
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
        <label for="form-control-label" class="col-sm-2 col-form-label">Work Order</label>
        <div class="col-sm-10">
            <input type="text" class="form-control rec" name="work_order">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Vendor Code</label>
        <div class="col-sm-10">
            <input type="text" class="form-control rec" name="vendor_code">
        </div>
    </div>
    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Order Validity</label>
        <div class="col-sm-10">
            <input type="date" class="form-control rec" name="order_validity">
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Division<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
           <select class="form-control" id="divisionID" name="division_id" required> 
                               <option value="0"> Select Division</option>
                       <?php if($divisions->count() > 0): ?>
                           <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                               </select>
                               
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

<script>
$('#divisionID').on('change',function(){
        var division_ID = $(this).val();
         //alert(division_ID);
        $("#departmentID").html('<option value="null">--Select--</option>');
        
        if(divisionID)
        {
            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'GET',
                url:"<?php echo e(route('admin.departmentGet')); ?>/" + division_ID,
                contentType:'application/json',
                dataType:"json",
                success:function(data){
                    console.log(data);
                    for(var i=0;i<data.length;i++){
                        $("#departmentID").append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                    }
                }
            });
           
        }else{
            $('#departmentID').html('<option value="null">Select Division first</option>');
        }
    });
    </script>
        </div>
    </div>

    <div class="form-group row">
        <label for="form-control-label" class="col-sm-2 col-form-label">Department<span style="color:red;font-size: 20px;">*</span></label>
        <div class="col-sm-10">
           <select class="form-control" id="departmentID" name="department_id" required> 
                <option value="0"> Select Department</option>    
                </select>
            
        </div>
    </div>              
            

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
    <!-- HIT Insert -->
    <div class="form-group row">
        <div class="col-sm-12 text-center">
            <input type="submit" name="submit" onclick="return form_validate()" class="btn btn-primary" value="Submit">
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/orders/create.blade.php ENDPATH**/ ?>