<?php
use App\Division;
use App\UserLogin;
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List of Vendor PF Documents</a></li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 5): ?>
    return redirect('admin/dashboard');
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Vendor PF Challan & ECR</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('admin.vendor_pf_details.create')); ?>"
                    class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn"
                    id="uploadBtn">
                    <i class="fas fa-upload me-2 upload-icon" id="uploadIcon"></i>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>&nbsp;
                    <span id="uploadText"> Upload Document's</span>
                </a>
                <style>
                    .upload-btn i {
                        transition: all 0.3s ease;
                    }
                </style>


                <style>
                    .upload-btn:hover .upload-icon {
                        animation: bounceUpload 0.6s;
                    }

                    @keyframes bounceUpload {
                        0% {
                            transform: translateY(0);
                        }

                        30% {
                            transform: translateY(-5px);
                        }

                        60% {
                            transform: translateY(2px);
                        }

                        100% {
                            transform: translateY(0);
                        }
                    }
                </style>


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

        <div class="table-responsive ">
            <table class="table table-striped table-sm" id="listall">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Vendor Name</th>
                        <th>Wage Month</th>
                        <th>PF Challan</th>
                        <th>ECR</th>
                        <th>Status</th>
                        <th>Uploaded Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($pf_lists->count() > 0): ?>
                        <?php        $count = 1; ?>
                        <?php $__currentLoopData = $pf_lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pf_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $vendorname = UserLogin::where('id', $pf_list->vendor_id)->first();

                                // Determine the status based on the value in 'status'
                                if ($pf_list->status == 'Pending_with_hr') {
                                    $status = '<i class="fas fa-user-clock text-warning me-1"></i> Pending With HR Team';
                                } elseif ($pf_list->status == 'completed') {
                                    $status = '<i class="fas fa-check-circle text-success me-1"></i> Completed';
                                } elseif ($pf_list->status == 'reject') {
                                    $status = '<i class="fas fa-times-circle text-danger me-1"></i> Rejected';
                                } else {
                                    $status = '<i class="fas fa-exclamation-circle text-danger me-1"></i> Something Went Wrong';
                                }

                            ?>

                            <tr style="white-space: nowrap;">
                                <td><?php echo e($count++); ?></td>
                                <td><?php echo e($vendorname->name); ?></td>
                                <td><?php echo e(date('F-Y ', strtotime($pf_list->month))); ?></td>
                                <td><a href="../<?php echo e($pf_list->pf_challan); ?>" target="_blank" class="btn btn-success btn-sm">View /
                                        Download</a>
                                </td>
                                <td><a href="../<?php echo e($pf_list->ecr); ?>" target="_blank" class="btn btn-success btn-sm">View /
                                        Download</a>
                                </td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo e(date('d-m-Y H:i:s', strtotime(@$pf_list->created_date))); ?> </td>
                                <td>
                                    <a href="<?php echo e(route('admin.vendor_pf_details.edit', \Crypt::encrypt($pf_list->id))); ?>"
                                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="View Full Details">
                                        <i class="fas fa-eye me-1"></i> &nbsp;Details
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align:center; padding: 20px;">
                                <div
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: #dc3545;">
                                    <!-- SVG Icon (a simple warning or search icon) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 0 24 24" width="48"
                                        fill="#dc3545">
                                        <path d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     6.5 6.5 0 109.5 16c1.61 0 3.09-.59 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     4.23-1.57l.27.28v.79l5 4.99L20.49 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     19l-4.99-5zm-6 0C8.01 14 6 11.99 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     6 9.5S8.01 5 10.5 5 15 7.01 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     15 9.5 12.99 14 10.5 14z" />
                                    </svg>
                                    <!-- Message -->
                                    <span style="margin-top: 10px; font-size: 16px;">No Data Found</span>
                                </div>
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="row p-2">
            <div class="col-sm-12">
                
            </div>
        </div>

    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('scripts'); ?>
        <script>var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#listall').DataTable({
                    // Optional configuration here
                    // Search is enabled by default
                    "paging": true,
                    "ordering": true,
                    "info": true
                });
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php endif; ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_pf_challan/index.blade.php ENDPATH**/ ?>