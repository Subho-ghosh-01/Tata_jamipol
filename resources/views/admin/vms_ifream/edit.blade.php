{{-- vehicle_pass_view.blade.php --}}
<?php
use App\Division;
use App\Department;
use App\UserLogin;

$vms = DB::table('vehicle_pass')->where('id', $vms_details->id)->first();
$vms_flow = DB::table('vehicle_pass_flow')->where('vehicle_pass_id', $vms->id)->where('type_status', 'New')->where('level', '0')->where('status', 'N')->first();

$type = $vms->apply_by_type == '1' ? 'Employee' : 'Vendor';
$not_required = $vms->apply_by_type == '1' ? '' : 'hidden';
$user_check_safety = UserLogin::where('id', $user_id)->select('clm_role')->first();

if($vms->created_by == Session::get('user_idSession') && $vms->status == 'return'){
    $hide = "";
    }else{
        $hide ="hidden";
    }
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'JAMIPOL SURAKSHA') }}</title>
<!-- Fonts -->
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

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('vms.index')}}">List of VMS Documents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Pass Management System</li>
@endsection

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
    @csrf

    <div class="container mt-4 pb-4">
        <!-- Header -->
        <div class="mb-4">
            <h3 class="fw-bold text-dark">
                <i class="fas fa-id-card-alt me-2 text-primary"></i>
                {{ $type }} Vehicle Gate Pass Management System
            </h3>
            <hr class="border-top border-primary" />
        </div>


        <input type="hidden" value="{{$vms->id}}" name="vehicle_id">
        <div class="form-group">
            <label>Apply Vehicle Pass For</label>
            <select class="form-control hd" name="vehicle_type_required" required>
                <option value="">--Select--</option>
                <option value="two_wheeler" @if($vms->vehicle_pass_for == 'two_wheeler'){{'selected'}}@endif>🏍️ Two
                    Wheeler
                </option>
                @if($type == 'Employee')
                    <option {{$not_required ?? ''}} value="four_wheeler"
                        @if($vms->vehicle_pass_for == 'four_wheeler'){{'selected'}}@endif>🚗 Car</option>
                @endif
            </select>
        </div>


        @if($type != 'Employee')
            <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" class="form-control hd" value="{{ $vms->employee_name }}" name="emp_name">
            </div>
            <div class="mb-3">
                <label class="form-label">Gate Pass No</label>
                <input type="text" class="form-control hd" value="{{ $vms->gp }}" name="gp">
            </div>
        @endif

        <!-- Vehicle Details -->
        <div class="mb-3">
            <label class="form-label">Vehicle Owner Name</label>
            <input type="text" class="form-control hd" value="{{ $vms->vehicle_owner_name }}" name="owner_name">


        </div>

        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label">Vehicle Registration No.</label>
                <input type="text" class="form-control hd" value="{{ @$vms->vehicle_registration_no }}"
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
                <a href="{{ asset($vms->vehicle_registration_doc) }}" class="btn btn-outline-primary btn-sm">View
                    Document</a>
            </div>
        </div>

        <!-- Insurance -->
        <div class="row g-3 mt-12">
            <div class="col-md-12">
                <label class="form-label">Insurance Valid From</label>
                <input type="date" class="form-control hd" value="{{ $vms->insurance_valid_from }}"
                    name="insurance_from">
            </div>
            <div class="col-md-12">
                <label class="form-label">Insurance Valid To</label>
                <input type="date" class="future-date form-control hd" value="{{ $vms->insurance_valid_to }}" name="insurance_to">

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
                <a href="{{ asset($vms->insurance_doc) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">View</a>
            </div>
        </div>


        <div class="form-group">
            <label>Vehicle Type</label>
            <select class="form-control hd" name="vehicle_category" id="vehicle_category" required>
                <option value="">--Select--</option>
                <option value="Petrol" @if($vms->vehicle_type == 'Petrol'){{'selected'}}@endif>⛽ Petrol</option>
                @if($type == 'Employee')
                    <option {{$not_required ?? ''}} value="Diesel" @if($vms->vehicle_type == 'Diesel'){{'selected'}}@endif>🛢️
                        Diesel</option>
                    <option {{$not_required ?? ''}} value="CNG" @if($vms->vehicle_type == 'CNG'){{'selected'}}@endif>🧯 CNG
                    </option>
                @endif
                <option value="EV" @if($vms->vehicle_type == 'EV'){{'selected'}}@endif>🔌 EV</option>
                @if($type == 'Employee')
                    <option {{$not_required ?? ''}} value="Hybrid" @if($vms->vehicle_type == 'Hybrid'){{'selected'}}@endif>♻️
                        Hybrid</option>
                @endif

            </select>
        </div>
        <!-- Vehicle Type -->
       <div class="row g-3 mt-2">
    <div class="col-md-6">
        <label class="form-label">Vehicle Registration Date - From</label>
        <input type="date" class="form-control hd" value="{{ $vms->vehicle_registration_date }}" name="registration_date">
    </div>
    <div class="col-md-6">
        <label class="form-label">Vehicle Registration Date - To</label>
        <input type="date" class="form-control hd future-date" value="{{ $vms->registraction_to }}" name="registration_date_to">
    </div>
