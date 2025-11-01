<?php
use App\Division;
use App\UserLogin;
?>

@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Safety Performance System</li>
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @php
        // Restrict sub_type 5 users
        if (Session::get('user_sub_typeSession') == 5) {
            echo "<script>window.location.href='" . route('admin.dashboard') . "';</script>";
            exit;
        }
    @endphp
    @php
        $day = date('j'); // current day of month (1–31)

        $hidden = ($day >= 27 || $day == 1) ? '' : 'hidden';
        $check = Session::get('user_typeSession');
        $date = date('Y-m');
        if ($check == 2) {
            $found = DB::table('vendor_mis')
                ->where('created_by', Session::get('user_idSession'))
                ->where('month', $date)
                ->first();

            if ($found && isset($found->id)) {
                echo "<script>Swal.fire({
                                icon: 'success',
                                title: 'Already Submitted this month data!',
                                text: '',
                                position: 'top'
                            });</script>";
            }
        }

    @endphp
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 mb-4">
            <i class="fas fa-truck"></i> Safety Performance System
        </h1>

        <div {{ $hidden }} class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('vendor_mis.create', ['user_id' => $id ?? 0]) }}"
                class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn">
                <i class="fas fa-upload me-2 upload-icon"></i>
                <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>
                <span id="uploadText">Create</span>
            </a>
        </div>
    </div>

    <div class="form-group-row">
        <div class="col-sm-12 text-center">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        @if (Session::get('user_typeSession') == '1')
            <table class="table table-striped table-sm" id="vms-table">
                <thead>
                    <tr style="white-space: nowrap;">
                        <th>🔢 Sl. No</th>
                        <th>🏭 Vendor Name</th>
                        <th>🏢 Division</th>
                        <th>🏷️ Department</th>
                        <th>🗓️ Month</th>
                        <th>📌 Status</th>
                        <th>⏰ Created</th>
                        <th>🛠️ Action</th>
                    </tr>
                </thead>
                <tbody id="vms-table-body"></tbody>
            </table>
        @else
            <table class="table table-striped table-sm" id="vms-table1">
                <thead>
                    <tr style="white-space: nowrap;">
                        <th>🔢 Sl. No</th>
                        <th>🗓️ Month</th>
                        <th>📌 Status</th>
                        <th>⏰ Created</th>
                        <th>🛠️ Action</th>
                    </tr>
                </thead>
                <tbody id="vms-table-body1"></tbody>
            </table>
        @endif
    </div>

    <div id="data-loader" class="text-center my-4">
        <div class="spin-loader"></div>
        <div class="loader-text">Loading data, please wait...</div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.getElementById('data-loader');
            const isAdmin = '{{ Session::get('user_typeSession') }}' === '1';
            const tableBody = document.getElementById(isAdmin ? 'vms-table-body' : 'vms-table-body1');
            const tableId = isAdmin ? '#vms-table' : '#vms-table1';
            const date = new Date().getDate(); // 1–31
            let hidden = '';


            if (date >= 27 || date === 1) {
                hidden = '';        // show
            } else {
                hidden = 'hidden';  // hide
            }
            fetch('{{ route("admin.vendor_mis.list") }}')
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';
                    if (data.status === 'ok' && Array.isArray(data.data)) {
                        let rows = '';
                        data.data.forEach((item, index) => {
                            let statusText = '';
                            let hideEdit = '';

                            switch (item.status) {
                                case 'approve':
                                    statusText = 'Approved';
                                    hideEdit = 'hidden';
                                    break;
                                case 'draft':
                                    statusText = 'Draft';
                                    break;
                                case 'pending_with_safety':
                                    statusText = 'Pending For Safety Dept';
                                    break;
                                case 'return':
                                    statusText = 'Rejected';
                                    break;
                                default:
                                    statusText = item.status || '';
                            }

                            rows += `
                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                            <td>${index + 1}</td>
                                                                                                                                                                                                                                                            ${isAdmin ? `<td>${item.vendor_name || ''}</td>
                                                                                                                                                                                                                                                                         <td>${item.division_name || ''}</td>
                                                                                                                                                                                                                                                                         <td>${item.department_name || ''}</td>` : ''}
                                                                                                                                                                                                                                                            <td>${item.month || ''}</td>
                                                                                                                                                                                                                                                            <td>${statusText}</td>
                                                                                                                                                                                                                                                            <td>${item.created_datetime ? new Date(item.created_datetime).toLocaleString('en-IN', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            }) : ''}</td>
                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                <a href="/vendor_mis/${item.id}/edit" class="btn btn-sm btn-success rounded-pill px-3 me-2">
                                                                                                                                                                                                                                                                    View
                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                                                                <a href="/vendor_mis/edit_entry/${item.id}" class="btn btn-sm btn-danger rounded-pill px-3" ${hideEdit} ${hidden}>
                                                                                                                                                                                                                                                                    Edit
                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                        </tr>`;
                        });

                        tableBody.innerHTML = rows;
                        $(tableId).DataTable();
                    } else {
                        tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No records found</td></tr>';
                    }
                })
                .catch(err => {
                    loader.innerHTML = `<div class="text-danger">⚠️ Error loading data.</div>`;
                    console.error('Error fetching data:', err);
                });
        });
    </script>

@endsection