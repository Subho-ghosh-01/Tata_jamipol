@extends('admin.app')

@php
    use App\UserLogin;

    $vendorname = UserLogin::where('id', $vendor_attendance->vendor_id)->first();
    $hr_name = UserLogin::where('id', $vendor_attendance->hr_by)->first();


    $statusData = match ($vendor_attendance->status) {
        'Pending_with_hr' => [
            'icon' => '<i class="fas fa-user-clock text-info me-1"></i>', // clock/user icon
            'label' => 'Pending With HR Team'
        ],
        'completed' => [
            'icon' => '<i class="fas fa-check-circle text-success me-1"></i>', // green check
            'label' => 'Completed'
        ],
        'reject' => [
            'icon' => '<i class="fas fa-times-circle text-danger me-1"></i>', // green check
            'label' => 'Rejected'
        ],
        default => [
            'icon' => '<i class="fas fa-question-circle text-muted me-1"></i>', // question mark for unknown
            'label' => 'Unknown'
        ],
    };


    $icn = '';
    $htxt = '';
    $foemview = '';

    switch ($vendor_attendance->status) {
        case 'Pending_with_hr':
            $htxt = 'HR';
            $foemview = 'style="display:block;"';
            break;

        case 'Pending_with_account':
            $htxt = 'Account';
            $foemview = 'style="display:block;"';
            break;

        default:
            $htxt = 'Something went wrong. Please try again.';
            $icn = '<i class="fas fa-exclamation-circle me-1 text-danger"></i>';
            $foemview = 'style="display:none;"'; // optional fallback
            break;
    }
@endphp


@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.vendor_esic_details.index') }}">List of Vendor Esic Documents</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Vendor Esic Documents</li>
@endsection

@if(Session::get('user_sub_typeSession') == 5)
    <script>window.location.href = "{{ url('admin/dashboard') }}";</script>
