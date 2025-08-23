
<?php
use App\Division;
use App\Department;
use App\UserLogin;

$vms = DB::table('vehicle_pass')->where('id', $vms_details->id)->first();
$vms_flow = DB::table('vehicle_pass_flow')->where('vehicle_pass_id', $vms->id)->where('type_status', 'New')->where('level', '0')->where('status', 'N')->first();

$type = $vms->apply_by_type == '1' ? 'Employee' : 'Vendor';
$not_required = $vms->apply_by_type == '1' ? '' : 'hidden';
$user_check_safety = UserLogin::where('id', $user_id)->select('id')->first();
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e(config('app.name', 'JAMIPOL SURAKSHA')); ?></title>
<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<!-- <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/> -->
<!-- Styles -->
<link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/jquery.dataTables.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/buttons.dataTables.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/sweetalert.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/admin.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/fontawesome-free/css/all.min.css')); ?>">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('vms.index')); ?>">List of VMS Documents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Pass Management System</li>
<?php $__env->stopSection(); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .container {
        background: #fff;
        border-radius: 16px;
        padding: 30px 40px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
    }

    h3.fw-bold {
        font-size: 1.6rem;
        color: #003366;
    }

    .animated-hr {
        border: none;
        border-top: 2px solid #afbdd3;
        width: 0;
        animation: expandLineRightToLeft 2s forwards;
        transform-origin: right;
    }

    @keyframes expandLineRightToLeft {
        to {
            width: 100%;
        }
    }

    label::after {
        content: " *";
        color: red;
        font-weight: bold;
    }

    .driver-fields {
        display: none;
    }
