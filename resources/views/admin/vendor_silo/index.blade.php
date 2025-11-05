<?php
use App\Division;
use App\UserLogin;
?>
@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Vendor SILO Tanker Management / List</li>
@endsection

@section('content')
@if(Session::get('user_sub_typeSession') == 5)
    <div class="alert alert-danger text-center my-5">
        🚫 You don’t have permission to access this page.
    </div>
@else

<style>
    .nav-tabs .nav-link.active { background-color: #0d6efd; color: #fff; }
    .table thead th { text-align:center; vertical-align:middle; }
    .table td { vertical-align: middle; text-align:center; }
    .badge { font-size: 0.85rem; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-truck"></i> Vendor SILO Tanker Management</h4>
    <a href="{{ route('vendor_silo.create', ['user_id' => $id]) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-plus-circle"></i> Add New
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
                                        data-id="{{ $list->id }}" data-sl="{{ $list->vehicle_registration_no }}" data-bs-toggle="modal"
                                        data-bs-target="#exclusionModal">Exclude</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>No records</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- ================== Admin / Other Users (Tabs) ================== --}}
@else
<ul class="nav nav-tabs mb-3" id="vendorTabs" role="tablist">
    @if(Session::get('user_sub_typeSession') == 3)
    <li class="nav-item"><button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#allTab">All ({{ count($vms_lists) }})</button></li>
    @endif
    <li class="nav-item"><button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pendingTab">Pending With Me ({{ count($vms_pendinglists) }})</button></li>
    <li class="nav-item"><button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#activeTab">Active ({{ count($vms_activelists) }})</button></li>
    <li class="nav-item"><button class="nav-link" id="inactive-tab" data-bs-toggle="tab" data-bs-target="#inactiveTab">Inactive ({{ count($vms_inactivelists) }})</button></li>
</ul>

<div class="tab-content">

    <!-- All -->
    <div class="tab-pane fade" id="allTab">
        <div class="card card-body table-responsive">
            <table id="allTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                       
                        <th>Vehicle No</th>
                        
                        <th>Work Order No</th>
                        <th>Status</th>
                       
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vms_lists as $key => $list)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $list->division_name ?? '-' }}</td>
                            <td>{{ $list->vendor_name ?? '-' }}</td>
                           
                            <td>{{ $list->vehicle_registration_no ?? '-' }}</td>
                           
                            <td>{{ $list->work_order_no ?? '-' }}</td>
                            <td>
                                @switch($list->status)
                                    @case('approve') <span class="badge bg-success">Approved</span> @break
                                    @case('inactive') <span class="badge bg-danger">Inactive</span> @break
                                    @default <span class="badge bg-warning text-dark">{{ ucfirst($list->status ?? '-') }}</span>
                                @endswitch
                            </td>
                           
                            <td>{{ $list->created_datetime ?? '-' }}</td>
                            <td>
                                <a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">View</a>
                                @if($list->status == 'draft')
                                    <a href="{{ route('vendor_silo.edit', $list->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>No records found</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending -->
    <div class="tab-pane fade show active" id="pendingTab">
        <div class="card card-body table-responsive">
            <table id="pendingTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                        <th>Vehicle No</th>
            
                        <th>Work Order No</th>
                        <th>Status</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vms_pendinglists as $key => $list)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $list->division_name ?? '-' }}</td>
                            <td>{{ $list->vendor_name ?? '-' }}</td>
                            <td>{{ $list->vehicle_registration_no ?? '-' }}</td>
                          
                            <td>{{ $list->work_order_no ?? '-' }}</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td>{{ $list->created_datetime ?? '-' }}</td>
                            <td><a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr>No pending records</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active -->
    <div class="tab-pane fade" id="activeTab">
        <div class="card card-body table-responsive">
            <table id="activeTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                       
                        <th>Vehicle No</th>
                        
                        <th>Work Order No</th>
                     
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vms_activelists as $key => $list)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $list->division_name ?? '-' }}</td>
                            <td>{{ $list->vendor_name ?? '-' }}</td>
                        
                            <td>{{ $list->vehicle_registration_no ?? '-' }}</td>
                            
                            <td>{{ $list->work_order_no ?? '-' }}</td>
                          
                            <td>{{ $list->created_datetime ?? '-' }}</td>
                            <td>
                                <a href="{{ route('vendor_silo.edit_entry', $list->id) }}" class="btn btn-sm btn-primary">View</a>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>No active records</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inactive -->
    <div class="tab-pane fade" id="inactiveTab">
        <div class="card card-body table-responsive">
            <table id="inactiveTable" class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Sl No</th>
                        <th>Division</th>
                        <th>Vendor</th>
                     
                        <th>Vehicle No</th>
                     
                        <th>Work Order No</th>
                      
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vms_inactivelists as $key => $list)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $list->division_name ?? '-' }}</td>
                            <td>{{ $list->vendor_name ?? '-' }}</td>
                         
                            <td>{{ $list->vehicle_registration_no ?? '-' }}</td>
                          
                            <td>{{ $list->work_order_no ?? '-' }}</td>
                          
                            <td>{{ $list->created_datetime ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>No inactive records</tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endif
@endsection
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
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function() {
    $('#vendorTable, #allTable,#pendingTable, #activeTable, #inactiveTable').DataTable();

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

