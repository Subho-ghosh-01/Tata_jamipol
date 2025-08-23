<?php 
use App\Department;
use App\UserLogin;
use App\Division;
use Illuminate\Support\Facades\Session;

$gatepassv = DB::table('Clms_gatepass')->where('id', $id)->first();

if (Session::get('clm_role') == 'hr_dept') {
	// For HR: Fetch flow where to_id is 0
	$flow = DB::table('clms_flow')
		->where('clms_id', $gatepassv->id)
		->where('to_id', '0')
		->where('status', 'N')
		->first();
}
else {
	// For others: Match to_id with logged-in user
	$flow = DB::table('clms_flow')
		->where('clms_id', $gatepassv->id)
		->where('to_id', Session::get('user_idSession'))
		->where('status', 'N')
		->first();
}

// Then get the desired flow at same level
$desired_flow = null;


$desired_flow = DB::table('clms_desired_flow')
	->where('clms_id', $gatepassv->id)
	->where('level', @$flow->level)
	->first();

$user_status = @$desired_flow->user_status;
$type = @$desired_flow->type_user;



?>


<?php $__env->startSection('breadcrumbs'); ?>
	<li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="<?php echo e(route('admin.approve_clms.index')); ?>"> Exit Employee Deatils</a> </li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


	<?php if(session('message')): ?>
		<div class="alert <?php echo e(session('message') == 'uploaded successfully.' ? 'alert-success' : 'alert-success'); ?>">
			<?php echo e(session('message')); ?>

		</div>
	<?php endif; ?>
	<?php if(session('message') || $errors->any()): ?>
		<script>
			window.onload = function () {
				const submitBtn = document.getElementById('submit-btn');
				const spinner = document.getElementById('spinner');
				const btnText = document.getElementById('btn-text');

				submitBtn.disabled = false;
				spinner.classList.add('d-none'); // Hide spinner
				btnText.innerText = 'Submit Document';
				setTimeout(function () {
					location.reload();
				}, 2000); // 3000ms = 3 seconds
			};
		</script>
	<?php endif; ?>


	<?php if($errors->any()): ?>
		<div class="alert alert-danger">
			<ul>
				<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li><?php echo e($error); ?></li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</ul>
		</div>
	<?php endif; ?>


	<?php $__currentLoopData = $flowsy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flowy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<?php 
$user = UserLogin::where('id',$flowy->updated_by)->first();
$desired_flow = DB::table('clms_desired_flow')->where('id',$flowy->desired_id)->first();
	?>
		<div class="col-12 position-relative p-2">
			<div class="card shadow-sm rounded-4 watermark-card">
				<div class="card-header bg-primary text-white d-flex align-items-center">
					<i class="fas fa-user-check me-2"></i>&nbsp;
					<h5 class="mb-0"><?php if($desired_flow->user_status == 'executing'): ?> <?php echo e('Executing'); ?> <?php elseif($desired_flow->user_status =='vendor'): ?><?php echo e('Vendor'); ?><?php elseif($desired_flow->user_status =='hr'): ?><?php echo e('HR'); ?><?php endif; ?> Updated Details</h5>
				</div>

				<div class="card-body">
					
					<?php if(!empty($flowy->doc)): ?>
						<p class="mb-2">
							<strong><?php if($desired_flow->type_user == 'full'): ?> <?php echo e('Full & Final'); ?><?php elseif($desired_flow->type_user == 'bank'): ?><?php echo e('Bank'); ?><?php endif; ?> Document:</strong>
							<a href="<?php echo e(url('../../' . $flowy->doc)); ?>" target="_blank" class="btn btn-outline-primary btn-sm ms-2">
								View Document
							</a>

						</p>

					<?php endif; ?>

					
					<p class="mb-2">
						<strong>Updated By:</strong> <?php echo e($user->name ?? 'NA'); ?>

					</p>

					
					<p class="mb-2">
						<strong>Update Datetime:</strong>
						<?php echo e(isset($flowy->updated_datetime) ? date('d-m-Y H:i:s', strtotime($flowy->updated_datetime)) : 'NA'); ?>

					</p>

					
					<?php if(!empty($flowy->decision)): ?>
						<p class="mb-0">
							<strong>Action / Decision:</strong>
							<span class="text-capitalize"><?php echo e($flowy->decision); ?></span>
						</p>
					<?php endif; ?>

					<?php if(!empty($flowy->remarks)): ?>
						<p class="mb-0">
							<strong>Remarks:</strong>
							<span class="text-capitalize"><?php echo e($flowy->remarks); ?></span>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

	<?php if($user_status == 'vendor' && ($type == 'full' or $type == 'bank')): ?>
		<form action="<?php echo e(route('admin.exit.exit_update1')); ?>" method="post" enctype="multipart/form-data" id="form"
			autocomplete="off">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="clms_id" value="<?php echo e($id); ?>">
			<div class="alert alert-info p-2 mb-3 small" style="font-size: 0.9rem;">
				<strong>Instructions:</strong> Please upload only
				<code>.pdf</code>, <code>.doc</code>, <code>.docx</code>, <code>.xls</code>, <code>.xlsx</code>,
				<code>.jpg</code>, <code>.jpeg</code>, or <code>.png</code> files. Maximum file size:
				<strong>5MB</strong>.
			</div>

			<div class="col-md-12 mb-4">
				<h3 class="fw-bold">
					<h5>Pls Upload your <?php if($type == 'full'): ?>
					<?php echo e('Full & Final Document'); ?><?php elseif($type == 'bank'): ?><?php echo e('Bank Doc'); ?><?php endif; ?>
					</h5>
				</h3>

				<!-- Vendor Details Panel - Full Width -->


				<!-- Upload Panels - Half Width Each -->
				<div class="row">

					<!-- File Upload 1 -->
					<div class="col-md-12">
						<div class="custom-file-upload text-center" id="uploadArea1">
							<h5 class="text-primary fw-bold mb-3 p-2"><?php if($type == 'full'): ?>
							<?php echo e('Full & Final Document'); ?><?php elseif($type == 'bank'): ?><?php echo e('Bank Doc'); ?><?php endif; ?>
							</h5>

							<input type="file" name="document1" id="fileUpload1" class="d-none"
								onchange="previewFile(event, 'preview1')">
							<label for="fileUpload1" class="upload-label">
								<i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
								<p class="mb-0 text-muted">Click or drag file here to upload</p>
							</label>
							<div id="preview1" class="mt-3 text-start small text-muted"></div>
							<br>
						</div>
					</div>


				</div>

				<!-- Submit Button -->
				<div class="text-center mt-4">


					<button type="submit" class="btn btn-primary btn-lg px-4 py-2 rounded-pill" id="submit-btn">
						<i class="fas fa-spinner fa-spin me-2 d-none" id="spinner"></i>
						<span id="btn-text">Submit Document </span>
					</button>

				</div>



		</form>
	<?php endif; ?>
	<br>
	<?php if(($user_status == 'hr' || $user_status == 'executing') && $type == 'check'): ?>
	
		<div class="card-body material-card mt-4 shadow rounded-3">

			<form action="<?php echo e(route('admin.exit.exit_update1')); ?>" method="post" enctype="multipart/form-data" id="form"
			autocomplete="off">
			<?php echo csrf_field(); ?>

				<div class="material-header mb-3">
					<h3><i class="bi bi-person-check-fill me-2"></i><?php if($user_status == 'hr'): ?> <?php echo e('HR'); ?><?php else: ?> <?php echo e('Executing'); ?><?php endif; ?>	 Approval Panel</h3>
				</div>
