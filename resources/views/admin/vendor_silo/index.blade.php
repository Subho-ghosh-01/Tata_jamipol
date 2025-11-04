<?php
use App\Division;
use App\UserLogin;
?>
@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vendor SILO Tanker Management / List</li>
@endsection

@section('content')
@if(Session::get('user_sub_typeSession') == 5)
    <div class="alert alert-danger text-center my-5">
        🚫 You don’t have permission to access this page.
    </div>
@else
<style>
    table.dataTable th { white-space: nowrap; text-align: center; vertical-align: middle; }
    .modal-content { border-radius: 8px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .modal-header { background-color: #dc3545; color: white; border-radius: 8px 8px 0 0; }
    .form-label { font-weight: 500; color: #333; }
    .form-control { border-radius: 4px; border: 1px solid #ddd; }
    .form-control:focus { border-color: #dc3545; box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25); }
    .btn { border-radius: 4px; }
    .blink-arrow { font-weight: bold; animation: arrowBlink 1s infinite; margin-right: 5px; font-size: 1.2rem; }
    @keyframes arrowBlink { 50% { opacity: 0; } }
</style>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-truck"></i> Vendor SILO Tanker Management</h1>
    <a href="{{ route('vendor_silo.create', ['user_id' => $id]) }}"
       class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
        <i class="fas fa-upload me-2"></i>Create
    </a>
</div>

@if (session('message'))
    <div class="alert alert-success text-center">{{ session('message') }}</div>
@endif

{{-- ================== Vendor Type 2 (Simpler Table) ================== --}}
@if(Session::get('user_typeSession') == 2)
    <div class="card mt-3">
        <div class="card-body table-responsive">
            <table class="table table-striped table-sm" id="vendorTable">
                <thead>
                    <tr>
                        <th>🔢 Sl. No</th>
                        <th>📄 Work-Order No</th>
                        <th>📄 Vehicle Registration No</th>
                        <th>📊 Status</th>
                        <th>🕒 Created</th>
                        <th>⚙️ Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vms_lists as $key => $list)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $list->work_order_no ?? '-' }}</td>
                            <td>{{ $list->vehicle_registration_no ?? '-' }}</td>
                            <td>
                                @switch($list->status)
                                    @case('approve')
                                        <span class="blink-arrow text-success">➡</span>
                                        <span class="badge bg-success">Active</span>
                                        @break
                                    @case('pending_with_inclusion_user')
                                        <span class="badge bg-warning text-dark">Pending With Inclusion User</span>
                                        @break
                                    @case('pending_with_safety')
                                        <span class="badge bg-info text-white">Pending With Safety</span>
                                        @break
                                    @case('draft')
                                        <span class="badge bg-secondary text-white">Draft</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ ucfirst($list->status ?? 'Unknown') }}</span>
                                @endswitch
                            </td>
                            <td>{{ $list->created_datetime }}</td>
                            <td>
                                <a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">Details</a>
                                @if($list->status == 'draft')
                                    <a href="{{ route('vendor_silo.edit', $list->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                                @if($list->created_by == Session::get('user_idSession') && $list->status == 'approve')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-id="{{ $list->id }}" data-sl="{{ $list->full_sl }}" data-bs-toggle="modal"
                                        data-bs-target="#exclusionModal">Exclude</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No records</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- ================== Admin / Other Users (Tabs) ================== --}}
