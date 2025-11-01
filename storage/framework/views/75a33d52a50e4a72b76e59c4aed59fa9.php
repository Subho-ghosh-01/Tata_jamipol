<?php 
$vms = DB::table('vendor_mis')->where('id', $vms_details->id)->first();

$vms_flow = DB::table('vendor_mis_flow')->where('vendor_mis');



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Safety Performance — Best UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />


    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'JAMIPOL SURAKSHA')); ?></title>
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        :root {
            --coral: #61b3ff;
            --coral-600: #4baeff;
            --soft-bg: #f6f7fb;
        }

        body {
            background: var(--soft-bg);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        .section {
            display: none;
            opacity: 0;
            transform: translateY(6px);
            transition: all .25s ease;
        }

        .section.active {
            display: block;
            opacity: 1;
            transform: none;
        }

        .stepper {
            display: flex;
            gap: 16px;
            align-items: center;
            margin-bottom: 18px;
        }

        .stepper .step {
            flex: 1;
            position: relative;
            text-align: center;
        }

        .step .dot {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e9ecef;
            color: #6c757d;
        }

        .step.active .dot {
            background: var(--coral);
            color: #fff;
            box-shadow: 0 0 0 6px rgba(255, 111, 97, .18);
        }

        .step .label {
            margin-top: 6px;
            font-weight: 600;
            color: #6c757d;
            font-size: .95rem;
        }

        .step.active .label {
            color: var(--coral);
        }

        .step::after {
            content: "";
            position: absolute;
            top: 19px;
            left: 55%;
            right: -43%;
            height: 4px;
            background: #e9ecef;
        }

        .step:last-child::after {
            display: none;
        }

        .step.active::after {
            background: linear-gradient(90deg, var(--coral), #ffc2bc);
        }

        .btn-coral {
            background: var(--coral);
            border-color: var(--coral);
        }

        .btn-coral:hover {
            background: var(--coral-600);
            border-color: var(--coral-600);
        }

        .badge-soft {
            background: #fff;
            border: 1px solid #ececec;
        }

        .sticky-nav {
            position: sticky;
            bottom: 0;
            z-index: 1030;
            backdrop-filter: saturate(180%) blur(6px);
            background: rgba(255, 255, 255, .85);
            border-top: 1px solid #eee;
        }

        .hint {
            color: #6c757d;
            font-size: .9rem;
        }

        .file-list {
            list-style: none;
            margin: 8px 0 0;
            padding: 0;
        }

        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 8px 12px;
            margin-bottom: 6px;
        }

        .file-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .file-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 360px;
        }

        .remove-file {
            border: none;
            background: transparent;
            color: #dc3545;
            font-size: 1.05rem;
        }

        .is-invalid+.file-list,
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .modal-header {
            background: #1f1f1f;
            color: #fff;
        }

        .nav-tabs .nav-link.active {
            color: var(--coral) !important;
            border-color: var(--coral) var(--coral) #fff !important;
        }

        .nav-tabs .nav-link {
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .file-name {
                max-width: 160px;
            }
        }
    </style>
</head>

