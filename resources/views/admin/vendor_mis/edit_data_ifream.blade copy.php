<?php
use App\Division;
use App\Department;
use App\UserLogin;

$vms = DB::table('vendor_mis')->where('id', $vms_details->id)->first();

$vms_flow = DB::table('vendor_mis_flow')->where('vendor_mis');
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
    @csrf

    <input type="hidden" value="{{$vms->id}}" name="vendor_mis_id">

    <div class="container mt-4 pb-4">
        <div class="text my-4">
            <h3 class="fw-bold text-dark">
                <i class="fas fa-id-card-alt me-2 text-primary"></i>
                Vendor Safety MIS (Edit)
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
        <style>
            .file-dropzone {
                border: 2px dashed #ccc;
                border-radius: 5px;
                padding: 10px;
                text-align: center;
                cursor: pointer;
                transition: border-color 0.3s, background-color 0.3s;
            }

            .file-dropzone.dragover {
                background-color: #f0f0f0;
                border-color: #999;
            }

            .file-dropzone.success {
                border-color: #28a745 !important;
                background-color: #e6ffed;
                color: #28a745;
            }
        </style>








        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .file-dropzone {
                border: 2px dashed #007bff;
                border-radius: 6px;
                padding: 20px;
                text-align: center;
                color: #6c757d;
                cursor: pointer;
                margin-top: 10px;
                transition: background-color 0.3s;
            }

            .file-dropzone.dragover {
                background-color: #e9f5ff;
                border-color: #0056b3;
                color: #0056b3;
            }

            .file-dropzone span {
                display: block;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .mandatory-star {
                margin-left: 4px;
            }
        </style>
        </head>

        <div class="lead-indicators mt-4">
            <h5 class="fw-bold">Basic Details:</h5>
            <div class="row">

                <div class="form-group col-3">
                    <label for="division">
                        Division <span class="text-danger"><strong>*</strong></span>
                    </label>
                    <select class="form-control indicator-value" name="division" id="division" required>
                        <option value="">Select Division</option>
                        @if($divs->count() > 0)
                        @foreach($divs as $division)
                        <option value="{{ $division->id }}" {{ ($vms->division_id ?? '') == $division->id ? 'selected' :
                            '' }}>
                            {{ $division->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-3">
                    <label for="plant">
                        Plant <span class="text-danger"><strong>*</strong></span>
                    </label>
                    <select class="form-control indicator-value hd" name="plant" id="plant" required>
                        <option value="">Select Plant</option>
                        {{-- This will be auto-populated by jQuery --}}
                    </select>
                </div>
                <div class="form-group col-3">
                    <label for="department">
                        Department <span class="text-danger"><strong>*</strong></span>
                    </label>
                    <select class="form-control indicator-value hd" name="department" id="department" required>
                        <option value="">Select Department</option>
                        {{-- This will be auto-populated by jQuery --}}
                    </select>
                </div>
                <script>
                    $(document).ready(function () {
                        var selectedDivision = "{{ $vms->division_id ?? '' }}";
                        var selectedPlant = "{{ $vms->plant_id ?? '' }}";
                        var selectedDepartment = "{{ $vms->department_id ?? '' }}";

                        function loadPlants(divisionID, callback) {
                            $("#plant").html('<option value="">Select Plant</option>');
                            $("#department").html('<option value="">Select Department</option>');

                            if (!divisionID) return;

                            $.ajax({
                                type: 'GET',
                                url: "{{ route('admin.departmentGet_vendor_mis') }}/" + divisionID,
                                dataType: "json",
                                success: function (data) {
                                    $.each(data, function (i, item) {
                                        var selected = (item.id == selectedPlant) ? 'selected' : '';
                                        $("#plant").append('<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>');
                                    });
                                    if (callback) callback();
                                }
                            });
                        }

                        function loadDepartments(plantID) {
                            $("#department").html('<option value="">Select Department</option>');

                            if (!plantID) return;

                            $.ajax({
                                type: 'GET',
                                url: "{{ route('admin.PlantGet_vendor_mis') }}/" + plantID,
                                dataType: "json",
                                success: function (data) {
                                    $.each(data, function (i, item) {
                                        var selected = (item.id == selectedDepartment) ? 'selected' : '';
                                        $("#department").append('<option value="' + item.id + '" ' + selected + '>' + item.department_name + '</option>');
                                    });
                                }
                            });
                        }

                        // On change of division
                        $('#division').on('change', function () {
                            var divisionID = $(this).val();
                            selectedPlant = ""; // reset selected plant on manual change
                            selectedDepartment = ""; // reset selected dept
                            loadPlants(divisionID);
                        });

                        // On change of plant
                        $('#plant').on('change', function () {
                            var plantID = $(this).val();
                            selectedDepartment = ""; // reset selected dept on manual change
                            loadDepartments(plantID);
                        });

                        // Auto-load if editing existing record
                        if (selectedDivision) {
                            loadPlants(selectedDivision, function () {
                                if (selectedPlant) {
                                    loadDepartments(selectedPlant);
                                }
                            });
                        }
                    });
                </script>

                <div class="form-group col-3">
                    <label for="month">
                        Month <span class="text-danger"><strong>*</strong></span>
                    </label>
                    <input type="month" class="form-control indicator-value" name="month" id="month"
                        value="{{$vms->month}}" required>
                </div>
            </div>
        </div>

        <div class="lead-indicators mt-4">

            <h5 class="fw-bold">Lead Indicators:</h5>

            <!-- Lead indicators 1 to 10 -->
            <!-- Only change index, names, ids and data-targets accordingly -->

            <!-- Template -->
            <!-- Repeat and adjust accordingly -->
            <!-- Example for each lead indicator -->

            <div class="form-group">
                <label>
                    1. Safety Training Session Conducted During The Month
                    <span class="text-danger"><strong>*</strong></span>
                </label>

                <input type="number" class="form-control indicator-value" name="lead1_val" id="lead1_val" min="0"
                    required value="{{$vms->lead1_val}}">
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead1_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead1_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead1_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead1_doc" id="lead1_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>2. Total Training Employee Hours <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead2_val" id="lead2_val" min="0"
                    value="{{$vms->lead2_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead2_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead2_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead2_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead2_doc" id="lead2_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>3. No of Mass Meeting Conducted <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead3_val" id="lead3_val" min="0"
                    value="{{$vms->lead3_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead3_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead3_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead3_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead3_doc" id="lead3_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>4. No of Line Walk Conducted <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead4_val" id="lead4_val" min="0"
                    value="{{$vms->lead4_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead4_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead4_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead4_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead4_doc" id="lead4_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>5. No of Site Safety Audit Conducted <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead5_val" id="lead5_val" min="0"
                    value="{{$vms->lead5_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead5_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead5_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead5_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead5_doc" id="lead5_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>6. No of Housekeeping Audit Conducted <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead6_val" id="lead6_val" min="0"
                    value="{{$vms->lead6_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead6_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead6_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead6_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead6_doc" id="lead6_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>7. No of PPE Audit Conducted <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead7_val" id="lead7_val" min="0"
                    value="{{$vms->lead7_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead7_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead7_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead7_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead7_doc" id="lead7_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>8. No of Tools-Tackles Audit Conducted <span
                        class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead8_val" id="lead8_val" min="0"
                    value="{{$vms->lead8_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead8_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead8_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead8_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead8_doc" id="lead8_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>9. No of Safety Kaizen Done <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead9_val" id="lead9_val" min="0"
                    value="{{$vms->lead9_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead9_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead9_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead9_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead9_doc" id="lead9_doc"
                        accept="application/pdf">
                </div>
            </div>

            <div class="form-group">
                <label>10. No of Near Miss Reported During the Month <span
                        class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lead10_val" id="lead10_val" min="0"
                    value="{{$vms->lead10_val}}" required>
                <label class="mt-2">Attachment</label>
                @if(!empty($vms->lead10_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lead10_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif
                <div class="file-dropzone" data-target="lead10_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lead10_doc"
                        id="lead10_doc" accept="application/pdf">
                </div>
            </div>
        </div>

        <div class="lag-indicators mt-4">
            <h5 class="fw-bold">Lag Indicators:</h5>

            <div class="form-group">
                <label>1. No of First Aid Case <span class="text-danger"><strong>*</strong></span></label>
                <input type="number" class="form-control indicator-value" name="lag1_val" id="lag1_val" min="0"
                    value="{{ $vms->lag1_val }}" required>

                <label class="mt-2">Attachment</label>

                @if (!empty($vms->lag1_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lag1_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
                @endif

                <div class="file-dropzone" data-target="lag1_doc">
                    <span>Drag & drop PDF here or click to upload</span>
                    <input type="file" class="form-control d-none indicator-attachment" name="lag1_doc" id="lag1_doc"
                        accept="application/pdf">
                </div>
            </div>
        </div>


        <div class="form-group">
            <label>2. No of Medical Treated Case <span class="text-danger"><strong>*</strong></span></label>
            <input type="number" class="form-control indicator-value" name="lag2_val" id="lag2_val" min="0"
                value="{{ $vms->lag2_val }}" required>
            <label class="mt-2">Attachment</label>
            @if (!empty($vms->lag2_doc))
            <div class="mb-2">
                <a href="{{ asset($vms->lag2_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    📎 View Existing File
                </a>
            </div>
            @endif
            <div class="file-dropzone" data-target="lag2_doc">
                <span>Drag & drop PDF here or click to upload</span>
                <input type="file" class="form-control d-none indicator-attachment" name="lag2_doc" id="lag2_doc"
                    accept="application/pdf">
            </div>
        </div>

        <div class="form-group">
            <label>3. No of LTIs <span class="text-danger"><strong>*</strong></span></label>
            <input type="number" class="form-control indicator-value" name="lag3_val" id="lag3_val" min="0"
                value="{{ $vms->lag3_val }}" required>
            <label class="mt-2">Attachment</label>
            @if (!empty($vms->lag3_doc))
            <div class="mb-2">
                <a href="{{ asset($vms->lag3_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    📎 View Existing File
                </a>
            </div>
            @endif
            <div class="file-dropzone" data-target="lag3_doc">
                <span>Drag & drop PDF here or click to upload</span>
                <input type="file" class="form-control d-none indicator-attachment" name="lag3_doc" id="lag3_doc"
                    accept="application/pdf">
            </div>
        </div>

        <div class="form-group">
            <label>4. No of Fatality <span class="text-danger"><strong>*</strong></span></label>
            <input type="number" class="form-control indicator-value" name="lag4_val" id="lag4_val" min="0"
                value="{{ $vms->lag4_val }}" required>
            <label class="mt-2">Attachment</label>
            @if (!empty($vms->lag4_doc))
            <div class="mb-2">
                <a href="{{ asset($vms->lag4_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    📎 View Existing File
                </a>
            </div>
            @endif
            <div class="file-dropzone" data-target="lag4_doc">
                <span>Drag & drop PDF here or click to upload</span>
                <input type="file" class="form-control d-none indicator-attachment" name="lag4_doc" id="lag4_doc"
                    accept="application/pdf">
            </div>
        </div>

        <div class="form-group">
            <label>5. No of Non Injury Incident <span class="text-danger"><strong>*</strong></span></label>
            <input type="number" class="form-control indicator-value" name="lag5_val" id="lag5_val" min="0"
                value="{{ $vms->lag5_val }}" required>
            <label class="mt-2">Attachment</label>
            @if (!empty($vms->lag5_doc))
            <div class="mb-2">
                <a href="{{ asset($vms->lag5_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    📎 View Existing File
                </a>
            </div>
            @endif
            <div class="file-dropzone" data-target="lag5_doc">
                <span>Drag & drop PDF here or click to upload</span>
                <input type="file" class="form-control d-none indicator-attachment" name="lag5_doc" id="lag5_doc"
                    accept="application/pdf">
            </div>
        </div>


    </div>

    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" style="font-size: 1.1rem;"
            id="submit-btn">
            <span id="spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
            <i class="fas fa-check-circle me-2" id="btn-icon"></i>
            <span id="btn-text">Submit</span>
        </button>
    </div>

</form>
<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzones = document.querySelectorAll('.file-dropzone');
        const MAX_FILE_SIZE_MB = 2;

        dropzones.forEach(dropzone => {
            const fileInputId = dropzone.getAttribute('data-target');
            const fileInput = document.getElementById(fileInputId);

            // Create error message container if not exists
            let messageBox = dropzone.querySelector('.error-message');
            if (!messageBox) {
                messageBox = document.createElement('div');
                messageBox.classList.add('error-message');
                messageBox.style.color = 'red';
                messageBox.style.fontSize = '0.85em';
                messageBox.style.marginTop = '5px';
                dropzone.appendChild(messageBox);
            }

            const showError = (msg) => {
                messageBox.textContent = msg;
                dropzone.classList.remove('success');
                dropzone.querySelector('span').textContent = "Drag & drop PDF here or click to upload";
                fileInput.value = ''; // Clear input
            };

            const clearError = () => {
                messageBox.textContent = '';
            };

            dropzone.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (file) {
                    if (file.type !== "application/pdf") {
                        showError("Only PDF files are allowed.");
                    } else if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                        showError("File size exceeds 2MB limit.");
                    } else {
                        dropzone.querySelector('span').textContent = file.name;
                        dropzone.classList.add('success');
                        clearError();
                    }
                } else {
                    dropzone.classList.remove('success');
                    showError("No file selected.");
                }
            });

            dropzone.addEventListener('dragover', e => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', e => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                const file = e.dataTransfer.files[0];

                if (file) {
                    if (file.type !== "application/pdf") {
                        showError("Only PDF files are allowed.");
                        return;
                    }
                    if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
                        showError("File is too large. Maximum allowed size is 2MB.");
                        return;
                    }

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    dropzone.querySelector('span').textContent = file.name;
                    dropzone.classList.add('success');
                    clearError();
                } else {
                    showError("No file dropped.");
                }
            });
        });

        // Automatically trigger input logic on load + on change
        const numericInputs = document.querySelectorAll('.indicator-value');

        numericInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                const formGroup = input.closest('.form-group');
                const attachmentInput = formGroup.querySelector('input[type="file"]');
                const attachmentLabel = formGroup.querySelectorAll('label')[1];

                // Remove existing star
                const existingStar = attachmentLabel.querySelector('.mandatory-star');
                if (existingStar) existingStar.remove();

                const value = parseInt(input.value || 0);
                const star = document.createElement('span');
                star.classList.add('text-danger', 'mandatory-star');

                if (value > 0) {
                    star.innerHTML = '<strong>* </strong>&nbsp;(Please upload a file not larger than 2MB)';
                    attachmentLabel.appendChild(star);
                    attachmentInput.removeAttribute('disabled');
                    attachmentInput.setAttribute('', '');
                } else {
                    star.innerHTML = '&nbsp;(Not Required to upload file)';
                    attachmentLabel.appendChild(star);
                    attachmentInput.removeAttribute('required');
                    attachmentInput.setAttribute('disabled', 'disabled');
                }
            });

            // ✅ Call input handler on load to apply logic
            input.dispatchEvent(new Event('input'));
        });
    });