</style>
<style>
    .container {
        background: #fff;
        border-radius: 16px;
        padding: 30px 40px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
    }

    h3.fw-bold {
        font-size: 1.6rem;
        color: #003366;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0066cc;
        box-shadow: 0 0 0 0.15rem rgba(0, 102, 204, 0.25);
    }

    .dropzone-wrapper {
        border: 2px dashed #91b0b3;
        border-radius: 6px;
        height: 100px;
        position: relative;
        background: #fff;
        transition: all 0.3s ease-in-out;
        text-align: center;
        /* display: flex;  <-- remove this to avoid centering */
        /* justify-content: center; */
        /* align-items: center; */
        cursor: pointer;
        padding: 20px;
        /* added padding for spacing inside */
    }

    .dropzone-wrapper.dragover {
        /* Remove background and border color changes */
        /* background: #f8f9fa; */
        /* border-color: #5cb85c; */
        border-color: #91b0b3;
        /* keep original border color on dragover */
    }

    .dropzone-wrapper.filled {
        background-color: #e8f5e9;
        border-color: #28a745;
        color: #155724;
        font-weight: 600;

        /* Center contents when filled */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .dropzone-desc {
        color: #999;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .dropzone-wrapper.filled .dropzone-desc {
        color: #28a745;
        font-size: 15px;
        text-align: center;
    }

    .dropzone-wrapper input[type="file"] {
        opacity: 0;
        position: absolute;
        height: 100%;
        width: 100%;
        cursor: pointer;
        top: 0;
        left: 0;
    }

    .dropzone-wrapper.filled::before {
        content: "✔";
        position: absolute;
        top: 8px;
        right: 12px;
        font-size: 20px;
        color: #28a745;


    }

    /* Red border on error */
    .dropzone-wrapper.error {
        border-color: red !important;
    }

    .dropzone-wrapper small.error-msg {
        color: red;
        display: block;
        margin-top: 5px;
        font-size: 0.85rem;
    }

    .dropzone-wrapper {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        position: relative;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }

    .dropzone-wrapper.dragover {
        border-color: #666;
    }

    /* Red border on error */
    .dropzone-wrapper.error {
        border-color: red !important;
    }

    .dropzone-wrapper small.error-msg {
        color: red;
        display: block;
        margin-top: 5px;
        font-size: 0.85rem;
    }

    label::after {
        content: " *";
        color: red;
        font-weight: bold;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
    }

    .card-header {
        background: #003366;
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    label {
        font-weight: 600;
        color: #333;
    }

    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }
</style>

<style>
    .animated-hr {
        border: none;
        border-top: 2px solid #afbdd3;
        width: 0;
        margin-left: auto;
        /* push line to the right */
        margin-right: 0;
        animation: expandLineRightToLeft 2s forwards;
        /* Make the origin of the width expansion from right */
        transform-origin: right;
    }


    @keyframes expandLineRightToLeft {
        to {
            width: 1020px;
        }
    }

    .small {
        font-size: smaller;
        font-weight: bold;
        background: linear-gradient(90deg, #6b5c5c, #c73b11, #a00e0e);
        background-size: 200% auto;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shine 5s linear infinite;
        display: inline-block;
    }

    @keyframes shine {
        0% {
            background-position: -200% center;
        }

        100% {
            background-position: 200% center;
        }
    }
</style>
<form action="" method="post" enctype="multipart/form-data" id="form_return" autocomplete="off">
    <?php echo csrf_field(); ?>
    <a class="btn btn-primary px-4 py-2 rounded-pill col-12 text-white shadow-sm d-inline-flex align-items-center"
        data-bs-toggle="collapse" data-bs-target="#collapseExample" role="button" aria-expanded="false"
        aria-controls="collapseExample">
        <i class="bi bi-pencil-square me-2"></i> <?php echo e($vms->full_sl); ?>

        <i class="bi bi-chevron-down ms-2 collapse-toggle-icon"></i>
    </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <div class="collapse" id="collapseExample">
        <div class="container mt-4 pb-4">
            <!-- Header -->
            <div class="mb-4">
                <h3 class="fw-bold text-dark">
                    <i class="fas fa-id-card-alt me-2 text-primary"></i>
                    <?php echo e($type); ?> Vehicle Gate Pass Management System (Edit Driver Details)
                </h3>
                <hr class="border-top border-primary" />
            </div>


            <input type="hidden" value="<?php echo e($vms->id); ?>" name="vehicle_id">
            <div class="form-group">
                <label>Apply Vehicle Pass For</label>
                <select class="form-control hd" name="vehicle_type_required" required>
                    <option value="">--Select--</option>
                    <option value="two_wheeler" <?php if($vms->vehicle_pass_for == 'two_wheeler'): ?><?php echo e('selected'); ?><?php endif; ?>>🏍️ Two
                        Wheeler
                    </option>
                    <?php if($type == 'Employee'): ?>
                        <option <?php echo e($not_required ?? ''); ?> value="four_wheeler"
                            <?php if($vms->vehicle_pass_for == 'four_wheeler'): ?><?php echo e('selected'); ?><?php endif; ?>>🚗 Car</option>
                    <?php endif; ?>
                </select>
            </div>


            <?php if($type != 'Employee'): ?>
                <div class="mb-3">
                    <label class="form-label">Employee Name</label>
                    <input type="text" class="form-control hd" value="<?php echo e($vms->employee_name); ?>" name="emp_name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Gate Pass No</label>
                    <input type="text" class="form-control hd" value="<?php echo e($vms->gp); ?>" name="gp">
                </div>
            <?php endif; ?>

            <!-- Vehicle Details -->
            <div class="mb-3">
                <label class="form-label">Vehicle Owner Name</label>
                <input type="text" class="form-control hd" value="<?php echo e($vms->vehicle_owner_name); ?>" name="owner_name">


            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Vehicle Registration No.</label>
                    <input type="text" class="form-control hd" value="<?php echo e(@$vms->vehicle_registration_no); ?>"
                        name="registration_no">

                    <label class="mt-2 dn">Upload Registration Certificate (RC)</label>

                    <div class="dropzone-wrapper dn">
                        <div class="dropzone-desc"><i class="fa fa-upload"></i>
                            <p>Drag & Drop file or click to browse</p>
                        </div>
                        <input type="file" name="rc_attachment" accept="application/pdf">
                    </div>

                    <div class="file-preview text-secondary small mt-1 dn"></div>
                    <small class="form-text text-muted ml-1 small dn">Only PDF file are allowed. Max size 5MB.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label d-block">Registration Certificate</label>
                    <a href="<?php echo e(asset($vms->vehicle_registration_doc)); ?>" class="btn btn-outline-primary btn-sm">View
                        Document</a>
                </div>
            </div>

            <!-- Insurance -->
            <div class="row g-3 mt-12">
                <div class="col-md-12">
                    <label class="form-label">Insurance Valid From</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->insurance_valid_from); ?>"
                        name="insurance_from">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Insurance Valid To</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->insurance_valid_to); ?>"
                        name="insurance_to">

                    <label class="mt-2 dn">Upload Insurance Document</label>
                    <div class="dropzone-wrapper dropzone dn">
                        <div class="dropzone-desc"><i class="fa fa-upload"></i>
                            <p>Drag & Drop file or click to browse</p>
                        </div>
                        <input type="file" name="insurance_attachment" accept="application/pdf">
                        <div class="file-preview text-secondary small mt-1"></div>
                    </div>
                    <small class="form-text text-muted ml-1 small dn">Only PDF file are allowed. Max size 5MB.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label d-block">Insurance Document</label>
                    <a href="<?php echo e(asset($vms->insurance_doc)); ?>" target="_blank"
                        class="btn btn-outline-primary btn-sm">View</a>
                </div>
            </div>


            <div class="form-group">
                <label>Vehicle Type</label>
                <select class="form-control hd" name="vehicle_category" id="vehicle_category" required>
                    <option value="">--Select--</option>
                    <option value="Petrol" <?php if($vms->vehicle_type == 'Petrol'): ?><?php echo e('selected'); ?><?php endif; ?>>⛽ Petrol</option>
                    <?php if($type == 'Employee'): ?>
                        <option <?php echo e($not_required ?? ''); ?> value="Diesel"
                            <?php if($vms->vehicle_type == 'Diesel'): ?><?php echo e('selected'); ?><?php endif; ?>>🛢️
                            Diesel</option>
                        <option <?php echo e($not_required ?? ''); ?> value="CNG" <?php if($vms->vehicle_type == 'CNG'): ?><?php echo e('selected'); ?><?php endif; ?>>🧯
                            CNG
                        </option>
                    <?php endif; ?>
                    <option value="EV" <?php if($vms->vehicle_type == 'EV'): ?><?php echo e('selected'); ?><?php endif; ?>>🔌 EV</option>
                    <?php if($type == 'Employee'): ?>
                        <option <?php echo e($not_required ?? ''); ?> value="Hybrid"
                            <?php if($vms->vehicle_type == 'Hybrid'): ?><?php echo e('selected'); ?><?php endif; ?>>♻️
                            Hybrid</option>
                    <?php endif; ?>

                </select>
            </div>
            <!-- Vehicle Type -->
            <div class="row g-3 mt-2">




                <div class="col-md-12">
                    <label class="form-label">Vehicle Registration Date</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->vehicle_registration_date); ?>"
                        name="registration_date">
                </div>
            </div>

            <!-- PUC -->
            <div class="row g-3 mt-2">
                <div class="col-md-12">
                    <label class="form-label">PUC Valid From</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->puc_valid_from); ?>" name="puc_from">
                </div>
                <div class="col-md-12">
                    <label class="form-label">PUC Valid To</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->puc_valid_to); ?>" name="puc_to">
                </div>
                <div class="col-md-12 dn">
                    <label class="mt-2">Upload PUC Document </label>
                    <div class="dropzone-wrapper dropzone">
                        <div class="dropzone-desc"><i class="fa fa-upload"></i>
                            <p>Drag & Drop file or click to browse</p>
                        </div>
                        <input type="file" name="puc_attachment" accept="application/pdf">
                    </div>

                    <small class="form-text text-muted ml-1 small">Only PDF file are allowed. Max size 5MB.</small>
                </div>
                <div class="col-md-12">
                    <label class="form-label d-block">PUC Attachment</label>
                    <a href="<?php echo e(asset($vms->puc_attachment_required)); ?>" target="_blank"
                        class="btn btn-outline-primary btn-sm">View</a>
                </div>
            </div>

            <?php if($type == 'Employee'): ?>
                <!-- Driver Info -->
                <div class="mt-3">
                    <label class="form-label">Vehicle Will be Driven By</label>
                    <input type="text" class="form-control hd" value="<?php echo e(ucfirst($vms->driven_by)); ?>" name="driver_type">
                </div>

                <?php if($vms->driven_by == 'driver'): ?>
                    <div class="mt-2">
                        <label class="form-label">Driver’s Name</label>
                        <input type="text" class="form-control hd" value="<?php echo e($vms->driver_name); ?>" name="driver_name">
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- License -->
            <div class="mt-12">
                <label class="form-label">Driving License No.</label>
                <input type="text" class="form-control hd" value="<?php echo e($vms->driving_license_no); ?>" name="license_no">
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-12">
                    <label class="form-label">License Valid From</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->license_valid_from); ?>"
                        name="license_valid_from">
                </div>
                <div class="col-md-12">
                    <label class="form-label">License Valid To</label>
                    <input type="date" class="form-control hd" value="<?php echo e($vms->license_valid_to); ?>"
                        name="license_valid_to">
                </div>
                <div class="col-md-12 dn">
                    <label class="mt-2">Upload Driving License</label>
                    <div class="dropzone-wrapper dropzone">
                        <div class="dropzone-desc"><i class="fa fa-upload"></i>
                            <p>Drag & Drop file or click to browse</p>
                        </div>
                        <input type="file" name="license_attachment" accept="application/pdf">
                    </div>
                    <small class="form-text text-muted ml-1 small">Only PDF file are allowed. Max size 5MB.</small>
                    <label class="form-label d-block">License Document</label>
                    <a href="<?php echo e(asset($vms->driving_license_doc)); ?>" target="_blank"
                        class="btn btn-outline-primary btn-sm">View</a>
                </div>
            </div>


            <label class="form-label dn">Remarks (Optional)</label>
            <textarea class="form-control dn" name="remarks" rows="3"
                placeholder="Enter any remarks (optional)"></textarea>




            <div class="text-center mt-4 dn">

                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm"
                    style="font-size: 1.1rem;" id="submit-btn">
                    <span id="spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                    <i class="fas fa-check-circle me-2" id="btn-icon"></i>
                    <span id="btn-text">Submit</span>
                </button>


            </div>

        </div>



    </div>
