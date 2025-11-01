

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.division.index')); ?>">List of Silo Filed</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Silo Filed</li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 2 || Session::get('user_sub_typeSession') == 1): ?>
    <script>window.location.href = "<?php echo e(url('admin/dashboard')); ?>";</script>
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <form id="fieldMasterForm" action="<?php echo e(route('admin.silo_master.store')); ?>" method="POST" autocomplete="off">
            <?php echo csrf_field(); ?>

            <style>
                /* Gray out preview fields */
                .form-control[disabled],
                .form-select[disabled],
                .form-check-input[disabled] {
                    background-color: #f8f9fa !important;
                    opacity: 1;
                    cursor: not-allowed;
                }

                /* Preview container */
                #fieldPreview {
                    padding: 15px;
                    border: 2px dashed #ccc;
                    background: #fdfdfd;
                    border-radius: 6px;
                    margin-top: 15px;
                }

                #fieldPreview::before {
                    content: "Preview Only";
                    display: block;
                    text-align: center;
                    font-size: 12px;
                    color: #888;
                    margin-bottom: 8px;
                    font-style: italic;
                }
            </style>

            <!-- Error Messages -->
            <div class="form-group-row">
                <div class="col-sm-12">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Success Message -->
            <div class="form-group-row">
                <div class="col-sm-12 text-center">
                    <?php if(session()->has('message')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('message')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Field Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Field Name (unique key)</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. lead1" required>
                <div class="form-text">Use lowercase/underscores. This will be the input’s "name".</div>
            </div>

            <!-- Label -->
            <div class="mb-3">
                <label for="label" class="form-label">Label (display text)</label>
                <input type="text" id="label" name="label" class="form-control" placeholder="e.g. Lead 1 Value" required>
            </div>

            <!-- Type -->
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="email">Email</option>
                    <option value="date">Date</option>
                    <option value="textarea">Textarea</option>
                    <option value="select">Select</option>
                    <option value="radio">Radio</option>
                </select>
            </div>

            <!-- Multiple -->
            <div class="mb-3 form-check" id="multipleWrapper" style="display:none;">
                <input type="checkbox" id="multiple" name="multiple" value="1" class="form-check-input">
                <label for="multiple" class="form-check-label">Multiple</label>
            </div>

            <!-- Options section -->
            <div id="optionsArea" class="mb-3" style="display:none;">
                <label class="form-label">Options</label>
                <div id="optionsList">
                    <div class="input-group mb-2 option-row">
                        <input type="text" name="options[]" class="form-control option-input"
                            placeholder="Option value (e.g. Open)">
                        <button type="button" class="btn btn-outline-danger remove-option">Remove</button>
                    </div>
                </div>
                <button type="button" id="addOption" class="btn btn-sm btn-secondary">+ Add option</button>
                <div class="form-text">For select/radio/checkbox types only.</div>
            </div>

            <!-- Required -->
            <div class="mb-3 form-check">
                <input type="checkbox" id="required" name="required" value="1" class="form-check-input">
                <label for="required" class="form-check-label">Required</label>
            </div>

            <!-- Order -->
            <div class="mb-3">
                <label for="order" class="form-label">Order</label>
                <input type="number" id="order" name="order" class="form-control" value="0">
            </div>

            <!-- Active -->
            <div class="mb-3 form-check">
                <input type="checkbox" id="active" name="active" value="1" class="form-check-input" checked>
                <label for="active" class="form-check-label">Active</label>
            </div>

            <!-- Preview -->
            <div class="mb-3">
                <h5>Field Preview</h5>
                <div id="fieldPreview" class="border p-3 rounded bg-light">
                    <em>Select a type and enter details to see preview...</em>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Save Field</button>
        </form>

        <!-- Script -->
        <script>
            const nameInput = document.getElementById('name');
            nameInput.addEventListener('input', function () {
                this.value = this.value
                    .toLowerCase()           // force lowercase
                    .replace(/[^a-z0-9_]/g, ''); // remove invalid chars
            });
        </script>
        <script>
            const typeSelect = document.getElementById('type');
            const optionsArea = document.getElementById('optionsArea');
            const optionsList = document.getElementById('optionsList');
            const multipleCheckbox = document.getElementById('multiple');
            const multipleWrapper = document.getElementById('multipleWrapper');

            // Show/hide options + multiple
            function toggleOptions() {
                if (typeSelect.value === 'select') {
                    optionsArea.style.display = 'block';
                    multipleWrapper.style.display = 'block';
                } else if (typeSelect.value === 'radio') {
                    optionsArea.style.display = 'block';
                    multipleWrapper.style.display = 'none';
                    multipleCheckbox.checked = false;
                } else {
                    optionsArea.style.display = 'none';
                    multipleWrapper.style.display = 'none';
                    multipleCheckbox.checked = false;
                }
                renderPreview();
            }

            typeSelect.addEventListener('change', toggleOptions);

            // Add new option
            document.getElementById('addOption').addEventListener('click', function () {
                const div = document.createElement('div');
                div.className = "input-group mb-2 option-row";
                div.innerHTML = `
                    <input type="text" name="options[]" class="form-control option-input" placeholder="Option value">
                    <button type="button" class="btn btn-outline-danger remove-option">Remove</button>`;
                optionsList.appendChild(div);
                renderPreview();
            });

            // Multiple checkbox toggle → only affects <select>
            multipleCheckbox.addEventListener('change', function () {
                renderPreview();
            });

            // Remove option
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-option')) {
                    e.target.closest('.option-row').remove();
                    renderPreview();
                }
            });

            // Render Preview
            function renderPreview() {
                const label = document.getElementById('label').value || 'Sample Label';
                const type = typeSelect.value;
                const required = document.getElementById('required').checked ? ' <span class="text-danger">*</span>' : '';
                const options = [...document.querySelectorAll('.option-input')].map(i => i.value).filter(v => v);
                const nameText = document.getElementById('name').value || 'field_name';

                let html = `<label class="form-label" for="${nameText}">${label} ${required}</label>`;

                if (['text', 'email', 'number', 'date'].includes(type)) {
                    html += `<input type="${type}" name="${nameText}" id="${nameText}" class="form-control" placeholder="Enter ${label}" disabled>`;
                } else if (type === 'textarea') {
                    html += `<textarea name="${nameText}" id="${nameText}" class="form-control" placeholder="Enter ${label}" disabled></textarea>`;
                } else if (type === 'select') {
                    const multipleAttr = multipleCheckbox.checked ? ' multiple size="4"' : '';
                    html += `<select name="${nameText}${multipleCheckbox.checked ? '[]' : ''}" 
                                 id="${nameText}" 
                                 class="form-control" disabled ${multipleAttr}>`;
                    html += `<option value="">-- Select --</option>`;
                    options.forEach(opt => {
                        html += `<option value="${opt}">${opt}</option>`;
                    });
                    html += `</select>`;
                } else if (type === 'radio') {
                    options.forEach((opt, idx) => {
                        html += `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${nameText}" id="radio${idx}" disabled>
                                <label class="form-check-label" for="radio${idx}">${opt}</label>
                            </div>`;
                    });
                }
                else if (type === 'checkbox') {
                    options.forEach((opt, idx) => {
                        html += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="${nameText}[]" id="check${idx}" disabled>
                                <label class="form-check-label" for="check${idx}">${opt}</label>
                            </div>`;
                    });
                }

                document.getElementById('fieldPreview').innerHTML = html;
            }

            // Bind live updates
            document.getElementById('label').addEventListener('input', renderPreview);
            document.getElementById('name').addEventListener('input', renderPreview);
            document.getElementById('required').addEventListener('change', renderPreview);
            document.addEventListener('input', function (e) {
                if (e.target.classList.contains('option-input')) {
                    renderPreview();
                }
            });

            // Initial run
            toggleOptions();
        </script>
    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/silo_master/create.blade.php ENDPATH**/ ?>