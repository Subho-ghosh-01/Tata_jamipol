

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.silo_master.index')); ?>">SILO Master List</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Field</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('admin.silo_master.update', $silo->id)); ?>" method="post" autocomplete="off">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if(session()->has('message')): ?>
            <div class="alert alert-success">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>

        <!-- Field Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Field Name (unique key)</label>
            <input type="text" id="name" name="name" class="form-control"
                value="<?php echo e(old('name', $silo->name)); ?>"
                placeholder="e.g. lead_1" required pattern="^[a-z0-9_]+$"
                title="Only lowercase letters, numbers, and underscores (_) allowed.">
            <div class="form-text">Use lowercase/underscores. This will be the input’s "name".</div>
        </div>

        <!-- Label -->
        <div class="mb-3">
            <label for="label" class="form-label">Label</label>
            <input type="text" id="label" name="label" class="form-control"
                value="<?php echo e(old('label', $silo->label)); ?>" required>
        </div>

        <!-- Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select id="type" name="type" class="form-control" required>
                <?php
                    $types = ['text', 'number', 'email', 'date', 'textarea', 'select', 'radio', 'checkbox'];
                ?>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(old('type', $silo->type) == $t ? 'selected' : ''); ?>>
                        <?php echo e(ucfirst($t)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <!-- Multiple -->
        <div class="mb-3 form-check" id="multipleWrapper" style="display:none;">
            <input type="checkbox" id="multiple" name="multiple" value="1" class="form-check-input"
                <?php echo e(old('multiple', $silo->ismultiple ?? 0) ? 'checked' : ''); ?>>
            <label for="multiple" class="form-check-label">Multiple</label>
        </div>

        <!-- Options -->
        <div id="optionsArea" class="mb-3" style="display:none;">
            <label class="form-label">Options</label>
            <div id="optionsList">
                <?php
                    $options = old('options', json_decode($silo->options) ?? []);
                ?>
                <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="input-group mb-2 option-row">
                        <input type="text" name="options[]" class="form-control option-input"
                            value="<?php echo e($opt); ?>" placeholder="Option value">
                        <button type="button" class="btn btn-outline-danger remove-option">Remove</button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <button type="button" id="addOption" class="btn btn-sm btn-secondary">+ Add option</button>
        </div>

        <!-- Required -->
        <div class="mb-3 form-check">
            <input type="checkbox" id="isrequired" name="required" value="1" class="form-check-input"
                <?php echo e(old('isrequired', $silo->isrequired) ? 'checked' : ''); ?>>
            <label for="isrequired" class="form-check-label">Required</label>
        </div>

        <!-- Display Order -->
        <div class="mb-3">
            <label for="displayorder" class="form-label">Display Order</label>
            <input type="number" id="displayorder" name="order" class="form-control"
                value="<?php echo e(old('displayorder', $silo->displayorder ?? 0)); ?>">
        </div>

        <!-- Active -->
        <div class="mb-3 form-check">
            <input type="checkbox" id="isactive" name="active" value="1" class="form-check-input"
                <?php echo e(old('isactive', $silo->isactive) ? 'checked' : ''); ?>>
            <label for="isactive" class="form-check-label">Active</label>
        </div>

        <!-- Preview -->
        <div class="mb-3">
            <h5>Field Preview</h5>
            <div id="fieldPreview" class="border p-3 rounded bg-light">
                <em>Select a type and enter details to see preview...</em>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary">Update Field</button>
    </form>

    <script>
        const nameInput = document.getElementById('name');
        const typeSelect = document.getElementById('type');
        const optionsArea = document.getElementById('optionsArea');
        const optionsList = document.getElementById('optionsList');
        const labelInput = document.getElementById('label');
        const requiredCheckbox = document.getElementById('isrequired');
        const fieldPreview = document.getElementById('fieldPreview');
        const multipleCheckbox = document.getElementById('multiple');
        const multipleWrapper = document.getElementById('multipleWrapper');

        // Force lowercase and valid characters
        nameInput.addEventListener('input', function () {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
        });

        // Show/hide options + multiple
        function toggleOptions() {
            if (typeSelect.value === 'select') {
                optionsArea.style.display = 'block';
                multipleWrapper.style.display = 'block';
            } else if (typeSelect.value === 'radio' || typeSelect.value === 'checkbox') {
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

        // Render live preview
        function renderPreview() {
            const label = labelInput.value || 'Sample Label';
            const type = typeSelect.value;
            const required = requiredCheckbox.checked ? ' <span class="text-danger">*</span>' : '';
            const options = [...document.querySelectorAll('.option-input')]
                .map(i => i.value).filter(v => v);
            const nameText = nameInput.value || 'field_name';

            let html = `<label class="form-label" for="${nameText}">${label} ${required}</label>`;

            if (['text', 'email', 'number', 'date'].includes(type)) {
                html += `<input type="${type}" name="${nameText}" id="${nameText}"
                            class="form-control" placeholder="Enter ${label}" disabled>`;
            } else if (type === 'textarea') {
                html += `<textarea name="${nameText}" id="${nameText}"
                            class="form-control" placeholder="Enter ${label}" disabled></textarea>`;
            } else if (type === 'select') {
                const multipleAttr = multipleCheckbox.checked ? ' multiple size="4"' : '';
                html += `<select name="${nameText}${multipleCheckbox.checked ? '[]' : ''}" 
                             id="${nameText}" class="form-control" disabled ${multipleAttr}>`;
                html += `<option value="">-- Select --</option>`;
                options.forEach(opt => html += `<option value="${opt}">${opt}</option>`);
                html += `</select>`;
            } else if (type === 'radio') {
                options.forEach((opt, idx) => {
                    html += `<div class="form-check">
                                <input class="form-check-input" type="radio" name="${nameText}" id="radio${idx}" disabled>
                                <label class="form-check-label" for="radio${idx}">${opt}</label>
                            </div>`;
                });
            } else if (type === 'checkbox') {
                options.forEach((opt, idx) => {
                    html += `<div class="form-check">
                                <input class="form-check-input" type="checkbox" name="${nameText}[]" id="check${idx}" disabled>
                                <label class="form-check-label" for="check${idx}">${opt}</label>
                            </div>`;
                });
            }

            fieldPreview.innerHTML = html;
        }

        // Event listeners
        typeSelect.addEventListener('change', toggleOptions);
        labelInput.addEventListener('input', renderPreview);
        nameInput.addEventListener('input', renderPreview);
        requiredCheckbox.addEventListener('change', renderPreview);
        multipleCheckbox.addEventListener('change', renderPreview);

        document.getElementById('addOption').addEventListener('click', function () {
            const div = document.createElement('div');
            div.className = "input-group mb-2 option-row";
            div.innerHTML = `
                <input type="text" name="options[]" class="form-control option-input" placeholder="Option value">
                <button type="button" class="btn btn-outline-danger remove-option">Remove</button>
            `;
            optionsList.appendChild(div);
            renderPreview();
        });

        // Remove option
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-option')) {
                e.target.closest('.option-row').remove();
                renderPreview();
            }
        });

        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('option-input')) renderPreview();
        });

        // Init
        toggleOptions();
        renderPreview();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/silo_master/edit.blade.php ENDPATH**/ ?>