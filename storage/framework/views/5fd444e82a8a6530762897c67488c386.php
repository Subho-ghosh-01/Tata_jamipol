<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dynamic SILO Form</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #eef2f7;
        }

        .card {
            border-radius: 1rem;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            padding: 1.2rem 1.5rem;
        }

        .card-header h3 {
            font-weight: 600;
            margin: 0;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.35rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.6rem;
            padding: 0.65rem 0.8rem;
            border: 1px solid #d4d8dd;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
        }

        .form-check {
            margin-bottom: 0.4rem;
        }

        .btn-primary {
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 0.6rem;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }

        .divider {
            border-top: 1px dashed #d1d5db;
            margin: 2rem 0;
        }

        .big-checkbox {
            transform: scale(1.5);
            margin-right: 8px;
            cursor: pointer;
        }

        /* Glow Effect */
        .glow-checkbox {
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.8);
            border-radius: 4px;
            transition: 0.3s ease-in-out;
        }

        /* Optional: pulse animation to make glow stand out */
        .glow-checkbox {
            animation: pulseGlow 1.5s infinite;
        }



        /* Style for multiple select */
        .stylish-multi {
            min-height: 140px;
            /* bigger box */
            border-radius: 0.6rem;
            padding: 0.6rem;
            background: #fff;
            overflow-y: auto;
        }

        /* Option styling */
        .stylish-multi option {
            padding: 8px 12px;
            margin: 2px 0;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Highlight when selected */
        .stylish-multi option:checked {
            background: #0d6efd;

            color: #fff;
        }
    </style>
    <style>
        /* Custom Multi-select look */
        .custom-multi {
            position: relative;
        }

        .custom-multi select[multiple] {
            display: none;
            /* hide the raw multiple select */
        }

        .custom-multi .selected-options {
            border: 1px solid #d4d8dd;
            border-radius: 0.6rem;
            padding: 0.6rem;
            min-height: 45px;
            background: #fff;
            cursor: pointer;
        }

        .custom-multi .selected-options span {
            display: inline-block;
            background: #3d73c5;
            color: #fff;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .custom-multi .dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #d4d8dd;
            border-radius: 0.6rem;
            background: #fff;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .custom-multi .dropdown-list label {
            display: block;
            padding: 6px 12px;
            cursor: pointer;
        }

        .custom-multi .dropdown-list label:hover {
            background: #f1f5f9;
        }
    </style>
</head>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".custom-multi").forEach(function (wrapper) {
            const select = wrapper.querySelector("select[multiple]");
            const selectedBox = document.createElement("div");
            selectedBox.className = "selected-options";
            selectedBox.textContent = "Select options...";
            wrapper.appendChild(selectedBox);

            const dropdown = document.createElement("div");
            dropdown.className = "dropdown-list";
            Array.from(select.options).forEach(opt => {
                const label = document.createElement("label");
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.value = opt.value;
                checkbox.checked = opt.selected;
                label.appendChild(checkbox);
                label.append(" " + opt.text);
                dropdown.appendChild(label);

                checkbox.addEventListener("change", () => {
                    opt.selected = checkbox.checked;
                    updateSelected();
                });
            });
            wrapper.appendChild(dropdown);

            function updateSelected() {
                const chosen = Array.from(select.selectedOptions).map(o => o.text);
                selectedBox.innerHTML = chosen.length
                    ? chosen.map(text => `<span>${text}</span>`).join("")
                    : "Select options...";
            }
            updateSelected();

            // Toggle dropdown
            selectedBox.addEventListener("click", () => {
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            });

            // Close when clicking outside
            document.addEventListener("click", e => {
                if (!wrapper.contains(e.target)) {
                    dropdown.style.display = "none";
                }
            });
        });
    });
</script>

