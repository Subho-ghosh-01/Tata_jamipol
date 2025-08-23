<?php 
use App\Division;
use App\Department;
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Work Orders</a></li>
<?php $__env->stopSection(); ?>

<!-- breadcrumbs end -->
<?php if(Session::get('user_sub_typeSession') == 2): ?>
    return redirect('admin/dashboard');
<?php else: ?>
    <!-- Content start section -->
    <?php $__env->startSection('content'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Work Order</h1>
            <div class="row mx-md-n4">
                <div class="col px-md-2">
                    <div class="p-3 bg-light"><a href="<?php echo e(route('admin.work_order_view')); ?>"
                            class="btn btn-sm btn-outline-secondary">Upload Work Order</a></div>
                </div>
                <div class="col px-md-2">
                    <div class="p-3 bg-light"><a href="<?php echo e(route('admin.work-order.create')); ?>"
                            class="btn btn-sm btn-outline-secondary">Add Work Order</a></div>
                </div>
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
            <table class="table table-striped table-sm" id="ListAll">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Vendor Code</th>
                        <th>Order Code</th>
                        <th>Order Validity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($workorders->count() > 0): ?>
                        <?php        $count = 1;?>
                        <?php $__currentLoopData = $workorders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workorder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($count++); ?></td>
                                <td><?php echo e($workorder->vendor_code); ?></td>
                                <td><?php echo e($workorder->order_code); ?></td>
                                <td><?php echo e($workorder->order_validity); ?></td>
                                <td><a class="btn btn-info btn-sm"
                                        href="<?php echo e(route('admin.work-order.edit', \Crypt::encrypt($workorder->id))); ?>">Edit</a>
                                    <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($workorder->id); ?>')">Delete</a>
                                    <form id="delete-<?php echo e($workorder->id); ?>"
                                        action="<?php echo e(route('admin.work-order.destroy', $workorder->id)); ?>" method="POST"
                                        style="display: none;">
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

<?php endif; ?>


<?php $__env->startSection('scripts'); ?>
    <script>
        function deleteRecord(id) {
            // alert(id)
            let choice = confirm("Are you sure want to delete the record permanently?");
            if (choice) {
                document.getElementById('delete-' + id).submit();
            }
        }

        $(document).ready(function () {
            $('#ListAll').DataTable();
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>