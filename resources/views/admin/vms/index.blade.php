@extends('admin.app')

@section('content')
    @section('breadcrumbs')
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Vehicle Management System / List</li>
    @endsection
    <!-- Bootstrap 5 JavaScript Bundle (includes Popper) -->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 mb-4">
                <i class="fas fa-truck"></i> Vehicle Gate Pass Management System
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('vms.create', ['user_id' => $id]) }}"
                    class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn"
                    id="uploadBtn">
                    <i class="fas fa-upload me-2 upload-icon" id="uploadIcon"></i>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>&nbsp;
                    <span id="uploadText">Apply For Pass</span>
                </a>
            </div>
        </div>
        {{-- Top Tabs: Approved / Surrender --}}
        <ul class="nav nav-tabs mb-4" id="vmsTopTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved"
                    type="button" role="tab">
                    ✅ Approved
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="surrender-tab" data-bs-toggle="tab" data-bs-target="#surrender" type="button"
                    role="tab">
                    🔄 Surrender
                </button>
            </li>
        </ul>

        <div class="tab-content" id="vmsTopTabsContent">

            {{-- Approved Tab --}}
            <div class="tab-pane fade show active" id="approved" role="tabpanel">
                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-light">
                        <ul class="nav nav-pills card-header-pills " id="approvedSubTabs" role="tablist">

                            @if(Session::get('user_sub_typeSession') == '3' || Session::get('clm_role') == 'Safety_dept')
                                {{-- Employee + Vendor --}}
                                <li class="nav-item">
                                    <button class="nav-link active" id="approved-employee-tab" data-bs-toggle="tab"
                                        data-bs-target="#approved-employee" type="button">
                                        👤 Employee
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="approved-vendor-tab" data-bs-toggle="tab"
                                        data-bs-target="#approved-vendor" type="button">
                                        🏢 Vendor
                                    </button>
                                </li>
                            @elseif(Session::get('user_sub_typeSession') == '1')
                                {{-- Employee only --}}
                                <li class="nav-item">
                                    <button class="nav-link active" id="approved-employee-tab" data-bs-toggle="tab"
                                        data-bs-target="#approved-employee" type="button">
                                        👤 Employee
                                    </button>
                                </li>
                            @else
                                {{-- Vendor only --}}
                                <li class="nav-item">
                                    <button class="nav-link active" id="approved-vendor-tab" data-bs-toggle="tab"
                                        data-bs-target="#approved-vendor" type="button">
                                        🏢 Vendor
                                    </button>
                                </li>
                            @endif

                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content">
                            @if(Session::get('user_sub_typeSession') == '3' || Session::get('clm_role') == 'Safety_dept')
                                <div class="tab-pane fade show active" id="approved-employee">
                                    @include('admin.partials.pass_table', ['vmsData' => $approvedEmployee])
                                </div>
                                <div class="tab-pane fade" id="approved-vendor">
                                    @include('admin.partials.pass_table', ['vmsData' => $approvedVendor])
                                </div>
                            @elseif(Session::get('user_sub_typeSession') == '1')
                                <div class="tab-pane fade show active" id="approved-employee">
                                    @include('admin.partials.pass_table', ['vmsData' => $approvedEmployee])
                                </div>
                            @else
                                <div class="tab-pane fade show active" id="approved-vendor">
                                    @include('admin.partials.pass_table', ['vmsData' => $approvedVendor])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Surrender Tab --}}
            <div class="tab-pane fade" id="surrender" role="tabpanel">
                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-light">
                        <ul class="nav nav-pills card-header-pills" id="surrenderSubTabs" role="tablist">

                            @if(Session::get('user_sub_typeSession') == '3' || Session::get('clm_role') == 'Safety_dept')
                                {{-- Employee + Vendor --}}
                                <li class="nav-item">
                                    <button class="nav-link active" id="surrender-employee-tab" data-bs-toggle="tab"
                                        data-bs-target="#surrender-employee" type="button">
                                        👤 Employee
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="surrender-vendor-tab" data-bs-toggle="tab"
                                        data-bs-target="#surrender-vendor" type="button">
                                        🏢 Vendor
                                    </button>
                                </li>
                            @elseif(Session::get('user_sub_typeSession') == '1')
                                {{-- Employee only --}}
                                <li class="nav-item">
                                    <button class="nav-link active" id="surrender-employee-tab" data-bs-toggle="tab"
                                        data-bs-target="#surrender-employee" type="button">
                                        👤 Employee
                                    </button>
                                </li>
                            @else
                                {{-- Vendor only --}}
                                <li class="nav-item">
                                    <button class="nav-link active" id="surrender-vendor-tab" data-bs-toggle="tab"
                                        data-bs-target="#surrender-vendor" type="button">
                                        🏢 Vendor
                                    </button>
                                </li>
                            @endif

                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content">
                            @if(Session::get('user_sub_typeSession') == '3' || Session::get('clm_role') == 'Safety_dept')
                                <div class="tab-pane fade show active" id="surrender-employee">
                                    @include('admin.partials.pass_table_return', ['vmsData' => $surrenderEmployee])
                                </div>
                                <div class="tab-pane fade" id="surrender-vendor">
                                    @include('admin.partials.pass_table_return', ['vmsData' => $surrenderVendor])
                                </div>
                            @elseif(Session::get('user_sub_typeSession') == '1')
                                <div class="tab-pane fade show active" id="surrender-employee">
                                    @include('admin.partials.pass_table_return', ['vmsData' => $surrenderEmployee])
                                </div>
                            @else
                                <div class="tab-pane fade show active" id="surrender-vendor">
                                    @include('admin.partials.pass_table_return', ['vmsData' => $surrenderVendor])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>



        {{-- Surrender Modal --}}
        <div class="modal fade" id="returnPassModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg rounded-4">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title w-100 text-center">🔄 Surrender Vehicle Pass</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Are you sure you want to <strong>surrender</strong> this vehicle pass?</p>
                        <form id="return-pass-form" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" id="surrenderReason" name="surrender_reason" required
                                    rows="3" placeholder="Provide a reason..."></textarea>
                            </div>
                            <input type="hidden" name="pass_id" id="returnPassId">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    data-bs-dismiss="modal">❌ Cancel</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4">✅ Yes, Surrender</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Fix aria-hidden warning when closing modal
            $('#returnPassModal').on('hide.bs.modal', function () {
                $(document.activeElement).blur();
            });

            // Set pass id when modal shown
            $('#returnPassModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var passId = button.data('id');
                $(this).find('#returnPassId').val(passId);
            });

            // Submit surrender form
            $('#return-pass-form').on('submit', function (e) {
                e.preventDefault();

                const form = $(this);
                const passId = $('#returnPassId').val();
                const button = form.find('button[type="submit"]');

                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('pass_id', passId);

                $.ajax({
                    url: '{{ route("vms_ifream.update_surrender") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pass Surrendered',
                            text: response.message || 'Vehicle pass has been surrendered.',
                        }).then(() => {
                            $('#returnPassModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        let errorMsg = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: errorMsg,
                        });
                        button.prop('disabled', false).html('✅ Yes, Surrender');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('table.vmsDataTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [[0, 'asc']], // default sort by first column
                language: {
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "⬅ Prev",
                        next: "Next ➡"
                    }
                }
            });
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
                $.fn.dataTable
                    .tables({ visible: true, api: true })
                    .columns.adjust()
                    .responsive.recalc();
            });
        });
    </script>
@endsection