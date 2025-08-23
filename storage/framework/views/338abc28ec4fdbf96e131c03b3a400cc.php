

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <style>
            /* 🔴 Expired PUC */
            .card-expired-puc {
                background-color: #dc3545 !important;
                color: #fff !important;
            }

            /* 🟠 Expired Insurance */
            .card-expired-insurance {
                background-color: #fd7e14 !important;
                color: #fff !important;
            }

            /* ⚫ Expired Registration */
            .card-expired-registration {
                background-color: #6c757d !important;
                color: #fff !important;
            }

            /* 🟣 Expired License */
            .card-expired-license {
                background-color: #343a40 !important;
                color: #fff !important;
            }
        </style>

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="fw-bold">Vehicle Gate Pass Management System</h3>
            <small class="text-muted">Report Summary</small>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="vmsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="<?php echo e(route('vms.vms_report', ['id' => 'found'])); ?>"
                    class="nav-link <?php echo e(request()->routeIs('vms.vms_report') ? 'active' : ''); ?>" role="tab">
                    📑 Report
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="<?php echo e(route('vms.vms_dashboard', ['id' => 'found'])); ?>"
                    class="nav-link <?php echo e(request()->routeIs('vms.vms_dashboard') ? 'active' : ''); ?>" role="tab">
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
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="All">All</option>
                                <option value="approve">Approved</option>
                                <option value="pending_with_safety">Pending With Safety</option>
                                <option value="pending_with_safety_surrender">Pending With Safety (Surrender)</option>
                                <option value="surrender">Surrendered</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Expiry Type</label>
                            <select name="expiry_type" class="form-control">
                                <option value="">All</option>
                                <option value="puc">PUC Expiry</option>
                                <option value="insurance">Insurance Expiry</option>
                                <option value="registration">Vehicle Registration Expiry</option>
                                <option value="license">License Expiry</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-3">
                            <button type="button" id="applyFilter" class="btn btn-primary w-100">Apply Filter</button>
                        </div>
                        <div class="col-md-3 mt-3">
                            <button id="exportExcel" class="btn btn-success w-100" type="button">Export Excel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dashboard Summary -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Vehicles</h5>
                        <h3 id="totalVehicles">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Approved</h5>
                        <h3 id="approved">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Surrendered</h5>
                        <h3 id="surrendered">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pending (Surrender)</h5>
                        <h3 id="pendingSurrender">0</h3>
                    </div>
                </div>
            </div>

            <!-- Expired Cards -->
            <div class="col-md-3 p-3">
                <div class="card card-expired-puc shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Expired PUC</h5>
                        <h3 id="expiredPUC">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3 p-3">
                <div class="card card-expired-insurance shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Expired Insurance</h5>
                        <h3 id="expiredInsurance">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3 p-3">
                <div class="card card-expired-registration shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Expired Registration</h5>
                        <h3 id="expiredRegistration">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3 p-3">
                <div class="card card-expired-license shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Expired License</h5>
                        <h3 id="expiredLicense">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="card mt-4 shadow-sm">
            <div class="card-header">Vehicle Report Summary</div>
            <div class="card-body">
                <div id="statusChart" style="height:400px;"></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        function loadSummary(filters = {}) {
            $.post("<?php echo e(route('admin.vms.filterJson_dashboard')); ?>", { _token: "<?php echo e(csrf_token()); ?>", ...filters }, function (response) {
                if (response.counts) {
                    let data = response.counts;

                    // Update cards
                    $('#totalVehicles').text(data.total);
                    $('#approved').text(data.approved);
                    $('#surrendered').text(data.surrendered);
                    $('#pendingSurrender').text(data.pendingSurrender);
                    $('#expiredPUC').text(data.expiredPUC);
                    $('#expiredInsurance').text(data.expiredInsurance);
                    $('#expiredRegistration').text(data.expiredRegistration);
                    $('#expiredLicense').text(data.expiredLicense);

                    // Highcharts Pie
                    Highcharts.chart('statusChart', {
                        chart: { type: 'pie' },
                        title: { text: 'Vehicle Report Summary' },
                        subtitle: { text: '' },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                dataLabels: {
                                    enabled: true,
                                    formatter: function () {
                                        let label = this.point.name + ': ' + this.y;
                                        if (this.percentage > 5) {
                                            label += ' (' + Highcharts.numberFormat(this.percentage, 1) + '%)';
                                        }
                                        return label;
                                    },
                                    style: {
                                        fontSize: '13px',
                                        textOutline: 'none',
                                        opacity: 0.85
                                    }
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Vehicles',
                            colorByPoint: true,
                            data: [
                                { name: 'Approved', y: data.approved, color: '#198754' },
                                { name: 'Surrendered', y: data.surrendered, color: '#dc3545', sliced: true, selected: true },
                                { name: 'Pending Surrender', y: data.pendingSurrender, color: '#ffc107' },
                                { name: 'Expired PUC', y: data.expiredPUC, color: '#dc3545' },
                                { name: 'Expired Insurance', y: data.expiredInsurance, color: '#fd7e14' },
                                { name: 'Expired Registration', y: data.expiredRegistration, color: '#6c757d' },
                                { name: 'Expired License', y: data.expiredLicense, color: '#343a40' }
                            ]
                        }]
                    });
                }
            });
        }

        $(document).ready(function () {
            loadSummary();

            $('#applyFilter').click(function () {
                let filters = {
                    from_date: $('input[name="from_date"]').val(),
                    to_date: $('input[name="to_date"]').val(),
                    status: $('select[name="status"]').val(),
                    expiry_type: $('select[name="expiry_type"]').val()
                };
                loadSummary(filters);
            });
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vms/vms_dashboard.blade.php ENDPATH**/ ?>