</form>

<?php if($user_id == $vms->created_by): ?>
    <!-- Driver Details Panel -->
    <div class="card shadow-lg border-0 mb-4 rounded-3">
        <div
            class="card-header bg-gradient bg-info text-white d-flex justify-content-between align-items-center rounded-top">
            <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i> Driver Details </h5>
        </div>
        <div class="card-body">
            <form id="driver_form" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="vms_id" value="<?php echo e($vms->id); ?>">
                <div class="form-group">
                    <label>Vehicle Will be Driven By</label>
                    <select class="form-control" id="driver_type" name="driver_type" required>
                        <option value="">--Select--</option>
                        <option value="self" <?php if($vms->driven_by == 'self'): ?> <?php echo e('selected'); ?> <?php endif; ?>>🙋‍♂️ Self</option>
                        <option value="driver" <?php if($vms->driven_by == 'driver'): ?> <?php echo e('selected'); ?> <?php endif; ?>>👤 Through Driver
                        </option>
                    </select>
                </div>


                <!-- Driver Details Box (hidden by default) -->
                <div id="driver_box" class="card shadow-sm border-0 mt-3" style="display: none;">


                    <div class="form-group">
                        <label>Driver’s Name</label>
                        <input type="text" class="form-control" name="driver_name" value="<?php echo e($vms->driver_name); ?>">
                    </div>

                </div>
                <div class="form-group">
                    <label>Driving License No.</label>
                    <input type="text" class="form-control" name="license_no" value="<?php echo e($vms->driving_license_no); ?>">
                </div>

                <div class="form-group">
                    <label>Driving License Validity</label>
                    <div class="form-row">
                        <div class="col">
                            <input type="date" class="form-control" name="license_valid_from"
                                value="<?php echo e($vms->license_valid_from); ?>" required>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="license_valid_to"
                                value="<?php echo e($vms->license_valid_to); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label>Upload Driving License</label>
                    <div class="dropzone-wrapper dropzone">
                        <div class="dropzone-desc">
                            <i class="fa fa-upload"></i>
                            <p>Drag & Drop file or click to browse</p>
                        </div>
                        <input type="file" name="license_attachment" accept="application/pdf" required>
                    </div>
                    <small class="form-text text-muted ml-1 small">
                        Only PDF file allowed. Max size 5MB.
                    </small>

                </div>

                <a href="<?php echo e(asset($vms->driving_license_doc)); ?>" target="_blank" class="btn btn-outline-primary btn-sm">View
                </a>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" id="previewBtn">
                        Preview & Submit
                    </button>
                </div>

                <!-- Script to toggle driver box -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const driverType = document.getElementById('driver_type');
                        const driverBox = document.getElementById('driver_box');

                        if (driverType) {
                            // Function to toggle box
                            const toggleDriverBox = function () {
                                if (driverType.value === 'driver') {
                                    driverBox.style.display = 'block';
                                } else {
                                    driverBox.style.display = 'none';
                                }
                            };

                            // Run on change
                            driverType.addEventListener('change', toggleDriverBox);
                            // Run once on page load
                            toggleDriverBox();
                        }
                    });
                    // Update driver Details





                </script>


            </form>
        </div>
    </div>
