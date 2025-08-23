
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Skill</a></li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    return redirect('admin/skill');
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Skills</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('admin.skill.create')); ?>" class="btn btn-sm btn-outline-secondary">Add Skill </a>
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
                        <th>Skill Name</th>
                        <th>Skill Rate</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($skills->count() > 0): ?>
                        <?php        $count = 1; ?>
                        <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ?>
                            <tr>
                                <td><?php echo e($count++); ?></td>
                                <td><?php echo e($skill->skill_name); ?></td>
                                <td><?php echo e(@$skill->skill_rate); ?> </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.skill.edit', \Crypt::encrypt($skill->id))); ?>"
                                        title="Edit">Edit</a> |
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($skill->id); ?>')">Delete</a>
                                    <form id="delete-<?php echo e($skill->id); ?>" action="<?php echo e(route('admin.skill.destroy', $skill->id)); ?>"
                                        method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="" style="color:red;text-align:center;">No Skill Found.....</td>
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
        function deleteRecord(id) {
            // alert(id)
            let choice = confirm("Are you sure? You want to delete the record Pamanently?");
            if (choice) {
                document.getElementById('delete-' + id).submit();
            }
        }
        $(document).ready(function () {
            $('#listall').DataTable();
        });


    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/skill/index.blade.php ENDPATH**/ ?>