</div>


        <!-- PUC -->
        <div class="row g-3 mt-2">
            <div class="col-md-12">
                <label class="form-label">PUC Valid From</label>
                <input type="date" class="form-control hd" value="{{ $vms->puc_valid_from }}" name="puc_from">
            </div>
            <div class="col-md-12">
                <label class="form-label">PUC Valid To</label>
                <input type="date" class="form-control hd future-date" value="{{ $vms->puc_valid_to }}" name="puc_to">
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
                <a href="{{ asset($vms->puc_attachment_required) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">View</a>
            </div>
        </div>

        @if($type == 'Employee')
            <!-- Driver Info -->
            <div class="mt-3">
                <label class="form-label">Vehicle Will be Driven By</label>
                <input type="text" class="form-control hd" value="{{ ucfirst($vms->driven_by) }}" name="driver_type">
            </div>

            @if($vms->driven_by == 'driver')
                <div class="mt-2">
                    <label class="form-label">Driver’s Name</label>
                    <input type="text" class="form-control hd" value="{{ $vms->driver_name }}" name="driver_name">
                </div>
            @endif
        @endif

        <!-- License -->
        <div class="mt-12">
            <label class="form-label">Driving License No.</label>
            <input type="text" class="form-control hd" value="{{ $vms->driving_license_no }}" name="license_no">
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-12">
                <label class="form-label">License Valid From</label>
                <input type="date" class="form-control hd" value="{{ $vms->license_valid_from }}"
                    name="license_valid_from">
            </div>
            <div class="col-md-12">
                <label class="form-label">License Valid To</label>
                <input type="date" class="form-control hd future-date" value="{{ $vms->license_valid_to }}" name="license_valid_to">
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
                <a href="{{ asset($vms->driving_license_doc) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">View</a>
            </div>
        </div>


        <label class="form-label dn">Remarks (Optional)</label>
        <textarea class="form-control dn" name="remarks" rows="3" placeholder="Enter any remarks (optional)"></textarea>




        <div class="text-center mt-4 dn "{{$hide}}>



            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" style="font-size: 1.1rem;"
                id="submit-btn">
                <span id="spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                <i class="fas fa-check-circle me-2" id="btn-icon"></i>
                <span id="btn-text">Submit</span>
            </button>


        </div>

    </div>


