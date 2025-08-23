

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active">Import Holiday</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-8">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger shadow-sm rounded">
                        <ul class="mb-0 ps-3">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(session('message')): ?>
                    <div class="alert alert-success shadow-sm rounded text-center">
                        <?php echo session('message'); ?>

                    </div>
                <?php endif; ?>

                
                <div class="card border-0 shadow-lg rounded" id="uploadCard">
                    <div class="card-header bg-gradient bg-primary text-white text-center rounded-top">
                        <h5 class="mb-1 d-flex justify-content-center align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white"
                                class="bi bi-calendar-heart-fill" viewBox="0 0 16 16">
                                <path
                                    d="M4 .5a.5.5 0 0 1 .5.5V2h6V1a.5.5 0 0 1 1 0v1h.5A1.5 1.5 0 0 1 13.5 3v1H2.5V3A1.5 1.5 0 0 1 4 .5ZM1 14V5h14v9a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2Zm6.995-7.138c.343-.443.773-.705 1.18-.705.455 0 .925.27 1.336.747.405.47.66 1.093.66 1.681 0 .563-.232 1.084-.613 1.447L8.5 12.125l-2.058-1.93A2.002 2.002 0 0 1 5.82 8.585c0-.588.255-1.21.66-1.681Z" />
                            </svg>
                            Import Yearly Leave List
                        </h5>
                        <div class="mt-2 small text-black">
                            <a href="<?php echo e(URL::to('documents/holiday_list_yearly.xlsx')); ?>"
                                class="btn btn-outline-light btn-sm">
                                <i class="bi bi-download me-1"></i> Download Sample Excel File
                            </a>

                        </div>


                    </div>
                    <div class="mt-2  text-black text-start">
                        ➤ First, <strong>download the sample Excel file</strong> provided above.<br>
                        ➤ Fill in the <strong>Employee P.No</strong>, <strong>Year</strong>, <strong>Name</strong>,
                        and <strong>Holiday Code wise number</strong> (e.g., <code>CL</code>,
                        <code>SL</code>, <code>PL</code>, etc.).<br>

                        ➤ Save the file in <code>.xlsx</code> format and upload it below.
                    </div>
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('admin.vendor_holiday.store')); ?>" method="POST" enctype="multipart/form-data"
                            id="uploadForm">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Upload Excel File</label>
                                <div id="drop-area" class="p-4 text-center border border-2 border-dotted rounded bg-light">
                                    <p class="text-muted mb-2">Drag & drop your Excel file here or click to browse</p>
                                    <input type="file" name="file_datas" id="file_datas" class="form-control d-none"
                                        accept=".xlsx, .xls" required>
                                    <button type="button" id="browseButton" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-folder2-open me-1"></i> Choose File
                                    </button>
                                    <div id="fileName" class="mt-2 text-success fst-italic">No file selected</div>
                                    <div id="errorMessage" class="mt-2 text-danger fw-semibold d-none"></div>
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

        
        <div id="loaderOverlay" class="fixed inset-0 bg-white bg-opacity-75 z-50 hidden" hidden>
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    </div>
                    <p class="fw-semibold text-primary">Uploading... Please wait</p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .border-dotted {
            border-style: dotted !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('uploadForm');
            const overlay = document.getElementById('loaderOverlay');
            const dropArea = document.getElementById("drop-area");
            const fileInput = document.getElementById("file_datas");
            const browseButton = document.getElementById("browseButton");
            const fileNameDisplay = document.getElementById("fileName");
            const errorMessage = document.getElementById("errorMessage");
            const allowedExtensions = [".xlsx"];

            form.addEventListener('submit', function () {
                overlay.classList.remove('hidden');
            });

            browseButton.addEventListener("click", () => fileInput.click());

            fileInput.addEventListener("change", () => {
                handleFile(fileInput.files[0]);
            });

            dropArea.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropArea.classList.add("border-primary", "bg-white");
            });

            dropArea.addEventListener("dragleave", () => {
                dropArea.classList.remove("border-primary", "bg-white");
            });

            dropArea.addEventListener("drop", (e) => {
                e.preventDefault();
                dropArea.classList.remove("border-primary", "bg-white");
                const file = e.dataTransfer.files[0];
                fileInput.files = e.dataTransfer.files;
                handleFile(file);
            });

            function handleFile(file) {
                if (file && allowedExtensions.some(ext => file.name.toLowerCase().endsWith(ext))) {
                    fileNameDisplay.textContent = file.name;
                    fileNameDisplay.classList.replace("text-danger", "text-success");
                    errorMessage.classList.add("d-none");
                } else {
                    fileInput.value = "";
                    fileNameDisplay.textContent = "No file selected";
                    fileNameDisplay.classList.replace("text-success", "text-danger");
                    errorMessage.textContent = "Only Excel files (.xlsx) are allowed.";
                    errorMessage.classList.remove("d-none");
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_holiday/create.blade.php ENDPATH**/ ?>