</script>



<script>
    document.getElementById('form').addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-btn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btn-text');

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.innerText = 'Processing...';

        const formData = new FormData(this); // 'this' refers to the form

        fetch('{{ route("vendor_mis.edit_data_update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // Do NOT set 'Content-Type' header manually with FormData
            },
            body: formData
        })
            .then(async response => {
                const contentType = response.headers.get('content-type') || '';

                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`Server responded with error: ${errorText.slice(0, 150)}`);
                }

                if (contentType.includes('application/json')) {
                    return response.json();
                } else {
                    const errorText = await response.text();
                    throw new Error(`Unexpected content type. Expected JSON. Got HTML: ${errorText.slice(0, 150)}`);
                }
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Form submitted successfully!',
                }).then(() => {
                    location.reload(); // Reload page after success
                });
            })
            .catch(error => {
                console.error('Form submission error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    html: `<small>${error.message.replace(/\n/g, '<br>')}</small>`
                });
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
                btnText.innerText = 'Submit';
            });
    });
</script>



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{--
<script>

    document.getElementById('form').addEventListener('submit', function () {
        const submitBtn = document.getElementById('submit-btn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btn-text');

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none'); // Show spinner
        btnText.innerText = 'Processing...';
    });
</script> --}}




<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>







<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropzones = document.querySelectorAll('.dropzone-wrapper');

        dropzones.forEach(function (zone) {
            const input = zone.querySelector('input[type="file"]');
            const desc = zone.querySelector('.dropzone-desc');

            let errorMsg = zone.querySelector('small.error-msg');
            if (!errorMsg) {
                errorMsg = document.createElement('small');
                errorMsg.classList.add('error-msg', 'text-danger');
                zone.appendChild(errorMsg);
            }

            function showError(message) {
                errorMsg.textContent = message;
                zone.classList.add('error');
            }

            function clearError() {
                errorMsg.textContent = '';
                zone.classList.remove('error');
            }

            function isPdfFile(file) {
                return file.name.toLowerCase().endsWith('.pdf');
            }

            function isFileSizeValid(file) {
                return file.size <= 2 * 1024 * 1024; // 2 MB
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
                        showError('File must be less than or equal to 2 MB!');
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
                        showError('File must be less than or equal to 2 MB!');
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

        // ⭐ Dynamic toggle of file input required state
        const numericInputs = document.querySelectorAll('.indicator-value');

        numericInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                const formGroup = input.closest('.form-group');
                const attachmentInput = formGroup.querySelector('input[type="file"]');
                const attachmentLabel = formGroup.querySelectorAll('label')[1];

                // Remove existing message
                const existingStar = attachmentLabel.querySelector('.mandatory-star');
                if (existingStar) existingStar.remove();

                const value = parseInt(input.value || 0);
                const star = document.createElement('span');
                star.classList.add('text-danger', 'mandatory-star');

                if (value > 0) {
                    star.innerHTML = '<strong>* </strong>&nbsp;(Please upload a file not larger than 2MB)';
                    attachmentLabel.appendChild(star);
                    attachmentInput.removeAttribute('disabled');
                    attachmentInput.setAttribute('required', 'required');
                } else {
                    star.innerHTML = '&nbsp;(Not Required to upload file)';
                    attachmentLabel.appendChild(star);

                    attachmentInput.removeAttribute('required');
                    attachmentInput.setAttribute('disabled', 'disabled');
                }
            });
        });
    });



</script>







<script type="text/javascript" src="{{ asset('js/app.js') }}"> </script>
<script type=" text/javascript" src="{{ asset('js/sweetalert.js') }}"> </script>

<script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"> </script>
<script type="text/javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"> </script>
<script type="text/javascript" src="{{ asset('js/jszip.min.js') }}"> </script>
<script type="text/javascript" src="{{ asset('js/buttons.html5.min.js') }}"> </script>
<script type="text/javascript" src="{{ asset('js/all.js') }}"> </script>

<!-- <script type="text/javascript" src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"> </script> -->
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