<?php endif; ?>
<!-- Preview Modal -->
<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <div class="modal-body">

                <div id="previewContent" class="p-3">
                    <!-- Dynamically filled preview details -->
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="consentCheckbox">
                    <label class="form-check-label fw-bold" for="consentCheckbox">
                        <i class="fas fa-check-circle text-success me-2"></i> I confirm that the above details are
                        correct.
                    </label>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" id="submitBtn" class="btn btn-success" disabled>
                    <i class="fas fa-paper-plane me-1"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize elements
        const consentCheckbox = document.getElementById("consentCheckbox");
        const submitBtn = document.getElementById("submitBtn");
        const previewBtn = document.getElementById("previewBtn");
        const fileInput = document.querySelector('input[name="license_attachment"]');
        const dropzone = document.querySelector('.dropzone-wrapper');
        const dropzoneText = document.querySelector('.dropzone-desc p');

        // Check if elements exist
        if (!consentCheckbox || !submitBtn || !previewBtn || !fileInput || !dropzone || !dropzoneText) {
            console.error("Required elements not found");
            return;
        }

        // Enable/disable submit button based on checkbox
        consentCheckbox.addEventListener("change", function () {
            submitBtn.disabled = !this.checked;
            submitBtn.classList.toggle("btn-secondary", !this.checked);
            submitBtn.classList.toggle("btn-primary", this.checked);
        });

        // Update file display when selected
        fileInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                dropzoneText.textContent = this.files[0].name;
                dropzone.classList.add('file-selected');
            } else {
                dropzoneText.textContent = 'Drag & Drop file or click to browse';
                dropzone.classList.remove('file-selected');
            }
        });

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form validation
        function validateForm() {
            const licenseNo = document.querySelector("[name='license_no']").value;
            const validFrom = document.querySelector("[name='license_valid_from']").value;
            const validTo = document.querySelector("[name='license_valid_to']").value;
            const fileInput = document.querySelector('input[name="license_attachment"]');

            if (!licenseNo || !validFrom || !validTo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields',
                    confirmButtonColor: '#3085d6',
                });
                return false;
            }

            if (fileInput.files.length === 0 && !"<?php echo e($vms->driving_license_doc ?? false); ?>") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Document Required',
                    text: 'Please upload a driving license document',
                    confirmButtonColor: '#3085d6',
                });
                return false;
            }

            return true;
        }

        // Show preview details
        previewBtn.addEventListener("click", function () {
            if (!validateForm()) {
                return;
            }

            const driverType = document.getElementById('driver_type');
            const isSelf = driverType.value === 'self';
            const selectedFile = fileInput.files[0];

            let fileName = 'No file selected';
            let fileStatus = '';
            let fileNote = '';

            if (selectedFile) {
                fileName = selectedFile.name;
                fileStatus = '<span class="badge bg-primary ms-2">New Upload</span>';
                fileNote = `<p class="small text-success mb-0 mt-1">
                <i class="fas fa-check-circle me-1"></i>
                Ready for upload (${formatFileSize(selectedFile.size)})
            </p>`;

                const fileUrl = URL.createObjectURL(selectedFile);
                fileName = `<a href="${fileUrl}" target="_blank">${fileName}</a>`;
            }
            else if ("<?php echo e($vms->driving_license_doc ?? false); ?>") {
                fileName = ``;
                fileStatus = '<span class="badge bg-secondary ms-2">Current File</span>';
            }

            const previewHtml = `
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-primary text-white rounded-top">
                        <h5 class="card-title mb-0"><i class="fas fa-file-alt me-2"></i> Driver Information Preview</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-car text-primary"></i> 
                                    </div>
                                    <div>
                                         <h6 class="mb-0 text-muted">Driven By</h6>
                                         <p class="mb-0 fw-bold">${isSelf ? 'Self' : 'Driver'}</p>
                                    </div>
                                </div>
                            </li>
                            ${!isSelf ? `
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-info rounded-circle p-2 me-3">
                                        <i class="fas fa-user-tie text-info"></i> 
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">Driver's Name</h6>
                                        <p class="mb-0 fw-bold">${document.querySelector("[name='driver_name']").value || 'Not provided'}</p>
                                    </div>
                                </div>
                            </li>
                            ` : ''}
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-warning rounded-circle p-2 me-3">
                                        <i class="fas fa-id-card text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">License Number </h6>
                                        <p class="mb-0 fw-bold">${document.querySelector("[name='license_no']").value || 'Not provided'}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-success rounded-circle p-2 me-3">
                                        <i class="fas fa-calendar-alt text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">License Validity</h6>
                                        <p class="mb-0 fw-bold">
                                            ${document.querySelector("[name='license_valid_from']").value || 'Not provided'} 
                                            to 
                                            ${document.querySelector("[name='license_valid_to']").value || 'Not provided'}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-danger rounded-circle p-2 me-3">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted">License Document</h6>
                                        <p class="mb-0 fw-bold">
                                            ${fileName} ${fileStatus}
                                            ${fileNote}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            `;

            document.getElementById("previewContent").innerHTML = previewHtml;
            new bootstrap.Modal(document.getElementById("previewModal")).show();
        });

        // Submit button handler
        submitBtn.addEventListener("click", function (e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Processing...
        `;

            // Prepare form data
            const form = document.getElementById("driver_form");
            const formData = new FormData(form);

            // Using the correct route name from your definition
            fetch("<?php echo e(route('vms_ifream.update_driver_details')); ?>", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const successHtml = `
                    <div class="text-center py-4">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="text-success mb-3">${data.title || 'Submission Successful!'}</h3>
                        <p class="text-muted">${data.message || 'Your driver details have been updated.'}</p>
                        <div class="mt-4 pt-3 border-top">
                            <button class="btn btn-outline-primary rounded-pill px-4" data-bs-dismiss="modal">
                                <i class="fas fa-check me-2"></i> Done
                            </button>
                        </div>
                    </div>
                `;
                        document.getElementById("previewContent").innerHTML = successHtml;
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorHtml = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${error.message || 'Error submitting form. Please try again.'}
                </div>
            `;
                    document.getElementById("previewContent").innerHTML = errorHtml;
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    });
</script>

