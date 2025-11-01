

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="fw-bold">Safety Performance System</h3>
            <small class="text-muted">Report Summary</small>
        </div>
        <ul class="nav nav-tabs mb-3" id="vmsTabs" role="tablist">

            <li class="nav-item" role="presentation">
                <a href="<?php echo e(route('mis_report', ['id' => 'found'])); ?>"
                    class="nav-link <?php echo e(request()->routeIs('vendor_mis.mis_report') ? 'active' : ''); ?>" role="tab">
                    📑 Report
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a href="<?php echo e(route('mis_dashboard', ['id' => 'found'])); ?>"
                    class="nav-link <?php echo e(request()->routeIs('vendor_mis.mis_dashboard') ? 'active' : ''); ?>" role="tab">
                    📊 Dashboard
                </a>
            </li>

        </ul>
        <!-- Filter Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">Filter Records</div>
            <div class="card-body">
                <form id="filterForm">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <?php if(Session::get('user_sub_typeSession') == '3'): ?>
                            <div class="col-md-4">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select class="form-control" name="division" id="division" required>
                                    <option value="">Select Division</option>
                                    <?php if($divisions->count() > 0): ?>
                                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Plant <span class="text-danger">*</span></label>
                                <select class="form-control" name="plant" id="plant" required>
                                    <option value="">Select Plant</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-control" name="department" id="department" required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-4">
                            <label class="form-label">From month</label>
                            <input type="month" name="from_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Month</label>
                            <input type="month" name="to_date" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Vendor</label>
                            <select name="vid" id="vid" class="form-control">
                                <option value="">All</option>
                                <?php if($vendors->count() > 0): ?>
                                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vendor->id); ?>"><?php echo e($vendor->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="button" id="applyFilter" class="btn btn-primary w-100">Apply Filter</button>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button id="exportExcel" class="btn btn-success w-100" type="button">Export Excel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Table -->
        <div class="card shadow-sm">
            <div class="card-header">Report Data</div>
            <div class="card-body table-responsive">
                <table class="table table-striped" id="Table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Plant Name</th>
                            <th>Division Name</th>
                            <th>Department Name</th>
                            <th>Month</th>
                            <th>Vendor Name</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <script>
        let table;

        function loadTable(filters = {}) {
            if (table) {
                table.destroy();
            }

            table = $('#Table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "<?php echo e(route('admin.vendor_mis.filterJson')); ?>",
                    type: 'POST',
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        ...filters
                    },
                    dataSrc: 'data'
                },
                columns: [
                    { data: null }, // SL column
                    { data: 'division_id' },
                    { data: 'plant_id' },
                    { data: 'department_id' },
                    { data: 'month' },
                    { data: 'vendor_code' },
                    { data: 'status' },
                    { data: 'created_by' },
                    { data: 'created_datetime' }
                ],
                order: [[1, 'desc']],
                paging: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                autoWidth: false,
                // Remove columnDefs for SL
                drawCallback: function (settings) {
                    var api = this.api();
                    api.rows({ order: 'applied', search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
                        var cell = this.node().querySelector('td:first-child');
                        cell.innerHTML = rowLoop + 1; // proper SL after sorting/searching
                    });
                }
            });
        }


        $(document).ready(function () {
            loadTable(); // initial load

            $('#applyFilter').click(function () {
                let filters = {
                    from_date: $('input[name="from_date"]').val(),
                    to_date: $('input[name="to_date"]').val(),
                    status: $('select[name="status"]').val(),
                    expiry_type: $('select[name="expiry_type"]').val(),
                    division: $('select[name="division"]').val(),
                    plant: $('select[name="plant"]').val(),
                    department: $('select[name="department"]').val(),
                    vid: $('select[name="vid"]').val()
                };

                loadTable(filters);
            });

            $('#exportExcel').click(function () {
                let filters = {
                    from_date: $('input[name="from_date"]').val(),
                    to_date: $('input[name="to_date"]').val(),
                    status: $('select[name="status"]').val(),
                    expiry_type: $('select[name="expiry_type"]').val(),
                    division: $('select[name="division"]').val(),
                    plant: $('select[name="plant"]').val(),
                    department: $('select[name="department"]').val(),
                    vid: $('select[name="vid"]').val()
                };

                $.post("<?php echo e(route('admin.vendor_mis.filterJson')); ?>", { _token: "<?php echo e(csrf_token()); ?>", ...filters }, function (response) {
                    if (response.data && response.data.length) {
                        let excelData = response.data.map((row, index) => ({
                            '#': index + 1, // SL column
                            'Division': row.division_id,
                            'Plant': row.plant_id,
                            'Department': row.department_id,
                            'Month': row.month,

                            '1. Safety Training Session Conducted During The Month': row.lead1_val,
                            '2. Total Training Employee Hours': row.lead2_val,
                            '3. No of Mass Meeting Conducted': row.lead3_val,
                            '4. No of Line Walk Conducted': row.lead4_val,
                            '5. No of Site Safety Audit Conducted': row.lead5_val,
                            '6. No of Housekeeping Audit Conducted': row.lead6_val,
                            '7. No of PPE Audit Conducted': row.lead7_val,
                            '8. No of Tools-Tackles Audit Conducted': row.lead8_val,
                            '9. No of Safety Kaizen Done': row.lead9_val,
                            '10. No of Near Miss Reported During the Month': row.lead10_val,

                            '1. No of First Aid Case': row.lag1_val,
                            '2. No of Medical Treated Case': row.lag2_val,
                            '3. No of LTIs': row.lag3_val,
                            '4. No of Fatality': row.lag4_val,
                            '5. No of Non Injury Incident': row.lag5_val,
                            '6. No of Severity 4&5 Violation Reported': row.lag6_val,

                            'Vendor ID': row.vendor_id,
                            'Vendor Code': row.vendor_code,
                            'Created By': row.created_by,
                            'Created Datetime': row.created_datetime,
                            'Updated By': row.updated_by,
                            'Updated Datetime': row.updated_datetime,
                            'Status': row.status,
                            'Draft': row.draft,
                        }));


                        let ws = XLSX.utils.json_to_sheet(excelData);
                        let wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, "VMS Report");
                        XLSX.writeFile(wb, "vms_report.xlsx");
                    } else {
                        alert('No data found for export.');
                    }
                });
            });
        });
    </script>
    <script>$('#division').on('change', function () {
            var division_ID = $(this).val();

            $("#plant").html('<option value="">--Select--</option>');
            $("#department").html('<option value="">--Select--</option>');


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


            $("#department").html('<option value="">--Select--</option>');
            $('#vid').html('<option>--Select--</option>');

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
            $.ajax({
                type: 'GET',
                url: "/admin/vendors_by_plant/" + plantID,
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        const code = data[i].vendor_code ? ` (${data[i].vendor_code})` : '';
                        $("#vid").append(`<option value="${data[i].id}">${data[i].name}${code}</option>`);
                    }
                }
            });


        });</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_mis/mis_report.blade.php ENDPATH**/ ?>