
<!-- breadcrumbs start -->
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Job Category</a></li>
<?php $__env->stopSection(); ?>
<!-- breadcrumbs end -->
<?php if(Session::get('user_sub_typeSession') == 2): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<!-- Content start section -->
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List of Job Category</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?php echo e(route('admin.job.create')); ?>" class="btn btn-sm btn-outline-secondary">Add Job Category</a>
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
    <?php if(Session::get('user_sub_typeSession') == 3 || Session::get('user_sub_typeSession') == 1): ?>
    <form class="form-inline" autocomplete=off action="<?php echo e(route('admin.getjoblist')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="form-group mb-3">
            <select class="form-control rec" id="division_id" name="division_id"  onchange="getDepartment(this,this.value)">
                <option value="">Select Division</option>
                <?php if($divisions->count() > 0): ?>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(@$division->id); ?>"><?php echo e(@$division->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group mx-sm-3 mb-3">
            <select class="form-control rec" id="department_id" name="department_id">
                <option value="">Select Department</option>
            </select>     
        </div>
        <div class="form-group mx-sm-2 mb-3">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Jobs" onclick="return check();">
        </div>
    </form>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="ListAll">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Job Category Name </th>
                    <th>SWP/SOP No.</th>
                    <th>High Risk Activity</th>
                    <th>Power Clearance Required</th>
                    <th>Confined Space</th>
                    <th>Six Direction Hazards</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($jobs->count() > 0): ?>
                <?php $count =  1 ;?>
                    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($count++); ?></td>
                            <td><?php echo e($job->job_title); ?></td>
                            <td><?php echo e($job->swp_number); ?></td>
                            <td><?php if($job->high_risk == 'on'): ?> Yes <?php else: ?> No <?php endif; ?></td>
                            <td><?php if($job->power_clearance == 'on'): ?> Yes <?php else: ?> No <?php endif; ?></td>
                            <td><?php if($job->confined_space == 'on'): ?> Yes <?php else: ?> No <?php endif; ?></td>
                            <td><a class="btn btn-info btn-sm" data-id="<?php echo e($job->id); ?>"  data-toggle="modal" data-target="#myModal" data-whatever="@mdo"> View</a></td>
                            <td><a class="btn btn-info btn-sm" href="<?php echo e(route('admin.job.edit',\Crypt::encrypt($job->id))); ?>">Edit</a>
                            <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($job->id); ?>')">Delete</a>
                                <form id="delete-<?php echo e($job->id); ?>" action="<?php echo e(route('admin.job.destroy',$job->id)); ?>" method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="" style="color:red;text-align:center;">No Jobs Found.....</td>
                </tr>
                <?php endif; ?>
                
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<!-- Content end Section -->
<?php endif; ?>


<?php $__env->startSection('scripts'); ?>
<script>
    function deleteRecord(id){
        // alert(id)
        let  choice = confirm("Are you sure want to delete the record permanently?");
        if(choice){
            document.getElementById('delete-'+id).submit();
        }
    }
    $(document).ready(function(){
        $('#myModal').on('show.bs.modal', function (e) {
            $('#table_app').html("");
            var job_id = $(e.relatedTarget).data('id');
            // alert(job_id);
                $.ajax({
                    type:'GET',
                    url:"<?php echo e(route('admin.getSixDirectionals')); ?>/" + job_id,
                    contentType:'application/json',
                    dataType:"HTML",
                    success:function(data){
                        // console.log(data);
                            $('#table_app').append(`<div class="modal-body1">`);
                            $('#table_app').append(data);
                            $('#table_app').append(`</div>`);
                    }
                });
        });
    });
    $(document).ready(function() {
        $('#ListAll').DataTable();
    });
    function getDepartment(th,divisionID) {
        if(divisionID!="")
        {
            $("#department_id").html('<option value="">--Select--</option>');
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
    

    function check()
    {
        var flag=true;
        $(".rec").each(function(e){
            if($(this).val()=="")
            {
                $(this).addClass("verror");
                flag=false;
            }
            else
            {
                $(this).removeClass("verror");
            }
            })
            if(flag==true)
            {
            
            }
            else
            {
                return false;
            }
    }     
</script>
<!-- open the model for display the hazareds -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Six Directional Hazards </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="table_app">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/jobs/index.blade.php ENDPATH**/ ?>