</form>
<script>
    document.getElementById('form_return').addEventListener('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-btn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btn-text');

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.innerText = 'Processing...';

        // Prepare FormData (includes file input)
        const form = document.getElementById('form_return');
        const formData = new FormData(form);

        fetch('{{ route("vms_ifream.update_return") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // Do NOT set 'Content-Type': multipart/form-data — fetch sets it automatically
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                Swal.fire('Success', data.message, 'success').then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        location.reload(); // Reload the page
                    }
                })
            })
            .catch(err => {
                console.error(err);
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
@php
    $vehiclePassFlows = DB::table('vehicle_pass_flow')
        ->where('vehicle_pass_id', $vms->id)
        ->where('status', 'Y')
        ->where('type_status', 'New')
        ->get();
@endphp

@forelse($vehiclePassFlows as $vms1)
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
                    value="{{ ucfirst($vms1->desion ?? 'Edited/Corrected  by Vendor') }}" disabled>
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

@if($user_check_safety->clm_role == 'Safety_dept')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @php  
        $flow = DB::table('vehicle_pass_flow')->where('vehicle_pass_id', $vms->id)->where('status', 'N')->where('level', '!=', '0')->where('type_status', 'New')->first();
    @endphp
    <div class="card-body material-card mt-4 shadow rounded-3" @if(@$flow->id) {{''}}@else{{'hidden'}}@endif>

        <form id="hr_form" method="POST">
            @csrf
            <div class="material-header mb-3">
                <center>
                    <h3>👷‍♂️ Approval Panel</h3>
                </center>
            </div>
            <input type="hidden" name="flow_id" value="{{$flow->id ?? ''}}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Select Action </label>
                <div class="d-flex gap-4">
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

                <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder="Write your remarks here..."
                    required></textarea>
            </div>

            <div class="text-center">
    <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold shadow-sm rounded-pill" id="submit-btn1">
        <span id="btn-text1">Submit</span>
        <i class="fas fa-spinner fa-spin ms-2 d-none" id="spinner1"></i>
    </button>
</div>
        </form>
    </div>
@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Add this in your <head> or just before closing </body> tag -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    document.getElementById('hr_form').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = document.getElementById('hr_form');
        const submitBtn = document.getElementById('submit-btn1');
        const spinner = document.getElementById('spinner1');
        const btnText = document.getElementById('btn-text1');
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

        fetch('{{ route("vms_ifream.update", $vms_details->id) }}', {
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
<script>// Tomorrow's date
    function getTomorrow() {
        const t = new Date();
        t.setDate(t.getDate() + 1);
        return t.toISOString().split("T")[0];
    }

    const tomorrow = getTomorrow();

    // Restrict only future-date inputs
    document.querySelectorAll('input.future-date').forEach(input => {
        input.setAttribute("min", tomorrow);

        // Validate typed input
        input.addEventListener("blur", function () {
            if (this.value && this.value < tomorrow) {
                alert("Past dates are not allowed!");
                this.value = tomorrow; // reset to tomorrow if invalid
            }
        });
    });

    // Handle "from" -> "to" only if from input is future-date
    document.querySelectorAll('input.future-date[data-date="from"]').forEach(fromInput => {
        fromInput.addEventListener("change", function () {
            const toInputName = this.name.replace("from", "to");
            const toInput = document.querySelector(`input[name="${toInputName}"]`);
            if (toInput) {
                toInput.setAttribute("min", this.value);

                if (!toInput.value || toInput.value < this.value) {
                    toInput.value = this.value;
                }
            }
        });

        fromInput.addEventListener("blur", function () {
            const toInputName = this.name.replace("from", "to");
            const toInput = document.querySelector(`input[name="${toInputName}"]`);
            if (toInput && toInput.value < this.value) {
                toInput.value = this.value;
            }
        });
    });

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
    var vmsFlowExists = {{ $vms_flow ? 'true' : 'false' }};
    $(document).ready(function () {
        if (!vmsFlowExists) {
            // Hide elements
            $('.dn').addClass('d-none');

            // Disable elements
            $('.hd').prop('disabled', true).addClass('disabled');
        }
    });

</script>

@section('scripts')




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
    <script type="text/javascript" src="{{ asset('js/app.js') }}"> </script>
    <script type="text/javascript" src="{{ asset('js/sweetalert.js') }}"> </script>

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


@endsection