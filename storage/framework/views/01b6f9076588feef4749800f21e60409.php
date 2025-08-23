<?php
use App\Division;
use App\UserLogin;
?>

<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Management System / List</a></li>
<?php $__env->stopSection(); ?>
<?php if(Session::get('user_sub_typeSession') == 5): ?>
    return redirect('admin/dashboard');
<?php else: ?>
    <?php $__env->startSection('content'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 mb-4">
                <i class="fas fa-truck"></i> Vehicle Management System / List
            </h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo e(route('vms.create', ['user_id' => $id])); ?>"
                    class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center px-3 shadow-sm upload-btn"
                    id="uploadBtn">
                    <i class="fas fa-upload me-2 upload-icon" id="uploadIcon"></i>
                    <i class="fas fa-spinner fa-spin me-2 d-none" id="spinnerIcon"></i>&nbsp;
                    <span id="uploadText"> Apply For Pass</span>
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
                        <th>#</th>
                        <th>Owner</th>
                        <th>Registration No</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                        <!-- more headers -->
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


    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('scripts'); ?>
        <script>var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loader = document.getElementById('data-loader');
                fetch('<?php echo e(route("admin.vms.list")); ?>')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched Data:', data);

                        if (data.status === 'ok' && Array.isArray(data.data)) {
                            const tableBody = document.getElementById('vms-table-body');
                            let rows = '';
                            loader.style.display = 'none'; // Hide loader
                            data.data.forEach((item, index) => {
                                rows += `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>${index + 1}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>${item.vehicle_owner_name || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>${item.vehicle_registration_no || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>${item.vehicle_type || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>${item.status || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>${item.created_at || ''}</td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td><a href="/vms/${item.enc_id}/edit/"><button class="btn btn-info">Action</button></a></td>
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

    <?php $__env->stopSection(); ?>

<?php endif; ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vms_ifream/index.blade.php ENDPATH**/ ?>