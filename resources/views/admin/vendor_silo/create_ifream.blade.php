<?php
use App\Division;
use App\Department;
use App\UserLogin;


?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'JAMIPOL SURAKSHA') }}</title>
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<!-- <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css"/> -->
<!-- Styles -->
<link href="{{  asset('css/app.css') }}" rel="stylesheet">
<link href="{{  asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{  asset('css/buttons.dataTables.min.css') }}" rel="stylesheet">
<link href="{{  asset('css/sweetalert.css') }}" rel="stylesheet">
<link href="{{  asset('css/admin.css')}}" rel="stylesheet">
<link href="{{  asset('css/fontawesome-free/css/all.min.css') }}">

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

                        <form id="workOrderForm" enctype="multipart/form-data" novalidate>
                            @csrf

                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="step1">
                                <h5>Basic Information:</h5>
                                <br>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Work Order No <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="work_order_no"
                                            id="work_order_no" placeholder="Enter Work-Order No" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Validity <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="validity" id="validity" required
                                            readonly>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Division <span class="text-danger">*</span></label>
                                        <select class="form-select" name="division" id="division" required>
                                            <option value="">Select Division</option>
                                            @if($divs->count() > 0)
                                                @foreach($divs as $division)
                                                    <option value="{{$division->id}}">{{$division->name}}</option>
                                                @endforeach
                                            @endif
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
                                    <select class="form-select" name="approver" required>
                                        <option value="">Select Approver</option>
                                        <option value="1">Approver 1</option>
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
                                        placeholder="Enter Vehicle Registration No" required>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Registration Proof <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="regUpload">
                                        <input type="file" class="d-none" name="vehicle_reg_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Insurance From <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="insurance_from" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Insurance To <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="insurance_to" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Insurance Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="insuranceUpload">
                                        <input type="file" class="d-none" name="insurance_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Valid Fitness Inspection Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fitness_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fitness Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="fitnessUpload">
                                        <input type="file" class="d-none" name="fitness_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PUC Inspection Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="puc_inspection_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PUC Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="puc_due_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PUC Certificate <span class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="pucUpload">
                                        <input type="file" class="d-none" name="puc_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <label class="form-label">Valid Road Permit<span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"> From Date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="puc_inspection_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PUC Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="puc_due_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Road Permit Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="pucUpload">
                                        <input type="file" class="d-none" name="puc_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
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
                                        <option value="Local Movement">Local Movement</option>
                                        <option value="To Other States">To Other States</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div id="otherStateFields" class="d-none">
                                    <div class="mb-3">
                                        <label class="form-label">DFMS Available <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="dfms_available" required>
                                            <option value="">Select Option</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">GPS Tracker Available <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="gps_tracker_available" required>
                                            <option value="">Select Option</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Hatch Strainers In All Hatch<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="hatch_strainers" name="hatch_strainers" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Fuel Tank Strainer<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="fuel_tank_stainers" name="fuel_tank_stainers"
                                        required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Battery Placement at Outside of Prime Mover<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="battery_placement" name="battery_placement"
                                        required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availablility of Fire Extinguisher <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="fire_extinguisher" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of First Aid Box <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="first_aid_box" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Stepney (Spare Tyre) <span
                                            class="text-danger">*</span></label>


                                    <select class="form-select" name="stepney" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div class="invalid-feedback">Required field</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Scotch Blocks (4 Nos)<span
                                            class="text-danger">*</span></label>


                                    <select class="form-select" name="scotch_block" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Availability of Earth Chain<span
                                            class="text-danger">*</span></label>


                                    <select class="form-select" name="earth_block" required>
                                        <option value="">Select Option</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
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
                                            required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Vessel Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_vessel_due_date"
                                            required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Vessel Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="vesselUpload">
                                        <input type="file" class="d-none" name="pressure_vessel_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Gauge Calibration Date<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_gauge_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Gauge Calibration Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_gauge_due_date" required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pressure Gauge Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="vesselUpload">
                                        <input type="file" class="d-none" name="pressure_gauge_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Relief Valve Test Date<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_relief_test_date"
                                            required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pressure Relief Valve Due Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="pressure_relief_due_date"
                                            required>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pressure Relief Certificate <span
                                            class="text-danger">*</span></label>
                                    <div class="file-upload-container" id="vesselUpload">
                                        <input type="file" class="d-none" name="pressure_vessel_file" required>
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fas fa-upload me-2"></i>Upload File
                                        </button>
                                        <p class="mt-2 mb-0 small text-muted">(Max 2MB, PDF/JPG/PNG)</p>
                                        <div class="invalid-feedback">Required field</div>
                                    </div>
                                </div>
                                Note : Every Field is Mandatory to have if any point is selected as 'No' Then Safety
                                Department will reject the request.
                                {{-- <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="declaration">
                                    <label class="form-check-label" for="declaration">
                                        I certify that all information provided is accurate
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="invalid-feedback">You must accept the declaration</div>
                                </div> --}}
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary next-step" style="display: none;">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button type="submit" class="btn btn-success submit-step">
                                    <i class="fas fa-check me-2"></i> Submit
                                </button>
                            </div>
                        </form>
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
                stepForm.find('input[type="file"][required]').each(function () {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

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

            // File upload click handlers
            $('.file-upload-container').each(function () {
                const container = $(this);
                const fileInput = container.find('input[type="file"]');
                const uploadBtn = container.find('button');

                // Handle click on upload button
                uploadBtn.click(function () {
                    fileInput.click();
                });

                // Handle file selection
                fileInput.change(function () {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                        const maxSize = 2 * 1024 * 1024; // 2MB

                        if (!validTypes.includes(file.type)) {
                            alert('Only JPG, PNG, and PDF files are allowed.');
                            $(this).val('');
                            return;
                        }

                        if (file.size > maxSize) {
                            alert('File size must be less than 2MB.');
                            $(this).val('');
                            return;
                        }

                        // Remove invalid class if file is valid
                        $(this).removeClass('is-invalid');
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
    </script>






    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $('#division').on('change', function () {
            var division_ID = $(this).val();

            $("#plant").html('<option value="">--Select--</option>');
            $("#department").html('<option value="null">--Select--</option>');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "{{route('admin.departmentGet_vendor_mis')}}/" + division_ID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#plant").append('<option value="' + data[i].id + '" >' + data[i].name + '</option>');
                    }
                }
            });


        });




        $('#plant').on('change', function () {
            var plantID = $(this).val();


            $("#department").html('<option value="null">--Select--</option>');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "{{route('admin.PlantGet_vendor_mis')}}/" + plantID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#department").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
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




    <script type=" text/javascript" src="{{ asset('js/app.js') }}"> </script>
    <script type=" text/javascript" src="{{ asset('js/sweetalert.js') }}"> </script>

    <script type=" text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/jszip.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/buttons.html5.min.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/all.js') }}"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.22.0/autocomplete.jquery.min.js"
        integrity="sha512-sYSJW8c3t/hT4R6toey7NwQmlrPMTqvDk10hsoD8oaeXUZRexAzrmpp5kVlTfy6Ru7b1+Tte2qBrRE7FOX1vgA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- jQuery UI CSS (for styling the autocomplete dropdown) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script type="text/javascript">
        var path = "{{ route('admin.autocomplete_silo') }}";
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
                    url: "{{route('admin.autoworkorder_silo')}}/" + cid,
                    contentType: 'application/json',
                    data: { cid: $(this).val() },
                    dataType: "json",
                    success: function (data) {
                        //  alert(data);
                        $("#validity").val(data);
                    }
                })
            }
        })

    </script>
    <!-- <script type="text/javascript" src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"> </script> -->