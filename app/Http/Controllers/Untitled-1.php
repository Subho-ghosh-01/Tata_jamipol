<?php
use App\Division;
use App\Department;
use App\UserLogin;
$vms = DB::table('vendor_mis')->where('id', $vms_details->id)->first();
$vms_flow = DB::table('vendor_mis_flow')->where('vendor_mis_id', $vms->id)->where('status', 'N')->orderBy('id', 'asc')->first();

$vehicle_status = $vms->status;
$vendor_level = $vms_flow->level ?? 'NA';
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>



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

<script>
    var vehicleStatus = "{{ $vehicle_status }}";
    var vendor_level = "{{$vendor_level}}";
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


        } else {


        }
    });
</script>


<form action="" method="post" enctype="multipart/form-data" id="form" autocomplete="off">
    @csrf
    <input type="hidden" value="{{$vms->id}}" name="vendor_mis_id">

    <!-- ======= TABS ======= -->
    <ul class="nav nav-tabs pro-tabs justify-content-start" id="vmsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button"
                role="tab">
                <span class="tab-number">1</span> Basic Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="lead-tab" data-bs-toggle="tab" data-bs-target="#lead" type="button" role="tab">
                <span class="tab-number">2</span> Lead Indicators
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="lag-tab" data-bs-toggle="tab" data-bs-target="#lag" type="button" role="tab">
                <span class="tab-number">3</span> Lag Indicators
            </button>
        </li>
    </ul>



    <style>
        /* Professional / Wizard-style Tabs */
        .pro-tabs {
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
            padding: 0 10px;
        }

        .pro-tabs .nav-link {
            border: none;
            color: #495057;
            font-weight: 500;
            position: relative;
            padding: 10px 20px;
            margin-right: 5px;
            transition: all 0.3s;
            border-radius: 8px 8px 0 0;
        }

        .pro-tabs .nav-link .tab-number {
            display: inline-block;
            background: #dee2e6;
            color: #495057;
            font-weight: bold;
            width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-radius: 50%;
            margin-right: 8px;
            transition: all 0.3s;
        }

        .pro-tabs .nav-link.active {
            background: #007bff;
            color: #fff;
        }

        .pro-tabs .nav-link.active .tab-number {
            background: #fff;
            color: #007bff;
        }

        .pro-tabs .nav-link:hover {
            background: #e9f5ff;
            color: #007bff;
        }

        .pro-tab-content {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }
    </style>


    <!-- ======= TAB CONTENT ======= -->
    <div class="tab-content p-4 border border-top-0" id="vmsTabContent">
        <!-- ======= BASIC DETAILS TAB ======= -->
        <div class="tab-pane fade show active" id="basic" role="tabpanel">
            <div class="row">
                <div class="form-group col-3">
                    <label>Division <span class="text-danger">*</span></label>
                    <select class="form-control indicator-value hd" name="division" id="division" required>
                        <option value="">Select Division</option>
                        @foreach($divs as $division)
                            <option value="{{ $division->id }}" {{ ($vms->division_id ?? '') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-3">
                    <label>Plant <span class="text-danger">*</span></label>
                    <select class="form-control indicator-value hd" name="plant" id="plant" required>
                        <option value="">Select Plant</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label>Department <span class="text-danger">*</span></label>
                    <select class="form-control indicator-value hd" name="department" id="department" required>
                        <option value="">Select Department</option>
                    </select>
                </div>
                <div class="form-group col-3">
                    <label>Month <span class="text-danger">*</span></label>
                    <input type="month" class="form-control indicator-value hd" name="month" id="month"
                        value="{{$vms->month}}" required>
                </div>
            </div>
        </div>

        <!-- ======= LEAD INDICATORS TAB ======= -->
        <div class="tab-pane fade" id="lead" role="tabpanel">
            @for($i = 1; $i <= 10; $i++)
                @php $leadDocs = json_decode($vms->{'lead' . $i . '_doc'}, true); @endphp
                <div class="form-group">
                    <label>{{ $i }}. {{ $leadIndicators[$i - 1] ?? 'Lead Indicator ' . $i }} <span
                            class="text-danger">*</span></label>
                    <input type="number" class="form-control indicator-value hd" name="lead{{$i}}_val" id="lead{{$i}}_val"
                        min="0" value="{{$vms->{'lead' . $i . '_val'} ?? 0}}" required>

                    <label class="mt-2">Attachment</label>
                    @if(!empty($leadDocs) && is_array($leadDocs))
                        <div class="row mb-2">
                            @foreach($leadDocs as $index => $file)
                                <div class="col-md-2 mb-2">
                                    <a href="{{ asset($file) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                        📎 View File {{ $index + 1 }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="file-dropzone dn" data-target="lead{{$i}}_doc">
                        <span>Drag & drop PDF here or click to upload</span>
                        <input type="file" class="form-control d-none indicator-attachment" name="lead{{$i}}_doc"
                            id="lead{{$i}}_doc" accept="application/pdf">
                    </div>
                </div>
            @endfor
        </div>

        <!-- ======= LAG INDICATORS TAB ======= -->
        <div class="tab-pane fade" id="lag" role="tabpanel">
            @for($i = 1; $i <= 6; $i++)
                @php $lagDocs = json_decode($vms->{'lag' . $i . '_doc'}, true); @endphp
                <div class="form-group">
                    <label>{{ $i }}. {{ $lagIndicators[$i - 1] ?? 'Lag Indicator ' . $i }} <span
                            class="text-danger">*</span></label>
                    <input type="number" class="form-control indicator-value hd" name="lag{{$i}}_val" id="lag{{$i}}_val"
                        min="0" value="{{$vms->{'lag' . $i . '_val'} ?? 0}}" @if($i == 6) disabled @endif required>

                    <label class="mt-2">Attachment</label>
                    @if(!empty($lagDocs) && is_array($lagDocs))
                        <div class="row mb-2">
                            @foreach($lagDocs as $index => $file)
                                <div class="col-md-2 mb-2">
                                    <a href="{{ asset($file) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                        📎 View File {{ $index + 1 }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="file-dropzone dn" data-target="lag{{$i}}_doc">
                        <span>Drag & drop PDF here or click to upload</span>
                        <input type="file" class="form-control d-none indicator-attachment" name="lag{{$i}}_doc"
                            id="lag{{$i}}_doc" accept="application/pdf">
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- ======= SUBMIT BUTTON ======= -->
    <div class="text-center mt-4 dn">
        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" style="font-size: 1.1rem;"
            id="submit-btn">
            <span id="spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
            <i class="fas fa-check-circle me-2" id="btn-icon"></i>
            <span id="btn-text">Submit</span>
        </button>
    </div>
</form>


@php
    $VendorMisFlows = DB::table('vendor_mis_flow')
        ->where('vendor_mis_id', $vms->id)
        ->where('status', 'Y')

        ->get();
@endphp

@forelse($VendorMisFlows as $vms1)
    <div class="card-body material-card mt-4 shadow rounded-3">
        <div class="material-header mb-3">
            <center>
                <h3>📋 @if($vms1->department_id != 0){{"Safety"}}@else{{ "Vendor "}} @endif Decision Panel</h3>
            </center>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">📝 Decision</label>
                <input type="text" class="form-control"
                    value="{{ ucfirst($vms1->decision ?? 'Edited/Corrected  by Vendor') }}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">📅 Decision Datetime</label>
                <input type="text" class="form-control" value="{{date('d-m-Y H:i:s', strtotime($vms1->remarks_datetime))}}"
                    disabled>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">💬 Remarks</label>
            <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder=""
                disabled>{{$vms1->remarks}}</textarea>
        </div>
    </div>
@empty

@endforelse
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@php  
    $flow = DB::table('vendor_mis_flow')->where('vendor_mis_id', $vms->id)->where('status', 'N')->where('level', '!=', '0')->first();
@endphp
<div class="card-body material-card mt-4 shadow rounded-3" @if(@$flow->id) {{''}}@else{{'hidden'}}@endif>

    <form id="hr_form" method="POST">
        @csrf
        <div class="material-header mb-3">
            <center>
                <h3>👷‍♂️ Safety Approval Panel</h3>
            </center>
        </div>
        <input type="hidden" name="flow_id" value="{{$flow->id ?? ''}}">

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
        <div class="form-group">
            <label>6. No of Severity 4&5 Violation Reported</label>
            <input type="number" class="form-control indicator-value" name="lag6_val" id="lag6_val"
                value="{{$vms->lag6_val}}">
            <label class="mt-2">Attachment</label>
            @if (!empty($vms->lag6_doc))
                <div class="mb-2">
                    <a href="{{ asset($vms->lag5_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📎 View Existing File
                    </a>
                </div>
            @endif
            <div class="file-dropzone" data-target="lag6_doc">
                <span>Drag & drop PDF here or click to upload</span>
                <input type="file" class="form-control d-none indicator-attachment" name="lag6_doc" id="lag6_doc"
                    accept="application/pdf">
            </div>
            <small class="text-danger">* To be filled by Safety Professional Only</small>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Remarks </label>

            <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder="Write your remarks here..."
                required></textarea>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" style="font-size: 1.1rem;"
                id="submit-btn">
                <span id="spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                <i class="fas fa-check-circle me-2" id="btn-icon"></i>
                <span id="btn-text">Submit</span>
            </button>
        </div>
    </form>
</div>
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

<!-- JavaScript -->
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

        fetch('{{ route("vendor_mis.update", $vms_details->id) }}', {
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

        fetch('{{ route("vendor_mis.update_data") }}', {
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
                    attachmentInput.setAttribute('', '');
                } else {
                    star.innerHTML = '&nbsp;(Not Required to upload file)';
                    attachmentLabel.appendChild(star);

                    attachmentInput.removeAttribute('required');
                    attachmentInput.setAttribute('disabled', 'disabled');
                }
            });
        });
    });



    document.addEventListener('DOMContentLoaded', function () {
        const dropzones = document.querySelectorAll('.file-dropzone');
        const MAX_FILE_SIZE_MB = 2;

        dropzones.forEach(dropzone => {
            const fileInputId = dropzone.getAttribute('data-target');
            const fileInput = document.getElementById(fileInputId);

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
                fileInput.value = '';
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

        // ✅ Specific logic for lag6_val only
        const lag6Input = document.getElementById('lag6_val');
        if (lag6Input) {
            lag6Input.addEventListener('input', function () {
                const formGroup = lag6Input.closest('.form-group');
                const attachmentInput = formGroup.querySelector('input[type="file"]');
                const attachmentLabel = formGroup.querySelectorAll('label')[1];

                // Remove existing star
                const existingStar = attachmentLabel.querySelector('.mandatory-star');
                if (existingStar) existingStar.remove();

                const value = parseInt(lag6Input.value || 0);
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

            // ✅ Trigger logic on page load
            lag6Input.dispatchEvent(new Event('input'));
        }
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