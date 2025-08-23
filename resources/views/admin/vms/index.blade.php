<?php
use App\Division;
use App\UserLogin;
?>

@extends('admin.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Management System / List</li>
@endsection

@section('content')

    @if(Session::get('user_sub_typeSession') == 5)
        {{-- Redirect user from controller instead of Blade --}}
        <script>window.location.href = "{{ route('admin.dashboard') }}";</script>
    @else

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
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

        @if (session()->has('message'))
            <div class="alert alert-success text-center">
                {{ session('message') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-sm" id="vms-table">
                <thead>
                    <tr>
                        <th>#️⃣ Sl. No</th>
                        <th>#️⃣ Full Sl.</th>
                        <th>🙍‍♂️ Owner</th>
                        <th>🔖 Reg. No</th>
                        <th>🚙 Vehicle Type</th>
                        <th>📌 Status</th>
                        <th>🕒 Created</th>
                        <th>🛠️ Action</th>
                    </tr>
                </thead>
                <tbody id="vms-table-body">
                    <!-- Dynamic rows filled by JS -->
                </tbody>
            </table>
        </div>

        <div id="data-loader" class="text-center my-4">
            <div class="spin-loader"></div>
            <div class="loader-text">Loading data, please wait...</div>
        </div>

        {{-- Styles --}}
        <style>
            /* Loader Styles */
            #data-loader {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .spin-loader {
                width: 60px;
                height: 60px;
                border: 6px solid rgba(0, 123, 255, 0.2);
                border-top-color: #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            .loader-text {
                margin-top: 12px;
                font-size: 16px;
                color: #333;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                animation: fadeBlink 1.5s ease-in-out infinite;
            }

            @keyframes fadeBlink {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0;
                }
            }

            /* Modal header gradient */
            .modal-header.bg-gradient {
                background: linear-gradient(45deg, #dc3545, #ff6f61);
            }

            .modal-content.shadow-lg {
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
                border-radius: 15px;
            }

            .modal-body {
                font-size: 1.1rem;
                color: #333;
                text-align: center;
                padding: 30px 20px;
            }

            .btn-pill {
                border-radius: 50px !important;
                padding: 8px 20px;
                font-weight: 500;
            }

            /* Row highlight with watermark */
            .return-approved-row {
                position: relative;
                background-color: #f8d7da !important;
                /* light red */
                overflow: hidden;
            }

            .return-approved-row::before {
                content: "Document Expired";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-25deg);
                font-size: 2.5rem;
                color: rgba(0, 0, 0, 0.08);
                font-weight: 600;
                pointer-events: none;
                user-select: none;
                white-space: nowrap;
                z-index: 0;
                letter-spacing: 4px;
            }

            /* Upload button icon animation */
            .upload-btn i {
                transition: all 0.3s ease;
            }

            .upload-btn:hover .upload-icon {
                animation: bounceUpload 0.6s;
            }

            @keyframes bounceUpload {
                0% {
                    transform: translateY(0);
                }

                30% {
                    transform: translateY(-5px);
                }

                60% {
                    transform: translateY(2px);
                }

                100% {
                    transform: translateY(0);
                }
            }
        </style>

        {{-- Surrender Pass Modal --}}
        <div class="modal fade" id="returnPassModal" tabindex="-1" aria-labelledby="returnPassModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-gradient text-white">
                        <h5 class="modal-title w-100 text-center" id="returnPassModalLabel">🔄 Surrender Vehicle Pass</h5>
                        <button type="button" class="btn-close btn-close-white position-absolute" style="right: 15px;"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>🚗 Are you sure you want to <strong>Surrender</strong> this vehicle pass?</p>
                        <form id="return-pass-form" method="POST" style="margin: 0;">
                            @csrf
                            <div class="form-group mt-3">
                                <label for="surrenderReason">Reason for surrendering :</label>
                                <textarea class="form-control" id="surrenderReason" name="surrender_reason" required rows="3"
                                    placeholder="Please provide a reason for surrendering this pass..."></textarea>
                            </div>
                            <input type="hidden" name="pass_id" id="returnPassId" value="">
                            <div class="modal-footer justify-content-center pb-4">
                                <button type="button" class="btn btn-outline-secondary btn-pill" data-bs-dismiss="modal">❌
                                    Cancel</button>
                                <button type="submit" class="btn btn-danger btn-pill">✅ Yes, Surrender</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endif

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
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.getElementById('data-loader');
            fetch('{{ route("admin.vms.list") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok' && Array.isArray(data.data)) {
                        const tableBody = document.getElementById('vms-table-body');
                        let rows = '';
                        loader.style.display = 'none'; // Hide loader

                        // Helper: strip time part of Date (set time to 00:00:00)
                        function stripTime(date) {
                            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
                        }

                        const today = stripTime(new Date());
                        const expiryThresholdDays = 15;

                        // Calculate difference in days (date1 - date2)
                        function daysDiff(date1, date2) {
                            const diffTime = date1.getTime() - date2.getTime();
                            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        }

                        data.data.forEach((item, index) => {
                            const statusLower = item.status?.toLowerCase() || '';
                            const statusreturnLower = item.return_status?.toLowerCase() || '';

                            // Parse expiry dates and strip time
                            const licenseExpiry = item.license_valid_to ? stripTime(new Date(item.license_valid_to)) : null;
                            const insuranceExpiry = item.insurance_valid_to ? stripTime(new Date(item.insurance_valid_to)) : null;
                            const pucExpiry = item.puc_valid_to ? stripTime(new Date(item.puc_valid_to)) : null;

                            let expiryMessages = [];

                            // Check expiry of each document
                            function checkExpiryDate(dateObj, docName) {
                                if (!dateObj) return;
                                if (dateObj < today) {
                                    expiryMessages.push(`${docName} Expired ❌`);
                                } else {
                                    const diff = daysDiff(dateObj, today);
                                    if (diff > 0 && diff <= expiryThresholdDays) {
                                        expiryMessages.push(`${docName} Expires Soon ⚠️`);
                                    }
                                }
                            }

                            checkExpiryDate(licenseExpiry, 'License');
                            checkExpiryDate(insuranceExpiry, 'Insurance');
                            checkExpiryDate(pucExpiry, 'PUC');

                            // Build status label string
                            let statusLabel = '';

                            if (expiryMessages.length > 0) {
                                statusLabel = expiryMessages.join(', ');
                            } else if (statusreturnLower === 'approve' || statusreturnLower === 'approved') {
                                statusLabel = 'Surrender 🔁';
                            } else if (item.status) {
                                statusLabel = item.status.charAt(0).toUpperCase() + item.status.slice(1) +
                                    (statusLower === 'approve' ? ' ✅' :
                                        statusLower === 'return' ? ' ❌' :
                                            statusLower === 'pending_with_safety' ? ' ⏳' : '');
                            }

                            // Action button classes and labels
                            const actionClass =
                                statusLower === 'approve' ? 'btn-success' :
                                    statusLower === 'return' ? 'btn-danger' :
                                        statusLower === 'pending_with_safety' ? 'btn-warning' : 'btn-secondary';

                            const actionLabel =
                                statusLower === 'approve' ? '📄 Details' :
                                    statusLower === 'return' ? '✏️ Edit' :
                                        statusLower === 'pending_with_safety' ? '⏳ Action' : '🔘 Action';

                            // Surrender button
                            let surrenderButton = '';
                            if (statusLower === 'approve' && (statusreturnLower === '' || statusreturnLower === 'return')) {
                                surrenderButton = `
                                                                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            data-toggle="modal" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            data-target="#returnPassModal" 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            data-id="${item.id}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            🔁 Surrender
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </button>`;
                            } else if (statusreturnLower !== '') {
                                surrenderButton = `
                                                                                        <a href="/vms/edit_return/${item.id}">
                                                                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-3 ms-2 ">
                                                                                                🔁 Surrender Details
                                                                                            </button>
                                                                                        </a>`;
                            }

                            rows += `
                                                                                    <tr>
                                                                                        <td>${index + 1}</td>
                                                                                        <td>${item.full_sl}</td>
                                                                                        <td>${item.vehicle_owner_name || ''}</td>
                                                                                        <td>${item.vehicle_registration_no || ''}</td>
                                                                                        <td>${item.vehicle_type || ''}</td>
                                                                                        <td>${statusLabel}</td>
                                                                                        <td>${item.created_at || ''}</td>
                                                                                        <td>
                                                                                            <a href="/vms/${item.id}/edit">
                                                                                                <button class="btn btn-sm rounded-pill px-3 ms-2 ${actionClass}">
                                                                                                    ${actionLabel}
                                                                                                </button>
                                                                                            </a>
                                                                                            ${surrenderButton}
                                                                                            <a href="/vms/edit_driver_details/${item.id}">
                                                                                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 ms-2 ">
                                                                                                Edit Driver Details
                                                                                            </button>
                                                                                        </a>
                                                                                        </td>
                                                                                    </tr>`;
                        });

                        tableBody.innerHTML = rows;
                        $('#vms-table').DataTable();
                    } else {
                        console.warn('No data or invalid format');
                    }
                })
                .catch(err => console.error('Error fetching data:', err));
        });





    </script>
@endsection