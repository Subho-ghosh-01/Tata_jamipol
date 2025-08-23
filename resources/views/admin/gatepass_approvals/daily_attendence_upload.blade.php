@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Import Attendance</li>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                {{-- Alert: Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm rounded">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Alert: Success --}}
                @if (session('message'))
                    <div class="alert alert-success shadow-sm rounded text-center">
                        {!! session('message') !!}
                    </div>
                @endif


                {{-- Upload Card --}}
                <div class="card custom-shadow rounded border-0" id="uploadCard">
                    <div class="card-header bg-primary text-white text-center rounded-top">
                        <h5 class="mb-0">Daily Attendance Upload</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <a href="{{ URL::to('public/documents/daily_attendence_upload.xlsx') }}"
                                class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-download me-1"></i> Download Sample Excel File
                            </a>
                        </div>

                        <form action="{{ route('admin.daily_attendence_import') }}" method="POST"
                            enctype="multipart/form-data" id="uploadForm">
                            @csrf

                            <!-- Drag & Drop Upload Box -->
                            <style>
                                #drop-area {
                                    border: 2px dotted rgb(0, 124, 128);
                                    /* Change from dashed to dotted */
                                    border-radius: 10px;
                                    background-color: #fdfdfd;
                                    transition: background-color 0.3s ease, box-shadow 0.3s ease;
                                    cursor: pointer;
                                }

                                #drop-area:hover {
                                    background-color: #fff0f0;
                                    box-shadow: 0 0 10px rgba(14, 132, 148, 0.3);
                                }

                                #fileName {
                                    font-size: 0.95rem;
                                }
                            </style>
                            <div class="mb-3 row">
                                <label for="form-control-label" class="col-sm-3 col-form-label">Division<span
                                        style="color:red;font-size: 20px;">*</span></label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="" name="division_id" required>
                                        <option value="">Select The Division</option>
                                        @if($divisions->count() > 0)
                                            @foreach($divisions as $division)
                                                <option value="{{$division->id}}">{{$division->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Excel File</label>

                                <div id="drop-area" class="p-4 text-center">
                                    <p class="text-muted mb-2">Drag & drop your Excel file here, or click to select</p>
                                    <input type="file" name="file_datas" id="file_datas" class="form-control d-none"
                                        accept=".xlsx, .xls" required>
                                    <button type="button" id="browseButton" class="btn btn-outline-secondary btn-sm">Choose
                                        File</button>
                                    <div id="fileName" class="mt-2 text-success fst-italic">...</div>
                                    <div id="errorMessage" class="mt-2 text-danger fw-semibold" style="display: none;">
                                    </div>

                                </div>
                            </div>


                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-upload me-1"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>


        <!-- Loader Overlay -->
        <!-- Fullscreen Loader Overlay (Tailwind) -->
        <div id="loaderOverlay" class="fixed inset-0 bg-gray-100 bg-opacity-80 z-[1050] shadow-xl hidden">
            <div class="flex justify-center items-center h-full">
                <div class="flex items-center space-x-4">
                    <span class="text-blue-600 font-semibold text-base ">Loading...</span>
                    <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('scripts')
    <style>
        .custom-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">




    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('uploadForm').addEventListener('submit', function () {
                document.getElementById('loaderOverlay').style.display = 'block';
            });
        });
    </script>
    <script>
        const dropArea = document.getElementById("drop-area");
        const fileInput = document.getElementById("file_datas");
        const browseButton = document.getElementById("browseButton");
        const fileNameDisplay = document.getElementById("fileName");
        const errorMessage = document.getElementById("errorMessage");

        const allowedExtensions = [".xlsx"];

        function isExcelFile(fileName) {
            return allowedExtensions.some(ext => fileName.toLowerCase().endsWith(ext));
        }

        function showError(msg) {
            errorMessage.textContent = msg;
            errorMessage.style.display = "block";
        }

        function clearError() {
            errorMessage.textContent = "";
            errorMessage.style.display = "none";
        }

        browseButton.addEventListener("click", () => fileInput.click());

        fileInput.addEventListener("change", () => {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (isExcelFile(file.name)) {
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.classList.remove("text-danger");
                    fileNameDisplay.classList.add("text-success");
                    clearError();
                } else {
                    fileInput.value = "";
                    fileNameDisplay.textContent = "";
                    fileNameDisplay.classList.remove("text-success");
                    showError("Only Excel files ( .xlsx) are allowed.");
                }
            }
        });

        dropArea.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropArea.classList.add("bg-green", "shadow-sm");
        });

        dropArea.addEventListener("dragleave", () => {
            dropArea.classList.remove("bg-green", "shadow-sm");
        });

        dropArea.addEventListener("drop", (e) => {
            e.preventDefault();
            dropArea.classList.remove("bg-green", "shadow-sm");

            const droppedFiles = e.dataTransfer.files;
            if (droppedFiles.length > 0) {
                const file = droppedFiles[0];
                if (isExcelFile(file.name)) {
                    fileInput.files = droppedFiles;
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.classList.remove("text-danger");
                    fileNameDisplay.classList.add("text-success");
                    clearError();
                } else {
                    fileInput.value = "";
                    fileNameDisplay.textContent = "";
                    fileNameDisplay.classList.remove("text-success");
                    showError("Only Excel files (.xlsx) are allowed.");
                }
            }
        });
    </script>


@endsection