<body class="py-5">
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

    <div class="container" style="max-width: 900px;">
        <div class="card shadow-lg">
            <div
                class="card-header text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <h3 class="mb-0">📋 Daily Inspection System</h3>
                <div id="google_translate_element" class="mt-2 mt-sm-0"></div>
            </div>
            <div class="card-body p-4">

                <form id="dynamicForm" class="needs-validation" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Division <span class="text-danger">*</span></label>
                            <select class="form-select" name="division_id" id="division" required>
                                <option value="">Select Division</option>
                                <?php if($divs->count() > 0): ?>
                                    <?php $__currentLoopData = $divs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <select class="form-select" name="section_id" id="plant" required>
                                <option value="">Select Section</option>

                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Active Silo Tanker List <span class="text-danger">*</span></label>
                            <select class="form-select" name="silo_id" id="" required>
                                <option value="">Select Active Silo Tanker</option>
                                <?php if($active_list->count() > 0): ?>
                                    <?php $__currentLoopData = $active_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($list->id); ?>"><?php echo e($list->full_sl); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="result" class="form-label">Photo (Optional for driver)</label>
                            <input type="file" class="form-control" name="photo">

                            <span class="text-danger"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-4" id="formFields">
                        <!-- Fields will be rendered here -->
                    </div>

                    <div class="divider"></div>
                    <div class="row">
                        <div class="form-group col-md-auto">
                            <label for="language-selector" class="form-label">Select Language to Speak</label>
                            <div class="input-group">
                                <select asp-for="Language" id="language-selector" class="form-control"
                                    style="margin-right:10px;" required>
                                    <option>--Please choose a language to start speaking--</option>
                                    <option value="en">English</option>
                                    <option value="hi">Hindi</option>
                                    <option value="bn">Bengali</option>
                                    <option value="or">Oriya</option>
                                    <option value="te">Telugu</option>
                                    <option value="mr">Marathi</option>
                                    <option value="ta">Tamil</option>
                                    <option value="gu">Gujarati</option>
                                    <option value="ml">Malayalam</option>
                                    <option value="kn">Kannada</option>
                                    <option value="pa">Punjabi</option>
                                    <option value="ur">Urdu</option>
                                </select>
                                <button id="start-btn" type="button" class="btn btn-primary btn-sm"
                                    style="margin-right:10px;">
                                    <i class="fas fa-play" style="margin-right: 8px;"></i> Start Recording
                                </button>
                                <button id="stop-btn" type="button" disabled class="btn btn-danger btn-sm"
                                    style="display:none; margin-right:10px;">
                                    <i class="fas fa-stop" style="margin-right: 8px;"></i> Stop Recording
                                </button>
                                <button id="download-audio" type="button" class="btn btn-warning btn-sm"
                                    style="display:none; margin-right:10px;">
                                    <i class="fas fa-download" style="margin-right: 8px;"></i> Download Audio
                                </button>
                                <button id="play-audio" type="button" class="btn btn-info btn-sm"
                                    style="display:none; margin-right:10px;">
                                    <i class="fas fa-play-circle btn-icon"></i> Play Voice
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Consent Checkbox -->
                    <!-- Consent Checkbox -->
                    <!-- Consent Checkbox -->
                    <!-- Row 5 -->
                    <div class="row p-3">

                        <div class="form-group col-md-6">
                            <label for="result" class="form-label">Remarks (Local Language)</label>
                            <textarea rows="3" id="transcription" class="form-control"
                                placeholder="Your local language speech will appear here..."
                                name="remarks_local"></textarea>
                            <div class="loader" id="loader" style="display: none"></div>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="result" class="form-label required">Remarks (English)</label>
                            <textarea asp-for="Details" id="translation" rows="3" class="form-control"
                                placeholder="Your english translation will appear here..." required
                                name="remarks_english"></textarea>
                            <div class="loader" id="loader" style="display: none"></div>
                            <span class="text-danger"></span>
                        </div>
                        <div class="loader" id="loader" style="display:none;"></div>
                        <input type="file" name="remarks_voice" id="audio-file-input" accept="audio/*"
                            class="form-control" style="display:none;" />
                    </div>

                    <br>
                    <hr>
                    <div class="form-check mb-3 text-center">

                        <label class="form-check-label ms-2" for="consent"><input
                                class="form-check-input big-checkbox glow-checkbox" type="checkbox" id="consent"
                                required>&nbsp;&nbsp;
                            I hereby confirm that the information provided is true and complete.
                        </label>
                        <div class="invalid-feedback">
                            You must agree before submitting.
                        </div>
                    </div>



                    <div class="mt-3 d-flex justify-content-center">
                        <button type="submit" id="submitBtn" class="btn btn-primary w-50" disabled>Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5.3.3 Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Bootstrap JS Bundle -->

    <script>
        const consentCheckbox = document.getElementById('consent');
        const submitBtn = document.getElementById('submitBtn');

        consentCheckbox.addEventListener('change', function () {
            submitBtn.disabled = !this.checked;
        });
    </script>
    <script>
        // Laravel fields passed to JS
        const fields = <?php echo json_encode($fields, 15, 512) ?>;

        const formFields = document.getElementById('formFields');

        fields.forEach(field => {
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6';

            // Label
            const label = document.createElement('label');
            label.className = 'form-label';
            label.setAttribute('for', field.name);
            if (field.isrequired == 1) {
                label.innerHTML = field.label + ' <span style="color:red">*</span>';
            } else {
                label.innerText = field.label;
            }

            col.appendChild(label);

            let input;
            const options = field.options ? JSON.parse(field.options) : [];

            if (['text', 'number', 'email', 'date'].includes(field.type)) {
                input = document.createElement('input');
                input.type = field.type;
                input.name = field.name;
                input.id = field.name;
                input.className = 'form-control';
                input.placeholder = 'Enter ' + field.label.toLowerCase();
                if (field.isrequired == 1) input.required = true;

            } else if (field.type === 'textarea') {
                input = document.createElement('textarea');
                input.name = field.name;
                input.id = field.name;
                input.className = 'form-control';
                input.rows = 3;
                input.placeholder = 'Enter ' + field.label.toLowerCase();
                if (field.isrequired == 1) input.required = true;
            } else if (field.type === 'select') {
                let wrapper = document.createElement('div');

                // Create select element
                const select = document.createElement('select');
                select.name = field.ismultiple == 1 ? field.name + '[]' : field.name;
                select.id = field.name;
                select.className = 'form-select';
                if (field.isrequired == 1) select.required = true;

                if (field.ismultiple == 1) {
                    select.multiple = true;
                    wrapper.className = 'custom-multi'; // wrapper for custom multi dropdown
                } else {
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.innerText = '-- Select --';
                    select.appendChild(defaultOption);
                }

                options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.innerText = opt;
                    select.appendChild(option);
                });

                wrapper.appendChild(select);
                input = wrapper;

            } else if (field.type === 'radio' || field.type === 'checkbox') {
                input = document.createElement('div');
                options.forEach(opt => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'form-check';

                    const inp = document.createElement('input');
                    inp.type = field.type;
                    inp.name = field.type === 'checkbox' ? field.name + '[]' : field.name;
                    inp.value = opt;
                    inp.className = 'form-check-input';
                    inp.id = field.name + '_' + opt.replace(/\s+/g, '_');
                    if (field.isrequired == 1 && field.type === 'radio') inp.required = true;

                    const span = document.createElement('label');
                    span.className = 'form-check-label';
                    span.setAttribute('for', inp.id);
                    span.innerText = opt;

                    wrapper.appendChild(inp);
                    wrapper.appendChild(span);
                    input.appendChild(wrapper);
                });
            }


            if (input) {
                col.appendChild(input);

                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.innerText = 'Required field';
                col.appendChild(feedback);
            }


            formFields.appendChild(col);
        });

        // Bootstrap validation
        // Bootstrap validation
        (function () {
            'use strict';

            const form = document.getElementById('dynamicForm');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated'); // triggers invalid-feedback display
            }, false);
        })();



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
                url: "<?php echo e(route('admin.departmentGet_vendor_mis')); ?>/" + division_ID,
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

    </script>
    <script src="<?php echo e(asset('js/tevo.js')); ?>"></script>
    <script src="<?php echo e(asset('js/speechtotext.js')); ?>"></script>
    <!-- translate silo -->
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $("#dynamicForm").on("submit", function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Show loader, disable button
                $("#loader").show();
                $("#submitBtn").prop("disabled", true).text("Submitting...");

                $.ajax({
                    url: "<?php echo e(route('silo_daily_inspection.store')); ?>", // Your Laravel route
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
                        $("#dynamicForm")[0].reset();
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
</body>

</html><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/silo_daily_inspection.blade.php ENDPATH**/ ?>