<style>
    .dropzone-wrapper {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px dashed #ced4da;
        border-radius: 6px;
        padding: 20px;
        text-align: center;
    }

    .dropzone-wrapper:hover {
        border-color: #0d6efd;
        background-color: #f8f9fa;
    }

    .dropzone-wrapper.file-selected {
        border-color: #198754;
        background-color: #f8f9fa;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
    }

    .bg-light-primary {
        background-color: rgba(58, 123, 213, 0.1);
    }

    .bg-light-info {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-light-success {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-light-secondary {
        background-color: rgba(108, 117, 125, 0.1);
    }
</style>


<script>
    document.getElementById("driver_type").addEventListener("change", function () {
        var value = this.value;
        document.getElementById("driver_box").style.display = (value === "driver") ? "block" : "none";
    });

    // Initial check
    // document.getElementById("driver_box").style.display =
    //     (document.getElementById("driver_type").value === "driver") ? "block" : "none";

</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropzones = document.querySelectorAll('.dropzone-wrapper');

        dropzones.forEach(function (zone) {
            const input = zone.querySelector('input[type="file"]');
            const desc = zone.querySelector('.dropzone-desc');

            // Create or get error message element
            let errorMsg = zone.querySelector('small.error-msg');
            if (!errorMsg) {
                errorMsg = document.createElement('small');
                errorMsg.classList.add('error-msg', 'text-danger');
                zone.appendChild(errorMsg);
            }

            function showError(message) {
                errorMsg.textContent = message;
                zone.classList.add('error');  // Add red border class
            }

            function clearError() {
                errorMsg.textContent = '';
                zone.classList.remove('error'); // Remove red border class
            }

            function isPdfFile(file) {
                return file.name.toLowerCase().endsWith('.pdf');
            }

            function isFileSizeValid(file) {
                return file.size <= 5 * 1024 * 1024; // 5 MB
            }

            input.addEventListener('change', function () {
                if (input.files.length > 0) {
                    const file = input.files[0];
                    if (!isPdfFile(file)) {
                        showError('Only PDF files are allowed!');
                        input.value = "";
                        zone.classList.remove('filled');
                        desc.innerHTML = '<i class="fa fa-upload"></i> Drag & Drop file or click';
                    } else if (!isFileSizeValid(file)) {
                        showError('File must be less than or equal to 5 MB!');
                        input.value = "";
                        zone.classList.remove('filled');
                        desc.innerHTML = '<i class="fa fa-upload"></i> Drag & Drop file or click';
                    } else {
                        zone.classList.add('filled');
                        desc.textContent = file.name;
                        clearError();
                    }
                } else {
                    zone.classList.remove('filled');
                    desc.innerHTML = '<i class="fa fa-upload"></i> Drag & Drop file or click';
                    clearError();
                }
            });

            zone.addEventListener('dragover', function (e) {
                e.preventDefault();
                zone.classList.add('dragover');
            });

            zone.addEventListener('dragleave', function () {
                zone.classList.remove('dragover');
            });

            zone.addEventListener('drop', function (e) {
                e.preventDefault();
                zone.classList.remove('dragover');

                const dt = e.dataTransfer;
                if (dt.files.length > 0) {
                    const file = dt.files[0];
                    if (!isPdfFile(file)) {
                        showError('Only PDF files are allowed!');
                        zone.classList.remove('filled');
                        desc.innerHTML = '<i class="fa fa-upload"></i> Drag & Drop file or click';
                    } else if (!isFileSizeValid(file)) {
                        showError('File must be less than or equal to 5 MB!');
                        zone.classList.remove('filled');
                        desc.innerHTML = '<i class="fa fa-upload"></i> Drag & Drop file or click';
                    } else {
                        zone.classList.add('filled');
                        desc.textContent = file.name;
                        clearError();
                    }
                }
            });
        });
    });