<body class="py-3 py-md-4">

    <div class="container">
        <!-- Stepper -->
        <div class="stepper" id="stepper">
            <div class="step active" data-step="1">
                <div class="dot"><i class="bi bi-info-circle"></i></div>
                <div class="label">Basic Info</div>
            </div>
            <div class="step" data-step="2">
                <div class="dot"><i class="bi bi-clipboard-check"></i></div>
                <div class="label">Lead Indicators</div>
            </div>
            <div class="step" data-step="3">
                <div class="dot"><i class="bi bi-graph-up"></i></div>
                <div class="label">Lag Indicators</div>
            </div>
        </div>

        <!-- Form -->
        <form id="form" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" value="<?php echo e($user_id ?? ''); ?>" name="uid">
            <input type="hidden" name="status" id="statusField" value="draft">
            <input type="hidden" name="id" id="recordId" value="<?php echo e($vms->id); ?>">

            <!-- Section 1 -->
            <section class="section active" id="section1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3" style="color: rgb(37, 69, 110);">Basic Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select class="form-control indicator-value" name="division" id="division" required>
                                    <option value="">Select Division</option>
                                    <?php if($divs->count() > 0): ?>
                                        <?php $__currentLoopData = $divs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($division->id); ?>" <?php echo e(($vms->division_id ?? '') == $division->id ? 'selected' :
                                            ''); ?>>
                                                                        <?php echo e($division->name); ?>

                                                                    </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Plant <span class="text-danger">*</span></label>
                                <select class="form-control" name="plant" id="plant" required>
                                    <option value="">Select Plant</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-control" name="department" id="department" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reporting Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" name="report_month" id="report_month"
                                    value="<?php echo e($vms->month); ?>" required>
                            </div>
                        </div>
                        <p class="hint mt-3"><i class="bi bi-lightbulb"></i> You can move back anytime; your inputs
                            remain saved.</p>
                    </div>
                </div>
            </section>

            <script>
                $(document).ready(function () {
                    var selectedDivision = "<?php echo e($vms->division_id ?? ''); ?>";
                    var selectedPlant = "<?php echo e($vms->plant_id ?? ''); ?>";
                    var selectedDepartment = "<?php echo e($vms->department_id ?? ''); ?>";

                    function loadPlants(divisionID, callback) {
                        $("#plant").html('<option value="">Select Plant</option>');
                        $("#department").html('<option value="">Select Department</option>');

                        if (!divisionID) return;

                        $.ajax({
                            type: 'GET',
                            url: "<?php echo e(route('admin.departmentGet_vendor_mis')); ?>/" + divisionID,
                            dataType: "json",
                            success: function (data) {
                                $.each(data, function (i, item) {
                                    var selected = (item.id == selectedPlant) ? 'selected' : '';
                                    $("#plant").append('<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>');
                                });
                                if (callback) callback();
                            }
                        });
                    }

                    function loadDepartments(plantID) {
                        $("#department").html('<option value="">Select Department</option>');

                        if (!plantID) return;

                        $.ajax({
                            type: 'GET',
                            url: "<?php echo e(route('admin.PlantGet_vendor_mis')); ?>/" + plantID,
                            dataType: "json",
                            success: function (data) {
                                $.each(data, function (i, item) {
                                    var selected = (item.id == selectedDepartment) ? 'selected' : '';
                                    $("#department").append('<option value="' + item.id + '" ' + selected + '>' + item.department_name + '</option>');
                                });
                            }
                        });
                    }

                    // On change of division
                    $('#division').on('change', function () {
                        var divisionID = $(this).val();
                        selectedPlant = ""; // reset selected plant on manual change
                        selectedDepartment = ""; // reset selected dept
                        loadPlants(divisionID);
                    });

                    // On change of plant
                    $('#plant').on('change', function () {
                        var plantID = $(this).val();
                        selectedDepartment = ""; // reset selected dept on manual change
                        loadDepartments(plantID);
                    });

                    // Auto-load if editing existing record
                    if (selectedDivision) {
                        loadPlants(selectedDivision, function () {
                            if (selectedPlant) {
                                loadDepartments(selectedPlant);
                            }
                        });
                    }
                });
            </script>
            <!-- Section 2: Lead -->
            <section class="section" id="section2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="mb-0" style="color: green;">Lead Indicators</h4>
                            <span class="badge badge-soft rounded-pill px-3 py-2"><i class="bi bi-info-circle"></i> If
                                value > 0, attach proof</span>
                        </div>
                        <hr>
                        <div id="leadContainer" class="row g-4"></div>
                    </div>
                </div>
            </section>

            <!-- Section 3: Lag -->
            <section class="section" id="section3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="mb-0" style="color: red;">Lag Indicators</h4>
                            <span class="badge badge-soft rounded-pill px-3 py-2"><i class="bi bi-info-circle"></i> If
                                value > 0, attach proof</span>
                        </div>
                        <hr>
                        <div id="lagContainer" class="row g-4"></div>

                        <div class="mt-4">
                            <label class="form-label">Overall Comments</label>
                            <textarea class="form-control" rows="3" name="overall_comments"
                                placeholder="Add any summary or notes…"></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sticky footer nav -->
            <div class="sticky-nav py-3">
                <div class="container d-flex gap-2 justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-secondary" id="btnPrev"><i
                                class="bi bi-arrow-left"></i> Previous</button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary d-none" id="btnSaveDraft"><i
                                class="bi bi-cloud-arrow-up"></i> Save Draft</button>
                        <button type="button" class="btn btn-coral" id="btnNext"><span id="nextLbl">Save & Next</span><i
                                class="bi bi-arrow-right ms-1"></i></button>
                        <button type="button" class="btn btn-success d-none" id="btnPreview"><i class="bi bi-eye"></i>
                            Preview</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-eye"></i> Review & Confirm</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#pv-basic" type="button" role="tab">Basic Info</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#pv-lead" type="button" role="tab">Lead Indicators</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#pv-lag" type="button" role="tab">Lag Indicators</button></li>
                    </ul>
                    <div class="tab-content p-3">
                        <div class="tab-pane fade show active" id="pv-basic" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6"><strong>Division:</strong> <span id="pv_division"></span></div>
                                <div class="col-md-3"><strong>Plant:</strong> <span id="pv_plant"></span></div>
                                <div class="col-md-3"><strong>Department:</strong> <span id="pv_department"></span>
                                </div>
                                <div class="col-md-6"><strong>Month:</strong> <span id="pv_month"></span></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pv-lead" role="tabpanel">
                            <div id="pvLeadList"></div>
                        </div>
                        <div class="tab-pane fade" id="pv-lag" role="tabpanel">
                            <div id="pvLagList"></div>
                        </div>
                    </div>

                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="consentCheck">
                        <label class="form-check-label" for="consentCheck">I confirm the above information is correct
                            and true in nature.</label>
                    </div>
                </div>
                <div class="modal-footer flex-column align-items-start">
                    <div class="text-danger small mb-2" id="pvWarning" style="display:none;">
                        ⚠ You have not filled all required fields. Missing: <span id="missingSections"></span>
                    </div>
                    <div class="d-flex justify-content-end w-100 gap-2">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-warning" id="btnDraft">
                            <i class="bi bi-save"></i> Save as Draft
                        </button>
                        <button class="btn btn-success" id="btnFinalSubmit">
                            <i class="bi bi-check2-circle"></i> Submit
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Center Loader -->
    <div id="pageLoader"
        class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50"
        style="z-index: 2000;">
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="spinner-border text-primary mb-2" role="status"></div>
            <p class="mb-0 fw-bold">Saving...</p>
        </div>
    </div>

    <!-- Bootstrap + Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* === Same JS as you had (stepper, file upload, preview, validation, etc.) === */
        /* keep your original script here (no need to change except add Laravel CSRF if AJAX used) */
    </script>
    <script>
        window.vmsData = <?php echo json_encode($vms, 15, 512) ?>;

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        /* ---------- Config ---------- */
        const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
        const ALLOWED_EXT = ["pdf"];

        const leadNames = [
            "1. Safety Training Session Conducted During The Month",
            "2. Total Training Employee Hours",
            "3. No of Mass Meeting Conducted",
            "4. No of Line Walk Conducted",
            "5. No of Site Safety Audit Conducted",
            "6. No of Housekeeping Audit Conducted",
            "7. No of PPE Audit Conducted",
            "8. No of Tools-Tackles Audit Conducted",
            "9. No of Safety Kaizen Done",
            "10. No of Near Miss Reported During the Month"
        ];

        const lagNames = [
            "1. No of First Aid Case",
            "2. No of Medical Treated Case",
            "3. No of LTIs",
            "4. No of Fatality",
            "5. No of Non Injury Incident"
        ];

        /* ---------- Helpers ---------- */
        const fmtSize = bytes => {
            if (bytes === 0) return "0 B";
            const k = 1024, dm = 1, sizes = ["B", "KB", "MB", "GB", "TB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
        };

        /* ---------- State ---------- */
        let currentStep = 1;
        const TOTAL_STEPS = 3;
        const files = { lead: {}, lag: {} };
    </script>
    <script>
        // let files = { lead: {}, lag: {} };           // newly uploaded files
        let filesToDelete = { lead: {}, lag: {} };  // DB files marked for deletion

        // Parse DB saved files
        function parseSavedDocs(savedDocsRaw) {
            if (!savedDocsRaw) return [];
            if (Array.isArray(savedDocsRaw)) return savedDocsRaw;
            if (typeof savedDocsRaw === "string") {
                try {
                    return savedDocsRaw.trim().startsWith("[") ? JSON.parse(savedDocsRaw) : savedDocsRaw.split(",").map(s => s.trim()).filter(s => s);
                } catch (e) {
                    return [savedDocsRaw];
                }
            }
            return [savedDocsRaw];
        }

        // Build indicators (lead / lag)
        function buildIndicators(containerId, type, names) {
            const container = document.getElementById(containerId);
            names.forEach((label, i) => {
                const idx = i + 1;
                const valKey = `${type}${idx}_val`;
                const docKey = `${type}${idx}_doc`;

                const savedVal = window.vmsData?.[valKey] ?? "";
                const savedDocsRaw = window.vmsData?.[docKey] ?? [];
                const savedDocs = parseSavedDocs(savedDocsRaw);

                files[type][idx] = files[type][idx] || [];
                filesToDelete[type][idx] = filesToDelete[type][idx] || [];

                const wrap = document.createElement("div");
                wrap.className = "col-12";

                if (type === "lead" && idx === 1) {
                    wrap.innerHTML = `
        <div class="p-3 border rounded-4 bg-light mb-3 shadow-sm">
            <div class="row g-3 align-items-end">
                <div class="col-sm-12">
                    <label class="form-label fw-semibold">${label}</label>
                    <input type="number" min="0" class="form-control val-input"
                        name="${type}${idx}_val" value="${savedVal}" placeholder="0" data-type="${type}" data-index="${idx}">
                </div>
            </div>
        </div>`;
                } else {
                    wrap.innerHTML = `
        <div class="p-3 border rounded-4 bg-light mb-3 shadow-sm">
            <div class="row g-3 align-items-end">
                <div class="col-sm-3">
                    <label class="form-label fw-semibold">${label}</label>
                    <input type="number" min="0" class="form-control val-input"
                        name="${type}${idx}_val" value="${savedVal}" placeholder="0" data-type="${type}" data-index="${idx}">
                </div>
                <div class="col-sm-9">
                    <label class="form-label fw-semibold">Attachment(s)</label>
                    <input type="file" class="form-control up-input" accept=".pdf"
                        name="${type}${idx}_doc[]" data-type="${type}" data-index="${idx}" multiple>
                    <ul class="file-list mt-2" id="${type}-list-${idx}"></ul>
                </div>
            </div>
        </div>`;
                }
                container.appendChild(wrap);

                // Render existing files as links + hidden inputs
                const list = document.getElementById(`${type}-list-${idx}`);
                savedDocs.forEach(doc => {
                    const li = document.createElement("li");
                    li.className = "file-item d-flex justify-content-between align-items-center mb-1";

                    const docPath = typeof doc === "string" ? doc : doc.path || "";
                    const docName = typeof doc === "string" ? doc.split("/").pop() : doc.name || "Document";

                    li.innerHTML = `
                <div>
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                    <a href="/${docPath}" target="_blank">${docName}</a>
                    <input type="hidden" name="${type}${idx}_existing[]" value="${docPath}">
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger"
                    data-remove-existing data-type="${type}" data-idx="${idx}" data-id="${docPath}">
                    <i class="bi bi-x-circle"></i> Remove
                </button>`;
                    list.appendChild(li);
                });
            });
        }

        // Render newly uploaded files without removing existing ones
        function renderFileList(type, idx) {
            const list = document.getElementById(`${type}-list-${idx}`);
            if (!list) return;

            // Remove previous new files from DOM
            Array.from(list.querySelectorAll('[data-remove-new]')).forEach(n => n.closest('li').remove());

            // Render new uploaded files
            files[type][idx]?.forEach((f, i) => {
                const li = document.createElement('li');
                li.className = "file-item d-flex justify-content-between align-items-center mb-1";
                li.innerHTML = `
            <div><i class="bi bi-file-earmark-text me-2 text-success"></i>${f.name}</div>
            <button type="button" class="btn btn-sm btn-outline-danger"
                data-remove-new data-type="${type}" data-idx="${idx}" data-file="${i}">
                <i class="bi bi-x-circle"></i> Remove
            </button>`;
                list.appendChild(li);
            });
        }

        // Handle remove buttons
        document.addEventListener("click", e => {
            const existingBtn = e.target.closest("[data-remove-existing]");
            if (existingBtn) {
                const type = existingBtn.dataset.type;
                const idx = existingBtn.dataset.idx;
                const id = existingBtn.dataset.id;

                filesToDelete[type][idx].push(id); // mark for deletion
                existingBtn.closest("li").remove();
                return;
            }

            const newBtn = e.target.closest("[data-remove-new]");
            if (newBtn) {
                const type = newBtn.dataset.type;
                const idx = newBtn.dataset.idx;
                const fileIndex = newBtn.dataset.file;

                files[type][idx].splice(fileIndex, 1);
                renderFileList(type, idx);
            }
        });

        // Handle new file uploads
        document.addEventListener("change", e => {
            const input = e.target.closest(".up-input");
            if (!input) return;

            const type = input.dataset.type;
            const idx = input.dataset.index;

            Array.from(input.files).forEach(f => {
                if (f.name.split('.').pop().toLowerCase() !== 'pdf') {
                    alert('Only PDF files are allowed');
                    return;
                }
                if (f.size > MAX_FILE_SIZE) {
                    alert(`File too large: ${fmtSize(f.size)} (Max 5MB)`);
                    return;
                }
                files[type][idx].push(f);
            });

            input.value = ""; // reset input
            renderFileList(type, idx);
        });

        // Initialize
        $(document).ready(() => {
            buildIndicators("leadContainer", "lead", leadNames);
            buildIndicators("lagContainer", "lag", lagNames);

            // Initialize attachment fields visibility
            setTimeout(() => {
                $('.val-input').each(function () {
                    $(this).trigger('input');
                });
            }, 100);
        });
    </script>



    <script>
        function updateStepper() {
            document.querySelectorAll('.stepper .step').forEach(s => {
                s.classList.toggle('active', Number(s.dataset.step) === currentStep);
            });
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            document.getElementById('section' + currentStep).classList.add('active');
            document.getElementById('btnPrev').disabled = (currentStep === 1);
            const nextBtn = document.getElementById('btnNext');
            const previewBtn = document.getElementById('btnPreview');
            if (currentStep < TOTAL_STEPS) {
                nextBtn.classList.remove('d-none'); previewBtn.classList.add('d-none');
            } else {
                nextBtn.classList.add('d-none'); previewBtn.classList.remove('d-none');
            }
        }
        updateStepper();

        document.getElementById('btnPrev').addEventListener('click', () => {
            if (currentStep > 1) { currentStep--; updateStepper(); }
        });
        document.getElementById('btnNext').addEventListener('click', () => {
            if (currentStep < TOTAL_STEPS) { currentStep++; updateStepper(); }
        });
    </script>
    <script>

    </script>

    <script>

        /* ---------- Attachment Label Toggle ---------- */
        $(document).on('input change keyup', '.val-input', function () {
            const type = $(this).data('type');
            const idx = $(this).data('index');
            const val = $(this).val();

            if (type === 'lead' && idx == 1) return;

            const $row = $(this).closest('.row');
            const $attachDiv = $row.find('.col-sm-9');
            const $inputDiv = $row.find('.col-sm-3');

            if (val == '' || val == '0' || parseFloat(val) <= 0) {
                $attachDiv.hide();
                $inputDiv.removeClass('col-sm-3').addClass('col-sm-12');
            } else {
                $attachDiv.show();
                $inputDiv.removeClass('col-sm-12').addClass('col-sm-3');
            }
        });

        /* ---------- Validation ---------- */
        function validateSection(step) {
            if (step === 1) {
                let ok = true;
                ['division', 'plant', 'department', 'report_month'].forEach(name => {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (!el.value) { el.classList.add('is-invalid'); ok = false; } else el.classList.remove('is-invalid');
                });
                if (!ok) window.scrollTo({ top: 0, behavior: 'smooth' });
                return ok;
            }
            if (step === 2 || step === 3) {
                const type = (step === 2 ? 'lead' : 'lag');
                const vals = document.querySelectorAll(`.val-input[data-type="${type}"]`);
                vals.forEach(v => {
                    const list = document.getElementById(`${type}-list-${v.dataset.index}`);
                    if (list) list.classList.remove('is-invalid');
                });
                return true;
            }
            return true;
        }

        /* ---------- Utility ---------- */
        function getVal(id) { const el = document.getElementById(id); return el ? el.value.trim() : ""; }

        /* ---------- Preview ---------- */
        function generatePreviewList(type, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            const inputs = document.querySelectorAll(`.val-input[data-type="${type}"]`);
            inputs.forEach(input => {
                const idx = Number(input.dataset.index);
                const val = input.value.trim();
                const label = (type === 'lead' ? leadNames[idx - 1] : lagNames[idx - 1]) || `${type} ${idx}`;
                const newFiles = files[type][idx] || [];
                const list = document.getElementById(`${type}-list-${idx}`);
                const existingFiles = list ? Array.from(list.querySelectorAll('[data-remove-existing]')).map(btn => {
                    const link = btn.parentElement.querySelector('a');
                    return link ? link.textContent : 'Document';
                }) : [];
                const allFiles = [...existingFiles, ...newFiles.map(f => f.name)];

                if (val !== "" || allFiles.length > 0) {
                    const div = document.createElement("div");
                    div.className = "mb-2 p-2 border rounded bg-light";
                    div.innerHTML = `<strong>${label}</strong><br>${val !== "" ? `Value: <span class="text-primary">${val}</span><br>` : ""}${allFiles.length > 0 && !(type === 'lead' && idx === 1) ? `Attachments:<ul>${allFiles.map(f => `<li>${f}</li>`).join("")}</ul>` : ""}`;
                    container.appendChild(div);
                }
            });
            if (container.innerHTML === "") {
                container.innerHTML = `<em class="text-muted">No data filled for ${type}
        indicators.</em>`;
            }
        }

        /* ---------- Loader ---------- */
        function showLoader(text = "Saving...") {
            const loader = document.getElementById('pageLoader');
            loader.querySelector('p').innerText = text; loader.classList.remove('d-none');
        }
        function hideLoader() { document.getElementById('pageLoader').classList.add('d-none'); }

        /* ---------- Save ---------- */
        function saveForm(successMessage, callback = null) {
            showLoader("Saving...");
            const form = document.getElementById('form');
            const formData = new FormData(form);

            // Append Lead files
            for (let idx in files.lead) {
                files.lead[idx].forEach(file => {
                    formData.append(`lead${idx}_doc[]`, file);
                });
            }

            // Append Lag files
            for (let idx in files.lag) {
                files.lag[idx].forEach(file => {
                    formData.append(`lag${idx}_doc[]`, file);
                });
            }

            fetch('<?php echo e(route("vendor_mis.store")); ?>', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.id) {
                        document.getElementById('recordId').value = data.id;
                    }

                    // Show SweetAlert instead of alert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMessage,
                        timer: 2000, // Auto close after 2 seconds
                        showConfirmButton: false
                    });

                    if (callback) callback();
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "Something went wrong while saving.",
                    });
                })
                .finally(() => { hideLoader(); });
        }

        /* ---------- Buttons ---------- */
        document.getElementById('btnNext').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('statusField').value = "draft";
            saveForm("Saved successfully!", () => { });
        });

        document.getElementById('btnDraft').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('statusField').value = "draft";
            saveForm("Saved successfully!");
            alert("Saved successfully!"); // Waits for user to click OK
            location.reload();
        });

        document.getElementById('btnFinalSubmit').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('statusField').value = "final";

            saveForm("Final submission successful!", () => {
                // Show SweetAlert first
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "Final submission successful!",
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload page after SweetAlert closes
                    location.reload();
                });
            });
        });


        /* ---------- Preview Modal ---------- */
        document.getElementById('btnPreview').addEventListener('click', () => {
            let missing = [];
            if (!getVal('division')) missing.push("Division");
            if (!getVal('plant')) missing.push("Plant");
            if (!getVal('department')) missing.push("Department");
            if (!getVal('report_month')) missing.push("Month");

            const divisionEl = document.getElementById('division');
            const plantEl = document.getElementById('plant');
            const departmentEl = document.getElementById('department');
            document.getElementById('pv_division').innerText = divisionEl.options[divisionEl.selectedIndex]?.text || "-";
            document.getElementById('pv_plant').innerText = plantEl.options[plantEl.selectedIndex]?.text || "-";
            document.getElementById('pv_department').innerText = departmentEl.options[departmentEl.selectedIndex]?.text || "-";
            document.getElementById('pv_month').innerText = getVal('report_month') || "-";
            generatePreviewList("lead", "pvLeadList");
            generatePreviewList("lag", "pvLagList");

            const warningBox = document.getElementById('pvWarning');
            const missingBox = document.getElementById('missingSections');
            const finalBtn = document.getElementById('btnFinalSubmit');
            const draftBtn = document.getElementById('btnDraft');

            if (missing.length > 0) {
                warningBox.style.display = "block";
                missingBox.innerText = "You are not eligible for Final Save. Missing: " + missing.join(", ");
                finalBtn.disabled = true;
            } else {
                warningBox.style.display = "none";
                finalBtn.disabled = !document.getElementById('consentCheck').checked;
            }
            draftBtn.disabled = false;

            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });

        /* ---------- Consent Checkbox ---------- */
        document.getElementById('consentCheck').addEventListener('change', function () {
            const finalBtn = document.getElementById('btnFinalSubmit');
            const warningBox = document.getElementById('pvWarning');
            const missingBox = document.getElementById('missingSections');

            let missing = [];

            // Check main fields only
            ['division', 'plant', 'department', 'report_month'].forEach(id => {
                const el = document.getElementById(id);
                if (!el || !el.value) missing.push(id.charAt(0).toUpperCase() + id.slice(1));
            });

            if (missing.length > 0) {
                warningBox.style.display = "block";
                missingBox.innerText = "Missing: " + missing.join(", ");
                finalBtn.disabled = true;
            } else {
                warningBox.style.display = "none";
                finalBtn.disabled = !this.checked;
            }
        });

    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>$('#division').on('change', function () {
            var division_ID = $(this).val();

            $("#plant").html('<option value="">--Select--</option>');
            $("#department").html('<option value="null">--Select--</option>');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.departmentGet_vendor_mis')); ?>/" + division_ID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#plant").append('<option value="' + data[i].id + '" >' + data[i].name + '</option>');
                    }
                }
            });


        });




        $('#plant').on('change', function () {
            var plantID = $(this).val();


            $("#department").html('<option value="null">--Select--</option>');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'GET',
                url: "<?php echo e(route('admin.PlantGet_vendor_mis')); ?>/" + plantID,
                contentType: 'application/json',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        $("#department").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                    }
                }
            });


        });</script>




</body>

</html><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_mis/edit_data_ifream.blade.php ENDPATH**/ ?>