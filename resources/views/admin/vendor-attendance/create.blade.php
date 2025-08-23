<?php
use App\Division;
use App\Department;
use App\UserLogin;
?>
@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.vendor_attendance.index')}}">List Of Wage Register</a></li>
    <li class="breadcrumb-item active" aria-current="page">Upload Attendance</li>
@endsection
@if(Session::get('user_sub_typeSession') == 4)
    return redirect('admin/dashboard');
@else
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    @section('content')
        @if($isUploaded == 'yes')
            <div class="alert alert-secondary">
                ✅ Attendance has already been uploaded for
                <strong>{{ $previousMonthYear }},
                    <span
                        style="animation: blinker 1s linear infinite; color: {{$color}}; @keyframes blinker { 50% { opacity: 0; } }">
                        {{ $form_status }}
                    </span>
                </strong>.
            </div>

        @endif
        @if($islast_date == "yes")

            <div class="alert"
                style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 4px; padding: 12px; font-weight: 600;">
                Last Date is crossed, you are unable to upload the file.
            </div>


        @endif

        @if(session('message'))
            <div
                class="alert {{ session('message') == 'Wage Register uploaded successfully.' ? 'alert-success' : 'alert-danger' }}">
                {{ session('message') }}
            </div>
        @endif
        @if(session('message') || $errors->any())
            <script>
                window.onload = function () {
                    const submitBtn = document.getElementById('submit-btn');
                    const spinner = document.getElementById('spinner');
                    const btnText = document.getElementById('btn-text');

                    submitBtn.disabled = false;
                    spinner.classList.add('d-none'); // Hide spinner
                    btnText.innerText = 'Submit Document';
                    setTimeout(function () {
                        location.reload();
                    }, 2000); // 3000ms = 3 seconds
                };
            </script>
        @endif


        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif





        <form action="{{ route('admin.vendor_attendance.store') }}" method="post" enctype="multipart/form-data" id="form"
            autocomplete="off">
            @csrf
            <div class="alert alert-info p-2 mb-3 small" style="font-size: 0.9rem;">
                <strong>Instructions:</strong> Please upload only
                <code>.pdf</code>, <code>.doc</code>, <code>.docx</code>, <code>.xls</code>, <code>.xlsx</code>,
                <code>.jpg</code>, <code>.jpeg</code>, or <code>.png</code> files. Maximum file size: <strong>5MB</strong>.
            </div>

            <div class="col-md-12 mb-4">
                <h3 class="fw-bold">
                    <i class="fas fa-upload" style="color: #2c62a0;"></i> Upload Wage Register
                </h3>


                <div class="row g-3">
                    <!-- Details Panel -->
                    <div class="col-12 order-1 p-2">
                        <div class="custom-file-upload p-4 border rounded h-100">
                            <div class="row g-3 align-items-center">
                                @if(!empty($vendorName->name))
                                    <div class="col-md-3">
                                        <p><strong>Vendor Name:</strong> {{$vendorName->name ?? 'NA'}}</p>
                                    </div>
                                @endif

                                <div class="col-md-3">
                                    <p><strong>Wage Month:</strong> {{$previousMonthYear}}</p>
                                </div>

                                <div class="col-md-3">
                                    <p><strong>Uploaded Month:</strong> {{$currentMonthYear}}</p>
                                </div>

                                <div class="col-md-6">
                                    <p><strong>Last Date to Upload:</strong>
                                        <a href="#" class="blink-text" style="color: #bb4123; font-weight: bold;">
                                            {{$setting_date->value}}th of {{$currentMonthYear}}
                                        </a>
                                    </p>
                                </div>

                                @php
                                    $style = (Session::get('user_sub_typeSession') == 2) ? 'd-none' : '';
                                @endphp
                                <div class="col-md-2 {{ $style }}">
                                    <label for="vendor_id" class="form-label fw-bold mb-1">
                                        <p><strong>Select Vendor:</strong></p>
                                    </label>
                                    <select name="vendor_id" id="vendor_id" class="form-select select2" required>
                                        <option value="">-- Select Vendor --</option>
                                        @foreach($vendorlist as $vendor)
                                            <option value="{{ $vendor->id }}" @if(isset($vendorName) && $vendor->id == $vendorName->id) selected @endif>
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Upload Panel 1 -->
                    <div class="col-md-6 order-2 order-md-1">
                        <div class="custom-file-upload text-center p-3 h-100">
                            <p class="fw-bold text-primary">Wage Register</p>
                            <input type="file" name="document1" id="fileUpload1" class="d-none"
                                onchange="previewFile(event, 'preview1')">
                            <label for="fileUpload1" class="upload-label d-block">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <p class="mb-0 text-muted">Click or drag file here to upload</p>
                            </label>
                            <div id="preview1" class="mt-3 text-start small text-muted"></div>
                        </div>
                    </div>

                    <!-- Upload Panel 2 -->
                    <div class="col-md-6 order-3 order-md-2">
                        <div class="custom-file-upload text-center p-3 h-100">
                            <p class="fw-bold text-primary">Bank Details
                            <p>
                                <input type="file" name="document2" id="fileUpload2" class="d-none"
                                    onchange="previewFile(event, 'preview2')">
                                <label for="fileUpload2" class="upload-label d-block">
                                    <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                    <p class="mb-0 text-muted">Click or drag file here to upload</p>
                                </label>
                            <div id="preview2" class="mt-3 text-start small text-muted"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                @php
                    $isDisabled = $isUploaded == 'yes' && $check_status->status != 'reject';
                    $islastdate_cross = $islast_date == 'yes';
                @endphp

                <button type="submit" class="btn btn-primary btn-lg px-4 py-2 rounded-pill" id="submit-btn" @if($isDisabled)
                disabled @endif @if($islastdate_cross) disabled @endif>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinner"></i>
                    <span id="btn-text">Submit Document </span>
                </button>

            </div>



        </form>

        <style>
            .custom-file-upload {
                cursor: pointer;
                background-color: #f8f9fa;
                transition: all 0.3s ease;
                border: 2px dashed #ccc;
                max-width: 1100px;
            }

            .custom-file-upload.dragover {
                background-color: #e3f2fd;
                border-color: #007bff;
            }

            .upload-label {
                cursor: pointer;
            }

            #preview {
                font-size: 0.9rem;
                word-break: break-word;
            }

            #preview strong {
                color: green;
            }

            .blink-text {
                animation: blink-animation 1.3s infinite step-start;
            }

            @keyframes blink-animation {
                0% {
                    opacity: 1;
                }

                50% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
            }


            #vendor_id {
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
                transition: border-color 0.3s, box-shadow 0.3s;
            }

            #vendor_id:focus {
                border-color: #FF6F61;
                box-shadow: 0 0 0 0.2rem rgba(255, 111, 97, 0.25);
            }

            #spinner {
                font-size: 1rem;
            }
        </style>

    @endsection
@endif
@section('scripts')
    <script>
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileUpload');

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.remove('dragover');
            });
        });



        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];

                // Create a new DataTransfer object to properly assign the file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                // Trigger preview
                previewFile({ target: fileInput });
            }

            dropArea.classList.remove('dragover');
        });

    </script>
    <script>
        function previewFile(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);

            if (!file) {
                preview.innerHTML = `<span class="text-danger">❌ No file selected.</span>`;
                return;
            }

            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'image/jpeg',
                'image/png'
            ];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (!allowedTypes.includes(file.type)) {
                preview.innerHTML = `<span class="text-danger">❌ Invalid file type: ${file.type}</span>`;
                return;
            }

            if (file.size > maxSize) {
                preview.innerHTML = `<span class="text-danger">❌ File too large. Max 5MB allowed.</span>`;
                return;
            }

            preview.innerHTML = `✅ <strong>${file.name}</strong> (${(file.size / 1024).toFixed(1)} KB)`;
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#vendor_id').select2({
                placeholder: "-- Select Vendor --",
                allowClear: true,
                width: '70%'
            });
        });
    </script>


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
    </script>



@endsection