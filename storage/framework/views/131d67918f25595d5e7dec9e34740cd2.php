<?php
use App\Division;
use App\Department;
use App\UserLogin;
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.vendor_esic_details.index')); ?>">List Of Vendor Esic Documents</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Upload Esic Documents</li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 4): ?>
    return redirect('admin/dashboard');
<?php else: ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <?php $__env->startSection('content'); ?>
        <?php if($isUploaded == 'yes'): ?>
            <div class="alert alert-secondary">
                ✅ Document's has already been uploaded for
                <strong><?php echo e($previousMonthYear); ?>,
                    <span
                        style="animation: blinker 1s linear infinite; color: <?php echo e($color); ?>; @keyframes blinker { 50% { opacity: 0; } }">
                        <?php echo e($form_status); ?>

                    </span>
                </strong>.
            </div>

        <?php endif; ?>

        <?php if($islast_date == 'yes'): ?>
            <div class="alert"
                style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 6px; padding: 12px; font-weight: 600; box-shadow: 0 2px 6px rgba(216, 123, 130, 0.3); font-family: Arial, sans-serif;">
                Last Date is crossed, you are unable to upload the file.
            </div>


        <?php endif; ?>

        <?php if(session('message')): ?>
            <div class="alert <?php echo e(session('message') == 'Esic uploaded successfully.' ? 'alert-success' : 'alert-danger'); ?>">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('message') || $errors->any()): ?>
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
        <?php endif; ?>


        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>





        <form action="<?php echo e(route('admin.vendor_esic_details.store')); ?>" method="post" enctype="multipart/form-data" id="form"
            autocomplete="off">
            <?php echo csrf_field(); ?>
            <div class="alert alert-info p-2 mb-3 small" style="font-size: 0.9rem;">
                <strong>Instructions:</strong> Please upload only
                <code>.pdf</code>, <code>.doc</code>, <code>.docx</code>, <code>.xls</code>, <code>.xlsx</code>,
                <code>.jpg</code>, <code>.jpeg</code>, or <code>.png</code> files. Maximum file size: <strong>5MB</strong>.
            </div>

            <div class="col-md-12 mb-4">
                <h3 class="fw-bold">
                    <i class="fas fa-upload" style="color: #2c62a0;"></i> Vendor ESIC Challan & Contribution
                </h3>

                <!-- Vendor Details Panel - Full Width -->
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="custom-file-upload border rounded p-4">
                            <div class="row mb-2">
                                <?php if(!empty($vendorName->name)): ?>
                                    <div class="col-md-3">
                                        <p><strong>Vendor Name:</strong> <?php echo e($vendorName->name); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-3">
                                    <p><strong>Wage Month:</strong> <?php echo e($previousMonthYear); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Uploaded Month:</strong> <?php echo e($currentMonthYear); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Last Date to Upload:</strong>
                                        <a href="#" class="blink-text" style="color: #bb3e1f; font-weight: bold;">
                                            <?php echo e($setting_date->value); ?>th of <?php echo e($currentMonthYear); ?>

                                        </a>
                                    </p>
                                </div>
                            </div>


                            <?php
                                $style = (Session::get('user_sub_typeSession') == 2) ? 'style=display:none;' : '';
                            ?>
                            <div class="mb-3" <?php echo $style; ?>>
                                <label for="vendor_id" class="form-label fw-bold">Select Vendor :</label>
                                <select name="vendor_id" id="vendor_id" class="form-select select2" required>
                                    <option value="">-- Select Vendor --</option>
                                    <?php $__currentLoopData = $vendorlist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vendor->id); ?>" <?php if(isset($vendorName) && $vendor->id == $vendorName->id): ?>
                                        selected <?php endif; ?>>
                                            <?php echo e($vendor->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Panels - Half Width Each -->
                <div class="row">
                    <!-- File Upload 1 -->
                    <div class="col-md-6">
                        <div class="custom-file-upload text-center" id="uploadArea1">
                            <h5 class="text-primary fw-bold mb-3 p-2">ESIC Challan</h5>

                            <input type="file" name="document1" id="fileUpload1" class="d-none"
                                onchange="previewFile(event, 'preview1')">
                            <label for="fileUpload1" class="upload-label">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <p class="mb-0 text-muted">Click or drag file here to upload</p>
                            </label>
                            <div id="preview1" class="mt-3 text-start small text-muted"></div>
                            <br>
                        </div>
                    </div>

                    <!-- File Upload 2 -->
                    <div class="col-md-6">
                        <div class="custom-file-upload text-center" id="uploadArea2">
                            <h5 class="text-primary fw-bold mb-3 p-2">ESIC Contribution</h5>

                            <input type="file" name="document2" id="fileUpload2" class="d-none"
                                onchange="previewFile(event, 'preview2')">
                            <label for="fileUpload2" class="upload-label">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <p class="mb-0 text-muted">Click or drag file here to upload</p>
                            </label>
                            <div id="preview2" class="mt-3 text-start small text-muted"></div>
                            <br>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <?php
                        $isDisabled = $isUploaded == 'yes' && $check_status->status != 'reject';
                        $islastdate_cross = $islast_date == "yes";
                    ?>

                    <button type="submit" class="btn btn-primary btn-lg px-4 py-2 rounded-pill" id="submit-btn" <?php if($isDisabled): ?>
                    disabled <?php endif; ?> <?php if($islastdate_cross): ?> disabled <?php endif; ?>>
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
                max-width: 1200px;
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

    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
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
            const maxSize = 5 * 1024 * 1024;

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

        // Drag-and-drop support
        function setupDragAndDrop(areaId, inputId) {
            const dropArea = document.getElementById(areaId);
            const fileInput = document.getElementById(inputId);

            dropArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropArea.classList.add('dragover');
            });

            dropArea.addEventListener('dragleave', () => {
                dropArea.classList.remove('dragover');
            });

            dropArea.addEventListener('drop', (e) => {
                e.preventDefault();
                dropArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        }

        // Initialize both upload areas
        setupDragAndDrop('uploadArea1', 'fileUpload1');
        setupDragAndDrop('uploadArea2', 'fileUpload2');
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



<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_esic_challan/create.blade.php ENDPATH**/ ?>