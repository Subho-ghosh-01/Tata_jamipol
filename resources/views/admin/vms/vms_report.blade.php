@extends('admin.app')

@section('content')
    <div class="container-fluid py-4">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="fw-bold">Vehicle Gate Pass Management System</h3>
            <small class="text-muted">Report Summary</small>
        </div>
        <ul class="nav nav-tabs mb-3" id="vmsTabs" role="tablist">

            <li class="nav-item" role="presentation">
                <a href="{{ route('vms.vms_report', ['id' => 'found']) }}"
                    class="nav-link {{ request()->routeIs('vms.vms_report') ? 'active' : '' }}" role="tab">
                    📑 Report
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a href="{{ route('vms.vms_dashboard', ['id' => 'found']) }}"
                    class="nav-link {{ request()->routeIs('vms.vms_dashboard') ? 'active' : '' }}" role="tab">
                    📊 Dashboard
                </a>
            </li>

        </ul>
        <!-- Filter Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">Filter Records</div>
            <div class="card-body">
                <form id="filterForm">
                    @csrf
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

        <!-- Report Table -->
        <div class="card shadow-sm">
            <div class="card-header">Report Data</div>
            <div class="card-body table-responsive">
                <table class="table table-striped" id="Table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pass No</th>
                            <th>Vehicle Type</th>
                            <th>Registration No</th>
                            <th>Owner Name</th>
                            <th>Status</th>
                            <th>User Type</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
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
                    url: "{{ route('admin.vms.filterJson') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ...filters
                    },
                    dataSrc: 'data'
                },
                columns: [
                    { data: null }, // SL column
                    { data: 'full_sl' },
                    { data: 'vehicle_pass_for', render: function (d) { return d === 'two_wheeler' ? 'Two Wheeler' : d === 'four_wheeler' ? 'Car' : 'N/A'; } },
                    { data: 'vehicle_registration_no' },
                    { data: 'vehicle_owner_name' },
                    {
                        data: null, render: function (row) {
                            let status = 'N/A';
                            if (row.status === 'pending_with_safety' && !row.return_status) status = 'Pending With Safety';
                            else if (row.status === 'approve' && !row.return_status) status = 'Approved';
                            else if (row.return_status === 'approve') status = 'Surrendered';
                            else if (row.return_status === 'pending_with_safety') status = 'Pending With Safety (Surrender)';

                            let warnings = [];
                            let today = new Date();
                            if (row.insurance_valid_to && new Date(row.insurance_valid_to) < today) warnings.push('Insurance Expired');
                            if (row.license_valid_to && new Date(row.license_valid_to) < today) warnings.push('License Expired');
                            if (row.vehicle_registration_date && new Date(row.vehicle_registration_date) < today) warnings.push('Registration Expired');
                            if (row.puc_valid_to && new Date(row.puc_valid_to) < today) warnings.push('PUC Expired');

                            if (warnings.length) status += '<br><small class="text-danger">' + warnings.join(', ') + '</small>';
                            return status;
                        }
                    },
                    { data: 'user_type' },
                    { data: null, render: function (row) { return row.created_by + (row.vendor_code ? ' (' + row.vendor_code + ')' : ''); } },
                    { data: 'created_at' }
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
                    expiry_type: $('select[name="expiry_type"]').val()
                };
                loadTable(filters);
            });

            $('#exportExcel').click(function () {
                let filters = {
                    from_date: $('input[name="from_date"]').val(),
                    to_date: $('input[name="to_date"]').val(),
                    status: $('select[name="status"]').val(),
                    expiry_type: $('select[name="expiry_type"]').val()
                };

                $.post("{{ route('admin.vms.filterJson') }}", { _token: "{{ csrf_token() }}", ...filters }, function (response) {
                    if (response.data && response.data.length) {
                        let excelData = response.data.map((row, index) => ({
                            '#': index + 1, // SL column
                            'Full SL': row.full_sl,
                            'Employee Name': row.employee_name,
                            'GP': row.gp,
                            'Vehicle Pass For': row.vehicle_pass_for,
                            'Owner Name': row.vehicle_owner_name,
                            'Registration No': row.vehicle_registration_no,
                            'Insurance Valid From': row.insurance_valid_from,
                            'Insurance Valid To': row.insurance_valid_to,
                            'Vehicle Type': row.vehicle_type,
                            'Vehicle Registration Date': row.vehicle_registration_date,
                            'PUC Valid From': row.puc_valid_from,
                            'PUC Valid To': row.puc_valid_to,
                            'Driven By': row.driven_by,
                            'Driver Name': row.driver_name,
                            'Driving License No': row.driving_license_no,
                            'License Valid From': row.license_valid_from,
                            'License Valid To': row.license_valid_to,
                            'Created At': row.created_at,
                            'Created By': row.created_by,
                            'Vendor Code': row.vendor_code,
                            'Status': row.status,
                            'Updated Datetime': row.updated_datetime,
                            'Return Status': row.return_status,
                            'Return Datetime': row.return_datetime,
                            'Return Reason': row.return_reason,
                            'User Type': row.user_type,
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
@endsection