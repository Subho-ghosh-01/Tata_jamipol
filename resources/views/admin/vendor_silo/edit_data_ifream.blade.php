<?php
use App\Division;
use App\Department;
use App\UserLogin;
$vms = DB::table('vendor_silo')->where('id', $vms_details->id)->first();
$vms_flow = DB::table('vendor_silo_flow')->where('vendor_silo_id', $vms->id)->where('status', 'N')->orderBy('id', 'asc')->first();

$vehicle_status = $vms->status;
$vendor_level = $vms_flow->level ?? 'NA';

$user_check_safety = UserLogin::where('id', $user_id)->select('clm_role','silo_role')->first();
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

    .form-container {
        max-height: 500px;
        /* Set your desired height */
        overflow-y: auto;
        /* Enables vertical scroll */
        padding: 10px;
        border: 1px solid #ccc;
    }
</style>
<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data" id="form" autocomplete="off">
        @csrf
        <input type="hidden" value="{{ $vms->id }}" name="vendor_mis_id">

        <!-- ===== Tabs ===== -->
        <ul class="nav nav-tabs pro-tabs justify-content-start" id="vmsTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic"
                    type="button">
                    <span class="tab-number">1.</span> Basic Information
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="lead-tab" data-bs-toggle="tab" data-bs-target="#lead" type="button">
                    <span class="tab-number">2.</span> Legal Compliance
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="lag-tab" data-bs-toggle="tab" data-bs-target="#lag" type="button">
                    <span class="tab-number">3.</span> Safety Parameter
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="valid-tab" data-bs-toggle="tab" data-bs-target="#valid" type="button">
                    <span class="tab-number">4.</span> Valid Certificate
                </button>
            </li>
        </ul>

        <div class="tab-content p-4 border border-top-0" id="vmsTabContent">

            <!-- ========== BASIC DETAILS TAB ========== -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <h5 class="fw-bold text-primary">Basic Details:</h5>
                <div class="row col-12">
                    <div class="form-group col-6">
                        <label>Work Order No <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="work_order_no" value="{{ $vms->work_order_no }}"
                            disabled>
                    </div>
                    <div class="form-group col-6">
                        <label>Validity <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="validity" value="{{ $vms->validity }}" disabled>
                    </div>
                    <div class="form-group col-6">
                        <label>Division <span class="text-danger">*</span></label>
                        <select class="form-control" name="division" id="division" disabled>
                            <option value="">Select Division</option>
                            @foreach($divs as $division)
                                <option value="{{ $division->id }}" {{ ($vms->division_id ?? '') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Section <span class="text-danger">*</span></label>
                        <select class="form-select" name="plant" id="plant" disabled>
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <label>Approver <span class="text-danger">*</span></label>
                        <select class="form-control" name="approver_id" id="approver_id" disabled>
                            <option value="">Select Approver</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ========== LEGAL COMPLIANCE TAB ========== -->
            <div class="tab-pane fade" id="lead" role="tabpanel">
                <h5 class="fw-bold ">Legal Compliance:</h5>
                <div class="form-group col-12">
                    <label>Vehicle Registration No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="vehicle_reg_no"
                        value="{{ $vms->vehicle_registration_no }}" disabled>
                    @if(!empty($vms->registration_doc))
                        <div class="mt-2">
                            <a href="{{ asset($vms->registration_doc) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Registration (RC Proof) Attachment 
                            </a>
                        </div>
                    @endif

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Insurance From <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="insurance_from" value="{{ $vms->insurance_from }}"
                            disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Insurance To <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="insurance_to" value="{{ $vms->insurance_to }}"
                            disabled>
                    </div>
                    @if(!empty($vms->insurance_doc))
                        <div class="mt-2">
                            <a href="{{ asset($vms->insurance_doc) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Insurance Attachment
                            </a>
                        </div>
                    @endif

                </div>
 <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Valid Fitness Inspection Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="valid_fitness_inspection_date"
                        value="{{ $vms->valid_fitness_inspection_date }}" disabled>

                        </div>
                   
<div class="col-md-6 mb-3"><label>Valid Fitness Inspection Due Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="valid_fitness_inspection_due_date"
                        value="{{ $vms->vehicle_fitness_due_date }}" disabled>                
                    </div>
                     @if(!empty($vms->fitness_certificate))
                        <div class="mt-2">
                            <a href="{{ asset($vms->fitness_certificate) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Fitness Attachment
                            </a>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>PUC Inspection Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="puc_inspection_date"
                            value="{{ $vms->puc_inspection_date }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>PUC Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="puc_inspection_due_date"
                            value="{{ $vms->puc_inspection_due_date }}" disabled>
                    </div>
                    @if(!empty($vms->puc_certificate))
                        <div class="mt-2">
                            <a href="{{ asset($vms->puc_certificate) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 PUC Attachment
                            </a>
                        </div>
                    @endif


                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 p-3">
                        <label>Valid Road Permit From <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="road_permit_from"
                            value="{{ $vms->valid_road_permit_date }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Valid Road Permit Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="road_permit_due"
                            value="{{ $vms->valid_road_permit_due_date }}" disabled>
                    </div>
                    @if(!empty($vms->road_permit_certificate))
                        <div class="mt-2">
                            <a href="{{ asset($vms->road_permit_certificate) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Road Permit Attachment
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            <!-- ========== SAFETY PARAMETER TAB ========== -->
            <div class="tab-pane fade" id="lag" role="tabpanel">
                <h5 class="fw-bold">Safety Parameters:</h5>
                <div class="form-group">
                    <label>Vehicle Deputed For <span class="text-danger">*</span></label>
                    <select class="form-control" name="vehicle_deputed_for" disabled>
                        <option value="">Select</option>
                        <option value="Local Movement" {{ $vms->vehicle_dupted_for == 'Local Movement' ? 'selected' : '' }}>
                            Local Movement</option>
                        <option value="To Other States" {{ $vms->vehicle_dupted_for == 'To Other States' ? 'selected' : '' }}>
                            To Other States</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>DFMS Available <span class="text-danger">*</span></label>
                    <select class="form-control" name="dfms_available" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->dfms == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->dfms == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>GPS Tracker Available <span class="text-danger">*</span></label>
                    <select class="form-control" name="gps_tracker_available" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->gps_tracker == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->gps_tracker == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Availability of Hatch Strainers <span class="text-danger">*</span></label>
                    <select class="form-control" name="hatch_strainers" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->hatch_strainers == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->hatch_strainers == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Availability of Fuel Tank Strainer <span class="text-danger">*</span></label>
                    <select class="form-control" name="fuel_tank_strainers" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->fuel_tank_strainers == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->fuel_tank_strainers == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Battery Placement Outside Prime Mover <span class="text-danger">*</span></label>
                    <select class="form-control" name="battery_placement" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->battery_placment == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->battery_placment == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fire Extinguisher Available <span class="text-danger">*</span></label>
                    <select class="form-control" name="fire_extinguisher" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->fire_extinguishers == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->fire_extinguishers == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>First Aid Box Available <span class="text-danger">*</span></label>
                    <select class="form-control" name="first_aid_box" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->first_aid_box == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->first_aid_box == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Stepney (Spare Tyre) <span class="text-danger">*</span></label>
                    <select class="form-control" name="stepney" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->stepney == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->stepney == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Scotch Blocks (4 Nos) <span class="text-danger">*</span></label>
                    <select class="form-control" name="scotch_blocks" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->scoth_block == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->scoth_block == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Earth Chain Available <span class="text-danger">*</span></label>
                    <select class="form-control" name="earth_chain" disabled>
                        <option value="">Select</option>
                        <option value="Yes" {{ $vms->earth_chain == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $vms->earth_chain == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>

            <!-- ========== VALID CERTIFICATE TAB ========== -->
            <div class="tab-pane fade" id="valid" role="tabpanel">
                <h5 class="fw-bold ">Valid Certificate:</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Pressure Vessel Test Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="pressure_vessel_test_date"
                            value="{{ $vms->vessel_test_date }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Pressure Vessel Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="pressure_vessel_due_date"
                            value="{{ $vms->vessel_due_date }}" disabled>
                    </div>
                    @if(!empty($vms->vessel_certiicate))
                        <div class="mt-2">
                            <a href="{{ asset($vms->vessel_certiicate) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Pressure Vessel Attachment
                            </a>
                        </div>
                    @endif

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Pressure Gauge Calibration Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="pressure_gauge_date"
                            value="{{ $vms->pressure_gauge_date }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Pressure Gauge Calibration Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="pressure_gauge_due_date"
                            value="{{ $vms->pressure_gauge_due_date }}" disabled>
                    </div>
                    @if(!empty($vms->pressure_gauge_certificate))
                        <div class="mt-2">
                            <a href="{{ asset($vms->pressure_gauge_certificate) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Pressure Gauge Attachment
                            </a>
                        </div>
                    @endif

                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Pressure Relief Valve Test Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="pressure_relief_test_date"
                            value="{{ $vms->pressure_relief_test_date }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Pressure Relief Valve Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="pressure_relief_due_date"
                            value="{{ $vms->pressure_relief_due_date }}" disabled>
                    </div>
                    @if(!empty($vms->pressure_relief_certificate))
                        <div class="mt-2">
                            <a href="{{ asset($vms->pressure_relief_certificate) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                📎 Pressure Relife Attachemnt
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>


        @if($vms->status == 'return' && $vms->created_by == $user_id)
            <div class=" text-center mt-4">
                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm" style="font-size: 1.1rem;">
                    <i class="fas fa-edit me-2"></i>
                    <a href="{{ route('vendor_silo.edit', $vms->id) }}" target="_blank" style="color:white"> Click here to
                        edit your
                        details
                    </a></button>
            </div>
        @endif

    </form>
</div>
@php
    $VendorMisFlows = DB::table('vendor_silo_flow')
        ->where('vendor_silo_id', $vms->id)
        ->where('status', 'Y')
        ->where('type', 'New')
        ->get();
@endphp

@forelse($VendorMisFlows as $vms1)
    <fieldset class="border p-3 mb-3 rounded">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-success">Tanker Inclusion</legend>
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
                        value="{{ ucfirst($vms1->decision ?? 'Edited/Corrected  by Vendor') }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">📅 Decision Datetime</label>
                    <input type="text" class="form-control"
                        value="{{date('d-m-Y H:i:s', strtotime($vms1->remarks_datetime))}}" disabled>
                </div>
            </div>
            @if($vms1->schedule_date != '1900-01-01' && !empty($vms1->schedule_date))
           <div class="row mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-semibold">📝 Schedule Date</label>
                    <input type="date" class="form-control"
                        value="{{ ucfirst(@$vms1->schedule_date ?? 'Edited/Corrected  by Vendor') }}" disabled>
                </div>
              
            </div>

@endif
            <div class="mb-4">
                <label class="form-label fw-semibold">💬 Remarks</label>
                <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder=""
                    disabled>{{$vms1->remarks}}</textarea>
            </div>
        </div>
    </fieldset>
@empty

@endforelse
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@php  
    $flow = DB::table('vendor_silo_flow')->where('vendor_silo_id', $vms->id)->where('status', 'N')->where('type', 'New')->where('level', '!=', '0')->first();
@endphp

@if(($user_check_safety->clm_role == 'Safety_dept' && @$flow->department_id == '2') || ($vms->approver_id == $user_id && $vms->status == 'pending_with_inclusion_user') || ($user_check_safety->silo_role == 'operation_dept' && @$flow->department_id == '3') )
    <fieldset class="border p-3 mb-3 rounded">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-success">Tanker Inclusion</legend>
        <div class="card-body material-card mt-4 shadow rounded-3" @if(@$flow->id) {{''}}@else{{'hidden'}}@endif>

            <form id="hr_form" method="POST">
                @csrf
                <div class="material-header mb-3">
                    <center>
                        <h3>👷‍♂️Approval Panel</h3>
                    </center>
                </div>
                <input type="hidden" name="flow_id" value="{{$flow->id ?? ''}}">
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
                        &nbsp;&nbsp;@if(@$flow->schedule  != 1)
                        <div>
                            <input type="radio" class="btn-check" name="action" id="btn-return" value="return"
                                autocomplete="off" hidden>
                            <label class="btn btn-outline-danger px-4 py-2 rounded-pill" for="btn-return">
                                <i class="fas fa-undo-alt me-1"></i> Reject
                            </label>
                        </div> @endif
                    </div>
                    <div id="action-error" class="text-danger small mt-1 d-none">Please select an action.</div>
                </div>
@if(@$flow->schedule  == 1)
                 <div class="mb-4">
                    <label class="form-label fw-semibold">Schedule Date </label>
   
                        <input type="date" class="form-control shadow-sm" name="schedule_date" required>
                </div>
                @endif
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
    </fieldset>
@endif
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
@php
    $VendorMisFlows = DB::table('vendor_silo_flow')
        ->where('vendor_silo_id', $vms->id)
        ->where('status', 'Y')
        ->where('type', 'Return')
        ->get();
@endphp

@forelse($VendorMisFlows as $vms1)
    <fieldset class="border p-3 mb-3 rounded">
        <legend class="float-none w-auto px-2 fs-6 fw-bold text-danger">Tanker Exclusion</legend>
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
                                value="{{ ucfirst($vms1->decision ?? 'Edited/Corrected  by Vendor') }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">📅 Decision Datetime</label>
                            <input type="text" class="form-control"
                                value="{{date('d-m-Y H:i:s', strtotime($vms1->remarks_datetime))}}" disabled>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">💬 Remarks</label>
                        <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder=""
                            disabled>{{$vms1->remarks}}</textarea>
                    </div>
                </div>
            </fieldset>
@empty

    @endforelse
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @php  
        $flow = DB::table('vendor_silo_flow')->where('vendor_silo_id', $vms->id)->where('status', 'N')->where('type', 'Return')->where('level', '!=', '0')->first();
    @endphp

    @if(($user_check_safety->clm_role == 'Safety_dept' && @$flow->department_id == '2') || ($vms->approver_id == $user_id && $vms->return_status == 'pending_with_inclusion_user'))
        <fieldset class="border p-3 mb-3 rounded">
            <legend class="float-none w-auto px-2 fs-6 fw-bold text-danger">Tanker Exclusion</legend>
            <div class="card-body material-card mt-4 shadow rounded-3" @if(@$flow->id) {{''}}@else{{'hidden'}}@endif>

                <form id="hr_form" method="POST">
                    @csrf
                    <div class="material-header mb-3">
                        <center>
                            <h3>👷‍♂️Approval Panel</h3>
                        </center>
                    </div>
                    <input type="hidden" name="flow_id" value="{{$flow->id ?? ''}}">
                    <input type="hidden" name="type" value="Return">
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
        </fieldset>
    @endif
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

        fieldset {
            border: 1px solid #ddd !important;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 6px;
        }

        legend {
            font-size: 14px;
            font-weight: 600;
            color: #dc3545;
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

            fetch('{{ route("vendor_silo.update", $vms_details->id) }}', {
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
    <script>
        $(document).ready(function () {
            let selectedPlant = "{{ old('plant', $vms->section_id ?? '') }}";


            // Division change
            $('#division').on('change', function () {
                var division_ID = $(this).val();
                $("#plant").html('<option value="">--Select Plant--</option>');

                if (division_ID) {
                    $.ajax({
                        type: 'GET',
                        url: "{{ route('admin.departmentGet_vendor_mis', '') }}/" + division_ID,
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
                }
            });



            // On page load → trigger division change if already selected
            if ($('#division').val()) {
                $('#division').trigger('change');
            }
        });



    </script>