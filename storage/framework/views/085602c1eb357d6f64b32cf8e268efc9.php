<?php
use App\Division;
use App\Department;
use App\UserLogin;

if ($usertype == '1') {
    $type = "Employee"; // don't change, other need to change many 
    $not_required = '';
} else {
    $type = "Vendor"; // don't change, other need to change many 
    $not_required = 'hidden';
}
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
</style>



<form action="" method="post" enctype="multipart/form-data" id="form" autocomplete="off" id="form">
    <?php echo csrf_field(); ?>

    <input type="hidden" value="<?php echo e($id); ?>" name="uid">
    <input type="hidden" value="<?php echo e($usertype); ?>" name="utype">
    <div class="container mt-4 pb-4">
        <div class="text my-4">
            <h3 class="fw-bold text-dark">
                <i class="fas fa-id-card-alt me-2 text-primary"></i>
                <?php echo e($type ?? ''); ?> Vehicle Gate Pass Management System
            </h3>

            <div style="display: flex; align-items: center;">
                <i class="vehicle" style="color: #afbdd3; font-size: 24px; margin-right: 10px;"></i>
                <hr class="animated-hr" />
            </div>


        </div>

        <style>
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






        <div class="form-group">
            <label>Apply Vehicle Pass For</label>
            <select class="form-control" name="vehicle_type_required" required>
                <option value="">--Select--</option>
                <option value="two_wheeler">🏍️ Two Wheeler</option>
                <?php if($type == 'Employee'): ?>
                    <option <?php echo e($not_required ?? ''); ?> value="four_wheeler">🚗 Car</option>
                <?php endif; ?>
            </select>
        </div>
        <?php if($type != 'Employee'): ?>
            <div class="form-group">
                <label>Employee Name</label>
                <input type="text" class="form-control" name="emp_name" placeholder="" required>
            </div>
            <div class="form-group">
                <label>Gate Pass No</label>
                <input type="text" class="form-control" name="gp" placeholder="" required>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label>Vehicle Owner Name</label>
            <input type="text" class="form-control" name="owner_name" placeholder="" required>
        </div>






        <div class="form-group">
            <label>Vehicle Registration No.</label>
            <input type="text" class="form-control" name="registration_no" required>
            <label class="mt-2">Upload Registration Certificate (RC)</label>

            <div class="dropzone-wrapper">
                <div class="dropzone-desc"><i class="fa fa-upload"></i>
                    <p>Drag & Drop file or click to browse</p>
                </div>
                <input type="file" name="rc_attachment" accept="application/pdf" required>
            </div>

            <div class="file-preview text-secondary small mt-1"></div>
            <small class="form-text text-muted ml-1 small">Only PDF file are allowed. Max size 5MB.</small>
        </div>


        <div class="form-group">
            <label>Vehicle Insurance Validity</label>
            <div class="form-row">
                <div class="col"> <label for="insurance_from"><small>From</small></label><input type="date"
                        class="form-control" name="insurance_from" required></div>
                <div class="col"><label for="insurance_from"><small>To</small></label><input type="date"
                        class="form-control" name="insurance_to" required></div>
            </div>
            <label class="mt-2">Upload Insurance Document</label>
            <div class="dropzone-wrapper dropzone">
                <div class="dropzone-desc"><i class="fa fa-upload"></i>
                    <p>Drag & Drop file or click to browse</p>
                </div>
                <input type="file" name="insurance_attachment" accept="application/pdf" required>
                <div class="file-preview text-secondary small mt-1"></div>
            </div>
            <small class="form-text text-muted ml-1 small">Only PDF file are allowed. Max size 5MB.</small>
        </div>


        <div class="form-group">
            <label>Vehicle Type</label>
            <select class="form-control" name="vehicle_category" id="vehicle_category" required>
                <option value="">--Select--</option>
                <option value="Petrol">⛽ Petrol</option>
                <?php if($type == 'Employee'): ?>
                    <option <?php echo e($not_required ?? ''); ?> value="Diesel">🛢️ Diesel</option>
                    <option <?php echo e($not_required ?? ''); ?> value="CNG">🧯 CNG</option>
                <?php endif; ?>
                <option value="EV">🔌 EV</option>
                <?php if($type == 'Employee'): ?>
                    <option <?php echo e($not_required ?? ''); ?> value="Hybrid">♻️ Hybrid</option>
                <?php endif; ?>

            </select>
        </div>


        <div class="form-group">
            <label>Vehicle Registration Date</label>
            <input type="date" class="form-control" name="registration_date" required>
        </div>


        <div class="form-group puc">
            <label>PUC Validity</label>
            <div class="form-row">
                <div class="col"><input type="date" class="form-control" name="puc_from"></div>
                <div class="col"><input type="date" class="form-control" name="puc_to"></div>
            </div>
            <label class="mt-2">Upload PUC Document</label>
            <div class="dropzone-wrapper dropzone">
                <div class="dropzone-desc"><i class="fa fa-upload"></i>
                    <p>Drag & Drop file or click to browse</p>
                </div>
                <input type="file" name="puc_attachment" accept="application/pdf">
            </div>
            <small class="form-text text-muted ml-1 small">Only PDF file are allowed. Max size 5MB.</small>
        </div>

        <?php if($type == 'Employee'): ?>
            <div class="form-group">
                <label>Vehicle Will be Driven By</label>
                <select class="form-control" id="driver_type" name="driver_type" required>
                    <option value="">--Select--</option>
                    <option value="self">🙋‍♂️ Self</option>
                    <option value="driver">👤 Through Driver</option>
                </select>
            </div>



        <?php endif; ?>
        <div class="form-group driver-fields">
            <label>Driver’s Name</label>
            <input type="text" class="form-control" name="driver_name">
        </div>
        <div class="form-group">
            <label>Driving License No.</label>
            <input type="text" class="form-control" name="license_no">
        </div>


        <div class="form-group">
            <label>Driving License Validity</label>
            <div class="form-row">
                <div class="col"><input type="date" class="form-control" name="license_valid_from" required></div>
                <div class="col"><input type="date" class="form-control" name="license_valid_to" required></div>
            </div>
            <label class="mt-2">Upload Driving License</label>
            <div class="dropzone-wrapper dropzone">
                <div class="dropzone-desc"><i class="fa fa-upload"></i>
                    <p>Drag & Drop file or click to browse</p>
                </div>
                <input type="file" name="license_attachment" accept="application/pdf" required>
            </div>
            <small class="form-text text-muted ml-1 small">Only PDF file are allowed. Max size 5MB.</small>
        </div><!-- Add this right before your closing </form> tag -->
        <style>
            #previewBtn:hover {
                background-color: #17a2b8;
                color: white;
            }
        </style>

        <div class="text-center mt-4">
            <button type="button" id="previewBtn"
                style="background-color:#0dcaf0; border:none; color:rgb(24, 23, 23); padding:8px 30px; border-radius:50px;">
                <i class="fas fa-eye"></i> Preview & Submit
            </button>
        </div>



        <!-- Preview Modal Structure -->
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-id-card-alt me-2"></i>
                            Vehicle Pass Application Preview
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="previewContent">
                        <!-- Preview content will be inserted here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-edit me-2"></i> Edit Details
                        </button>
                        <button id="confirmSubmit" class="btn btn-success" disabled>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                            <span id="btn-text"> Confirm & Submit</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Add this script section after your existing scripts -->
        <script>
            // Single submit handler for the form
            document.getElementById('form').addEventListener('submit', function (e) {
                e.preventDefault();
                submitForm();
            });

            // Preview button click handler
            // Preview button click handler
            document.getElementById('previewBtn').addEventListener('click', function () {
                // Validate required fields
                let isValid = true;
                document.querySelectorAll('[required]').forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please fill all required fields before previewing',
                        confirmButtonColor: '#003366'
                    });
                    return;
                }

                // Gather form data for preview
                const formData = {
                    vehicle_type: document.querySelector('[name="vehicle_type_required"]').value,
                    emp_name: document.querySelector('[name="emp_name"]')?.value || 'N/A',
                    gp_number: document.querySelector('[name="gp"]')?.value || 'N/A',
                    owner_name: document.querySelector('[name="owner_name"]').value,
                    registration_no: document.querySelector('[name="registration_no"]').value,
                    insurance: `${document.querySelector('[name="insurance_from"]').value} to ${document.querySelector('[name="insurance_to"]').value}`,
                    vehicle_category: document.querySelector('[name="vehicle_category"]').value,
                    registration_date: document.querySelector('[name="registration_date"]').value,
                    puc_validity: document.querySelector('[name="puc_from"]') ?
                        `${document.querySelector('[name="puc_from"]').value || 'N/A'} to ${document.querySelector('[name="puc_to"]').value || 'N/A'}` : 'N/A',
                    driver_type: document.querySelector('[name="driver_type"]')?.value || 'N/A',
                    driver_name: document.querySelector('[name="driver_name"]')?.value || 'N/A',
                    license_no: document.querySelector('[name="license_no"]').value,
                    license_validity: `${document.querySelector('[name="license_valid_from"]').value} to ${document.querySelector('[name="license_valid_to"]').value}`
                };

                function getFilePreviewLink(inputName) {
                    const fileInput = document.querySelector(`[name="${inputName}"]`);
                    if (!fileInput || !fileInput.files.length) return 'Not uploaded';
                    const file = fileInput.files[0];
                    const url = URL.createObjectURL(file);
                    return `<a href="${url}" download="${file.name}" target="_blank">${file.name}</a>`;
                }

                const files = {
                    rc: getFilePreviewLink('rc_attachment'),
                    insurance: getFilePreviewLink('insurance_attachment'),
                    puc: getFilePreviewLink('puc_attachment'),
                    license: getFilePreviewLink('license_attachment')
                };

                function generatePreviewItem(label, value) {
                    return `
            <div class="preview-item">
                <span class="preview-label">${label}:</span>
                <span class="preview-value">${value}</span>
            </div>`;
                }

                const previewHTML = `
        <div class="preview-container">
            <div class="preview-header mb-4">
                <h4 class="text-center mb-3" style="color: #003366;">
                    <i class="fas fa-car me-2"></i> Vehicle Pass Application Summary
                </h4>
                <hr style="border-top: 2px solid #003366;">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="preview-section mb-4">
                        <h5 class="preview-title"><i class="fas fa-info-circle me-2"></i> Basic Information</h5>
                        ${generatePreviewItem('Vehicle Type', formData.vehicle_type.replace('_', ' ').toUpperCase())}
                        ${formData.emp_name !== 'N/A' ? generatePreviewItem('Employee Name', formData.emp_name) : ''}
                        ${formData.gp_number !== 'N/A' ? generatePreviewItem('Gate Pass No', formData.gp_number) : ''}
                        ${generatePreviewItem('Owner Name', formData.owner_name)}
                    </div>

                    <div class="preview-section mb-4">
                        <h5 class="preview-title"><i class="fas fa-car me-2"></i> Vehicle Details</h5>
                        ${generatePreviewItem('Registration No', formData.registration_no)}
                        ${generatePreviewItem('Registration Date', formData.registration_date)}
                        ${generatePreviewItem('Vehicle Category', formData.vehicle_category)}
                        ${generatePreviewItem('RC Document', files.rc)}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="preview-section mb-4">
                        <h5 class="preview-title"><i class="fas fa-shield-alt me-2"></i> Insurance & Validity</h5>
                        ${generatePreviewItem('Insurance Period', formData.insurance)}
                        ${generatePreviewItem('Insurance Document', files.insurance)}
                        ${formData.puc_validity !== 'N/A to N/A' ?
                        generatePreviewItem('PUC Validity', formData.puc_validity) +
                        generatePreviewItem('PUC Document', files.puc) : ''}
                    </div>

                    ${formData.driver_type !== 'N/A' ? `
                    <div class="preview-section mb-4">
                        <h5 class="preview-title"><i class="fas fa-user me-2"></i> Driver Information</h5>
                        ${generatePreviewItem('Driver Type', formData.driver_type === 'self' ? 'Self' : 'Through Driver')}
                        ${formData.driver_name !== 'N/A' ? generatePreviewItem('Driver Name', formData.driver_name) : ''}
                    </div>` : ''}

                    <div class="preview-section">
                        <h5 class="preview-title"><i class="fas fa-id-card me-2"></i> License Details</h5>
                        ${generatePreviewItem('License No', formData.license_no)}
                        ${generatePreviewItem('License Validity', formData.license_validity)}
                        ${generatePreviewItem('License Document', files.license)}
                    </div>
                </div>
            </div>

            <div class="preview-consent mt-4 p-3 bg-light rounded">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="consentCheck" required>
                    <label class="form-check-label" for="consentCheck">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        I hereby declare that all the information provided is true and correct to the best of my knowledge.
                        I understand that any false information may result in rejection of my application.
                    </label>
                </div>
            </div>
        </div>`;

                // Insert preview HTML into modal
                document.getElementById('previewContent').innerHTML = previewHTML;

                // Disable confirm button initially
                const confirmSubmitBtn = document.getElementById('confirmSubmit');
                confirmSubmitBtn.disabled = true;

                // Setup consent checkbox listener
                const consentCheckbox = document.getElementById('consentCheck');
                consentCheckbox.addEventListener('change', function () {
                    confirmSubmitBtn.disabled = !this.checked;
                });

                // Show modal
                window.scrollTo(0, 0);
                const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
                previewModal.show();
            });

            // Confirm submit button click: call submitForm()
            document.getElementById('confirmSubmit').addEventListener('click', function () {
                submitForm();
                // Do NOT close the modal here, keep it open until submission completes
            });

            // submitForm function sends form data via fetch
            function submitForm() {
                const form = document.getElementById('form');
                const submitBtn = document.getElementById('confirmSubmit');
                const spinner = document.getElementById('spinner');
                const btnText = document.getElementById('btn-text');

                // Disable submit button & show spinner
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
                btnText.innerText = 'Processing...';

                const formData = new FormData(form);

                fetch('<?php echo e(route("vms_ifream.store")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || 'Form submitted successfully!',
                            icon: 'success',
                            confirmButtonColor: '#003366'
                        }).then(() => {
                            // Reload page after user confirms alert
                            window.location.reload();
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'Failed to submit form',
                            icon: 'error',
                            confirmButtonColor: '#003366'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                        btnText.innerText = 'Submit';
                        // Modal stays open until reload
                    });
            }

        </script>


        <!-- Add this CSS -->
        <style>
            .preview-container {
                font-family: 'Nunito', sans-serif;
            }

            .preview-title {
                color: #003366;
                font-size: 1.1rem;
                border-bottom: 1px solid #eee;
                padding-bottom: 5px;
                margin-bottom: 15px;
            }

            .preview-item {
                display: flex;
                margin-bottom: 10px;
                padding-bottom: 10px;
                border-bottom: 1px dashed #eee;
            }

            .preview-label {
                font-weight: 600;
                color: #555;
                min-width: 150px;
            }

            .preview-value {
                color: #333;
                word-break: break-word;
            }

            .preview-consent {
                border-left: 4px solid #ffc107;
            }

            #previewModal .modal-body {
                padding: 25px;
            }

            #previewModal .modal-header {
                border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            }

            #previewModal .modal-footer {
                border-top: 2px solid rgba(0, 0, 0, 0.1);
            }

            .is-invalid {
                border-color: #dc3545 !important;
            }
        </style>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            // document.getElementById('form').addEventListener('submit', function (e) {
            //     e.preventDefault();

            //     const submitBtn = document.getElementById('submit-btn');
            //     const spinner = document.getElementById('spinner');
            //     const btnText = document.getElementById('btn-text');

            //     // Show loading state
            //     submitBtn.disabled = true;
            //     spinner.classList.remove('d-none');
            //     btnText.innerText = 'Processing...';

            //     const form = document.getElementById('form');
            //     const formData = new FormData(form);

            //     fetch('<?php echo e(route("vms_ifream.store")); ?>', {
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //             // DO NOT add 'Content-Type' when using FormData
            //         },
            //         body: formData
            //     })
            //         .then(async res => {
            //             const contentType = res.headers.get("content-type") || "";

            //             if (!res.ok) {
            //                 const errorText = await res.text();
            //                 throw new Error(errorText);
            //             }

            //             if (contentType.includes("application/json")) {
            //                 return res.json();
            //             } else {
            //                 const errorText = await res.text();
            //                 throw new Error("Expected JSON but got HTML: " + errorText.slice(0, 100));
            //             }
            //         })
            //         .then(data => {
            //             Swal.fire('Success', data.message || 'Form submitted successfully!', 'success')
            //                 .then((result) => {
            //                     if (result.isConfirmed || result.isDismissed) {
            //                         location.reload();
            //                     }
            //                 });
            //         })
            //         .catch(err => {
            //             console.error(err);
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Error',
            //                 text: err.message || 'An unexpected error occurred!'
            //             });
            //         })
            //         .finally(() => {
            //             submitBtn.disabled = false;
            //             spinner.classList.add('d-none');
            //             btnText.innerText = 'Submit';
            //         });

            // });
        </script>



        <!-- SweetAlert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Show/hide driver fields
            document.getElementById("driver_type").addEventListener("change", function () {
                var value = this.value;
                document.querySelector(".driver-fields").style.display = (value === "driver") ? "block" : "none";
            });

            // Initial check
            document.querySelector(".driver-fields").style.display = "none";
        </script>


        <script>
            // Show/hide driver fields
            document.getElementById("vehicle_category").addEventListener("change", function () {
                var value = this.value;
                document.querySelector(".puc").style.display = (value === "EV") ? "none" : "block";
            });

            // Initial check
            document.querySelector(".puc").style.display = "block";
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
        </script><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vms_ifream/create.blade.php ENDPATH**/ ?>