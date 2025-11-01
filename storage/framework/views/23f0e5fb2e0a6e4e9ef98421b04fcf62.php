<?php
use App\Division;
use App\UserLogin;
?>


<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vendor SILO Tanker Management / List</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php if(Session::get('user_sub_typeSession') == 5): ?>
        
        <div class="alert alert-danger text-center my-5">
            🚫 You don’t have permission to access this page.
        </div>
    <?php else: ?>
        <style>
            table.dataTable th {
                white-space: nowrap;
                /* Prevent line break */
                text-align: center;
                /* Optional: center align */
                vertical-align: middle;
            }
        </style>
        <style>
            .modal-content {
                border-radius: 8px;
                border: none;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .modal-header {
                background-color: #dc3545;
                color: white;
                border-radius: 8px 8px 0 0;
            }

            .form-label {
                font-weight: 500;
                color: #333;
            }

            .form-control {
                border-radius: 4px;
                border: 1px solid #ddd;
            }

            .form-control:focus {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }

            .btn {
                border-radius: 4px;
            }

            .btn-xs {
                padding: 0.15rem 0.4rem;
                font-size: 0.75rem;
                border-radius: 0.2rem;
            }

            .blink-arrow {
                color: green;
                font-size: 14px;
                font-weight: bold;
                animation: blinkArrow 1s infinite;
            }

            .blink-arrow1 {
                color: red;
                font-size: 14px;
                font-weight: bold;
                animation: blinkArrow 1s infinite;
            }

            @keyframes blinkArrow {
                0% {
                    opacity: 1;
                    transform: translateX(0);
                }

                50% {
                    opacity: 0;
                    transform: translateX(3px);
                }

                100% {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
        </style>
        <style>
            .blink-arrow {
                font-weight: bold;
                animation: arrowBlink 1s infinite;
                margin-right: 5px;
                font-size: 1.2rem;
            }

            @keyframes arrowBlink {
                50% {
                    opacity: 0;
                }
            }
        </style>


        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 mb-4">
                <i class="fas fa-truck"></i> Vendor SILO Tanker Management
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('vendor_silo.create', ['user_id' => $id])); ?>"
                    class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn"
                    id="uploadBtn">
                    <i class="fas fa-upload me-2 upload-icon" id="uploadIcon"></i>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>&nbsp;
                    <span id="uploadText">Create</span>
                </a>
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



        <div class="card mt-3">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="vendorTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#activeList"
                            type="button" role="tab" aria-controls="activeList" aria-selected="true">
                            ✅ Active Tankers
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inactive-tab" data-bs-toggle="tab" data-bs-target="#inactiveList"
                            type="button" role="tab" aria-controls="inactiveList" aria-selected="false">
                            ❌ Inactive Tankers
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="vendorTabContent">
                    <!-- Active List -->
                    <div class="tab-pane fade show active" id="activeList" role="tabpanel" aria-labelledby="active-tab">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="activeTable">
                                <thead>
                                    <tr>
                                        <th>🔢 Sl. No</th>
                                        <th>🏭 Vendor Name</th>
                                        <th>🏬 Division</th>
                                        <th>🏷️ Section</th>
                                        <th>📄 Work-Order No</th>
                                        <th>📊 Status</th>
                                        <th>🕒 Created</th>
                                        <th>⚙️ Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $vms_lists->where('return_status', ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($list->vendor_name); ?></td>
                                            <td><?php echo e($list->division_name); ?></td>
                                            <td><?php echo e($list->section); ?></td>
                                            <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                                            <td>
                                                <?php if($list->status == 'approve'): ?>
                                                    <?php if($list->status == 'approve'): ?>
                                                        <span class="blink-arrow text-success">➡</span>
                                                        <span class="badge bg-success" style="color:white">Active</span>
                                                    <?php else: ?>
                                                        <span class="blink-arrow text-danger">➡</span>
                                                        <span class="badge bg-danger" style="color:white">Inactive</span>
                                                    <?php endif; ?>
                                                <?php elseif($list->status == 'pending_with_inclusion_user'): ?>
                                                    <span class="badge bg-success" style="color:white">Pending With Inclusion
                                                        User</span>
                                                <?php elseif($list->status == 'pending_with_safety'): ?>
                                                    <span class="badge bg-success" style="color:white">Pending With Safety</span>


                                                <?php endif; ?>
                                            </td>

                                            <td><?php echo e($list->created_datetime); ?></td>


                                            <td>
                                                <a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>"
                                                    class="btn btn-sm btn-primary">Details</a>

                                                <?php if($list->status == 'draft'): ?>
                                                    <a href="<?php echo e(route('vendor_silo.edit', $list->id)); ?>"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                <?php endif; ?>

                                                <?php if($list->created_by == Session::get('user_idSession') && $list->return_status == ''): ?>
                                                    <button type="button" class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                        data-id="<?php echo e($list->id); ?>" data-sl="<?php echo e($list->full_sl); ?>" data-bs-toggle="modal"
                                                        data-bs-target="#exclusionModal">
                                                        Exclude Silo Tanker
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No active records</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Inactive List -->
                    <div class="tab-pane fade" id="inactiveList" role="tabpanel" aria-labelledby="inactive-tab">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="inactiveTable">
                                <thead>
                                    <tr>
                                        <th>🔢 Sl. No</th>
                                        <th>🏭 Vendor Name</th>
                                        <th>🏬 Division</th>
                                        <th>🏷️ Section</th>
                                        <th>📄 Work-Order No</th>
                                        <th>📊 Status</th>
                                        <th>🕒 Created</th>
                                        <th>⚙️ Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $vms_lists->where('return_status', '!=', ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($key + 1); ?></td>
                                            <td><?php echo e($list->vendor_name); ?></td>
                                            <td><?php echo e($list->division_name); ?></td>
                                            <td><?php echo e($list->section); ?></td>
                                            <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                                            <td>
                                                <?php if($list->status == 'approve'): ?>
                                                    <?php if($list->status == 'approve' && $list->return_status == ''): ?>
                                                        <span class="blink-arrow text-success">➡</span>
                                                        <span class="badge bg-success" style="color:white">Active</span>
                                                    <?php else: ?>
                                                        <span class="blink-arrow text-danger">➡</span>
                                                        <span class="badge bg-danger" style="color:white">Inactive</span>
                                                    <?php endif; ?>
                                                <?php elseif($list->status == 'pending_with_inclusion_user'): ?>
                                                    <span class="badge bg-warning" style="color:white">Pending With Inclusion
                                                        User</span>
                                                <?php elseif($list->status == 'pending_with_safety'): ?>
                                                    <span class="badge bg-success" style="color:white">Pending With Safety
                                                        Department</span>
                                                <?php endif; ?>
                                            </td>


                                            <td><?php echo e($list->created_datetime); ?></td>

                                            <td>
                                                <a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>"
                                                    class="btn btn-sm btn-primary">Details</a>

                                                <?php if($list->status == 'draft'): ?>
                                                    <a href="<?php echo e(route('vendor_silo.edit', $list->id)); ?>"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                <?php endif; ?>


                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No inactive records</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        
        <div id="data-loader" class="text-center my-4">
            <div class="spin-loader"></div>

        </div>


        <!-- Modal -->
        <div class="modal fade" id="exclusionModal" tabindex="-1" aria-labelledby="exclusionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Exclude Silo Tanker</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="exclusionForm">
                        <div class="modal-body">
                            <div class="alert d-none" id="formAlert"></div>
                            <input type="hidden" name="id" id="tanker_id">
                            <div class="mb-3">
                                <label class="form-label">Tanker SL Number</label>
                                <input type="text" class="form-control" name="tanker_no" id="sl" required
                                    placeholder="e.g., ST-001" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Reason for Exclusion</label>
                                <textarea class="form-control" name="reason" rows="3" required
                                    placeholder="Enter reason..."></textarea>
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="submitExclusion" class="btn btn-danger">
                                <span class="btn-text">Submit Exclusion</span>
                                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php $__env->startSection('scripts'); ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#exclusionModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);   // Button that triggered modal
                let tankerId = button.data('id');
                let sl = button.data('sl');    // Extract info from data-* attribute
                $('#tanker_id').val(tankerId);         // Set hidden input value
                $('#sl').val(sl);
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#activeTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });

            $('#inactiveTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });
        });
    </script>




    <script>
        $(function () {
            const $form = $("#exclusionForm");
            const $button = $("#submitExclusion");
            const $spin = $button.find(".spinner-border");
            const $text = $button.find(".btn-text");

            // When modal opens → copy tanker_id from button
            $('#exclusionModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let tankerId = button.data('id');
                $('#tanker_id').val(tankerId);
            });

            function setLoading(state) {
                if (state) {
                    $button.prop("disabled", true);
                    $spin.removeClass("d-none");
                    $text.text("Processing...");
                } else {
                    $button.prop("disabled", false);
                    $spin.addClass("d-none");
                    $text.text("Submit Exclusion");
                }
            }

            $form.on("submit", function (e) {
                e.preventDefault();
                setLoading(true);

                $.ajaxSetup({
                    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") }
                });

                $.ajax({
                    url: "<?php echo e(route('vendor_silo.returnsilo')); ?>",
                    type: "POST",
                    data: $form.serialize(),
                    success: function (res) {
                        $("#formAlert")
                            .removeClass("d-none alert-danger")
                            .addClass("alert alert-success")
                            .text("Tanker excluded successfully!");

                        setTimeout(() => {
                            bootstrap.Modal.getInstance(document.getElementById("exclusionModal")).hide();
                            $form[0].reset();
                            $("#formAlert").addClass("d-none");
                        }, 1200);
                    },
                    error: function (xhr) {
                        $("#formAlert")
                            .removeClass("d-none alert-success")
                            .addClass("alert alert-danger")
                            .text(xhr.responseJSON?.message || "Something went wrong.");
                    },
                    complete: function () {
                        setLoading(false);
                    }
                });
            });
        });
    </script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_silo/index.blade.php ENDPATH**/ ?>