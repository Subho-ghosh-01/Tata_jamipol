<?php
use App\Division;
use App\Department;
use App\UserLogin;
$vms = DB::table('vendor_silo')->where('id', $vms_details->id)->first();
$vms_flow = DB::table('vendor_silo_flow')->where('vendor_silo_id', $vms->id)->where('status', 'N')->orderBy('id', 'asc')->first();

$vehicle_status = $vms->status;
$vendor_level = $vms_flow->level ?? 'NA';
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e(config('app.name', 'JAMIPOL SURAKSHA')); ?></title>
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

<!-- Font Awesome for upload icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">




<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    @media (min-width: 992px) {

        .container,
        .container-lg,
        .container-md,
        .container-sm {
            max-width: 1050px;
        }
    }
</style>



<style>
    .file-upload-container.has-file {
        border: 2px solid green;
        background: #eaffea;
        border-radius: 6px;
        padding: 8px;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }

    .step-indicator:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }

    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 100%;
    }

    .step .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
    }

    .step.active .step-number {
        background: #0d6efd;
    }

    .step.completed .step-number {
        background: #198754;
    }

    .step-label {
        font-size: 12px;
        color: #6c757d;
    }

    .step.active .step-label,
    .step.completed .step-label {
        color: #212529;
        font-weight: 500;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }

    .was-validated .form-control:invalid~.invalid-feedback {
        display: block;
    }

    .file-upload-container {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .file-upload-container.dragover {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }

    .file-preview-success {
        display: inline-block;
        background: #d1fae5;
        /* light green background */
        border: 2px solid #10b981;
        /* thick green border */
        border-radius: 0.5rem;
        padding: 0.4rem 0.8rem;
        margin-top: 0.5rem;
        font-size: 14px;
        font-weight: 600;
        color: #065f46;
        /* dark green text */
    }

    .file-preview-success a {
        color: #065f46;
        /* dark green */
        text-decoration: none;
    }

    .file-preview-success a:hover {
        text-decoration: underline;
    }
</style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-16">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">

                    </div>
                    <div class="card-body">
                        <style>
                            .step-indicator {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 2rem;
                                position: relative;
                                padding: 0 1rem;
                            }

                            .step-indicator::before {
                                content: '';
                                position: absolute;
                                top: 20px;
                                left: 0;
                                right: 0;
                                height: 3px;
                                background: #e0e0e0;
                                z-index: 1;
                            }

                            .step {
                                position: relative;
                                z-index: 2;
                                text-align: center;
                                width: 100%;
                            }

                            .step .step-number {
                                width: 40px;
                                height: 40px;
                                border-radius: 50%;
                                background: #e0e0e0;
                                color: #6c757d;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin: 0 auto 10px;
                                font-weight: bold;
                                border: 3px solid #fff;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                                transition: all 0.3s ease;
                            }

                            .step.active .step-number {
                                background: #0d6efd;
                                color: white;
                                transform: scale(1.1);
                            }

                            .step.completed .step-number {
                                background: #198754;
                                color: white;
                            }

                            .step.completed .step-number::after {
                                content: '\f00c';
                                font-family: 'Font Awesome 5 Free';
                                font-weight: 900;
                            }

                            .step-label {
                                font-size: 12px;
                                color: #6c757d;
                                font-weight: 500;
                            }

                            .step.active .step-label,
                            .step.completed .step-label {
                                color: #212529;
                                font-weight: 600;
                            }

                            .progress-connector {
                                position: absolute;
                                top: 20px;
                                left: 0;
                                height: 3px;
                                background: #198754;
                                z-index: 2;
                                transition: width 0.3s ease;
                            }
                        </style>

                        <div class="step-indicator">
                            <div class="progress-connector" id="step-progress"></div>
                            <div class="step active" id="step1-indicator">
                                <div class="step-number">1</div>
                                <div class="step-label">Basic Information</div>
                            </div>
                            <div class="step" id="step2-indicator">
                                <div class="step-number">2</div>
                                <div class="step-label">Legal Compliance</div>
                            </div>
                            <div class="step" id="step3-indicator">
                                <div class="step-number">3</div>
                                <div class="step-label">Safety Parameters</div>
                            </div>
                            <div class="step" id="step4-indicator">
                                <div class="step-number">4</div>
                                <div class="step-label">Valid Certificate</div>
                            </div>
                        </div>

                        <script>
                            function updateStepIndicator(currentStep, totalSteps) {
                                // Reset all steps
                                $('.step').removeClass('active completed');

                                // Update active and completed steps
                                $('.step').each(function (index) {
                                    const stepNumber = index + 1;
                                    if (stepNumber < currentStep) {
                                        $(this).addClass('completed');
                                    } else if (stepNumber === currentStep) {
                                        $(this).addClass('active');
                                    }
                                });

                                // Update progress connector
                                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                                $('#step-progress').css('width', progressPercentage + '%');
                            }

                            // Initialize with first step active
                            updateStepIndicator(1, 4);

                            // Call this whenever step changes
                            // Example: updateStepIndicator(2, 4) when moving to step 2
                        </script>

                        <form id="form" enctype="multipart/form-data" novalidate>
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="status" id="statusField" value="draft">
                            <input type="hidden" name="id" id="recordId" value="<?php echo e($vms->id); ?>">
                            <input type="hidden" name="uid" id="" value="<?php echo e($user_id); ?>">
                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="step1">
                                <h5>Basic Information:</h5>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Work Order No <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="work_order_no"
                                            id="work_order_no" placeholder="Enter Work-Order No"
                                            value="<?php echo e($vms->work_order_no); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Validity <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="validity" id="validity"
                                            value="<?php echo e($vms->validity); ?>" required readonly>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Division <span class="text-danger">*</span></label>
                                        <select class="form-select" name="division" id="division" required>
                                            <option value="">Select Division</option>
                                            <?php if($divs->count() > 0): ?>
                                                <?php $__currentLoopData = $divs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($division->id); ?>" <?php echo e(($vms->division_id ?? '') == $division->id ? 'selected' : ''); ?>>
                                                        <?php echo e($division->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Section <span class="text-danger">*</span></label>
                                        <select class="form-select" name="plant" id="plant" required>
                                            <option value="">Select Section</option>

                                        </select>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Approver <span class="text-danger">*</span></label>
                                    <select class="form-select" name="approver" id="approver_id" required>
                                        <option value="">Select Approver</option>
                                        <option value="6">Approver 1</option>
                                        <option value="2">Approver 2</option>
                                        <option value="3">Approver 3</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>

                            </div>

                            <!-- Step 2: Legal Compliance -->
                            <div class="form-step" id="step2">
                                <h5>Legal And Statutory Compliance

                                </h5>
                                <br>
                                <div class="mb-3">
                                    <label class="form-label">Vehicle Registration No <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="vehicle_reg_no"
                                        placeholder="Enter Vehicle Registration No"
                                        value="<?php echo e($vms->vehicle_registration_no); ?>" required>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Registration Proof <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="regUpload">
                                        <input type="file" class="d-none" name="vehicle_reg_file" id="vehicle_reg_file"
                                            accept="application/pdf" <?php if(empty($vms->registration_doc)): ?>
                                            <?php echo e('required'); ?><?php endif; ?>>

                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>

                                        <!-- Existing file from DB -->
                                        <?php if(!empty($vms->registration_doc)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->registration_doc)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->registration_doc)); ?>

                                                </a>
                                                <input type="hidden" name="existing_vehicle_reg_file"
                                                    value="<?php echo e($vms->registration_doc); ?>">
                                            </div>

                                        <?php endif; ?>

                                        <button type="button" class="btn btn-outline-primary" id="uploadBtn">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Insurance From <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="insurance_from"
                                            value="<?php echo e($vms->insurance_from); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Insurance To <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="insurance_to"
                                            value="<?php echo e($vms->insurance_to); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Insurance Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="insuranceUpload">
                                        <input type="file" class="d-none" name="insurance_file"
                                            <?php if(empty($vms->insurance_doc)): ?> <?php echo e('required'); ?><?php endif; ?>>
                                        <!-- Existing file from DB -->
                                        <?php if(!empty($vms->insurance_doc)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->insurance_doc)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->insurance_doc)); ?>

                                                </a>
                                                <input type="hidden" name="existing_insurance_file"
                                                    value="<?php echo e($vms->insurance_doc); ?>">
                                            </div>

                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Valid Fitness Inspection Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fitness_date"
                                            value="<?php echo e($vms->valid_fitness_inspection_date); ?>"
                                            <?php if(empty($vms->fitness_certificate)): ?><?php echo e('required'); ?><?php endif; ?>>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Valid Fitness Inspection Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fitness_due_date"
                                            value="<?php echo e($vms->vehicle_fitness_due_date); ?>"
                                            <?php if(empty($vms->fitness_certificate)): ?><?php echo e('required'); ?><?php endif; ?>>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fitness Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="fitnessUpload">
                                        <input type="file" class="d-none" name="fitness_file"
                                            <?php if(empty($vms->fitness_certificate)): ?><?php echo e('required'); ?><?php endif; ?>>
                                        <?php if(!empty($vms->fitness_certificate)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->fitness_certificate)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->fitness_certificate)); ?>

                                                </a>
                                                <input type="hidden" name="existing_fitness_file"
                                                    value="<?php echo e($vms->fitness_certificate); ?>">
                                            </div>

                                        <?php endif; ?>

                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PUC Inspection Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="puc_inspection_date"
                                            value="<?php echo e($vms->puc_inspection_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PUC Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="puc_due_date"
                                            value="<?php echo e($vms->puc_inspection_due_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PUC Certificate <span class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="pucUpload">
                                        <input type="file" class="d-none" name="puc_file"
                                            <?php if(empty($vms->puc_certificate)): ?><?php echo e('required'); ?><?php endif; ?>>
                                        <?php if(!empty($vms->puc_certificate)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->puc_certificate)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->puc_certificate)); ?>

                                                </a>
                                                <input type="hidden" name="existing_puc_file"
                                                    value="<?php echo e($vms->puc_certificate); ?>">
                                            </div>

                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <label class="form-label">Valid Road Permit<span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"> From Date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="valid_road_permit_date"
                                            value="<?php echo e($vms->valid_road_permit_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Due Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="valid_road_permit_due_date"
                                            value="<?php echo e($vms->valid_road_permit_due_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Road Permit Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="pucUpload">
                                        <input type="file" class="d-none" name="road_permit_certificate"
                                            <?php if(empty($vms->road_permit_certificate)): ?><?php echo e('required'); ?><?php endif; ?>>
                                        <?php if(!empty($vms->road_permit_certificate)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->road_permit_certificate)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->road_permit_certificate)); ?>

                                                </a>
                                                <input type="hidden" name="existing_road_permit_certificate"
                                                    value="<?php echo e($vms->road_permit_certificate); ?>">
                                            </div>

                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <!-- Navigation Buttons -->


                            </div>

                            <!-- Step 3: Safety Parameters -->
                            <div class="form-step" id="step3">
                                <h5>Safety Parameters :

                                </h5><br>
                                <div class="mb-3">
                                    <label class="form-label">Vehicle Deputed For <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="deputedFor" name="vehicle_deputed_for" required>
                                        <option value="">Select Option</option>
                                        <option value="Local Movement" <?php if($vms->vehicle_dupted_for == 'Local Movement'): ?>
                                        <?php echo e('selected'); ?><?php endif; ?>>Local Movement</option>
                                        <option value="To Other States" <?php if($vms->vehicle_dupted_for == 'To Other States'): ?>
                                        <?php echo e('selected'); ?><?php endif; ?>>To Other States</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div id="otherStateFields" class="d-none">
                                    <div class="mb-3">
                                        <label class="form-label">DFMS Available <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="dfms_available" required>
                                            <option value="">Select Option</option>
                                            <option value="Yes" <?php if($vms->dfms == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes</option>
                                            <option value="No" <?php if($vms->dfms == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No</option>
                                        </select>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">GPS Tracker Available <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="gps_tracker_available" required>
                                            <option value="">Select Option</option>
                                            <option value="Yes" <?php if($vms->gps_tracker == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes
                                            </option>
                                            <option value="No" <?php if($vms->gps_tracker == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Hatch Strainers In All Hatch<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="hatch_strainers" name="hatch_strainers" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->hatch_strainers == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes
                                        </option>
                                        <option value="No" <?php if($vms->hatch_strainers == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Fuel Tank Strainer<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="fuel_tank_stainers" name="fuel_tank_stainers"
                                        required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->fuel_tank_strainers == 'Yes'): ?> <?php echo e('selected'); ?><?php endif; ?>>
                                            Yes</option>
                                        <option value="No" <?php if($vms->fuel_tank_strainers == 'No'): ?> <?php echo e('selected'); ?><?php endif; ?>>No
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Battery Placement at Outside of Prime Mover<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="battery_placement" name="battery_placement"
                                        required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->battery_placment == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes
                                        </option>
                                        <option value="No" <?php if($vms->battery_placment == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No<
                                                /option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availablility of Fire Extinguisher <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="fire_extinguisher" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->fire_extinguishers == 'Yes'): ?> <?php echo e('selected'); ?><?php endif; ?>>
                                            Yes</option>
                                        <option value="No" <?php if($vms->fire_extinguishers == 'No'): ?> <?php echo e('selected'); ?><?php endif; ?>>No<
                                                /option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of First Aid Box <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="first_aid_box" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->first_aid_box == 'Yes'): ?><?php echo e('selected'); ?> <?php endif; ?>>
                                            Yes</option>
                                        <option value="No" <?php if($vms->first_aid_box == 'No'): ?><?php echo e('selected'); ?> <?php endif; ?>>No
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Stepney (Spare Tyre) <span
                                            class="text-danger">*</span></label>


                                    <select class="form-select" name="stepney" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->stepney == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes</option>
                                        <option value="No" <?php if($vms->stepney == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Scotch Blocks (4 Nos)<span
                                            class="text-danger">*</span></label>


                                    <select class="form-select" name="scotch_block" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->scoth_block == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes
                                        </option>
                                        <option value="No" <?php if($vms->scoth_block == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Earth Chain<span
                                            class="text-danger">*</span></label>


                                    <select class="form-select" name="earth_block" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes" <?php if($vms->earth_chain == 'Yes'): ?><?php echo e('selected'); ?><?php endif; ?>>Yes
                                        </option>
                                        <option value="No" <?php if($vms->earth_chain == 'No'): ?><?php echo e('selected'); ?><?php endif; ?>>No</option>
                                    </select>

                                </div>


                            </div>
                            <!-- Step 4: Certifications -->
                            <div class="form-step" id="step4">
                                <h5>Valid Certificate :

                                </h5><br>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Vessel Test Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_vessel_test_date"
                                            value="<?php echo e($vms->vessel_test_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Vessel Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_vessel_due_date"
                                            value="<?php echo e($vms->vessel_due_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Vessel Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="vesselUpload">
                                        <input type="file" class="d-none" name="pressure_vessel_file"
                                            <?php if(empty($vms->vessel_certiicate)): ?><?php echo e('required'); ?><?php endif; ?>>
                                        <?php if(!empty($vms->vessel_certiicate)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->vessel_certiicate)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->vessel_certiicate)); ?>

                                                </a>
                                                <input type="hidden" name="existing_pressure_vessel_file"
                                                    value="<?php echo e($vms->vessel_certiicate); ?>">
                                            </div>
                                        <?php endif; ?>

                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Gauge Calibration Date<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_gauge_date"
                                            value="<?php echo e($vms->pressure_gauge_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Gauge Calibration Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_gauge_due_date"
                                            value="<?php echo e($vms->pressure_gauge_due_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pressure Gauge Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="vesselUpload">
                                        <input type="file" class="d-none" name="pressure_gauge_file"
                                            <?php if(empty($vms->pressure_gauge_certificate)): ?> <?php echo e('required'); ?><?php endif; ?>>
                                        <?php if(!empty($vms->pressure_gauge_certificate)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->pressure_gauge_certificate)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->pressure_gauge_certificate)); ?>

                                                </a>
                                                <input type="hidden" name="existing_pressure_gauge_file"
                                                    value="<?php echo e($vms->pressure_gauge_certificate); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Relief Valve Test Date<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_relief_test_date"
                                            value="<?php echo e($vms->pressure_relief_test_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Relief Valve Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_relief_due_date"
                                            value="<?php echo e($vms->pressure_relief_due_date); ?>" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pressure Relief Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="vesselUpload">
                                        <input type="file" class="d-none" name="pressure_relief_file"
                                            <?php if(empty($vms->pressure_relief_certificate)): ?> <?php echo e('required'); ?> <?php endif; ?>>
                                        <?php if(!empty($vms->pressure_relief_certificate)): ?>
                                            <div class="file-preview-success" id="filePreview">
                                                <a href="<?php echo e(asset($vms->pressure_relief_certificate)); ?>" target="_blank">
                                                    📄 <?php echo e(basename($vms->pressure_relief_certificate)); ?>

                                                </a>
                                                <input type="hidden" name="existing_pressure_relief_file"
                                                    value="<?php echo e($vms->pressure_relief_certificate); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                Note : Every Field is Mandatory to have if any point is selected as 'No' Then Safety
                                Department will reject the request.
                                
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary next-step" style="display: none;"
                                    id="nextsave">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button type="button" class="btn btn-success submit-step" id="savepreview">
                                    <i class="fas fa-check me-2"></i> Save & Preview
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="pageLoader"
        class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50"
        style="z-index: 2000;">
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="spinner-border text-primary mb-2" role="status"></div>
            <p class="mb-0 fw-bold">Saving...</p>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-eye"></i> Review & Confirm</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#pv-basic" type="button" role="tab">Basic Info</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#pv-lead" type="button" role="tab">Legal & Statutory Compliance</but
                                    ton>
                        </li>
                        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#pv-lag" type="button" role="tab">Safety Parameters</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#pv-valid_certificate" type="button" role="tab">Valid
                                Certificates</button></li>
                    </ul>

                    <div class="tab-content p-3">

                        <!-- Basic Info -->
                        <div class="tab-pane fade show active" id="pv-basic" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6"><strong>Work Order No:</strong> <span
                                        id="pv_work_order_no"></span></div>
                                <div class="col-md-6"><strong>Validity:</strong> <span id="pv_validity"></span></div>
                                <div class="col-md-6"><strong>Division:</strong> <span id="pv_division"></span></div>
                                <div class="col-md-6"><strong>Section:</strong> <span id="pv_section"></span></div>
                                <div class="col-md-6"><strong>Approver:</strong> <span id="pv_approver"></span></div>
                            </div>
                        </div>

                        <!--  Legal Compliance -->

                        <div class="tab-pane fade" id="pv-lead" role="tabpanel">

                            <div class="row g-3">

                                <div class="col-md-6"><strong>Vehicle Registration No:</strong> <span
                                        id="pv_vehicle_reg_no"></span></div>

                                <div class="col-md-6"><strong>Registration Proof:</strong> <span
                                        id="pv_vehicle_reg_file"></span></div>

                                <div class="col-md-6"><strong>Insurance From:</strong> <span
                                        id="pv_insurance_from"></span></div>

                                <div class="col-md-6"><strong>Insurance To:</strong> <span id="pv_insurance_to"></ span>
                                </div>

                                <div class="col-md-6"><strong>Insurance Certificate:</strong>
                                    <span id="pv_insurance_file"></span>
                                </div>

                                <div class="col-md-6"><strong>Fitness Date:</strong> <span id="pv_fitness_date"></span>
                                </div>
                                <div class="col-md-6"><strong>Fitness Certificate:</strong> <span
                                        id="pv_fitness_file"></span></div>
                                <div class="col-md-6"><strong>PUC Inspection Date:</strong> <span
                                        id="pv_puc_inspection_date"></span></div>
                                <div class="col-md-6"><strong>PUC Due Date:</strong> <span id="pv_puc_due_date"></span>
                                </div>

                                <div class="col-md-6"><strong>PUC Certificate:</strong> <span id="pv_puc_file"></span>
                                </div>

                                <div class="col-md-6"><strong>Road Permit From:</strong>
                                    <sp a n id="pv_valid_road_permit_date"></span>
                                </div>

                                <div class="col-md-6"><strong>Road Permit Due Date:</strong> <span
                                        id="pv_valid_road_permit_due_date"></span></div>
                                <div class="col-md-6"><strong>Road Permit Certificate:</strong> <span
                                        id="pv_road_permit_certificate"></span></div>

                            </div>

                        </div>

                        <!-- Safety Parameters -->
                        <div class="tab-pane fade" id="pv-lag" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6"><strong>Vehicle Deputed For:</strong> <span
                                        id="pv_vehicle_deputed_for"></span></div>

                                <div class="col-md-6"><strong>DFMS Available:</strong> <span
                                        id="pv_dfms_available"></span></div>

                                <div class="col-md-6"><strong>GPS Tracker:</strong> <span
                                        id="pv_gps_tracker_available"></span></div>

                                <div class="col-md-6"><strong>Hatch Strainers:</strong>
                                    <sp a n id="pv_hatch_strainers"></span>
                                </div>

                                <div class="col-md-6"><strong>Fuel Tank Strainer:</strong> <span
                                        id="pv_fuel_tank_stainers"></span></div>

                                <div class="col-md-6"><strong>Battery Placement:</strong> <span
                                        id="pv_battery_placement"></span></div>
                                <div class="col-md-6"><strong>Fire Extinguisher:</strong> <span
                                        id="pv_fire_extinguisher"></span></div>
                                <div class="col-md-6"><strong>First Aid Box:</strong> <span
                                        id="pv_first_aid_box"></span></div>
                                <div class="col-md-6"><strong>Stepney:</strong> <span id="pv_stepney"></span></div>

                                <div class="col-md-6"><strong>Scotch Blocks:</strong> <span id="pv_scotch_block"></span>
                                </div>
                                <div class="col-md-6"><strong>Earth Chain:</strong> <span id="pv_earth_block"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Valid Certificates -->
                        <div class="tab-pane fade" id="pv-valid_certificate" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6"><strong>Pressure Vessel Test Date:</strong> <span
                                        id="pv_pressure_vessel_test_date"></span></div>
                                <div class="col-md-6"><strong>Pressure Vessel Due Date:</strong> <span
                                        id="pv_pressure_vessel_due_date"></span></div>
                                <div class="col-md-6"><strong>Vessel Certificate:</strong> <span
                                        id="pv_pressure_vessel_file"></span></div>
                                <div class="col-md-6"><strong>Pressure Gauge Calibration Date:</strong> <span
                                        id="pv_pressure_gauge_date"></span></div>
                                <div class="col-md-6"><strong>Pressure Gauge Calibration Due Date:</strong> <span
                                        id="pv_pressure_gauge_due_date"></span></div>
                                <div class="col-md-6"><strong>Gauge Certificate:</strong> <span
                                        id="pv_pressure_gauge_file"></span></div>
                                <div class="col-md-6"><strong>Pressure Relief Test Date:</strong> <span
                                        id="pv_pressure_relief_test_date"></span></div>
                                <div class="col-md-6"><strong>Pressure Relief Due Date:</strong> <span
                                        id="pv_pressure_relief_due_date"></span></div>
                                <div class="col-md-6"><strong>Relief Certificate:</strong> <span
                                        id="pv_pressure_relief_file"></span></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="consentCheck">
                        <label class="form-check-label" for="consentCheck">I confirm the information and attachments are
                            accurate.</label>
                    </div>
                </div>

                <div class="modal-footer flex-column align-items-start">

                    <div class="d-flex justify-content-end w-100 gap-2">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-warning" id="btnDraft">
                            <i class="bi bi-save"></i> Save as Draft
                        </button>
                        <button class="btn btn-success" id="btnFinalSubmit" disabled>
                            <i class="bi bi-check2-circle"></i> Submit Final
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            let currentStep = 1;
            const totalSteps = 4;

            // Initialize form
            showStep(currentStep);

            // Show current step and update indicators
            function showStep(step) {
                // Hide all steps
                $('.form-step').removeClass('active');

                // Show current step
                $(`#step${step}`).addClass('active');

                // Update step indicators
                $('.step').removeClass('active completed');
                for (let i = 1; i <= step; i++) {
                    if (i < step) {
                        $(`#step${i}-indicator`).addClass('completed');
                    } else {
                        $(`#step${i}-indicator`).addClass('active');
                    }
                }

                // Update navigation buttons
                $('.prev-step').prop('disabled', step === 1);
                $('.next-step').toggle(step < totalSteps);
                $('.submit-step').toggle(step === totalSteps);
            }

            // Next button click handler
            $(document).on('click', '.next-step', function () {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                    // Scroll to top of form
                    $('html, body').animate({
                        scrollTop: $('.card-body').offset().top - 20
                    }, 300);
                }
            });

            // Previous button click handler
            $(document).on('click', '.prev-step', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                    // Scroll to top of form
                    $('html, body').animate({
                        scrollTop: $('.card-body').offset().top - 20
                    }, 300);
                }
            });

            // Form submission handler
            $('#workOrderForm').submit(function (e) {
                e.preventDefault();
                if (validateStep(currentStep)) {
                    alert('Form submitted successfully!');
                    // Uncomment for actual form submission:
                    // this.submit();
                }
            });

            // Validate current step
            function validateStep(step) {
                let isValid = true;
                const stepForm = $(`#step${step}`);

                // Validate all required fields in current step
                stepForm.find('[required]').each(function () {
                    if (!$(this).val() ||
                        ($(this).is(':checkbox') && !$(this).is(':checked')) ||
                        ($(this).is('select') && $(this).val() === '')) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Special validation for file inputs
                // stepForm.find('input[type="file"][required]').each(function () {
                //     const fileInput = $(this);
                //     const existingField = fileInput.closest('.file-upload-container').find('input[type="hidden"].existing-file');

                //     // Condition: no new file selected AND no existing file path
                //     if (!fileInput.val() && (!existingField.length || !existingField.val())) {
                //         fileInput.addClass('is-invalid');
                //         isValid = false;
                //     } else {
                //         fileInput.removeClass('is-invalid');
                //     }
                // });


                if (!isValid) {
                    // Scroll to first invalid field
                    $('html, body').animate({
                        scrollTop: stepForm.find('.is-invalid').first().offset().top - 100
                    }, 300);
                }

                return isValid;
            }

            // Toggle Other State fields based on selection
            $('#deputedFor').change(function () {
                if ($(this).val() === 'To Other States') {
                    $('#otherStateFields').removeClass('d-none');
                    $('#otherStateFields [required]').prop('required', true);
                } else {
                    $('#otherStateFields').addClass('d-none');
                    $('#otherStateFields [required]').prop('required', false);
                }
            });

            $(document).ready(function () {
                if ($('#deputedFor').val()) {
                    $('#deputedFor').trigger('change');
                }
            });




            // File upload click handlers
            $('.file-upload-container').each(function () {
                const container = $(this);
                const fileInput = container.find('input[type="file"]');
                const uploadBtn = container.find('button');


                // Handle click on upload button
                // uploadBtn.click(function (e) {
                //     e.preventDefault(); // stop default button behavior
                //     fileInput.val('');  // reset so same file can be re-selected
                //     fileInput.click();  // open file picker
                // });


                // Handle file selection
                fileInput.change(function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const validTypes = ['application/pdf'];
                        const maxSize = 5 * 1024 * 1024; // 5MB

                        if (!validTypes.includes(file.type)) {
                            alert('Only PDF files are allowed.');
                            $(this).val('');
                            return;
                        }

                        if (file.size > maxSize) {
                            alert('File size must be less than 5MB.');
                            $(this).val('');
                            return;
                        }

                        // ✅ show selected file name/link in container
                        container.find('.file-name').remove();
                        container.append(`<div class="file-name">
           
        </div>`);
                    }
                });


                // Drag and drop functionality
                container.on('dragover', function (e) {
                    e.preventDefault();
                    container.addClass('dragover');
                });

                container.on('dragleave drop', function (e) {
                    e.preventDefault();
                    container.removeClass('dragover');
                });

                container.on('drop', function (e) {
                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput[0].files = files;
                        fileInput.trigger('change');
                    }
                });
            });
        });



        $(document).ready(function () {
            // Loop over all upload containers
            $('.file-upload-container').each(function () {
                const container = $(this);
                const fileInput = container.find('input[type="file"]');
                const uploadBtn = container.find('button');

                // ✅ Check if existing file (hidden input or preview link) is present
                if (container.find('input[type="hidden"]').length > 0 || container.find('a').length > 0) {
                    container.addClass('has-file'); // mark container as valid
                }

                // Handle file selection
                fileInput.change(function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const validTypes = ['application/pdf'];
                        const maxSize = 5 * 1024 * 1024; // 5MB

                        if (!validTypes.includes(file.type)) {
                            alert('Only PDF files are allowed.');
                            $(this).val('');
                            return;
                        }

                        if (file.size > maxSize) {
                            alert('File size must be less than 5MB.');
                            $(this).val('');
                            return;
                        }

                        // ✅ mark container green
                        container.addClass('has-file');

                        // Show selected file name
                        container.find('.file-name').remove();
                        container.append(`<div class="file-name text-success">
                    📄 ${file.name}
                </div>`);
                    }
                });

                // Drag and drop
                container.on('dragover', function (e) {
                    e.preventDefault();
                    container.addClass('dragover');
                });

                container.on('dragleave drop', function (e) {
                    e.preventDefault();
                    container.removeClass('dragover');
                });

                container.on('drop', function (e) {
                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput[0].files = files;
                        fileInput.trigger('change');
                    }
                });
            });
        });

    </script>






    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function () {
            let selectedPlant = "<?php echo e(old('plant', $vms->section_id ?? '')); ?>";

            let selectedapprover = "<?php echo e(old('approver_id', $vms->approver_id ?? '')); ?>";

            // Division change
            $('#division').on('change', function () {
                var division_ID = $(this).val();
                $("#plant").html('<option value="">--Select Plant--</option>');
                $("#approver_id").html('<option value="">--Select --</option>');
                if (division_ID) {
                    $.ajax({
                        type: 'GET',
                        url: "<?php echo e(route('admin.departmentGet_vendor_mis', '')); ?>/" + division_ID,
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, item) {
                                $("#plant").append('<option value="' + item.id + '">' + item.name + '</option>');
                            });

                            // If we have a saved Plant → select it + trigger change
                            if (selectedPlant) {
                                $("#plant").val(selectedPlant).trigger('change');
                                selectedPlant = ''; // prevent infinite loop
                            }
                        }
                    });

                    $.ajax({
                        type: 'GET',
                        url: "<?php echo e(route('admin.inclusionGet_vendor_silo', '')); ?>/" + selectedPlant,
                        dataType: "json",
                        success: function (data) {
                            $.each(data, function (i, item) {
                                $("#approver_id").append('<option value="' + item.id + '">' + item.name + '</option>');
                            });

                            // If we have a saved Plant → select it + trigger change
                            if (selectedapprover) {
                                $("#approver_id").val(selectedapprover).trigger('change');
                                selectedapprover = ''; // prevent infinite loop
                            }
                        }
                    });
                }
            });



            // On page load → trigger division change if already selected
            if ($('#division').val()) {
                $('#division').trigger('change');
            }
        });

        $('#plant').on('change', function () {
            var plantID = $(this).val();


            $("#department").html('<option value="null">--Select--</option>');
            $("#approver_id").html('<option value="">--Select--</option>');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.PlantGet_vendor_mis')); ?>/" + plantID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#department").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                    }
                }
            });

            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.inclusionGet_vendor_silo')); ?>/" + plantID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#approver_id").append('<option value="' + data[i].id + '" >' + data[i].name + '</option>');
                    }
                }
            });


        });

    </script>


    <style>
        /* Add this to your existing styles */
        .file-upload-success {
            color: #198754;
            display: none;
        }

        .file-upload-container.has-file {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.05);
        }

        .file-info {
            margin-top: 10px;
            display: none;
        }
    </style>

    <!-- Update your JavaScript with this: -->
    <style>
        /* Add this to your existing styles */
        .file-upload-success {
            color: #198754;
            display: none;
        }

        .file-upload-container.has-file {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.05);
        }

        .file-info {
            margin-top: 10px;
            display: none;
            padding: 5px;
            background-color: #e8f5e9;
            border-radius: 4px;
            color: #2e7d32;
        }
    </style>

    <style>
        /* Add this to your existing styles */
        .file-upload-success {
            color: #198754;
            display: none;
        }

        .file-upload-container.has-file {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.05);
        }

        .file-upload-container.has-error {
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }

        .file-info {
            margin-top: 10px;
            display: none;
            padding: 5px;
            border-radius: 4px;
        }

        .file-info.success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .file-info.error {
            background-color: #ffebee;
            color: #c62828;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('.file-upload-container').each(function () {
                const container = $(this);
                const fileInput = container.find('input[type="file"]');
                const uploadBtn = container.find('button');
                const fileInfo = $('<div class="file-info"></div>');

                uploadBtn.after(fileInfo);

                uploadBtn.click(function () {
                    fileInput.click();
                });

                fileInput.change(function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                        const maxSize = 2 * 1024 * 1024; // 2MB

                        container.removeClass('has-error has-file');
                        fileInfo.removeClass('success error').hide();

                        if (!validTypes.includes(file.type)) {
                            showError('Only JPG, PNG, and PDF files are allowed.');
                            return;
                        }

                        if (file.size > maxSize) {
                            showError('File size must be less than 2MB.');
                            return;
                        }

                        container.addClass('has-file');
                        fileInfo.addClass('success')
                            .html(`<i class="fas fa-check-circle me-2"></i> ${file.name} (${formatFileSize(file.size)})`)
                            .show();
                        $(this).removeClass('is-invalid');
                    } else {
                        resetFileUI();
                    }
                });

                function showError(message) {
                    alert(message); // Show alert popup
                    container.addClass('has-error');
                    fileInput.addClass('is-invalid');
                    fileInfo.addClass('error')
                        .html(`<i class="fas fa-exclamation-circle me-2"></i> ${message}`)
                        .show();
                    fileInput.val('');
                }

                function resetFileUI() {
                    container.removeClass('has-file has-error');
                    fileInfo.removeClass('success error').hide();
                    fileInput.removeClass('is-invalid');
                }

                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }

                container.on('dragover', function (e) {
                    e.preventDefault();
                    container.addClass('dragover');
                });

                container.on('dragleave drop', function (e) {
                    e.preventDefault();
                    container.removeClass('dragover');
                });

                container.on('drop', function (e) {
                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput[0].files = files;
                        fileInput.trigger('change');
                    }
                });
            });
        });
    </script>




    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <script type=" text/javascript" src="<?php echo e(asset('js/app.js')); ?>"> </script>
    <script type=" text/javascript" src="<?php echo e(asset('js/sweetalert.js')); ?>"> </script>

    <script type=" text/javascript" src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/dataTables.buttons.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/jszip.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/buttons.html5.min.js')); ?>"> </script>
    <script type="text/javascript" src="<?php echo e(asset('js/all.js')); ?>"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.22.0/autocomplete.jquery.min.js"
        integrity="sha512-sYSJW8c3t/hT4R6toey7NwQmlrPMTqvDk10hsoD8oaeXUZRexAzrmpp5kVlTfy6Ru7b1+Tte2qBrRE7FOX1vgA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- jQuery UI CSS (for styling the autocomplete dropdown) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script type="text/javascript">
        var path = "<?php echo e(route('admin.autocomplete_silo')); ?>";
        $("#work_order_no").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: path,
                    type: 'GET',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function (data) {
                        response(data.map(item => {
                            return item.order_code
                        }));
                    }
                });
            },
        });
    </script>
    <script type="text/javascript">
        $("#work_order_no").blur(function (e) {
            if ($(this).val() != "") {
                var cid = $(this).val();
                // alert(cid);
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('admin.autoworkorder_silo')); ?>/" + cid,
                    contentType: 'application/json',
                    data: { cid: $(this).val() },
                    dataType: "json",
                    success: function (data) {
                        //  alert(data);
                        $("#validity").val(data);
                    }
                })
            }
        });

        // Due date validation
        $(document).ready(function () {
            const dateFields = [
                'insurance_to',
                'fitness_due_date',
                'puc_due_date',
                'valid_road_permit_due_date',
                'pressure_vessel_due_date',
                'pressure_gauge_due_date',
                'pressure_relief_due_date'
            ];

            function validateDateField(input) {
                const today = new Date().toISOString().split('T')[0];
                const selectedDate = $(input).val();
                const fieldName = $(input).attr('name');

                if (selectedDate && selectedDate <= today) {
                    $(input).addClass('is-invalid');
                    $(`#${fieldName}_error`).show();
                } else {
                    $(input).removeClass('is-invalid');
                    $(`#${fieldName}_error`).hide();
                }
            }

            // 🔹 Validate on change
            $('input[name="insurance_to"], input[name="fitness_due_date"], input[name="puc_due_date"], input[name="valid_road_permit_due_date"], input[name="pressure_vessel_due_date"], input[name="pressure_gauge_due_date"], input[name="pressure_relief_due_date"]')
                .on('change', function () {
                    validateDateField(this);
                });

            // 🔹 Validate on load
            dateFields.forEach(field => {
                const input = $(`input[name="${field}"]`);
                if (input.length) validateDateField(input);
            });
        });

    </script>






    <script>
        document.getElementById("consentCheck").addEventListener("change", function () {
            const finalBtn = document.getElementById("btnFinalSubmit");
            finalBtn.disabled = !this.checked; // enable only if checked
        });
        document.getElementById('nextsave').addEventListener('click', e => {
            e.preventDefault();

            document.getElementById('statusField').value = "draft";

            saveForm("Saved successfully!", () => { });
        });
        document.getElementById('btnDraft').addEventListener('click', e => {
            e.preventDefault();

            if (!document.getElementById("consentCheck").checked) {
                alert("You must give consent before submitting!");
                return;
            }

            document.getElementById('statusField').value = "draft";

            saveForm("Draft submission successful!", () => {
                // Show SweetAlert first
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "Draft submission successful!",
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload page after SweetAlert closes
                    location.reload();
                });
            });
        });


        document.getElementById('btnFinalSubmit').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('statusField').value = "final";

            saveForm("Final submission successful!", () => {
                // Show SweetAlert first
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "Final submission successful!",
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload page after SweetAlert closes
                    location.reload();
                });
            });
        });


        // function getVal(id) {
        //     let el = document.getElementById(id);
        //     if (!el) return "";
        //     if (el.tagName === "INPUT" || el.tagName === "SELECT" || el.tagName === "TEXTAREA") {
        //         return el.value.trim();
        //     }
        //     return el.textContent.trim();
        // }
        // function getVal(name) {
        //     let el = document.querySelector(`[name="${name}"]`);
        //     if (!el) return "";
        //     if (el.tagName === "INPUT" || el.tagName === "SELECT" || el.tagName === "TEXTAREA") {
        //         if (el.type === "checkbox") {
        //             return el.checked ? "Yes" : "No";
        //         }
        //         if (el.type === "radio") {
        //             let checked = document.querySelector(`[name="${name}"]:checked`);
        //             return checked ? checked.value : "";
        //         }
        //         return el.value.trim();
        //     }
        //     return el.textContent.trim();
        // }







        function getVal(name) {
            let el = document.querySelector(`[name="${name}"]`);
            if (!el) return "";

            if (el.tagName === "INPUT") {
                if (el.type === "checkbox") {
                    return el.checked ? "Yes" : "No";
                }
                if (el.type === "radio") {
                    let checked = document.querySelector(`[name="${name}"]:checked`);
                    return checked ? checked.value : "";
                }
                if (el.type === "file") {
                    if (el.files && el.files.length > 0) {
                        let links = [];
                        for (let f of el.files) {
                            let url = URL.createObjectURL(f); // temporary blob URL
                            links.push(`<a href="${url}" target="_blank">${f.name}</a>`);
                        }
                        return links.join(", ");
                    }
                    return "No file chosen";
                }
                return el.value.trim();
            }

            if (el.tagName === "SELECT") {
                let sel = el.options[el.selectedIndex];
                return sel ? sel.text.trim() : "";
            }

            if (el.tagName === "TEXTAREA") {
                return el.value.trim();
            }

            return el.textContent.trim();
        }



        function setValue(id, val) {
            let el = document.getElementById(id);
            if (!el) return;

            if (!val) {
                el.innerText = "";
                return;
            }

            // If it's a file object (from getFileVal)
            if (typeof val === "object") {
                // For images show thumbnail, else just a link
                const isImage = /\.(jpg|jpeg|png|gif)$/i.test(val.name);
                if (isImage) {
                    el.innerHTML = `<a href="${val.url}" target="_blank">
                                <img src="${val.url}" alt="${val.name}" style="max-height:60px;"/>
                            </a>`;
                } else {
                    el.innerHTML = `<a href="${val.url}" target="_blank">${val.name}</a>`
                        + (val.isNew ? " <em>(new)</em>" : "");
                }
            } else {
                // Plain text values
                el.innerText = val;
            }
        }

        function getFileVal(name) {
            let input = document.getElementById(name);
            let existingFile = getVal("existing_" + name);

            // Case 1: User uploaded a new file
            if (input && input.files && input.files.length > 0) {
                let file = input.files[0];
                return {
                    url: URL.createObjectURL(file), // blob URL for preview
                    name: file.name,
                    isNew: true
                };
            }

            // Case 2: Show existing saved file
            if (existingFile) {
                return {
                    url: "/" + existingFile,
                    name: existingFile.split("/").pop(),
                    isNew: false
                };
            }

            return null;
        }

        // 🔹 Fill preview modal
        function fillPreview() {
            // Basic Info
            setValue("pv_work_order_no", getVal("work_order_no"));
            setValue("pv_validity", getVal("validity"));
            setValue("pv_division", getVal("division"));
            setValue("pv_section", getVal("plant"));
            setValue("pv_approver", getVal("approver"));

            // Legal Compliance
            setValue("pv_vehicle_reg_no", getVal("vehicle_reg_no"));
            setValue("pv_vehicle_reg_file", getFileVal("vehicle_reg_file"));
            setValue("pv_insurance_from", getVal("insurance_from"));
            setValue("pv_insurance_to", getVal("insurance_to"));
            setValue("pv_insurance_file", getFileVal("insurance_file"));
            setValue("pv_fitness_date", getVal("fitness_date"));
            setValue("pv_fitness_file", getFileVal("fitness_file"));
            setValue("pv_puc_inspection_date", getVal("puc_inspection_date"));
            setValue("pv_puc_due_date", getVal("puc_due_date"));
            setValue("pv_puc_file", getFileVal("puc_file"));
            setValue("pv_valid_road_permit_date", getVal("valid_road_permit_date"));
            setValue("pv_valid_road_permit_due_date", getVal("valid_road_permit_due_date"));
            setValue("pv_road_permit_certificate", getFileVal("road_permit_certificate"));

            // Safety Parameters
            setValue("pv_vehicle_deputed_for", getVal("vehicle_deputed_for"));
            setValue("pv_dfms_available", getVal("dfms_available"));
            setValue("pv_gps_tracker_available", getVal("gps_tracker_available"));
            setValue("pv_hatch_strainers", getVal("hatch_strainers"));
            setValue("pv_fuel_tank_stainers", getVal("fuel_tank_stainers"));
            setValue("pv_battery_placement", getVal("battery_placement"));
            setValue("pv_fire_extinguisher", getVal("fire_extinguisher"));
            setValue("pv_first_aid_box", getVal("first_aid_box"));
            setValue("pv_stepney", getVal("stepney"));
            setValue("pv_scotch_block", getVal("scotch_block"));
            setValue("pv_earth_block", getVal("earth_block"));

            // Valid Certificates
            setValue("pv_pressure_vessel_test_date", getVal("pressure_vessel_test_date"));
            setValue("pv_pressure_vessel_due_date", getVal("pressure_vessel_due_date"));
            setValue("pv_pressure_vessel_file", getFileVal("pressure_vessel_file"));
            setValue("pv_pressure_gauge_date", getVal("pressure_gauge_date"));
            setValue("pv_pressure_gauge_due_date", getVal("pressure_gauge_due_date"));
            setValue("pv_pressure_gauge_file", getFileVal("pressure_gauge_file"));
            setValue("pv_pressure_relief_test_date", getVal("pressure_relief_test_date"));
            setValue("pv_pressure_relief_due_date", getVal("pressure_relief_due_date"));
            setValue("pv_pressure_relief_file", getFileVal("pressure_relief_file"));
        }


        // attach to preview button
        document.getElementById("savepreview").addEventListener("click", function (e) {
            e.preventDefault();
            fillPreview();
            // show modal
            var previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

            document.getElementById('statusField').value = "draft";

            saveForm("Saved successfully!", () => { });

            previewModal.show();
        });








        function showLoader(text = "Saving...") { const loader = document.getElementById('pageLoader'); loader.querySelector('p').innerText = text; loader.classList.remove('d-none'); }
        function hideLoader() { document.getElementById('pageLoader').classList.add('d-none'); }

        function saveForm(successMessage, callback = null) {
            showLoader("Saving...");
            const form = document.getElementById('form');
            const formData = new FormData(form);



            fetch('<?php echo e(route("vendor_silo.store")); ?>', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.id) {
                        document.getElementById('recordId').value = data.id;
                    }

                    // Show SweetAlert instead of alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage,
                        timer: 2000,              // Auto close after 2 seconds
                        showConfirmButton: false
                    });

                    if (callback) callback();
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Something went wrong while saving.",
                    });
                })
                .finally(() => { hideLoader(); });
        }



    </script><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_silo/edit_ifream.blade.php ENDPATH**/ ?>