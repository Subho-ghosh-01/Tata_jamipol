
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</a></li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 0): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">CLMS Reports</h1>
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

    <form class="form-inline" autocomplete=off action="<?php echo e(route('admin.clms_report')); ?>"  method="POST">
    
        <?php echo csrf_field(); ?>  
       <div class="form-group mb-2">
            <select class="form-control" name="divi_id" onchange="getDepartment(this,this.value)">
                <option value="">ALL Division</option>
                <?php if($divisions->count() > 0): ?>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>
         <div class="form-group mb-2" style="display: none;">
            <select class="form-control" name="dept_id" id="department_id">
                <option value="">ALL Department</option>
            </select>
        </div> 
        <div class="form-group mx-sm-2 mb-2">
            <input type="text" name="fromdate" class="form-control" placeholder="From Date" id="start_date" required>
        </div>
        <div class="form-group mb-2">
            <input type="text" name="todate" class="form-control" placeholder="To Date" id="end_date" required>
        </div>
        <div class="form-group mx-sm-2 mb-2">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Report">
        </div>
    </form>
 
    <div class="table-responsive">
    <?php if($report): ?>
    <?php $from = $_REQUEST['fromdate'];
    $to = $_REQUEST['todate'];
    $div = $_REQUEST['divi_id'];
    ?>
    <a href="https://wps.jamipol.com/api/print_report.php?&from=<?= $from ?>&to=<?= $to ?>&division=<?= $div ?>" target="_blank"><input type="button" value="Export To Excel" class="btn btn-outline-primary"></a>

    <?php endif; ?>
        <table id="example" class="display table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                            <th>#</th>
                            <th>Sl No</th>
                           <th>Vendor Name</th>
                             <th>Name</th>
                            <th>Work Order No</th>
                           <th>Status</th>
                            <th>Action</th>
            </tr>
            </thead>
            <tbody>
                         <?php if($report): ?>
     <?php $count=1 ?>
                        <?php $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reports): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <?php
                        @$approver = DB::table('userlogins')->where('id',$reports->created_by)->first();
                        ?>
                        <?php
                         @$work = DB::table('work_order')->where('id',$reports->work_order_no)->first();
                        ?>
                          
               <tr>
                                <td><?php echo e($count ++); ?></td>
                                  <td><?php echo e($reports->full_sl); ?></td>
                                  <td><?php echo e(@$approver->name); ?></td>
                                 <td><?php echo e($reports->name); ?></td>
                                 <td><?php echo e(@$reports->work_order_no); ?></td>
                                 <td><?php if($reports->status == "Pending_for_shift_incharge"): ?>
                                            <?php echo e("Pending To Shift Incharge"); ?>

                                      <?php elseif($reports->status == "Pending_for_hr"): ?>
                                      <?php echo e("Pending To HR"); ?>

                                      <?php elseif($reports->status == "Pending_for_safety"): ?>
                                      <?php echo e("Pending To Safety"); ?>

                                      <?php elseif($reports->status == "Pending_for_plant_head"): ?>
                                      <?php echo e("Pending To Plant Head"); ?>

                                      <?php elseif($reports->status == "Pending_for_security"): ?>
                                      <?php echo e("Gatepass Approved"); ?>

                                      <?php elseif($reports->status == "Rejected"): ?>
                                      <?php echo e("Rejected"); ?>

                                   <?php endif; ?> </td>
                                     

                            <td><a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit_clms.edit',\Crypt::encrypt($reports->id))); ?>" title="Edit">Details</a>

                            </td>      
                            </tr>
                     
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                   <?php endif; ?>
                   </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    $('#start_date').datetimepicker({
        format:'Y/m/d'
    });
    $('#end_date').datetimepicker({
        format:'Y/m/d'
    });    
    $(document).ready(function() {
        $('#example').DataTable();
    });
    function getDepartment(th,divisionID) {
        if(divisionID!="")
        {
            $("#department_id").html('<option value="ALL">ALL</option>');
            if(divisionID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"<?php echo e(route('admin.job.department')); ?>/" + divisionID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        for(var i=0;i<data.length;i++){
                            $('#department_id').append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                        }
                    }
                });
            }else{
                $('#department_id').html('<option value="">Select Department</option>');
            }     
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/clms_report.blade.php ENDPATH**/ ?>