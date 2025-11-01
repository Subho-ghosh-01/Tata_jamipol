
<?php
use App\Division;
use App\Department;
use App\UserLogin;

$vms = DB::table('vendor_mis')->where('id', $vms_details->id)->first();
$user_id = Session::get('user_idSession');
?>



<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('vms.index')); ?>">List of VMS Documents</a></li>
    <li class="breadcrumb-item active" aria-current="page">Vehicle Pass Management System</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(Session::get('user_sub_typeSession') == 4): ?>
    <script>window.location.href = '<?php echo e(url("admin/dashboard")); ?>';</script>
<?php else: ?>
    <iframe id="myIframe" src="<?php echo e(route('vendor_silo.edit_data_ifream', [$vms->id, $user_id])); ?>"
        style="border: none; overflow: hidden;" width="101%" width="100%" height="" scrolling="no"></iframe>
    <center>
        <div class="classic-10" id="iframe-loader"></div>
    </center></iframe>

    <style>
        .classic-10 {
            --w: 10ch;
            font-weight: bold;
            font-family: monospace;
            font-size: 30px;

            line-height: 1.4em;
            letter-spacing: var(--w);
            width: var(--w);
            overflow: hidden;
            white-space: nowrap;
            color: #0000;
            text-shadow:
                calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000;
            animation: c10 2s infinite linear;
        }

        .classic-10:before {
            content: "Loading...";
        }

        @keyframes c10 {
            9.09% {
                text-shadow:
                    calc(0*var(--w)) -10px #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            18.18% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) -10px #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            27.27% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) -10px #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            36.36% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) -10px #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            45.45% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) -10px #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            54.54% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) -10px #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            63.63% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) -10px #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            72.72% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) -10px #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) 0 #000
            }

            81.81% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) -10px #000, calc(-9*var(--w)) 0 #000
            }

            90.90% {
                text-shadow:
                    calc(0*var(--w)) 0 #000, calc(-1*var(--w)) 0 #000, calc(-2*var(--w)) 0 #000, calc(-3*var(--w)) 0 #000, calc(-4*var(--w)) 0 #000,
                    calc(-5*var(--w)) 0 #000, calc(-6*var(--w)) 0 #000, calc(-7*var(--w)) 0 #000, calc(-8*var(--w)) 0 #000, calc(-9*var(--w)) -10px #000
            }
        }
    </style>
    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        const iframe = document.getElementById('myIframe');
        const loader = document.getElementById('iframe-loader');

        // Listen for message from iframe
        window.addEventListener('message', function (e) {
            if (e.data === 'iframeLoaded') {
                // Hide loader
                loader.style.display = 'none';

                // Show iframe
                iframe.style.display = 'block';

                // Adjust height
                try {
                    iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
                } catch (e) {
                    iframe.style.height = '1000px'; // fallback
                    console.warn('Could not access iframe height:', e);
                }
            }
        });
    </script>
    <script>
        window.addEventListener('load', function () {
            parent.postMessage('iframeLoaded', '*');
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_silo/edit_entry.blade.php ENDPATH**/ ?>