</script>

<script>
    var vmsFlowExists = <?php echo e($vms_flow ? 'true' : 'false'); ?>;
    $(document).ready(function () {
        if (!vmsFlowExists) {
            // Hide elements
            $('.dn').addClass('d-none');

            // Disable elements
            $('.hd').prop('disabled', true).addClass('disabled');
        }
    });

</script>

<?php $__env->startSection('scripts'); ?>




    <script>
        $(document).ready(function () {
            if ($('#driver_type').val() === 'driver') {
                $('.driver-fields').show();
            }
            if ($('#vehicle_category').val() === 'EV') {
                $('.puc').hide();
            }
        });
    </script>
    <script type="text/javascript" src="<?php echo e(asset('js/app.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/sweetalert.js')); ?>"> </script>

    <script type="text/javascript" src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/dataTables.buttons.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/jszip.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/buttons.html5.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/all.js')); ?>"> </script>




    <!-- <script type="text/javascript" src="<?php echo e(asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"> </script> -->
    <script type="text/javascript">
        function form_validate() {
            var flag = true;
            $(".rec").each(function (e) {

                if ($(this).val() == "") {
                    $(this).addClass("verror");
                    flag = false;
                }
                else {
                    $(this).removeClass("verror");
                }
            })
            if (flag == true) {
                var c = confirm("Are you sure want to save.");
                if (c) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
    </script>


<?php $__env->stopSection(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vms_ifream/edit_driver_details.blade.php ENDPATH**/ ?>