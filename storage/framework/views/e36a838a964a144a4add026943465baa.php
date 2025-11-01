

<?php $__env->startSection('styles'); ?>
    <style>
        .nav-pills .nav-link {
            border-radius: 20px;
            margin-right: 5px;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        #vendorSelect {
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .highcharts-container {
            border-radius: 8px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="fw-bold">Safety Performance System Dashboard</h3>
            <small class="text-muted">Lead & Lag Indicators</small>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="misTabs" role="tablist">
            <li class="nav-item">
                <a href="<?php echo e(route('mis_report', ['id' => 'found'])); ?>"
                    class="nav-link <?php echo e(request()->routeIs('vendor_mis.mis_report') ? 'active' : ''); ?>">📑 Report</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('mis_dashboard', ['id' => 'found'])); ?>"
                    class="nav-link <?php echo e(request()->routeIs('vendor_mis.mis_dashboard') ? 'active' : ''); ?>">📊 Dashboard</a>
            </li>
        </ul>

        <!-- Filter -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">Filter Records</div>
            <div class="card-body">
                <form id="filterForm">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <?php if(Session::get('user_sub_typeSession') == '3'): ?>
                            <div class="col-md-4">
                                <label class="form-label">Division</label>
                                <select class="form-control" name="division" id="division">
                                    <option value="">Select Division</option>
                                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Plant</label>
                                <select class="form-control" name="plant" id="plant">
                                    <option value="">Select Plant</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <select class="form-control" name="department" id="department">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-4">
                            <label class="form-label">From Month</label>
                            <input type="month" name="from_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Month</label>
                            <input type="month" name="to_date" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Vendor</label>
                            <select name="vendor" id="vendorFilterMain" class="form-control">
                                <option value="">All Vendors</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="button" id="applyFilter" class="btn btn-primary w-100">Apply Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vendor Tabs -->
        <div class="card mb-4 shadow-sm" hidden>
            <div class="card-header bg-info text-white fw-semibold">Vendor Comparison</div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="vendorTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-tab="all">All Vendors</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-tab="individual">Individual</button>
                    </li>
                </ul>
                <div id="vendorSelect" style="display:none;">
                    <select class="form-control" id="vendorFilter">
                        <option value="">Select Vendor</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">Lead Indicators</div>
                    <div class="card-body">
                        <div id="leadChart" style="height:350px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">Lag Indicators</div>
                    <div class="card-body">
                        <div id="lagChart" style="height:350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Comparison Charts -->




        <!-- Vendor Comparison Charts -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span>Vendor Lead & Lag Comparison Charts</span>
                <div>
                    <select id="chartVendor1" class="form-select form-select-sm me-2"
                        style="width:180px; display:inline-block;">
                        <option value="">Select Vendor 1</option>
                    </select>
                    <select id="chartVendor2" class="form-select form-select-sm" style="width:180px; display:inline-block;">
                        <option value="">Select Vendor 2</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">Lead Indicators Comparison</h6>
                        <div id="vendorLeadChart" style="height:350px;"></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger mb-3">Lag Indicators Comparison</h6>
                        <div id="vendorLagChart" style="height:350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Chart -->

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        const leadColors = ['#28a745', '#20c997', '#17a2b8', '#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#dc3545', '#fd7e14', '#ffc107'];
        const lagColors = ['#ffc107', '#fd7e14', '#dc3545', '#e83e8c', '#6f42c1', '#6610f2'];

        function loadVendors() {
            $.get("<?php echo e(route('admin.vendorGet_vendor_mis', 1)); ?>", function (vendors) {
                let options = '<option value="">All Vendors</option>';
                let compareOptions = '<option value="">Select Vendor</option>';
                vendors.forEach(v => {
                    const code = v.vendor_code ? ` (${v.vendor_code})` : '';
                    options += `<option value="${v.id}">${v.name}${code}</option>`;
                    compareOptions += `<option value="${v.id}">${v.name}${code}</option>`;
                });
                $('#vendorFilter, #vendorFilterMain').html(options);
                $('#vendor1, #vendor2, #chartVendor1, #chartVendor2').html(compareOptions);
            });
        }

        function loadSummary(filters = {}) {
            $.post("<?php echo e(route('admin.vendor_mis.filterJson_dashboard')); ?>",
                { _token: "<?php echo e(csrf_token()); ?>", ...filters },
                function (response) {
                    if (response.counts) renderCharts(response.counts);
                }
            );
        }

        function renderCharts(data) {
            const leadIndicatorNames = [
                'Safety Training',
                'Training Hours',
                'Mass Meeting',
                'Line Walk',
                'Safety Audit',
                'Housekeeping Audit',
                'PPE Audit',
                'Tools Audit',
                'Safety Kaizen',
                'Near Miss'
            ];

            let leadData = [];
            for (let i = 1; i <= 10; i++) {
                leadData.push({ y: data['lead' + i + '_val'] || 0, color: leadColors[i - 1] });
            }

            Highcharts.chart('leadChart', {
                chart: { type: 'column' },
                title: { text: null },
                xAxis: {
                    categories: leadIndicatorNames,
                    labels: { rotation: -45, style: { fontSize: '10px' } }
                },
                yAxis: { min: 0, title: { text: 'Count' } },
                legend: { enabled: false },
                plotOptions: { column: { dataLabels: { enabled: true } } },
                series: [{ name: 'Lead Indicators', data: leadData }]
            });

            const lagIndicatorNames = [
                'First Aid Case',
                'Medical Case',
                'LTIs',
                'Fatality',
                'Non Injury Incident',
                'Severity 4&5 Violation'
            ];

            let lagData = [];
            for (let i = 1; i <= 6; i++) {
                lagData.push({ y: data['lag' + i + '_val'] || 0, color: lagColors[i - 1] });
            }

            Highcharts.chart('lagChart', {
                chart: { type: 'column' },
                title: { text: null },
                xAxis: {
                    categories: lagIndicatorNames,
                    labels: { rotation: -45, style: { fontSize: '10px' } }
                },
                yAxis: { min: 0, title: { text: 'Count' } },
                legend: { enabled: false },
                plotOptions: { column: { dataLabels: { enabled: true } } },
                series: [{ name: 'Lag Indicators', data: lagData }]
            });

            Highcharts.chart('comparisonChart', {
                chart: { type: 'line' },
                title: { text: null },
                xAxis: { categories: ['Lead Total', 'Lag Total'] },
                yAxis: { min: 0, title: { text: 'Total Count' } },
                series: [{
                    name: 'Indicators',
                    data: [
                        leadData.reduce((sum, item) => sum + item.y, 0),
                        lagData.reduce((sum, item) => sum + item.y, 0)
                    ],
                    color: '#007bff'
                }]
            });
        }

        function loadVendorComparison(filters = {}) {
            $.post("<?php echo e(route('admin.vendor_mis.filterJson_dashboard')); ?>",
                { _token: "<?php echo e(csrf_token()); ?>", vendor_comparison: true, ...filters },
                function (response) {
                    if (response.vendor_data) {
                        renderVendorComparison(response.vendor_data);
                        $('#comparisonCards').show();
                    }
                }
            );
        }

        function renderVendorComparison(vendorData) {
            const leadIndicatorNames = [
                'Safety Training',
                'Training Hours',
                'Mass Meeting',
                'Line Walk',
                'Safety Audit',
                'Housekeeping Audit',
                'PPE Audit',
                'Tools Audit',
                'Safety Kaizen',
                'Near Miss'
            ];

            const leadData = [];
            for (let i = 1; i <= 10; i++) {
                const total = vendorData.reduce((sum, vendor) => sum + (vendor[`lead${i}_val`] || 0), 0);
                leadData.push({ y: total, color: leadColors[i - 1] });
            }

            Highcharts.chart('leadTotals', {
                chart: { type: 'column', height: 300 },
                title: { text: null },
                xAxis: {
                    categories: leadIndicatorNames,
                    labels: { rotation: -45, style: { fontSize: '9px' } }
                },
                yAxis: { min: 0, title: { text: 'Total Count' } },
                legend: { enabled: false },
                plotOptions: { column: { dataLabels: { enabled: true } } },
                series: [{ name: 'Lead Indicators', data: leadData }]
            });

            const lagIndicatorNames = [
                'First Aid Case',
                'Medical Case',
                'LTIs',
                'Fatality',
                'Non Injury Incident',
                'Severity 4&5 Violation'
            ];

            const lagData = [];
            for (let i = 1; i <= 6; i++) {
                const total = vendorData.reduce((sum, vendor) => sum + (vendor[`lag${i}_val`] || 0), 0);
                lagData.push({ y: total, color: lagColors[i - 1] });
            }

            Highcharts.chart('lagTotals', {
                chart: { type: 'column', height: 300 },
                title: { text: null },
                xAxis: {
                    categories: lagIndicatorNames,
                    labels: { rotation: -45, style: { fontSize: '9px' } }
                },
                yAxis: { min: 0, title: { text: 'Total Count' } },
                legend: { enabled: false },
                plotOptions: { column: { dataLabels: { enabled: true } } },
                series: [{ name: 'Lag Indicators', data: lagData }]
            });
        }

        $('#vendorTabs button').click(function () {
            $('#vendorTabs button').removeClass('active');
            $(this).addClass('active');

            const tab = $(this).data('tab');
            if (tab === 'individual') {
                $('#vendorSelect').show();
                $('#comparisonCards').hide();
                // Clear individual vendor selection when switching tabs
                $('#vendorFilter').val('');
                // Load data for main filter only
                loadSummary(getFilters());
            } else {
                $('#vendorSelect').hide();
                $('#comparisonCards').hide();
                // Clear individual vendor filter and load all vendors data
                $('#vendorFilter').val('');
                loadSummary(getFilters());
                loadVendorComparison(getFilters());
            }
        });

        $('#vendorFilter').change(function () {
            const vendorId = $(this).val();
            const filters = getFilters();
            if (vendorId) {
                filters.vendor_id = vendorId;
            }
            loadSummary(filters);
        });

        $('#vendorFilterMain').change(function () {
            const filters = getFilters();
            loadSummary(filters);
            if ($('#vendorTabs button.active').data('tab') === 'all') {
                loadVendorComparison(filters);
            }
        });

        $('#applyFilter').click(function () {
            const filters = getFilters();
            loadSummary(filters);

            if ($('#vendorTabs button.active').data('tab') === 'all') {
                loadVendorComparison(filters);
            }
            loadVendorComparisonTable();
        });

        function getFilters() {
            return {
                division: $('#division').val(),
                plant: $('#plant').val(),
                department: $('#department').val(),
                from_date: $('input[name="from_date"]').val(),
                to_date: $('input[name="to_date"]').val(),
                draft: $('select[name="draft"]').val(),
                vendor_id: $('#vendorFilterMain').val()
            };
        }

        function loadVendorComparisonTable() {
            const vendor1Id = $('#vendor1').val();
            const vendor2Id = $('#vendor2').val();

            if (!vendor1Id || !vendor2Id) {
                $('#vendorComparisonTable').html('<p class="text-muted">Please select two vendors to compare</p>');
                return;
            }

            const baseFilters = {
                division: $('#division').val(),
                plant: $('#plant').val(),
                department: $('#department').val(),
                from_date: $('input[name="from_date"]').val(),
                to_date: $('input[name="to_date"]').val(),
                draft: $('select[name="draft"]').val()
            };

            Promise.all([
                $.post("<?php echo e(route('admin.vendor_mis.filterJson_dashboard')); ?>", { _token: "<?php echo e(csrf_token()); ?>", vendor_id: vendor1Id, ...baseFilters }),
                $.post("<?php echo e(route('admin.vendor_mis.filterJson_dashboard')); ?>", { _token: "<?php echo e(csrf_token()); ?>", vendor_id: vendor2Id, ...baseFilters })
            ]).then(([vendor1Data, vendor2Data]) => {
                renderComparisonTable(vendor1Data.counts, vendor2Data.counts, vendor1Id, vendor2Id);
            });
        }

        function renderComparisonTable(vendor1Data, vendor2Data, vendor1Id, vendor2Id) {
            const leadIndicators = [
                'Safety Training Session',
                'Total Training Employee Hours',
                'Mass Meeting Conducted',
                'Line Walk Conducted',
                'Site Safety Audit',
                'Housekeeping Audit',
                'PPE Audit',
                'Tools-Tackles Audit',
                'Safety Kaizen Done',
                'Near Miss Reported'
            ];

            const lagIndicators = [
                'First Aid Case',
                'Medical Treated Case',
                'LTIs',
                'Fatality',
                'Non Injury Incident',
                'Severity 4&5 Violation'
            ];

            let tableHtml = `
                                                                                                                                                    <div class="table-responsive">
                                                                                                                                                        <table class="table table-bordered table-striped">
                                                                                                                                                            <thead class="table-dark">
                                                                                                                                                                <tr>
                                                                                                                                                                    <th>Indicator</th>
                                                                                                                                                                    <th>Vendor 1</th>
                                                                                                                                                                    <th>Vendor 2</th>
                                                                                                                                                                    <th>Difference</th>
                                                                                                                                                                </tr>
                                                                                                                                                            </thead>
                                                                                                                                                            <tbody>
                                                                                                                                                                <tr class="table-success"><td colspan="4"><strong>Lead Indicators</strong></td></tr>`;

            leadIndicators.forEach((indicator, index) => {
                const key = `lead${index + 1}_val`;
                const v1Val = vendor1Data[key] || 0;
                const v2Val = vendor2Data[key] || 0;
                const diff = v1Val - v2Val;
                const diffClass = diff > 0 ? 'text-success' : diff < 0 ? 'text-danger' : 'text-muted';

                tableHtml += `
                                                                                                                                                        <tr>
                                                                                                                                                            <td>${indicator}</td>
                                                                                                                                                            <td><span class="badge bg-primary">${v1Val}</span></td>
                                                                                                                                                            <td><span class="badge bg-info">${v2Val}</span></td>
                                                                                                                                                            <td><span class="${diffClass}">${diff > 0 ? '+' : ''}${diff}</span></td>
                                                                                                                                                        </tr>`;
            });

            tableHtml += '<tr class="table-warning"><td colspan="4"><strong>Lag Indicators</strong></td></tr>';

            lagIndicators.forEach((indicator, index) => {
                const key = `lag${index + 1}_val`;
                const v1Val = vendor1Data[key] || 0;
                const v2Val = vendor2Data[key] || 0;
                const diff = v1Val - v2Val;
                const diffClass = diff > 0 ? 'text-danger' : diff < 0 ? 'text-success' : 'text-muted';

                tableHtml += `
                                                                                                                                                        <tr>
                                                                                                                                                            <td>${indicator}</td>
                                                                                                                                                            <td><span class="badge bg-primary">${v1Val}</span></td>
                                                                                                                                                            <td><span class="badge bg-info">${v2Val}</span></td>
                                                                                                                                                            <td><span class="${diffClass}">${diff > 0 ? '+' : ''}${diff}</span></td>
                                                                                                                                                        </tr>`;
            });

            tableHtml += '</tbody></table></div>';
            $('#vendorComparisonTable').html(tableHtml);
        }

        $('#vendor1, #vendor2').change(function () {
            loadVendorComparisonTable();
        });

        $('#chartVendor1, #chartVendor2').change(function () {
            loadVendorComparisonCharts();
        });

        function loadVendorComparisonCharts() {
            const vendor1Id = $('#chartVendor1').val();
            const vendor2Id = $('#chartVendor2').val();

            if (!vendor1Id || !vendor2Id) {
                $('#vendorLeadChart, #vendorLagChart').html('<p class="text-muted text-center">Select two vendors to compare</p>');
                return;
            }

            const baseFilters = {
                division: $('#division').val(),
                plant: $('#plant').val(),
                department: $('#department').val(),
                from_date: $('input[name="from_date"]').val(),
                to_date: $('input[name="to_date"]').val(),
                draft: $('select[name="draft"]').val()
            };

            Promise.all([
                $.post("<?php echo e(route('admin.vendor_mis.filterJson_dashboard')); ?>", { _token: "<?php echo e(csrf_token()); ?>", vendor_id: vendor1Id, ...baseFilters }),
                $.post("<?php echo e(route('admin.vendor_mis.filterJson_dashboard')); ?>", { _token: "<?php echo e(csrf_token()); ?>", vendor_id: vendor2Id, ...baseFilters })
            ]).then(([vendor1Data, vendor2Data]) => {
                renderVendorComparisonCharts(vendor1Data.counts, vendor2Data.counts);
            });
        }

        function renderVendorComparisonCharts(vendor1Data, vendor2Data) {
            const leadCategories = ['Safety Training', 'Training Hours', 'Mass Meeting', 'Line Walk', 'Safety Audit', 'Housekeeping Audit', 'PPE Audit', 'Tools Audit', 'Safety Kaizen', 'Near Miss'];
            const lagCategories = ['First Aid Case', 'Medical Case', 'LTIs', 'Fatality', 'Non Injury Incident', 'Severity 4&5 Violation'];

            const vendor1Lead = [], vendor2Lead = [], vendor1Lag = [], vendor2Lag = [];

            for (let i = 1; i <= 10; i++) {
                vendor1Lead.push(vendor1Data[`lead${i}_val`] || 0);
                vendor2Lead.push(vendor2Data[`lead${i}_val`] || 0);
            }

            for (let i = 1; i <= 6; i++) {
                vendor1Lag.push(vendor1Data[`lag${i}_val`] || 0);
                vendor2Lag.push(vendor2Data[`lag${i}_val`] || 0);
            }

            Highcharts.chart('vendorLeadChart', {
                chart: { type: 'column' },
                title: { text: null },
                xAxis: { categories: leadCategories, labels: { rotation: -45, style: { fontSize: '9px' } } },
                yAxis: { min: 0, title: { text: 'Count' } },
                plotOptions: { column: { dataLabels: { enabled: true } } },
                series: [
                    { name: 'Vendor 1', data: vendor1Lead, color: '#007bff' },
                    { name: 'Vendor 2', data: vendor2Lead, color: '#28a745' }
                ]
            });

            Highcharts.chart('vendorLagChart', {
                chart: { type: 'column' },
                title: { text: null },
                xAxis: { categories: lagCategories, labels: { rotation: -45, style: { fontSize: '9px' } } },
                yAxis: { min: 0, title: { text: 'Count' } },
                plotOptions: { column: { dataLabels: { enabled: true } } },
                series: [
                    { name: 'Vendor 1', data: vendor1Lag, color: '#dc3545' },
                    { name: 'Vendor 2', data: vendor2Lag, color: '#ffc107' }
                ]
            });
        }

        $(document).ready(function () {
            // Set current month as default
            const currentMonth = new Date().toISOString().slice(0, 7);
            $('input[name="from_date"], input[name="to_date"]').val(currentMonth);

            loadVendors();

            // Load data with current month filter
            const initialFilters = {
                from_date: currentMonth,
                to_date: currentMonth
            };
            loadSummary(initialFilters);
            loadVendorComparison(initialFilters);
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
            $('#vendorFilterMain, #vendorFilter, #chartVendor1, #chartVendor2').html('<option value="">All Vendors</option>');

            if (plantID) {
                // Load departments
                $.ajax({
                    type: 'GET',
                    url: "<?php echo e(route('admin.PlantGet_vendor_mis')); ?>/" + plantID,
                    success: function (data) {
                        for (var i = 0; i < data.length; i++) {
                            $("#department").append('<option value="' + data[i].id + '" >' + data[i].department_name + '</option>');
                        }
                    }
                });

                // Load plant-specific vendors
                $.ajax({
                    type: 'GET',
                    url: "/admin/vendors_by_plant/" + plantID,
                    success: function (vendors) {
                        let options = '<option value="">All Vendors</option>';
                        for (var i = 0; i < vendors.length; i++) {
                            const code = vendors[i].vendor_code ? ` (${vendors[i].vendor_code})` : '';
                            options += `<option value="${vendors[i].id}">${vendors[i].name}${code}</option>`;
                        }
                        $('#vendorFilterMain, #vendorFilter, #chartVendor1, #chartVendor2').html(options);
                    }
                });
            } else {
                // Load all vendors if no plant selected
                loadVendors();
            }
        });</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_mis/mis_dashboard.blade.php ENDPATH**/ ?>