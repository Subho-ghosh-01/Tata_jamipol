

<?php
    use App\Division;
    use App\Department;
    use App\UserLogin;
    // Decode stored JSON from inspection table
    $formData = $silo_details->form_data ? json_decode($silo_details->form_data, true) : [];

    $vms = DB::table('silo_inspection')->where('id', $silo_details->id)->first();

    $vms_flow = DB::table('vendor_silo_inspection_flow')->where('vendor_silo_inspection_id', $vms->id)->where('status', 'N')->orderBy('id', 'asc')->first();
    $user_check_safety = UserLogin::where('id', $user_id)->select('clm_role')->first();
    $vehicle_status = $vms->status;
$vendor_level = $vms_flow->level ?? 'NA';

?>
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var vehicleStatus = "<?php echo e($vehicle_status); ?>";
    var vendor_level = "<?php echo e($vendor_level); ?>";
    $(document).ready(function () {

        if (vehicleStatus === "pending_with_safety" && vendor_level != '0') {
            // Hide elements
            $('.dn').addClass('d-none');
            $('.hd').prop('disabled', true).addClass('disabled');
        } else if (vehicleStatus === "approve") {
            // Hide everything (same as above, or customize as needed)
            $('.dn').addClass('d-none');
            $('.hd').prop('disabled', true).addClass('disabled');
        } else if (vehicleStatus === "return") {
            $('.dn').addClass('d-none');
            $('.hd').prop('disabled', true).addClass('disabled');
            $('.bn').prop('disabled',true).addClass('disabled');
        } else {
 $('.dn').addClass('d-none');
            $('.hd').prop('disabled', true).addClass('disabled');

        }
    });
    </script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Edit SILO Daily Inspection</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef2f7;
        }

        .card {
            border-radius: 1rem;
        }

        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
        }

        .form-label {
            font-weight: 500;
        }

        .divider {
            border-top: 1px dashed #ccc;
            margin: 20px 0;
        }

        .big-checkbox {
            transform: scale(1.5);
            margin-right: 8px;
            cursor: pointer;
        }

        .glow-checkbox {
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.8);
            border-radius: 4px;
        }
    </style>
    <style>
        #google_translate_element {
            margin: 15px 0;
        }

        /* Optional: hide ugly Google branding */
        .goog-logo-link,
        .goog-te-gadget span {
            display: none !important;
        }

        .goog-te-gadget {
            font-size: 0 !important;
        }

        /* Remove Google branding */
        .goog-logo-link,
        .goog-te-gadget span {
            display: none !important;
        }

        .goog-te-gadget {
            font-size: 0 !important;
            color: transparent !important;
        }

        /* Hide the top Google Translate banner */
        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        /* Reset body top padding after hiding */
        body {
            top: 0px !important;
        }


        /* Style the actual dropdown */
        .goog-te-gadget select {
            font-size: 14px !important;
            padding: 6px 10px !important;
            border-radius: 0.5rem !important;
            border: 1px solid #ced4da !important;
            background-color: #fff !important;
            color: #212529 !important;
            appearance: none !important;
            cursor: pointer !important;
            width: 200px !important;
        }

        /* Add hover/focus effect */
        .goog-te-gadget select:focus {
            outline: none !important;
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25) !important;
        }
    </style>
</head>

