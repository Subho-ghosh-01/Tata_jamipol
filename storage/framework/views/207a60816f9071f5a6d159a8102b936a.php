<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('Clms_gatepass')->where('id', $id)->first();
$skillType = (string) (is_string($gatepassv->skill_type) ?? '1');


$skill = DB::table('skill_clms')->where('id', $skillType)->first();

$skill_type = $skill->skill_name ?? $gatepassv->skill_type;
//echo $id;
//exit;
@$department_p = Department::where('id', @$gatepassv->department)->first();
@$division_p = Division::where('id', @$gatepassv->division_id)->first();
@$approver = UserLogin::where('id', @$gatepassv->created_by)->first();
@$approver_security = UserLogin::where('id', @$gatepassv->security_print_id)->first();
//@$work = DB::table('work_order')->where('id',@$gatepassv->work_order_no)->first();
?>


<?php $__env->startSection('breadcrumbs'); ?>
	<!-- <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
																																																																				<li class="breadcrumb-item"><a href="<?php echo e(route('admin.list_permit.index')); ?>"></a></li>-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<div class="form-group-row">
		<div class="col-sm-12" style="text-align:center;">
			<?php if(session()->has('message')): ?>
				<div class="alert alert-success">
					<?php echo e(session('message')); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="flex justify-content-between">
						<div class="card card-primary">
							<div
								class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-0">
								<h1 class="h3"><?php echo e(@$gatepassv->full_sl); ?> (<?php echo e(@$gatepassv->gp_status); ?>)</h1>

							</div>
							<div class="card-body">
								<table id="example1" class="table table-bordered ">
									<thead>
										<tr>
											<th>Vendor Name</th>
											<td class="col-7"> <?php echo e(@$approver->name); ?> </td>
										</tr>
										<tr>
											<th>Work Order No</th>
											<td><?php echo e(@$gatepassv->work_order_no); ?></td>
										</tr>
										<tr>
											<th>Work Order Validity</th>
											<td><?php echo e(date('d-m-Y', strtotime(@$gatepassv->work_order_validity))); ?></td>
										</tr>
										<tr>
											<th>Labour License No</th>
											<td><?php echo e(@$approver->lobour_license_no); ?></td>
										</tr>
										<tr>
											<th>Labour License Validity</th>
											<td><?php echo e(@$approver->labour_license_validity); ?></td>
										</tr>

										<tr>
											<th>Labour License Doc</th>
											<td><a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($approver->lobour_license_document); ?>"
													target="_blank">
													<button class="btn"><i class="fa fa-download"></i> View File</button>
												</a></td>
										</tr>
										<tr>
											<th>Name</th>
											<td><?php echo e(@$gatepassv->name); ?></td>
										</tr>

										<tr>
											<th>Son/Daughter/Wife of</th>
											<td><?php echo e(@$gatepassv->son_of); ?></td>
										</tr>
										<tr>
											<th>Gender</th>
											<td><?php echo e(ucfirst(@$gatepassv->gender)); ?></td>
										</tr>
										<tr>
											<th>Caste</th>
											<td><?php echo e(ucfirst(@$gatepassv->caste)); ?></td>
										</tr>
										<tr>
											<th>Employee Pno</th>
											<td><?php echo e(ucfirst(@$gatepassv->emp_pno)); ?></td>
										</tr>

										<tr>
											<th>Date of Birth</th>

											<td> <?php echo e(date('d-F-Y', strtotime(@$gatepassv->date_of_birth))); ?></td>
										</tr>
										<tr>
											<th>Mobile Number</th>

											<td> <?php echo e(@$gatepassv->mobile_no); ?></td>
										</tr>
										<tr>
											<th>Identity Proof</th>

											<td> <?php echo e(@$gatepassv->identity_proof); ?></td>
										</tr>
										<tr>
											<th>Identity Proof No</th>

											<td> <?php echo e(@$gatepassv->unique_id_no); ?></td>
										</tr>
										<tr>

											<!--<td>  <a href="https://docs.google.com/gview?url=aiplbaradwari.ddns.net:5002/jamipol_vms/public/documents/clm_pics/<?php echo e($gatepassv->upload_id_proof); ?>" target="_blank">
																																																																												<button class="btn"><i class="fa fa-download"></i> Download File</button> </a></td>-->
											<th>Identity Proof Photo Front </th>

											<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->upload_id_proof); ?>"
													target="_blank">
													<button class="btn"><i class="fa fa-download"></i> View File</button>
												</a></td>
										</tr>
										<tr>
											<th>Identity Proof Photo Back </th>

											<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->upload_id_proof_back); ?>"
													target="_blank">
													<button class="btn"><i class="fa fa-download"></i> View File</button>
												</a></td>
										</tr>
										<tr>
											<th>Education</th>

											<td> <?php echo e(@$gatepassv->education); ?></td>
										</tr>
										<?php if($gatepassv->education != "Below-Matric"): ?>
											<tr>
												<th>Board Name</th>

												<td> <?php echo e(@$gatepassv->board_name); ?></td>
											</tr>
											<tr>
												<th>Result</th>

												<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->upload_result); ?>"
														target="_blank">
														<button class="btn"><i class="fa fa-download"></i> View File</button>
													</a></td>
											</tr>
										<?php endif; ?>
										<tr>
											<th>UAN / PF</th>

											<td> <?php echo e(@$gatepassv->uan_no); ?></td>
										</tr>

										<tr>
											<th>UAN Document</th>

											<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->upload_pf_copy); ?>"
													target="_blank">
													<button class="btn"><i class="fa fa-download"></i> View File</button>
												</a></td>
										</tr>
										<?php if(@$gatepassv->esic_type == 'ESIC'): ?>
											<tr>
												<th>ESIC</th>

												<td> <?php echo e(@$gatepassv->esic); ?></td>
											</tr>
											<tr>
												<th>ESIC Document</th>

												<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->esic_document); ?>"
														target="_blank">
														<button class="btn"><i class="fa fa-download"></i> View File</button>
													</a></td>
											</tr>
										<?php elseif(@$gatepassv->esic_type == 'WCP'): ?>
											<tr>
												<th>Workman Compensation No</th>

												<td> <?php echo e(@$gatepassv->wcno); ?></td>
											</tr>

											<tr>
												<th>Workman Compensation Validity</th>

												<td> <?php echo e(date('d-F-Y', strtotime(@$gatepassv->wcv))); ?></td>
											</tr>
											<tr>
												<th>Workman Compensation Document</th>

												<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e(@$gatepassv->wcp_doc); ?>"
														target="_blank">
														<button class="btn"><i class="fa fa-download"></i> View File</button>
													</a></td>
											</tr>

										<?php endif; ?>
										<tr>
											<th>Skill Type</th>
											<td><?php echo e($skill_type); ?></td>


										</tr>
										<tr>
											<th>Skill Rate</th>
											<td><?php echo e($gatepassv->skill_rate); ?></td>


										</tr>
										<tr>
											<th>Blood Group</th>

											<td> <?php echo e(ucfirst(@$gatepassv->blood_group)); ?></td>
										</tr>
										<tr>
											<th>Medical Examination Date</th>

											<td><?php echo e(date('d-F-Y', strtotime(@$gatepassv->medical_examination_date))); ?></td>
										</tr>
										<tr>
											<th>Medical Fitness Copy</th>

											<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->upload_fittenss_copy); ?>"
													target="_blank">
													<button class="btn"><i class="fa fa-download"></i> View File</button>
												</a></td>
										</tr>
										<?php if($gatepassv->police_verification_copy != ''): ?>
											<tr>
												<th>Police Verification Date</th>

												<td><?php echo e(date('d-F-Y', strtotime(@$gatepassv->police_verification_date))); ?></td>
											</tr>
											<tr>
												<th>Police Verification Copy</th>

												<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->police_verification_copy); ?>"
														target="_blank">
														<button class="btn"><i class="fa fa-download"></i> View File</button>
													</a></td>
											</tr>

										<?php endif; ?>
										<?php if($gatepassv->passport_no != ''): ?>
											<tr>
												<th>Passport No</th>

												<td><?php echo e($gatepassv->passport_no); ?></td>
											</tr>

											<tr>
												<th>Passport Validity</th>

												<td><?php echo e(date('d-F-Y', strtotime(@$gatepassv->passport_validity))); ?></td>
											</tr>
											<tr>
												<th>Passport Copy</th>
												<td> <a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->passport_copy); ?>"
														target="_blank">
														<button class="btn"><i class="fa fa-download"></i> View File</button>
													</a></td>
											</tr>
										<?php endif; ?>

										<tr>
											<th>Photo </th>



											<td>


												<a href="https://wps.jamipol.com/documents/clm_pics/<?php echo e($gatepassv->upload_photo); ?>"
													target="_blank">
													<button class="btn"><i class="fa fa-download"></i> View File</button>
												</a>
											</td>
										</tr>
										<tr>
											<th>Valid From</th>

											<td><?php echo e(date('d-m-Y', strtotime(@$gatepassv->valid_to))); ?></td>
										</tr>
										<tr>
											<th>Upto</th>

											<td><?php echo e(date('d-m-Y', strtotime(@$gatepassv->valid_till))); ?></td>
										</tr>

									</thead>

								</table>

							</div>

							<!-- End Details-->
							<?php if(Session::get('clm_role') == 'Executing_agency' && @$gatepassv->status == 'Pending_executing'): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">

											<h5 style="text-white"> Executing Agency </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="approve">
														<label>Approve</label>
														<input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="reject">
														<label>Reject</label>
													</td>

												</tr>



												<tr>
													<th class="col-4">
														Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<textarea name="approver_remarks" rows="4" cols="120"
															class="form-control rec" placeholder="Executing Agency Remarks"
															value="" required></textarea>
													</td>
												</tr>
											</table>
											<center> <button type="submit" class="btn btn-primary" name="update"
													id="store_approvel">Update</button> </center>

										</form>
									</div>
								</div>
							<?php endif; ?>

							<?php if(@$gatepassv->pending_excueting_by != '' && @$gatepassv->pending_eccuting_date != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">
											<?php
												@$shift = UserLogin::where('id', @$gatepassv->pending_excueting_by)->first();
											 ?>
											<h5 style="text-white"> Executing Agency (<?php echo e(ucfirst(@$shift->name)); ?>) </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="text" class="form-control" readonly name="" id=""
															autocomplete="off"
															value="<?php echo e(ucfirst(@$gatepassv->pending_excuting_decision)); ?>">
													</td>

												</tr>


												<tr>
													<th class="col-4">
														Executing Agency Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->pending_excuting_remarks); ?>" autocomplete="off"
															readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Executing Agency Remarks Datetime
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->pending_eccuting_date))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>
											</table>


										</form>
									</div>
								</div>
							<?php endif; ?>
							<?php if(Session::get('clm_role') == 'hr_dept' && @$gatepassv->status == 'Pending_for_hr'): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">

											<h5 style="text-white"> HR Dept </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="approve">
														<label>Approve</label>
														<input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="reject">
														<label>Reject</label>
													</td>

												</tr>



												<tr>
													<th class="col-4">
														Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<textarea name="approver_remarks" rows="4" cols="120"
															class="form-control rec" placeholder="HR Remarks" value=""
															required></textarea>
													</td>
												</tr>
											</table>
											<center> <button type="submit" class="btn btn-primary" name="update"
													id="store_approvel">Update</button> </center>

										</form>
									</div>
								</div>
							<?php endif; ?>

							<!--END HR FORM-->
							<!-- START DETAILS HR-->
							<?php if(@$gatepassv->hr_by != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">
											<?php
												@$shift = UserLogin::where('id', @$gatepassv->hr_by)->first();
											 ?>
											<h5 style="text-white"> HR Dept (<?php echo e(ucfirst(@$shift->name)); ?>) </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="text" class="form-control" readonly name="" id=""
															autocomplete="off" value="<?php echo e(ucfirst(@$gatepassv->hr_decision)); ?>">
													</td>

												</tr>


												<tr>
													<th class="col-4">
														HR Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->hr_remarks); ?>" autocomplete="off" readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														HR Remarks Datetime
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->hr_datetime))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>
											</table>


										</form>
									</div>
								</div>
							<?php endif; ?>

							<?php if(Session::get('clm_role') == 'Safety_dept' && @$gatepassv->status == 'Pending_for_safety' && @$gatepassv->safety_training_by == ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">

											<h5 style="text-white"> Safety Dept </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="approve">
														<label>Approve</label>
														<input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="reject">
														<label>Reject</label>
													</td>

												</tr>



												<tr>
													<th class="col-4">
														Training Date
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">

														<input type="date" class="form-control rec" name="training_date" id=""
															autocomplete="off">
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Training Time
													</th>
													<td>

														<input type="time" class="form-control rec" name="training_time" id=""
															autocomplete="off">
													</td>
												</tr>
											</table>
											<center> <button type="submit" class="btn btn-primary" name="update"
													id="store_approvel">Update</button> </center>

										</form>
									</div>
								</div>

							<?php endif; ?>
							<?php if(@$gatepassv->safety_training_by != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">
											<?php
												@$shift = UserLogin::where('id', @$gatepassv->safety_training_by)->first();
											 ?>
											<h5 style="text-white"> Schedule of Safety Induction Training
												(<?php echo e(ucfirst(@$shift->name)); ?>) </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="text" class="form-control" readonly name="" id=""
															autocomplete="off"
															value="<?php echo e(ucfirst(@$gatepassv->pending_excuting_decision)); ?>">
													</td>

												</tr>


												<tr>
													<th class="col-4">
														Training Date
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(date('d-F-Y', strtotime(@$gatepassv->safety_training_date))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>
												<tr>
													<th class="col-4">
														Training Time
													</th>
													<td>

														<input type="text" class="form-control rec"
															value="<?php echo e(date('H:i:s', strtotime(@$gatepassv->safety_training_time))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Safety Remarks Datetime
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->

														<?php if($gatepassv->safety_datetime2 == ''): ?>

															<input type="text" class="form-control rec"
																value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->safety_datetime))); ?>"
																autocomplete="off" readonly>
														<?php else: ?>
															<input type="text" class="form-control rec"
																value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->safety_datetime2))); ?>"
																autocomplete="off" readonly>
														<?php endif; ?>
													</td>
												</tr>
											</table>


										</form>
									</div>
								</div>
							<?php endif; ?>

							<?php if(@$gatepassv->safety_by != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">
											<?php
												@$shift = UserLogin::where('id', @$gatepassv->safety_by)->first();
											 ?>
											<h5 style="text-white"> Safety Dept (<?php echo e(ucfirst(@$shift->name)); ?>) </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th class="col-4">
														Safety Induction Number
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->safety_pass_no); ?>" autocomplete="off" readonly>
													</td>
												</tr>

												<tr>
													<th>
														Decision
													</th>
													<td><input type="text" class="form-control" readonly name="" id=""
															autocomplete="off"
															value="<?php echo e(ucfirst(@$gatepassv->safety_decision)); ?>">
													</td>

												</tr>


												<tr>
													<th class="col-4">
														Safety Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->safety_remarks); ?>" autocomplete="off" readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Safety Remarks Datetime
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->safety_datetime))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>
											</table>


										</form>
									</div>
								</div>
							<?php endif; ?>

							<!--END DETAILS HR-->
							<!--Safety FORM START-->

							<?php if(Session::get('clm_role') == 'Safety_dept' && @$gatepassv->status == 'Pending_for_safety' && @$gatepassv->safety_training_by != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">

											<h5 style="text-white"> Safety Dept </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="approve">
														<label>Approve</label>
														<input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="reject">
														<label>Reject</label>
													</td>

												</tr>



												<tr>
													<th class="col-4">
														Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<textarea name="approver_remarks" rows="4" cols="120"
															class="form-control rec" placeholder="Safety Dept Remarks" value=""
															required></textarea>
													</td>
												</tr>
											</table>
											<center> <button type="submit" class="btn btn-primary" name="update"
													id="store_approvel">Update</button> </center>

										</form>
									</div>
								</div>

							<?php endif; ?>
							<!--safety FORM END-->





							<!--safety Details Start-->

							<!-- Safety Details END-->
							<?php if(@$gatepassv->shift_incharge_by != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">
											<?php
												@$shift = UserLogin::where('id', @$gatepassv->shift_incharge_by)->first();
											 ?>
											<h5 style="text-white"> Shift In Charge (<?php echo e(ucfirst(@$shift->name)); ?>) </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="text" class="form-control" readonly name="" id=""
															autocomplete="off"
															value="<?php echo e(ucfirst(@$gatepassv->shift_incharge_decision)); ?>">
													</td>

												</tr>

												<tr>
													<th class="col-4">
														Safety Training Number
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->safety_pass_no); ?>" autocomplete="off" readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Shift Incharge Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->shift_incharge_remarks); ?>" autocomplete="off"
															readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Shift Incharge Remarks Datetime
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->shift_incharge_datetime))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>
											</table>


										</form>
									</div>
								</div>
							<?php endif; ?>
							<?php if(Session::get('clm_role') == 'Shift_incharge' && @$gatepassv->status == 'Pending_for_shift_incharge'): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">

											<h5 style="text-white"> Shift In Charge </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="approve">
														<label>Approve</label>
														<input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="reject">
														<label>Reject</label>
													</td>
													<input type="hidden" class="form-control rec" name="id" id=""
														autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
												</tr>

												<tr class="col-4" style="display: none;">
													<th>
														Safety Training Number
													</th>
													<td>

														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec" name="safety_no" id=""
															autocomplete="off">
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<textarea name="approver_remarks" rows="4" cols="120"
															class="form-control rec" placeholder="Shift Incharge Remarks"
															value="" required></textarea>
													</td>
												</tr>
											</table>
											<center> <button type="submit" class="btn btn-primary" name="update"
													id="store_approvel">Update</button> </center>

										</form>
									</div>
								</div>
							<?php endif; ?>
							<!-- Plant Head Form Start-->
							<?php if(Session::get('clm_role') == 'plant_head' && @$gatepassv->status == 'Pending_for_plant_head'): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">

											<h5 style="text-white"> Plant Head </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="approve">
														<label>Approve</label>
														<input type="radio" class="btn-check" name="approver_decision" id=""
															autocomplete="off" value="reject">
														<label>Reject</label>
													</td>

												</tr>



												<tr>
													<th class="col-4">
														Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<textarea name="approver_remarks" rows="4" cols="120"
															class="form-control rec" placeholder="Plant Head Remarks" value=""
															required></textarea>
													</td>
												</tr>
											</table>
											<center> <button type="submit" class="btn btn-primary" name="update"
													id="store_approvel">Update</button> </center>
										</form>
									</div>
								</div>
							<?php endif; ?>
							<!--Plant Head Form End-->

							<!-- Plant Head Details Start-->
							<?php if(@$gatepassv->plant_head_by != ''): ?>
								<div class="card card-primary">
									<div class="card card-primary card text-white bg-primary mb-1">


										<div class="card-header" style="height:50px">
											<?php
												@$shift = UserLogin::where('id', @$gatepassv->plant_head_by)->first();
											 ?>
											<h5 style="text-white"> Plant Head (<?php echo e(ucfirst(@$shift->name)); ?>) </h5>

										</div>
									</div>
									<div class="card-body">
										<form class="form-horizontal" role="form" method="POST" autocomplete="off" id=""
											action="<?php echo e(route('admin.gatepassv.update1')); ?>">
											<?php echo csrf_field(); ?>
											<table class="table table-bordered table-hover table-condensed">

												<tr>
													<th>
														Decision
													</th>
													<td><input type="text" class="form-control" readonly name="" id=""
															autocomplete="off"
															value="<?php echo e(ucfirst(@$gatepassv->plant_head_decision)); ?>">
													</td>

												</tr>


												<tr>
													<th class="col-4">
														Plant Head Remarks
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(@$gatepassv->plant_head_remarks); ?>" autocomplete="off"
															readonly>
													</td>
												</tr>

												<tr>
													<th class="col-4">
														Plant Head Remarks Datetime
													</th>
													<td>
														<input type="hidden" class="form-control rec" name="id" id=""
															autocomplete="off" value="<?php echo e($gatepassv->id); ?>">
														<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
														<input type="text" class="form-control rec"
															value="<?php echo e(date('d-F-Y H:i:s A', strtotime(@$gatepassv->plant_head_datetime))); ?>"
															autocomplete="off" readonly>
													</td>
												</tr>
											</table>


										</form>
									</div>
								</div>
							<?php endif; ?>
							<!--Plant Head Details End-->
						</div>



					</div>





				</div>
			</div>
		</div>
	</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/edit_clms_new.blade.php ENDPATH**/ ?>