@else
    <div class="card mt-3">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="vendorTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pendingList" type="button" role="tab">🕓 Pending With Me</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#activeList" type="button" role="tab">✅ Active Tankers</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="inactive-tab" data-bs-toggle="tab" data-bs-target="#inactiveList" type="button" role="tab">❌ Inactive Tankers</button>
                </li>
            </ul>
        </div>

        <div class="card-body tab-content">

            {{-- =================== PENDING TAB =================== --}}
            <div class="tab-pane fade show active" id="pendingList" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="pendingTable">
                        <thead>
                            <tr>
                                <th>🔢 Sl. No</th>
                                <th>🏭 Vendor Name</th>
                                <th>🏬 Division</th>
                                <th>🏷️ Section</th>
                                <th>📄 Work-Order No</th>
                                <th>📊 Status</th>
                                <th>🕒 Created</th>
                                <th>⚙️ Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vms_lists as $key => $list)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $list->vendor_name }}</td>
                                    <td>{{ $list->division_name }}</td>
                                    <td>{{ $list->section }}</td>
                                    <td>{{ $list->work_order_no ?? '-' }}</td>
                                    <td>
                                        @if($list->status == 'pending_with_inclusion_user')
                                            <span class="badge bg-warning text-dark">Pending With Inclusion User</span>
                                        @elseif($list->status == 'pending_with_safety')
                                            <span class="badge bg-info text-white">Pending With Safety</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ ucfirst($list->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $list->created_datetime }}</td>
                                    <td><a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">Details</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No pending records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- =================== ACTIVE TAB =================== --}}
            <div class="tab-pane fade" id="activeList" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="activeTable">
                        <thead>
                            <tr>
                                <th>🔢 Sl. No</th>
                                <th>🏭 Vendor Name</th>
                                <th>🏬 Division</th>
                                <th>🏷️ Section</th>
                                <th>📄 Work-Order No</th>
                                <th>📊 Status</th>
                                <th>🕒 Created</th>
                                <th>⚙️ Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vms_activelists as $key => $list)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $list->vendor_name }}</td>
                                    <td>{{ $list->division_name }}</td>
                                    <td>{{ $list->section }}</td>
                                    <td>{{ $list->work_order_no ?? '-' }}</td>
                                    <td>
                                        <span class="blink-arrow text-success">➡</span>
                                        <span class="badge bg-success text-white">Active</span>
                                    </td>
                                    <td>{{ $list->created_datetime }}</td>
                                    <td><a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">Details</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No active records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- =================== INACTIVE TAB =================== --}}
            <div class="tab-pane fade" id="inactiveList" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="inactiveTable">
                        <thead>
                            <tr>
                                <th>🔢 Sl. No</th>
                                <th>🏭 Vendor Name</th>
                                <th>🏬 Division</th>
                                <th>🏷️ Section</th>
                                <th>📄 Work-Order No</th>
                                <th>📊 Status</th>
                                <th>🕒 Created</th>
                                <th>⚙️ Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vms_inactivelists as $key => $list)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $list->vendor_name }}</td>
                                    <td>{{ $list->division_name }}</td>
                                    <td>{{ $list->section }}</td>
                                    <td>{{ $list->work_order_no ?? '-' }}</td>
                                    <td>
                                        <span class="blink-arrow text-danger">➡</span>
                                        <span class="badge bg-danger text-white">Inactive</span>
                                    </td>
                                    <td>{{ $list->created_datetime }}</td>
                                    <td><a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">Details</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted">No inactive records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endif

{{-- ================== Exclude Modal ================== --}}
<div class="modal fade" id="exclusionModal" tabindex="-1" aria-labelledby="exclusionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exclude Silo Tanker</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="exclusionForm">
                <div class="modal-body">
                    <div class="alert d-none" id="formAlert"></div>
                    <input type="hidden" name="id" id="tanker_id">
                    <div class="mb-3">
                        <label class="form-label">Tanker SL Number</label>
                        <input type="text" class="form-control" id="sl" name="tanker_no" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Exclusion</label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitExclusion" class="btn btn-danger">
                        <span class="btn-text">Submit Exclusion</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function() {
    $('#vendorTable, #pendingTable, #activeTable, #inactiveTable').DataTable();

    $('#exclusionModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        $('#tanker_id').val(button.data('id'));
        $('#sl').val(button.data('sl'));
    });

    const $form = $("#exclusionForm");
    const $button = $("#submitExclusion");
    const $spin = $button.find(".spinner-border");
    const $text = $button.find(".btn-text");

    function setLoading(state) {
        $button.prop("disabled", state);
        $spin.toggleClass("d-none", !state);
        $text.text(state ? "Processing..." : "Submit Exclusion");
    }

    $form.on("submit", function (e) {
        e.preventDefault();
        setLoading(true);

        $.ajaxSetup({ headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") } });

        $.post("{{ route('vendor_silo.returnsilo') }}", $form.serialize())
            .done(() => {
                $("#formAlert").removeClass("d-none alert-danger").addClass("alert alert-success")
                    .text("Tanker excluded successfully!");
                setTimeout(() => location.reload(), 1000);
            })
            .fail(xhr => {
                $("#formAlert").removeClass("d-none alert-success").addClass("alert alert-danger")
                    .text(xhr.responseJSON?.message || "Something went wrong.");
            })
            .always(() => setLoading(false));
    });
});
</script>
@endsection