@else
    @section('content')


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('message'))
            <div class="alert alert-{{ session('message_type', 'info') }} alert-dismissible fade show d-flex align-items-center shadow-sm rounded-2 px-4 py-3 mt-3"
                role="alert">
                @if(session('message_icon'))
                    <i class="{{ session('message_icon') }} me-3 fs-5"></i> {{-- me-3 for a bit more spacing --}}
                @endif
                <div class="flex-grow-1">
                    {{   session('message') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> {{-- Optional close
                button --}}
            </div>
        @endif



        <div class="col-md-12 mb-4">
            <h3 class="fw-bold">
                <i class="fas fa-eye" style="color: #43a02c;"></i> View Vendor Esic Challan & Contribution
            </h3>
            {!! $inform_message ?? '' !!}


            <div class="row g-3">
                <!-- Details Panel -->
                <div class="col-md-4">

                    <div class="custom-file-upload p-4 border rounded h-100">

                        <p><strong>Vendor Name:</strong> {{ $vendorname->name ?? 'NA' }}</p>
                        <p><strong>Wage Month:</strong> {{ date('F-Y', strtotime($vendor_attendance->month)) }}</p>
                        <p><strong>Status:</strong> {!! $statusData['icon'] !!} {{ '' }} {{ $statusData['label'] }}</p>
                        <p><strong>Uploaded Date:</strong>
                            {{ date('d-m-Y H:i:s', strtotime($vendor_attendance->created_date)) ?? 'NA' }}</p>



                    </div>
                </div>

                <!-- File Upload Panel -->
                <div class="col-md-4">

                    <div class="custom-file-upload text-center p-4 border rounded shadow-sm h-100 bg-light">
                        <h5 class="text-primary fw-bold mb-3">ESIC Challan</h5>

                        @php
                            $filePath = $vendor_attendance->esic_challan;
                            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                            $fileName = basename($filePath);
                            $fullPath = asset($filePath);
                            $icon = match (true) {
                                in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) => 'fa-file-image text-info',
                                $ext === 'pdf' => 'fa-file-pdf text-danger',
                                in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel text-success',
                                in_array($ext, ['doc', 'docx']) => 'fa-file-word text-primary',
                                default => 'fa-file-alt text-secondary',
                            };
                        @endphp

                        <i class="fas {{ $icon }} fa-2x mb-1"></i>
                        <p class="text-muted">File Preview ({{ strtoupper($ext) }})</p>

                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ $fullPath }}" class="img-fluid rounded mb-2" style="max-height: 200px;" alt="Preview">
                        @elseif ($ext === 'pdf')
                            <iframe src="{{ $fullPath }}" class="w-100 mb-2" style="height: 120px;" frameborder="0"></iframe>
                        @else
                            {{-- <p><strong>File Name:</strong> {{ $fileName }}</p> --}}
                        @endif

                        <a href="{{ $fullPath }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-download"></i> View / Download
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom-file-upload text-center p-4 border rounded shadow-sm h-100 bg-light">
                        <h5 class="text-primary fw-bold mb-3">ESIC Contribution</h5>
                        @php
                            $filePath = $vendor_attendance->esic_contribution;
                            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                            $fileName = basename($filePath);
                            $fullPath = asset($filePath);
                            $icon = match (true) {
                                in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) => 'fa-file-image text-info',
                                $ext === 'pdf' => 'fa-file-pdf text-danger',
                                in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel text-success',
                                in_array($ext, ['doc', 'docx']) => 'fa-file-word text-primary',
                                default => 'fa-file-alt text-secondary',
                            };
                        @endphp

                        <i class="fas {{ $icon }} fa-2x mb-1"></i>
                        <p class="text-muted">File Preview ({{ strtoupper($ext) }})</p>

                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ $fullPath }}" class="img-fluid rounded mb-2" style="max-height: 200px;" alt="Preview">
                        @elseif ($ext === 'pdf')
                            <iframe src="{{ $fullPath }}" class="w-100 mb-2" style="height: 120px;" frameborder="0"></iframe>
                        @else
                            {{-- <p><strong>File Name:</strong> {{ $fileName }}</p> --}}
                        @endif

                        <a href="{{ $fullPath }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-download"></i> View / Download
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($vendor_attendance->hr_by != '')
            <div class="col-12 position-relative p-2">
                <div class="card shadow-sm rounded-4 watermark-card">
                    <!-- Watermark Overlay -->
                    @php
                        $decision = strtolower($vendor_attendance->hr_decision ?? '');
                    @endphp

                    @if($decision === 'approve')
                        <div class="watermark-overlay text-success">
                            <i class="fas fa-check-circle me-2"></i> APPROVED
                        </div>
                    @elseif($decision === 'reject')
                        <div class="watermark-overlay text-danger">
                            <i class="fas fa-times-circle me-2"></i> REJECTED
                        </div>
                    @endif


                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="fas fa-user-check me-2"></i>&nbsp;
                        <h5 class="mb-0">HR Details</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>HR Name:&nbsp;</strong> {{ $hr_name->name ?? 'NA' }}</p>
                        <p class="mb-2 d-flex align-items-center">
                            <strong class="me-2">HR Decision:&nbsp;</strong>
                            @php $decision = strtolower($vendor_attendance->hr_decision ?? ''); @endphp

                            @if($decision === 'approve')
                                &nbsp;
                                <i class="fas fa-check-circle text-success me-1"></i>&nbsp; {{ ucfirst($decision) }}
                                &nbsp;
                            @elseif($decision === 'reject')
                                &nbsp;
                                <i class="fas fa-times-circle text-danger me-1"></i>&nbsp; {{ ucfirst($decision) }}
                                &nbsp;
                            @else
                                &nbsp;
                                <i class="fas fa-question-circle text-muted me-1"></i>&nbsp; {{ ucfirst($decision ?: 'NA') }}
                                &nbsp;
                            @endif
                        </p>

                        <p class="mb-2"><strong>HR Remarks:&nbsp;</strong> {{ $vendor_attendance->hr_remarks ?? 'NA' }}</p>
                        <p class="mb-0"><strong>HR Approve Datetime:&nbsp;</strong>
                            {{ isset($vendor_attendance->hr_decision_datetime) ? date('d-m-Y H:i:s', strtotime($vendor_attendance->hr_decision_datetime)) : 'NA' }}
                        </p>
                    </div>
                </div>
            </div>


            <style>
                .card-body p {
                    font-size: 1rem;
                    color: #444;
                }

                .card-body strong {
                    color: #222;
                }

                .card-header {
                    font-weight: 400;
                    font-size: 1.25rem;
                }

                .watermark-card {
                    position: relative;
                    overflow: hidden;
                }

                .watermark-overlay {
                    position: absolute;
                    top: 60%;
                    left: 30%;
                    transform: translate(-50%, -50%) rotate(-25deg);
                    font-size: 3rem;
                    font-weight: bold;
                    opacity: 0.20;
                    white-space: nowrap;
                    pointer-events: none;
                    z-index: 0;
                    font-family: 'Font Awesome 5 Free', sans-serif;
                }

                .watermark1-overlay {
                    position: absolute;
                    top: 90%;
                    left: 40%;
                    transform: translate(-50%, -50%) rotate(-25deg);
                    font-size: 3rem;
                    font-weight: bold;
                    opacity: 0.20;
                    white-space: nowrap;
                    pointer-events: none;
                    z-index: 1;
                    font-family: 'Font Awesome 5 Free', sans-serif;
                }

                .watermark-card .card-header,
                .watermark-card .card-body {
                    position: relative;
                    z-index: 1;
                }
            </style>


        @endif


        @if(($vendor_attendance->status == 'Pending_with_hr' && Session::get('clm_role') == 'hr_dept'))
            <div class="card-body material-card mt-4 shadow rounded-3" {!! $foemview ?? '' !!}>

                <form action="{{ route('admin.vendor_esic_details.update', $vendor_attendance->id) }}" method="POST"
                    autocomplete="off" id="hr_form">
                    @csrf
                    @method('PUT')

                    <div class="material-header mb-3">
                        {!! $icn ?: '<i class="bi bi-person-check-fill me-2"></i>' !!} {{ $htxt ?? 'NA' }} Approval Panel
                    </div>



                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select Action <span class="text-danger">*</span></label>
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
                                <input type="radio" class="btn-check" name="action" id="btn-return" value="reject"
                                    autocomplete="off" hidden>
                                <label class="btn btn-outline-danger px-4 py-2 rounded-pill" for="btn-return">
                                    <i class="fas fa-undo-alt me-1"></i> Reject
                                </label>
                            </div>
                        </div>
                        <div id="action-error" class="text-danger small mt-1 d-none">Please select an action.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>

                        <textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder="Write your remarks here..."
                            required></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold shadow-sm rounded-pill" id="submit-btn">
                            <i class="fas fa-spinner fa-spin me-2 d-none" id="spinner"></i> <span id="btn-text"> Submit </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif



        <style>
            .material-card {
                background-color: #ffffff9d;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 6px 12px 18px rgba(0, 0, 0, 0.08);
            }

            .material-header {
                background: linear-gradient(to right, #cab32e, #e6d675);
                color: #212529;
                font-size: 22px;
                font-weight: 500;
                padding: 12px 24px;
                border-radius: 8px;
                text-align: center;
            }



            .form-control,
            textarea {
                border-radius: 8px;
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @endsection
@endif

@section('scripts')
    <!-- You can add JS-specific logic here if needed -->
    <script>
        document.getElementById("hr_form").addEventListener("submit", function (e) {
            const approve = document.getElementById("btn-approve").checked;
            const ret = document.getElementById("btn-return").checked;
            const errorBox = document.getElementById("action-error");

            const submitBtn = document.getElementById('submit-btn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btn-text');

            if (!approve && !ret) {
                e.preventDefault();
                errorBox.classList.remove("d-none");
            } else {
                errorBox.classList.add("d-none");
                // Show loading state
                submitBtn.disabled = true;
                spinner.classList.remove('d-none'); // Show spinner
                btnText.innerText = 'Processing...';
            }


        });
    </script>

@endsection