<input type="hidden" name="clms_id" value="<?php echo e($id); ?>">


				<div class="mb-4">
					<label class="form-label fw-semibold">Select Action <span class="text-danger">*</span></label>
					<div class="d-flex gap-4">
						<div>
							<input type="radio" class="btn-check" hidden name="action" id="btn-approve" value="approve"
								autocomplete="off">
							<label class="btn btn-outline-success px-4 py-2 rounded-pill" for="btn-approve">
								<i class="fas fa-check-circle me-1"></i> Approve
							</label>
						</div>
						&nbsp;&nbsp;
						<div>
							<input type="radio" class="btn-check" name="action" id="btn-return" value="reject"
								autocomplete="off" hidden>
							<label class="btn btn-outline-danger px-4 py-2 rounded-pill" for="btn-return">
								<i class="fas fa-undo-alt me-1"></i> Reject
							</label>
						</div>
					</div>
					<div id="action-error" class="text-danger small mt-1 d-none">Please select an action.</div>
				</div>

				<div class="mb-4">
					<label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>

					<textarea name="remarks" rows="4" class="form-control shadow-sm" placeholder="Write your remarks here..."
						required></textarea>
				</div>

				<div class="text-center">
					<button type="submit" class="btn btn-primary px-5 py-2 fw-semibold shadow-sm rounded-pill" id="submit-btn">
						<i class="fas fa-spinner fa-spin me-2 d-none" id="spinner"></i> <span id="btn-text"> Submit </span>
					</button>
				</div>
			</form>
		</div>
	<?php endif; ?>


	<style>
		.material-card {
			background-color: #ffffff9d;
			padding: 20px;
			border-radius: 12px;
			box-shadow: 0 6px 12px 18px rgba(0, 0, 0, 0.08);
		}

		.material-header {
			background: linear-gradient(to right, #cab32e, #e6d675);
			color: #212529;
			font-size: 22px;
			font-weight: 500;
			padding: 12px 24px;
			border-radius: 8px;
			text-align: center;
		}



		.form-control,
		textarea {
			border-radius: 8px;
			transition: 0.3s ease-in-out;
		}

		.form-control:focus {
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
			border-color: #80bdff;
		}

		textarea {
			resize: vertical;
		}


		.btn-check:checked+.btn-outline-success {
			background-color: #28a745;
			color: white;
			border-color: #28a745;
			box-shadow: 0 0 0 0.25rem rgba(5, 221, 55, 0.3);
		}

		.btn-check:checked+.btn-outline-danger {
			background-color: hsl(12, 100%, 51%);
			color: #f4faff;
			border-color: #ff3907fa;
			box-shadow: 0 0 0 0.25rem rgba(238, 21, 5, 0.3);
		}

		.material-header i {
			font-size: 1.2rem;
			vertical-align: middle;
		}
	</style>

	<style>
		.custom-file-upload {
			cursor: pointer;
			background-color: #f8f9fa;
			transition: all 0.3s ease;
			border: 2px dashed #ccc;
			max-width: 1200px;
		}

		.custom-file-upload.dragover {
			background-color: #e3f2fd;
			border-color: #007bff;
		}

		.upload-label {
			cursor: pointer;
		}

		#preview {
			font-size: 0.9rem;
			word-break: break-word;
		}

		#preview strong {
			color: green;
		}

		.blink-text {
			animation: blink-animation 1.3s infinite step-start;
		}

		@keyframes blink-animation {
			0% {
				opacity: 1;
			}

			50% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}


		#vendor_id {
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
			transition: border-color 0.3s, box-shadow 0.3s;
		}

		#vendor_id:focus {
			border-color: #FF6F61;
			box-shadow: 0 0 0 0.2rem rgba(255, 111, 97, 0.25);
		}

		#spinner {
			font-size: 1rem;
		}
	</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
	<script>
		const dropArea = document.getElementById('dropArea');
		const fileInput = document.getElementById('fileUpload');

		['dragenter', 'dragover'].forEach(eventName => {
			dropArea.addEventListener(eventName, (e) => {
				e.preventDefault();
				e.stopPropagation();
				dropArea.classList.add('dragover');
			});
		});

		['dragleave', 'drop'].forEach(eventName => {
			dropArea.addEventListener(eventName, (e) => {
				e.preventDefault();
				e.stopPropagation();
				dropArea.classList.remove('dragover');
			});
		});



		dropArea.addEventListener('drop', (e) => {
			e.preventDefault();
			e.stopPropagation();

			const files = e.dataTransfer.files;
			if (files.length > 0) {
				const file = files[0];

				// Create a new DataTransfer object to properly assign the file
				const dataTransfer = new DataTransfer();
				dataTransfer.items.add(file);
				fileInput.files = dataTransfer.files;

				// Trigger preview
				previewFile({ target: fileInput });
			}

			dropArea.classList.remove('dragover');
		});

	</script>
	<script>
		function previewFile(event, previewId) {
			const file = event.target.files[0];
			const preview = document.getElementById(previewId);

			if (!file) {
				preview.innerHTML = `<span class="text-danger">❌ No file selected.</span>`;
				return;
			}

			const allowedTypes = [
				'application/pdf',
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/vnd.ms-excel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'image/jpeg',
				'image/png'
			];
			const maxSize = 5 * 1024 * 1024;

			if (!allowedTypes.includes(file.type)) {
				preview.innerHTML = `<span class="text-danger">❌ Invalid file type: ${file.type}</span>`;
				return;
			}

			if (file.size > maxSize) {
				preview.innerHTML = `<span class="text-danger">❌ File too large. Max 5MB allowed.</span>`;
				return;
			}

			preview.innerHTML = `✅ <strong>${file.name}</strong> (${(file.size / 1024).toFixed(1)} KB)`;
		}

		// Drag-and-drop support
		function setupDragAndDrop(areaId, inputId) {
			const dropArea = document.getElementById(areaId);
			const fileInput = document.getElementById(inputId);

			dropArea.addEventListener('dragover', (e) => {
				e.preventDefault();
				dropArea.classList.add('dragover');
			});

			dropArea.addEventListener('dragleave', () => {
				dropArea.classList.remove('dragover');
			});

			dropArea.addEventListener('drop', (e) => {
				e.preventDefault();
				dropArea.classList.remove('dragover');
				const files = e.dataTransfer.files;
				if (files.length > 0) {
					fileInput.files = files;
					fileInput.dispatchEvent(new Event('change'));
				}
			});
		}

		// Initialize both upload areas
		setupDragAndDrop('uploadArea1', 'fileUpload1');
		setupDragAndDrop('uploadArea2', 'fileUpload2');
	</script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		$(document).ready(function () {
			$('#vendor_id').select2({
				placeholder: "-- Select Vendor --",
				allowClear: true,
				width: '70%'
			});
		});
	</script>


	<script>
		document.getElementById('form').addEventListener('submit', function () {
			const submitBtn = document.getElementById('submit-btn');
			const spinner = document.getElementById('spinner');
			const btnText = document.getElementById('btn-text');

			// Show loading state
			submitBtn.disabled = true;
			spinner.classList.remove('d-none'); // Show spinner
			btnText.innerText = 'Processing...';
		});
	</script>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/exit_emp_details.blade.php ENDPATH**/ ?>