<?php
use App\Division;
use App\UserLogin;
?>


<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Inclusion/Exclusion System</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(Session::get('user_sub_typeSession') == 5): ?>
    <div class="alert alert-danger text-center my-5">
        🚫 You don’t have permission to access this page.
    </div>
<?php else: ?>

<style>
    .nav-tabs .nav-link.active { background-color: #0d6efd; color: #fff; }
    .table thead th { text-align:center; vertical-align:middle; }
    .table td { vertical-align: middle; text-align:center; }
    .badge { font-size: 0.85rem; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-truck"></i> SILO Tanker Management</h4>
    <?php if(Session::get('user_typeSession') ==2): ?>
    <a href="<?php echo e(route('vendor_silo.create', ['user_id' => $id])); ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-plus-circle"></i> Add New
    </a>
    <?php endif; ?>
</div>
<style>
    /* Make table header sticky */
    .table-responsive {
        max-height: 450px; /* scrollable height */
        overflow-y: auto;
    }

    table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa; /* Light gray background */
        box-shadow: 0 2px 2px rgba(0, 0, 0, 0.05);
    }

    /* Optional: make Inclusion/Exclusion tabs fixed while scrolling */
    .sticky-tabs {
        position: sticky;
        top: 0;
        z-index: 20;
        background: #fff;
        padding-top: 5px;
    }
</style>

<?php if(session('message')): ?>
    <div class="alert alert-success text-center"><?php echo e(session('message')); ?></div>
<?php endif; ?>

<?php if(Session::get('user_typeSession') == 2): ?>
    <?php
    $inclusionLists = $vms_lists->where('return_status', '')->values();
    $exclusionLists = $vms_lists->where('return_status', '!=', '')->values();
?>
<style>
div.dt-buttons {
    float: right;
    margin-bottom: 10px;
}
</style>

<div class="card mt-3">
    <div class="card-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="inclusion-tab" data-bs-toggle="tab" data-bs-target="#inclusion" type="button" role="tab">
                    🚛 Inclusion (<?php echo e($inclusionLists->count()); ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exclusion-tab" data-bs-toggle="tab" data-bs-target="#exclusion" type="button" role="tab">
                    ❌ Exclusion (<?php echo e($exclusionLists->count()); ?>)
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="vendorTabsContent">

            <!-- Inclusion Tab -->
            <div class="tab-pane fade show active" id="inclusion" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm align-middle" id="inclusionTable">
                        <thead class="table-light">
                            <tr>
                                <th> Sl. No</th>
                                <th> Work-Order No</th>
                                <th> Vehicle Registration No</th>
                                <th> Status</th>
                                <th> Created</th>
                                <th> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $inclusionLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                                    <td><?php echo e($list->vehicle_registration_no ?? '-'); ?></td>
                                    <td>
                                        <?php switch($list->status):
                                            case ('approve'): ?>
                                                <span class="blink-arrow text-success">➡</span>
                                                <span class="badge bg-success">Active</span>
                                                <?php break; ?>
                                            <?php case ('pending_with_inclusion_user'): ?>
                                                <span class="badge bg-info text-dark">Pending For Inclusion User</span>
                                                <?php break; ?>
                                            <?php case ('pending_with_safety'): ?>
                                                <span class="badge bg-info text-white">Pending For Safety</span>
                                                <?php break; ?>
                                            <?php case ('draft'): ?>
                                                <span class="badge bg-secondary text-white">Draft</span>
                                                <?php break; ?>
                                                <?php case ('pending_with_operation_dept'): ?>
                                                 <span class="badge bg-info text-dark">Pending For Operation Dept</span>
                                                 <?php break; ?>
                                                  <?php case ('return'): ?>
                                                 <span class="badge bg-warning text-dark">Returned</span>
                                                 <?php break; ?> 
                                                 <?php case ('reject'): ?>
                                                <span class="badge bg-danger  text-white">Rejected</span>
                                                <?php break; ?>
                                                <?php case ('pending_with_safety_training'): ?>
                                                 <span class="badge bg-info text-dark">Pending For Safety Dept</span>
                                                 <?php break; ?>
                                                 <?php case ('return_by_safety'): ?>
                                                 <span class="badge bg-warning text-dark">Returned</span>
                                                 <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-light text-dark"><?php echo e(ucfirst($list->status ?? 'Unknown')); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><?php echo e($list->created_datetime); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>" class="btn btn-sm btn-primary">Details</a>

                                        <?php if($list->status == 'draft' || $list->status == 'return' || $list->status  == 'return_by_safety'): ?>
                                            <a href="<?php echo e(route('vendor_silo.edit', $list->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <?php endif; ?>

                                        <?php if($list->created_by == Session::get('user_idSession') && 
                                            ($list->status == 'approve'  && ($list->return_status == '' || $list->return_status == 'return'))): ?>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-id="<?php echo e($list->id); ?>" data-sl="<?php echo e($list->vehicle_registration_no); ?>" 
                                                data-bs-toggle="modal" data-bs-target="#exclusionModal">Exclude</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="6" class="text-center text-muted">No Inclusion Records</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Exclusion Tab -->
            <div class="tab-pane fade" id="exclusion" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm align-middle" id="exclusionTable">
                        <thead class="table-light">
                            <tr>
                                <th>Sl. No</th>
                                <th>Work-Order No</th>
                                <th> Vehicle Registration No</th>
                                <th> Return Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $exclusionLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                                    <td><?php echo e($list->vehicle_registration_no ?? '-'); ?></td>
                                    <td>
                                        <?php switch($list->return_status):
                                            case ('approve'): ?>
                                                <span class="badge bg-danger text-white">Excluded</span>
                                                
                                                <?php break; ?>
                                            <?php case ('pending_with_inclusion_user'): ?>
                                                <span class="badge bg-warning text-dark">Pending With Exclusion User </span>
                                                <?php break; ?>
                                            <?php case ('pending_with_safety'): ?>
                                                <span class="badge bg-info text-white">Pending With Safety</span>
                                                <?php break; ?>
                                            <?php case ('reject'): ?>
                                                <span class="badge bg-secondary text-white">Rejected</span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-light text-dark"><?php echo e(ucfirst($list->return_status ?? 'Unknown')); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td><?php echo e($list->created_datetime); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>" class="btn btn-sm btn-primary">Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="6" class="text-center text-muted">No Exclusion Records</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>




<?php else: ?>
<style>
/* --- Base Tabs --- */
.nav-tabs {
    border-bottom: 2px solid #e9ecef;
    gap: 6px;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    border-radius: 8px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.nav-tabs .nav-link:hover {
    background-color: #e9ecef;
    color: #0d6efd;
}

/* --- Default Active Tab --- */
.nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: #fff;
    border: 2px solid #0d6efd;
    font-weight: 600;
}

/* --- Highlighted Active (New Offer look) --- */
.nav-tabs .nav-link#active-tab {
    position: relative;
    font-weight: 700;
    background: linear-gradient(90deg, #007bff, #6610f2);
    color: #fff;
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 18px rgba(13, 110, 253, 0.5);
    animation: softBounce 2s infinite ease-in-out;
    transform-origin: center;
}

/* --- Soft Bounce Animation --- */
@keyframes softBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

/* --- Animated Gradient Text --- */
.nav-tabs .nav-link#active-tab span {
    background: linear-gradient(270deg, #fff, #ffc107, #ff5722, #ffffff);
    background-size: 400% 400%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradientFlow 4s ease infinite;
}

/* --- Text Gradient Animation --- */
@keyframes gradientFlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* --- “NEW!” Badge with Glow --- */
.nav-tabs .nav-link#active-tab::after {
    content: "NEW!";
    position: absolute;
    top: -10px;
    right: -12px;
    background: #ffc107;
    color: #000;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 6px;
    box-shadow: 0 0 8px rgba(255, 193, 7, 0.6);
    animation: pulseGlow 1.8s infinite ease-in-out;
}

/* --- Glow for Badge --- */
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 5px rgba(255, 193, 7, 0.7); }
    50% { box-shadow: 0 0 12px rgba(255, 193, 7, 1); }
}

