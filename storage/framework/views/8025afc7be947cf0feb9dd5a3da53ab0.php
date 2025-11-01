<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered shadow-sm rounded vmsDataTable">
        <thead class="table">
            <tr>
                <th style="white-space: nowrap;">#️⃣</th>
                <th style="white-space: nowrap;">📄 Full Sl.</th>
                <th style="white-space: nowrap;">👤 Owner</th>
                <th style="white-space: nowrap;">🚘 Reg. No</th>
                <th style="white-space: nowrap;">🚗 Vehicle Type</th>
                <th style="white-space: nowrap;">📌 Status</th>
                <th style="white-space: nowrap;">🗓 Applied On</th>
                <th style="white-space: nowrap;">⚙ Action</th>
            </tr>
        </thead>
        <tbody class="text-nowrap">
            <?php $__currentLoopData = $vmsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($item->full_sl); ?></td>
                    <td><?php echo e($item->vehicle_owner_name); ?></td>
                    <td><?php echo e($item->vehicle_registration_no); ?></td>
                    <td><?php echo e(ucfirst($item->vehicle_type)); ?></td>
                    <td>
                        <?php if(strtolower($item->status) == 'approve'): ?>
                            <span style="white-space: nowrap; display: inline-flex; align-items: center; gap: 5px;">
                                ✅ Approve
                                <button class="btn btn-info open-vehicle rounded-circle" data-id="<?php echo e($item->id); ?>"
                                    style="width:24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center; font-size:14px;">
                                    <i class="fas fa-info"></i>
                                </button>
                            </span>
                        <?php elseif($item->status && strtolower($item->status) == 'pending_with_safety'): ?>
                            <span style="white-space: nowrap; display: inline-flex; align-items: center; gap: 5px;">
                                ⚠️ Pending for Safety
                                <button class="btn btn-info open-vehicle rounded-circle" data-id="<?php echo e($item->id); ?>"
                                    style="width:24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center; font-size:14px;">
                                    <i class="fas fa-info"></i>
                                </button>
                            </span>
                        <?php elseif($item->status && strtolower($item->status) == 'return'): ?>
                            <span style="white-space: nowrap; display: inline-flex; align-items: center; gap: 5px;">
                                ❌ Rejected By Safety
                                <button class="btn btn-info open-vehicle rounded-circle" data-id="<?php echo e($item->id); ?>"
                                    style="width:24px; height:24px; padding:0; display:flex; align-items:center; justify-content:center; font-size:14px;">
                                    <i class="fas fa-info"></i>
                                </button>
                            </span>
                        <?php endif; ?>






                    </td>
                    <td><?php echo e($item->created_at); ?></td>
                    <td class="text-nowrap">
                        <?php
                            // Determine action button
                            if ($item->status && strtolower($item->status) == 'pending_with_safety' && Session::get('clm_role') == 'Safety_dept') {
                                $actionLabel = '⏳ Action';
                                $actionClass = 'btn-warning';
                            } elseif ($item->status && strtolower($item->status) == 'approve') {
                                $actionLabel = 'Details';
                                $actionClass = 'btn-info';
                            } elseif ($item->status && strtolower($item->status) == 'return' && $item->created_by == Session::get('user_idSession')) {
                                $actionLabel = 'Edit';
                                $actionClass = 'btn-info';
                            } else {
                                $actionLabel = 'Details';
                                $actionClass = 'btn-primary';
                            }
                        ?>

                        <a href="<?php echo e(url('/vms/' . $item->id . '/edit')); ?>"
                            class="btn btn-sm rounded-pill px-3 ms-2 <?php echo e($actionClass); ?>">
                            <?php echo e($actionLabel); ?>

                        </a>

                        <?php if(strtolower($item->status) == 'approve' && (!$item->return_status) && Session::get('user_idSession') == $item->created_by): ?>
                            <button class="btn btn-danger btn-sm rounded-pill px-3 ms-2" data-bs-toggle="modal"
                                data-bs-target="#returnPassModal" data-id="<?php echo e($item->id); ?>">
                                🔁 Surrender
                            </button>
                            <a href="/vms/edit_driver_details/<?php echo e($item->id); ?>">
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3 ms-2">
                                    Edit Driver Details
                                </button>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<style>
    .open-vehicle:hover {
        transform: scale(1.2);
        transition: transform 0.2s;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }
</style>
<div class="modal fade vehicleModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Vehicle Validity Details</h5>
                <button type="button" class="btn-close close-vehicle" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body vehicle-modal-body">
                <div class="text-center">Loading...</div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-vehicle" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(document).on('click', '.open-vehicle', function () {
            var vehicleId = $(this).data('id');
            $('.vehicleModal').modal('show');
            $('.vehicle-modal-body').html('<div class="text-center">Loading...</div>');

            $.ajax({
                url: '/vehicle/get/' + vehicleId,
                type: 'GET',
                success: function (data) {
                    var today = new Date();
                    var todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                    function getStatus(dateStr) {
                        if (!dateStr) return { text: 'N/A', color: 'gray' };
                        var expiryDate = new Date(dateStr);
                        var diffDays = Math.ceil((expiryDate - todayDate) / (1000 * 60 * 60 * 24));

                        if (expiryDate < todayDate) return { text: 'Expired', color: 'red' };
                        else if (diffDays <= 15) return { text: 'Expiring soon (' + diffDays + ' days)', color: 'orange' };
                        else return { text: 'Valid', color: 'green' };
                    }

                    var fields = [
                        { name: 'Registration', date: data.registration_date_to },
                        { name: 'Insurance', date: data.insurance_valid_to },
                        { name: 'PUC', date: data.puc_valid_to },
                        { name: 'License', date: data.license_valid_to }
                    ];

                    var htmlContent = '<div class="row g-3">';
                    fields.forEach(function (f) {
                        var status = getStatus(f.date);
                        htmlContent += `
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="card-title">${f.name}</h6>
                                <p class="card-text mb-1"><strong>Expiry Date:</strong> ${f.date ?? 'N/A'}</p>
                                <p class="card-text mb-0"><strong>Status:</strong> 
                                    <span style="color:${status.color}; font-weight:bold;">${status.text}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                    });
                    htmlContent += '</div>';

                    $('.vehicle-modal-body').html(htmlContent);

                },
                error: function () {
                    $('.vehicle-modal-body').html('<div class="text-danger text-center">Failed to fetch data.</div>');
                }
            });
        });
        $(document).on('click', '.close-vehicle', function () {
            $('.vehicleModal').modal('hide');
        });
    });
</script><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/partials/pass_table.blade.php ENDPATH**/ ?>