<body class="py-5">

    <div class="container" style="max-width: 1050px;">
        <div class="card shadow-lg">
            <div
                class="card-header text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <h3 class="mb-0">📋 Daily Inspection System</h3>
                <div id="google_translate_element" class="mt-2 mt-sm-0"></div>
            </div>
            <div class="card-body p-4">

                <form id="dynamicForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="id" value="<?php echo e($silo_details->id); ?>">

                    <div class="row">
                        <!-- Division -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Division</label>
                            <select class="form-select hd " name="division_id" id="division" required>
                                <option value="">Select Division</option>
                                <?php $__currentLoopData = $divs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($division->id); ?>" <?php echo e($silo_details->division_id == $division->id ? 'selected' : ''); ?>>
                                        <?php echo e($division->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Section -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section</label>
                            <select class="form-select hd" name="section_id" id="plant" required>
                                <option value="">Select Section</option>

                            </select>
                        </div>

                        <!-- Silo Tanker -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Active Silo Tanker</label>
                            <select class="form-select hd" name="silo_id" required>
                                <option value="">Select Tanker</option>
                                <?php $__currentLoopData = $active_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($list->id); ?>" <?php echo e($silo_details->silo_tanke_id == $list->id ? 'selected' : ''); ?>>
                                        <?php echo e($list->full_sl); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Photo -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control hd" name="image">
                            <?php if($silo_details->image): ?>
                                <small>Current: <a href="<?php echo e(asset('' . $silo_details->image)); ?>"
                                        target="_blank">View</a></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    
                    <div class="row" id="formFields"></div>

                    <hr>

                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Remarks (Local)</label>
                            <textarea class="form-control hd" rows="3"
                                name="remarks_local"><?php echo e($silo_details->remarks_local); ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Remarks (English)</label>
                            <textarea class="form-control hd" rows="3"
                                name="remarks_english"><?php echo e($silo_details->remarks_english); ?></textarea>
                        </div>
                    </div>

                    
                    <div class="form-check mb-3 text-center dn">

                        <label class="form-check-label ms-2" for="consent"><input
                                class="form-check-input big-checkbox glow-checkbox" type="checkbox" id="consent"
                                required>&nbsp;&nbsp;
                            I hereby confirm that the information provided is true and complete.
                        </label>
                        <div class="invalid-feedback">
                            You must agree before submitting.
                        </div>
                    </div>

                    
                    <div class="mt-3 d-flex justify-content-center dn">
                        <button type="submit" id="submitBtn" class="btn btn-primary w-50" disabled>Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <?php
        $VendorMisFlows = DB::table('vendor_silo_inspection_flow')
            ->where('vendor_silo_inspection_id', $vms->id)
            ->where('status', 'Y')
            ->get();
    ?>

    <?php $__empty_1 = true; $__currentLoopData = $VendorMisFlows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vms1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        
            
            <div class="card-body material-card mt-4 shadow rounded-3">
                <div class="material-header mb-3">
                    <center>
                        <h3>📋 Decision Panel</h3>
                    </center>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">📝 Decision</label>
                        <input type="text" class="form-control"
                            value="<?php echo e(ucfirst($vms1->decision ?? 'Edited/Corrected  by Vendor')); ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">📅 Decision Datetime</label>
                        <input type="text" class="form-control"
                            value="<?php echo e(date('d-m-Y H:i:s', strtotime($vms1->remarks_datetime))); ?>" disabled>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">💬 Remarks</label>
                    <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder=""
                        disabled><?php echo e($vms1->remarks); ?></textarea>
                </div>
            </div>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

    <?php endif; ?>

    <?php  
        $flow = DB::table('vendor_silo_inspection_flow')->where('vendor_silo_inspection_id', $vms->id)->where('status', 'N')->where('level', '!=', '0')->first();
    ?>

    <?php if(($user_check_safety->clm_role == 'Safety_dept' && @$flow->department_id == '2') || (6 == $user_id && $vms->status == 'pending_with_vendor_supervisor')): ?>
       
            
            <div class="card-body material-card mt-4 shadow rounded-3" <?php if(@$flow->id): ?> <?php echo e(''); ?><?php else: ?><?php echo e('hidden'); ?><?php endif; ?>>

                <form id="hr_form" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="material-header mb-3">
                        <center>
                            <h3>👷‍♂️Approval Panel</h3>
                        </center>
                    </div>
                    <input type="hidden" name="flow_id" value="<?php echo e($flow->id ?? ''); ?>">
                    <input type="hidden" name="type" value="New">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Action </label>
                        <div class="d-flex gap-2">
                            <div>
                                <input type="radio" class="btn-check" hidden name="action" id="btn-approve" value="approve"
                                    autocomplete="off">
                                <label class="btn btn-outline-success px-4 py-2 rounded-pill" for="btn-approve">
                                    <i class="fas fa-check-circle me-1"></i> Approve
                                </label>
                            </div>
                            &nbsp;&nbsp;
                            <div>
                                <input type="radio" class="btn-check" name="action" id="btn-return" value="return"
                                    autocomplete="off" hidden>
                                <label class="btn btn-outline-danger px-4 py-2 rounded-pill" for="btn-return">
                                    <i class="fas fa-undo-alt me-1"></i> Reject
                                </label>
                            </div>
                        </div>
                        <div id="action-error" class="text-danger small mt-1 d-none">Please select an action.</div>
                    </div>


                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remarks </label>

                        <textarea name="remarks" rows="4" class="form-control shadow-sm"
                            placeholder="Write your remarks here..." required></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm bn"
                            style="font-size: 1.1rem;" id="submit-btn">
                            <span id="spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                            <i class="fas fa-check-circle me-2" id="btn-icon"></i>
                            <span id="btn-text">Submit</span>
                        </button>
                    </div>
                </form>
                </div>

    <?php endif; ?>

    
    <style>
    .material-header {
        background-color: #c9d64db0;
        padding-top: 10px;
        padding-bottom: 10px;
        border-radius: 50px;

    }

    .material-card {
        background-color: #ffffff9d;
        padding: 20px;
        border-radius: 30px;
        box-shadow: 0 6px 12px 18px rgba(0, 0, 0, 0.08);
    }

    .form-control,
    textarea {
        border-radius: 30px;
        transition: 0.3s ease-in-out;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        border-color: #80bdff;
    }

    textarea {
        resize: vertical;
    }


    .btn-check:checked+.btn-outline-success {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(5, 221, 55, 0.3);
    }

    .btn-check:checked+.btn-outline-danger {
        background-color: hsl(12, 100%, 51%);
        color: #f4faff;
        border-color: #ff3907fa;
        box-shadow: 0 0 0 0.25rem rgba(238, 21, 5, 0.3);
    }

    .material-header i {
        font-size: 1.2rem;
        vertical-align: middle;
    }
</style>    
    <!-- jQuery + Bootstrap + SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const fields = <?php echo json_encode($fieldMasters, 15, 512) ?>;
        const savedData = <?php echo json_encode($formData, 15, 512) ?>; // decoded json from DB
        const formFields = document.getElementById('formFields');

        fields.forEach(field => {
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6 mb-3';

            // Label
            const label = document.createElement('label');
            label.className = 'form-label';
            label.setAttribute('for', field.name);
            label.innerHTML = field.label + (field.isrequired == 1 ? ' <span style="color:red">*</span>' : '');
            col.appendChild(label);

            let input;
            const options = field.options ? JSON.parse(field.options) : [];
            const savedValue = savedData[field.name] ?? '';

            // ---------- text / number / email / date ----------
            if (['text', 'number', 'email', 'date'].includes(field.type)) {
                input = document.createElement('input');
                input.type = field.type;
                input.name = field.name;   // ✅ FIXED
                input.id = field.name;
                input.className = 'form-control hd';
                input.value = savedValue;
                if (field.isrequired == 1) input.required = true;

                // ---------- textarea ----------
            } else if (field.type === 'textarea') {
                input = document.createElement('textarea');
                input.name = field.name;   // ✅ FIXED
                input.id = field.name;
                input.className = 'form-control hd';
                input.rows = 3;
                input.value = savedValue;
                if (field.isrequired == 1) input.required = true;

                // ---------- select ----------
            } else if (field.type === 'select') {
                input = document.createElement('select');
                input.name = field.ismultiple == 1 ? field.name + "[]" : field.name;   // ✅ FIXED
                input.id = field.name;
                input.className = 'form-select hd';
                if (field.isrequired == 1) input.required = true;
                if (field.ismultiple == 1) input.multiple = true;

                if (field.ismultiple != 1) {
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.innerText = '-- Select --';
                    input.appendChild(defaultOption);
                }

                options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.innerText = opt;
                    if (field.ismultiple == 1 && Array.isArray(savedValue)) {
                        if (savedValue.includes(opt)) option.selected = true;
                    } else {
                        if (savedValue == opt) option.selected = true;
                    }
                    input.appendChild(option);
                });

                // ---------- radio / checkbox ----------
            } else if (field.type === 'radio' || field.type === 'checkbox') {
                input = document.createElement('div');
                options.forEach(opt => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'form-check';

                    const inp = document.createElement('input');
                    inp.type = field.type;
                    inp.name = field.type === 'checkbox'
                        ? field.name + "[]"   // ✅ FIXED
                        : field.name;        // ✅ FIXED
                    inp.value = opt;
                    inp.className = 'form-check-input';
                    inp.id = field.name + '_' + opt.replace(/\s+/g, '_');

                    if (field.type === 'checkbox' && Array.isArray(savedValue)) {
                        if (savedValue.includes(opt)) inp.checked = true;
                    } else {
                        if (savedValue == opt) inp.checked = true;
                    }

                    const span = document.createElement('label');
                    span.className = 'form-check-label';
                    span.setAttribute('for', inp.id);
                    span.innerText = opt;

                    wrapper.appendChild(inp);
                    wrapper.appendChild(span);
                    input.appendChild(wrapper);
                });
            }

            if (input) col.appendChild(input);
            formFields.appendChild(col);
        });

        // Bootstrap validation
        (function () {
            'use strict';
            const form = document.getElementById('dynamicForm');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        })();
    </script>
    <script>
        $(document).ready(function () {
            $("#dynamicForm").on("submit", function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Show loader, disable button
                $("#loader").show();
                $("#submitBtn").prop("disabled", true).text("Submitting...");

                $.ajax({
                    url: "<?php echo e(route('silo_daily_inspection.store', '')); ?>",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("#loader").hide();
                        $("#submitBtn").prop("disabled", false).text("Submit");

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Form submitted successfully!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Optionally reset form
                        setTimeout(() => {
                            window.location.reload(); // ✅ reloads current page
                        }, 2000);
                    },
                    error: function (xhr) {
                        $("#loader").hide();
                        $("#submitBtn").prop("disabled", false).text("Submit");

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || "Something went wrong. Please try again."
                        });
                    }
                });
            });
        });
    </script>
    <script> $('#division').on('change', function () {
            var division_ID = $(this).val();

            $("#plant").html('<option value="">--Select--</option>');
            $("#department").html('<option value="">--Select--</option>');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.departmentGet_vendor_mis', '')); ?>/" + division_ID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    let savedPlant = "<?php echo e($silo_details->section_id ?? ''); ?>"; // 👈 comes from DB

                    for (var i = 0; i < data.length; i++) {
                        let selected = (savedPlant == data[i].id) ? 'selected' : '';
                        $("#plant").append('<option value="' + data[i].id + '" ' + selected + '>' + data[i].name + '</option>');
                    }
                }
            });
        });

        // 👇 trigger once on page load (for edit)
        $(document).ready(function () {
            if ($("#division").val()) {
                $("#division").trigger('change');
            }
        });

    </script>
    <script>
        const consentCheckbox = document.getElementById('consent');
        const submitBtn = document.getElementById('submitBtn');

        consentCheckbox.addEventListener('change', function () {
            submitBtn.disabled = !this.checked;
        });
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                {
                    pageLanguage: 'en',
                    includedLanguages: 'en,hi,bn,ta,te,ml,gu,kn,mr,pa',
                    autoDisplay: false
                },
                'google_translate_element'
            );
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>

        document.getElementById('hr_form').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = document.getElementById('hr_form');
            const submitBtn = document.getElementById('submit-btn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btn-text');
            const actionError = document.getElementById('action-error');

            const actionSelected = document.querySelector('input[name="action"]:checked');
            if (!actionSelected) {
                actionError.classList.remove('d-none');
                return;
            } else {
                actionError.classList.add('d-none');
            }

            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.innerText = 'Processing...';

            const formData = new FormData(form);
            formData.append('_method', 'PUT'); // Laravel method spoofing for update

            fetch('<?php echo e(route("silo_daily_inspection.update", $silo_details->id)); ?>', {
                method: 'POST', // still POST, Laravel treats it as PUT because of the _method field
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(async res => {
                    const contentType = res.headers.get("content-type");
                    if (!res.ok) {
                        if (contentType && contentType.includes("application/json")) {
                            const errorData = await res.json();
                            throw new Error(errorData.message || 'Server Error');
                        } else {
                            const errorText = await res.text(); // fallback if JSON fails
                            throw new Error(errorText);
                        }
                    }
                    return res.json();
                })
                .then(data => {
                    Swal.fire('Success', data.message, 'success').then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'An unexpected error occurred!'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.innerText = 'Submit';
                });
        });

    </script>
</body>

</html><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_silo/edit_data_ifream_silo_daily.blade.php ENDPATH**/ ?>