/* --- Responsive Adjustments --- */
@media (max-width: 768px) {
    .nav-tabs {
        flex-wrap: wrap;
    }
    .nav-tabs .nav-link {
        width: 100%;
        text-align: center;
        margin-bottom: 5px;
    }
}
</style>

<ul class="nav nav-tabs mb-3" id="vendorTabs" role="tablist">
    <?php if(Session::get('user_sub_typeSession') == 3): ?>
    <li class="nav-item">
        <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#allTab">
            All (<?php echo e(count($vms_lists)); ?>)
        </button>
    </li>
    <?php endif; ?>

    <li class="nav-item">
        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pendingTab">
            Pending With Me (<?php echo e(count($vms_pendinglists)); ?>)
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#activeTab">
            <span>Active (<?php echo e(count($vms_activelists)); ?>)</span>
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" id="inactive-tab" data-bs-toggle="tab" data-bs-target="#inactiveTab">
            Inactive (<?php echo e(count($vms_inactivelists)); ?>)
        </button>
    </li>
</ul>




<div class="tab-content">

    <!-- All -->
    <div class="tab-pane fade" id="allTab">
        <div class="card card-body table-responsive">
            <table id="allTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                       
                        <th>Vehicle No</th>
                        
                        <th>Work Order No</th>
                        <th>Status</th>
                       
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $vms_lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($list->division_name ?? '-'); ?></td>
                            <td><?php echo e($list->vendor_name ?? '-'); ?></td>
                            <td><?php echo e($list->vehicle_registration_no ?? '-'); ?></td>
                            <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                            <td>
                                <?php if($list->return_status == ''): ?>
                                <?php switch($list->status):
                                    case ('approve'): ?> <span class="badge bg-success">Approved</span> <?php break; ?>
                                    <?php case ('inactive'): ?> <span class="badge bg-danger">Inactive</span> <?php break; ?>
                                    <?php case ('pending_with_operation_dept'): ?> <span class="badge bg-info">Pending For Operation Dept</span> <?php break; ?>
                                    <?php case ('pending_with_safety_training'): ?> <span class="badge bg-info">Pending For Safety Dept</span> <?php break; ?>
                                     <?php case ('pending_with_inclusion_user'): ?> <span class="badge bg-info">Pending For Inclusion User</span> <?php break; ?>
                                    <?php default: ?> <span class="badge bg-warning text-dark"><?php echo e(ucfirst($list->status ?? '-')); ?></span>
                                <?php endswitch; ?>
                                <?php else: ?>
                                <?php switch($list->return_status):
                                    case ('approve'): ?>
    <span class="badge bg-danger text-white">Excluded</span>
