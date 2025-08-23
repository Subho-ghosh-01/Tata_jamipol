<?php 
use App\Division;
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Department</a></li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Department</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('admin.department.create')); ?>" class="btn btn-sm btn-outline-secondary">Add Department </a>
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
<?php if(Session::get('user_sub_typeSession') == 3): ?>
    <form class="form-inline" autocomplete=off action="<?php echo e(route('admin.getdepartmentlist')); ?>" method="post">
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
        <div class="form-group mx-sm-2 mb-3">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Department" onclick="return check();">
        </div>
    </form>
<?php endif; ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="listall">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Department Name</th>
                    <th>Division</th>
                    <th>Action</th>
                 </tr>
            </thead>
            <tbody>
                <?php if($departments->count() > 0 ): ?> 
                    <?php $count = 1; ?>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                            $division_name  = Division::where('id',$department->division_id)->get();
                        ?>
                        <tr>
                            <td><?php echo e($count++); ?></td>
                            <td><?php echo e($department->department_name); ?></td>
                            <td><?php echo e(@$division_name[0]->name); ?> </td>
                            <td>
                            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.department.edit',\Crypt::encrypt($department->id))); ?>" title="Edit">Edit</a> |
                            <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($department->id); ?>')">Delete</a>
                                <form id="delete-<?php echo e($department->id); ?>" action="<?php echo e(route('admin.department.destroy',$department->id)); ?>" method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>            
                <tr>
                    <td colspan="4" class="" style="color:red;text-align:center;">No Departments Found.....</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
<script>
    function deleteRecord(id){
        // alert(id)
        let  choice = confirm("Are you sure? You want to delete the record Pamanently?");
        if(choice){
            document.getElementById('delete-'+id).submit();
        }
    }
    $(document).ready(function() {
        $('#listall').DataTable();
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
    {}
    else
    {
        return false;
    }
}   
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/departments/index.blade.php ENDPATH**/ ?>