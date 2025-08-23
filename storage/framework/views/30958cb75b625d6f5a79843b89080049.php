

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vendor Settings</li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <div class="container py-4">
            <div class="card shadow-lg border-0">
                
                <div class="card-header bg-maroon text-white">
                    <h4 class="mb-0">🛠 Settings Configuration</h4>
                </div>
                <div class="card-body bg-light">

                    <?php if(session()->has('message')): ?>
                        <div class="alert alert-success text-center">
                            <?php echo e(session('message')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- Attendance -->
                    <div class="mb-4">
                        <label class="form-label">Vendor Wage Register Last Day For Every Month:</label>
                        <div class="input-group">
                            <select id="attendance_last_day" class="form-control col-6">
                                <?php for($d = 1; $d <= 31; $d++): ?>
                                    <option value="<?php echo e($d); ?>" <?php echo e(@$setting_vendor_attendance[0]->value == $d ? 'selected' : ''); ?>>
                                        <?php echo e($d); ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;&nbsp;
                            <button class="btn btn-info save-setting col-3" data-type="attendance"
                                data-select="#attendance_last_day">Save</button>
                        </div>
                    </div>

                    <!-- ESIC -->
                    <div class="mb-4">
                        <label class="form-label">Vendor ESIC Challan & Contribution Upload Last Day For Every Month:</label>
                        <div class="input-group">
                            <select id="esic_last_day" class="form-control col-6">
                                <?php for($d = 1; $d <= 31; $d++): ?>
                                    <option value="<?php echo e($d); ?>" <?php echo e(@$setting_vendor_esic[0]->value == $d ? 'selected' : ''); ?>><?php echo e($d); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                             &nbsp;&nbsp;
                            <button class="btn btn-info save-setting col-3" data-type="esic" data-select="#esic_last_day">Save
                                </button>
                        </div>
                    </div>

                    <!-- PF -->
                    <div class="mb-4">
                        <label class="form-label">Vendor PF Challan & ECR Upload Last Day For Every Month:</label>
                        <div class="input-group">
                            <select id="pf_last_day" class="form-control col-6">
                                <?php for($d = 1; $d <= 31; $d++): ?>
                                    <option value="<?php echo e($d); ?>" <?php echo e(@$setting_vendor_pf[0]->value == $d ? 'selected' : ''); ?>><?php echo e($d); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                             &nbsp;&nbsp;
                            <button class="btn btn-info save-setting col-3" data-type="pf" data-select="#pf_last_day">Save
                                </button>
                        </div>
                    </div>
                  <div class="mb-4">
                        <label class="form-label">Vendor Bonus Return / Filling :</label>
                        <div class="input-group">
                            &nbsp;
                            <input type="month" name="bonus_month" id="bonus_month" class="form-control col-6" value="<?php echo e(@$setting_bonus[0]->value); ?>">
                             &nbsp;&nbsp;
                            <button class="btn btn-info save-setting col-3" data-type="bonus_month" data-select="#bonus_month">Save
                                </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Half Yearly Return :</label>
                        <div class="input-group ">
                            <small class="me-3  fw-bold col-1">First Month.</small>
                            &nbsp;
                            <input type="month" name="half_yearly1" id="half_yearly1" class="form-control col-2" value="<?php echo e(@$setting_half_yearly1[0]->value); ?>">
                             &nbsp;
                             <small class="me-3 fw-bold col-1">Second Month.</small>
                             <input type="month" name="half_yearly2" id="half_yearly2" class="form-control col-2" value="<?php echo e(@$setting_half_yearly2[0]->value); ?>">
                             &nbsp;
                            <button class="btn btn-info save-setting col-3"
    data-type="half_yearly"
    data-select="#half_yearly1,#half_yearly2">Save</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php $__env->stopSection(); ?>
<?php endif; ?>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<?php $__env->startSection('scripts'); ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSRF Meta -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Custom Script -->
    <script>
        $(document).ready(function () {
           

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.save-setting').on('click', function (e) {
    e.preventDefault();

    let type = $(this).data('type');
    let selectors = $(this).data('select').split(',');
    let values = selectors.map(s => $(s).val());

    if (type === 'half_yearly') {
        $.ajax({
            url: "<?php echo e(route('admin.settings_master.store')); ?>",
            method: 'POST',
            data: {
                type_name: type,
                half_yearly1: values[0],
                half_yearly2: values[1],
            },
            success: function (response) {
                toastr.success(response.message);
            },
            error: function (xhr) {
                alert('Failed to update setting: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    } else {
        $.ajax({
            url: "<?php echo e(route('admin.settings_master.store')); ?>",
            method: 'POST',
            data: {
                type_name: type,
                value: values[0], // just one value for single selectors
            },
            success: function (response) {
                 toastr.success(response.message);
            },
            error: function (xhr) {
                alert('Failed to update setting: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    }
});
        });
    </script>
<?php $__env->stopSection(); ?>


<style>
    .bg-maroon {
        background-color: #277b9c !important;
    }
    .toast-success {
    background-color: #28a745 !important;
}
.toast-error {
    background-color: #dc3545 !important;
}
.toast-info {
    background-color: #17a2b8 !important;
}
.toast-warning {
    background-color: #ffc107 !important;
}


</style>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/settings_master/index.blade.php ENDPATH**/ ?>