<?php break; ?>
        
                                    <?php default: ?> <span class="badge bg-warning text-dark"><?php echo e(ucfirst($list->status ?? '-')); ?></span>
                                <?php endswitch; ?>
                                <?php endif; ?>
                            </td>
                           
                            <td><?php echo e($list->created_datetime ?? '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>" class="btn btn-sm btn-primary">View</a>
                                <?php if($list->status == 'draft' && $list->created_by == Session::get('user_idSession')): ?>
                                    <a href="<?php echo e(route('vendor_silo.edit', $list->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>No records found</tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending -->
    <div class="tab-pane fade show active" id="pendingTab">
        <div class="card card-body table-responsive">
            <table id="pendingTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                        <th>Vehicle No</th>
            
                        <th>Work Order No</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $vms_pendinglists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($list->division_name ?? '-'); ?></td>
                            <td><?php echo e($list->vendor_name ?? '-'); ?></td>
                            <td><?php echo e($list->vehicle_registration_no ?? '-'); ?></td>
                          
                            <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                            <?php if($list->return_status == ''): ?>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <?php else: ?>
                             <td><span class="badge bg-warning text-dark">Pending (Exclusion)</span></td>
                             <?php endif; ?>
                            <td><?php echo e($list->created_datetime ?? '-'); ?></td>
                            <td><a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>No pending records</tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active -->
    <div class="tab-pane fade" id="activeTab">
        <div class="card card-body table-responsive">
            <table id="activeTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                       
                        <th>Vehicle No</th>
                        
                        <th>Work Order No</th>
                     
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $vms_activelists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($list->division_name ?? '-'); ?></td>
                            <td><?php echo e($list->vendor_name ?? '-'); ?></td>
                        
                            <td><?php echo e($list->vehicle_registration_no ?? '-'); ?></td>
                            
                            <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                          
                            <td><?php echo e($list->created_datetime ?? '-'); ?></td>
                            <td>
                                <a href="<?php echo e(route('vendor_silo.edit_entry', $list->id)); ?>" class="btn btn-sm btn-primary">View</a>
                                
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>No active records</tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inactive -->
    <div class="tab-pane fade" id="inactiveTab">
        <div class="card card-body table-responsive">
            <table id="inactiveTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                     
                        <th>Vehicle No</th>
                     
                        <th>Work Order No</th>
                      
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $vms_inactivelists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($list->division_name ?? '-'); ?></td>
                            <td><?php echo e($list->vendor_name ?? '-'); ?></td>
                         
                            <td><?php echo e($list->vehicle_registration_no ?? '-'); ?></td>
                          
                            <td><?php echo e($list->work_order_no ?? '-'); ?></td>
                          
                            <td><?php echo e($list->created_datetime ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>No inactive records</tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

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
                        <label class="form-label">Vehicle Number</label>
                        <input type="text" class="form-control" id="sl" name="tanker_no" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Exclusion</label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitExclusion" class="btn btn-danger">
                        <span class="btn-text">Submit Exclusion</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- ✅ DataTables Buttons Extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(function() {
    // Initialize DataTables with Excel export button
    $('#inclusionTable,#exclusionTable,#vendorTable,#allTable,#pendingTable,#activeTable,#inactiveTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Export Excel',
                className: 'btn btn-success btn-sm',
                title: 'Vendor_SILO_Report',
                exportOptions: { columns: ':visible' }
            }
        ]
    });

    // Modal handling for Exclusion
    $('#exclusionModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        $('#tanker_id').val(button.data('id'));
        $('#sl').val(button.data('sl'));
    });

    const $form = $("#exclusionForm");
    const $button = $("#submitExclusion");
    const $spin = $button.find(".spinner-border");
    const $text = $button.find(".btn-text");

    function setLoading(state) {
        $button.prop("disabled", state);
        $spin.toggleClass("d-none", !state);
        $text.text(state ? "Processing..." : "Submit Exclusion");
    }

    $form.on("submit", function (e) {
        e.preventDefault();
        setLoading(true);

        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") } });

        $.post("<?php echo e(route('vendor_silo.returnsilo')); ?>", $form.serialize())
            .done(() => {
                $("#formAlert").removeClass("d-none alert-danger").addClass("alert alert-success")
                    .text("Tanker excluded successfully!");
                setTimeout(() => location.reload(), 1000);
            })
            .fail(xhr => {
                $("#formAlert").removeClass("d-none alert-success").addClass("alert alert-danger")
                    .text(xhr.responseJSON?.message || "Something went wrong.");
            })
            .always(() => setLoading(false));
    });
});
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_silo/index.blade.php ENDPATH**/ ?>