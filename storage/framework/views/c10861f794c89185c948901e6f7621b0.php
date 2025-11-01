

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Silo Fields</li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    <script>window.location.href = "<?php echo e(url('admin/dashboard')); ?>";</script>
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Silo Fields</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('admin.silo_master.create')); ?>" class="btn btn-sm btn-outline-secondary">+ Add New</a>
            </div>
        </div>

        <!-- Success Message -->
        <div class="form-group-row">
            <div class="col-sm-12 text-center">
                <?php if(session()->has('message')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-sm" id="listall">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Field Name</th>
                        <th>Label</th>
                        <th>Type</th>
                        <th>Required</th>
                        <th>Active</th>
                        <th>Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($silo_master->count() > 0): ?>
                        <?php $count = 1; ?>
                        <?php $__currentLoopData = $silo_master; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($count++); ?></td>
                                <td><?php echo e($field->name); ?></td>
                                <td><?php echo e($field->label); ?></td>
                                <td><?php echo e(ucfirst($field->type)); ?></td>
                                <td>
                                    <?php if($field->isrequired): ?>
                                        <span class="badge bg-success">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($field->isactive): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($field->displayorder); ?></td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="<?php echo e(route('admin.silo_master.edit', \Crypt::encrypt($field->id))); ?>">Edit</a>
                                    |
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($field->id); ?>')">Delete</a>

                                    <form id="delete-<?php echo e($field->id); ?>" action="<?php echo e(route('admin.silo_master.destroy', $field->id)); ?>"
                                        method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-danger">No Silo Field Found...</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        function deleteRecord(id) {
            if (confirm("Are you sure you want to delete this record permanently?")) {
                document.getElementById('delete-' + id).submit();
            }
        }

        $(document).ready(function () {
            $('#listall').DataTable();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/silo_master/index.blade.php ENDPATH**/ ?>