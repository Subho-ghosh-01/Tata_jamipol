
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Division</a></li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">List of Division</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?php echo e(route('admin.division.create')); ?>" class="btn btn-sm btn-outline-secondary">Add Division </a>
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
    <div class="table-responsive">
        <table class="table table-striped table-sm" id="listall">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Action</th>
                 </tr>
            </thead>
            <tbody>
                <?php if($divisions->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <tr>
                            <td><?php echo e($count++); ?></td>
                            <td><?php echo e($division->name); ?></td>
                            <td><?php echo e($division->abbreviation); ?></td>
                            <td>
                            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.division.edit',\Crypt::encrypt($division->id))); ?>" title="Edit">Edit</a> |
                            <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($division->id); ?>')">Delete</a>
                                <form id="delete-<?php echo e($division->id); ?>" action="<?php echo e(route('admin.division.destroy',$division->id)); ?>" method="POST" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>            
                <tr>
                    <td colspan="6" class="" style="color:red;text-align:center;">No Division Found.....</td>
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
        let  choice = confirm("Are you sure want to delete the record Pamanently?");
        if(choice){
            document.getElementById('delete-'+id).submit();
        }
    }
    $(document).ready(function() {
        $('#listall').DataTable();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/divisions/index.blade.php ENDPATH**/ ?>