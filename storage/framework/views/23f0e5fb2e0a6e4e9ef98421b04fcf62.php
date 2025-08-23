<?php
use App\Division;
use App\UserLogin;
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vendor SILO Tanker Management / List</a></li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 5): ?>
    return redirect('admin/dashboard');
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 mb-4">
                <i class="fas fa-truck"></i> Vendor SILO Tanker Management / List
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('vendor_silo.create', ['user_id' => $id])); ?>"
                    class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn"
                    id="uploadBtn">
                    <i class="fas fa-upload me-2 upload-icon" id="uploadIcon"></i>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>&nbsp;
                    <span id="uploadText">Create</span>
                </a>
                <style>
                    .upload-btn i {
                        transition: all 0.3s ease;
                    }
                </style>


                <style>
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


            </div>
        </div>
        <div class="form-group-row">
            <div class="col-sm-12" style="text-align:center;">
                <?php if(session()->has('message')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-responsive ">


            <table class="table table-striped table-sm" id="vms-table">
                <thead>
                    <tr>
                        <th title="Serial Number">🔢 Sl. No</th>
                        <th title="Name of the Vendor">🏭 Vendor Name</th>
                        <th title="Division / Department">🏬 Division</th>
                        <th title="Section within Division">🏷️ Section</th>
                        <th title="Work Order Number">📄 Work-Order No</th>
                        <th title="Current Status">📊 Status</th>
                        <th title="Date & Time Created">🕒 Created</th>
                        <th title="Available Actions">⚙️ Action</th>
                    </tr>




                </thead>
                <tbody id="vms-table-body">
                    <!-- Dynamic rows from JS -->
                </tbody>
            </table>

        </div><!-- Loader Wrapper -->
        <div id="data-loader" class="text-center my-4">
            <style>
                /* Center the loader */
                #data-loader {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                }

                /* Spinner animation */
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

                /* Blinking (fade in/out) loader text */
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
            </style>

            <!-- Spinner -->
            <div class="spin-loader"></div>

            <!-- Animated Fading Text -->
            <div class="loader-text">Loading data, please wait...</div>
        </div>

        <style>
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
        </style>

        <div class="modal fade" id="returnPassModal" tabindex="-1" role="dialog" aria-labelledby="returnPassModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-gradient text-white">
                        <h5 class="modal-title w-100 text-center" id="returnPassModalLabel">
                            🔄 Surrender Vehicle Pass
                        </h5>
                        <button type="button" class="close text-white position-absolute" style="right: 15px;"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 1.4rem;">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>🚗 Are you sure you want to <strong>Surrender</strong> this vehicle pass?</p>

                    </div>
                    <div class="modal-footer justify-content-center pb-4">
                        <button type="button" class="btn btn-outline-secondary btn-pill" data-dismiss="modal">❌ Cancel</button>
                        <form id="return-pass-form" method="POST" style="margin: 0;">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="pass_id" id="returnPassId" value="">
                            <button type="submit" class="btn btn-danger btn-pill">✅ Yes, Surrender</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function () {

                // Fix aria-hidden warning when closing modal
                $('#returnPassModal').on('hide.bs.modal', function () {
                    $(document.activeElement).blur();
                });

                $('#return-pass-form').on('submit', function (e) {
                    e.preventDefault();

                    const form = $(this);
                    const passId = $('#returnPassId').val();
                    const button = form.find('button[type="submit"]');

                    // Disable button and show spinner
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

                    const formData = new FormData(this);
                    formData.append('_token', '<?php echo e(csrf_token()); ?>'); // CSRF for Laravel
                    formData.append('pass_id', passId); // Ensure pass_id is present

                    $.ajax({
                        url: '<?php echo e(route("vms_ifream.update_surrender")); ?>',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            console.log('SUCCESS:', response);

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
                            console.error('ERROR:', xhr);

                            let errorMsg = 'Something went wrong. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: errorMsg,
                            });

                            // Reset the button
                            button.prop('disabled', false).html('✅ Yes, Surrender');
                        }
                    });
                });
            });
        </script>
        <script>var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>

        <script>
            function formatMonthYear(m) {
                if (!m) return '';
                const [year, month] = m.split('-');
                const date = new Date(year, month - 1);
                return date.toLocaleString('default', { month: 'long', year: 'numeric' }); // "February 2024"
            }

            function formatDateTime(dtStr) {
                if (!dtStr) return '';
                const date = new Date(dtStr);
                return date.toLocaleString('en-IN', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }); // e.g. "01 Aug 2024, 01:45 PM"
            }


            document.addEventListener('DOMContentLoaded', function () {
                const loader = document.getElementById('data-loader');
                fetch('<?php echo e(route("admin.vendor_mis.list")); ?>')
                    .then(response => response.json())
                    .then(data => {
                        //console.log('Fetched Data:', data);

                        if (data.status === 'ok' && Array.isArray(data.data)) {
                            const tableBody = document.getElementById('vms-table-body');
                            let rows = '';
                            loader.style.display = 'none'; // Hide loader
                            data.data.forEach((item, index) => {
                                const statusLower = item.status?.toLowerCase() || '';
                                const statusreturnLower = item.return_status?.toLowerCase() || '';
                                const statusLabel = item.status
                                    ? item.status.charAt(0).toUpperCase() + item.status.slice(1) +
                                    (statusLower === 'approve' ? ' ✅' :
                                        statusLower === 'return' ? ' ❌' :
                                            statusLower === 'pending_with_safety' ? ' ⏳' : '')
                                    : '';

                                const actionClass =
                                    statusLower === 'approve' ? 'btn-success' :
                                        statusLower === 'return' ? 'btn-danger' :
                                            statusLower === 'pending_with_safety' ? 'btn-warning' : 'btn-secondary';

                                const actionLabel =
                                    statusLower === 'approve' ? '📄 Details' :
                                        statusLower === 'return' ? '✏️ Edit' :
                                            statusLower === 'pending_with_safety' ? '⏳ Action' : '🔘 Action';

                                let surrenderButton = '';
                                if (statusLower === 'approve' && (statusreturnLower === '' || statusreturnLower === 'return')) {
                                    surrenderButton = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <a href="/vendor_mis/edit_entry/${item.id}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3 ms-2 ">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          🔁   Edit
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </a>`;
                                } else {
                                    surrenderButton = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <a href="/vendor_mis/edit_entry/${item.id}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3 ms-2 ">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  🔁 Edit
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </a>`;
                                }

                                rows += `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${index + 1}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${item.vendor_name}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${item.division_name || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${item.department_name || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${formatMonthYear(item.month) || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${statusLabel}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>${formatDateTime(item.created_datetime) || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href="/vendor_mis/${item.id}/edit">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <button class="btn btn-sm rounded-pill px-3 ms-2 ${actionClass}">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ${actionLabel}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ${surrenderButton}
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

        <script>
            $('#returnPassModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var passId = button.data('id');
                $(this).find('#returnPassId').val(passId);
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php endif; ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_silo/index.blade.php ENDPATH**/ ?>