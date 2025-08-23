<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('visitor_gate_pass')->where('id', $id)->first();
//echo $id;
//exit;
@$department_p = Department::where('id', @$gatepassv->department)->first();
@$division_p = Division::where('id', @$gatepassv->division_id)->first();
@$approver = UserLogin::where('id', @$gatepassv->approver)->first();
@$approver_security = UserLogin::where('id', @$gatepassv->security_print_id)->first();

?>

@extends('admin.app')
@section('breadcrumbs')
	<!-- <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
																<li class="breadcrumb-item"><a href="{{route('admin.list_permit.index')}}"></a></li>-->
@endsection
@section('content')
	<div class="form-group-row">
		<div class="col-sm-12" style="text-align:center;">
			@if (session()->has('message'))
				<div class="alert alert-success">
					{{ session('message')}}
				</div>
			@endif
		</div>
	</div>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-0 mb-1">
		<h1 class="h3">{{@$gatepassv->full_sl}}</h1>

	</div>
	<div class="card card-primary">

		<div class="card-body">
			<table class="table table-bordered ">


				<tr>
					<th>Visitor Name</th>
					<td class="col-7">{{ @$gatepassv->visitor_name}}</td>
				</tr>
				<tr>
					<th>Visitor Mobile No</th>
					<td>{{ @$gatepassv->visitor_mobile_no}} </td>
				</tr>
				<tr>
					<th>Visitor Company</th>
					<td>{{ @$gatepassv->visitor_company}}</td>
				</tr>

				<tr>
					<th>Visitor Email</th>
					<td>{{ @$gatepassv->visitor_email}}</td>
				</tr>
				<tr>
					<th>Visitor Emergency Contact No</th>
					<td>{{@$gatepassv->visitor_emergency_contact_no}} </td>
				</tr>
				<tr>
					<th>Proof of Identity</th>
					<td>{{@$gatepassv->id_proof_type ?? ''}}</td>
				</tr>
				<tr>
					<th>Unique Identification Number</th>
					<td>{{@$gatepassv->id_number ?? ''}}</td>
				</tr>
				<tr>
					<th>Division</th>
					<td> {{@$division_p->name}}</td>
				</tr>

				<tr>
					<th>Department</th>
					<td>{{@$department_p->department_name}}</td>
				</tr>
				<tr>
					<th>To Meet</th>
					<td>{{@$approver->name}}</td>
				</tr>
				<tr>
					<th>Days</th>
					<td>{{@$gatepassv->days}}</td>
				</tr>
				<tr>
					<th>From Date</th>
					<td>{{date('d-m-Y', strtotime(@$gatepassv->from_date)) }}</td>
				</tr>
				<tr>
					<th>To Date</th>
					<td>{{date('d-m-Y', strtotime(@$gatepassv->to_date)) }}</td>
				</tr>
				<tr>
					<th>From Time </th>
					<td>{{date('h:i:s A', strtotime(@$gatepassv->from_time)) }}</td>
				</tr>
				<tr>
					<th>To Time </th>
					<td>{{date('h:i:s A', strtotime(@$gatepassv->to_time))}}</td>
				</tr>
				<tr>
					<th>Any material/equipment/laptop carried along? </th>
					<td>{{ @$gatepassv->any_material}}</td>
				</tr>
				<tr>
					<th>Is Visitor Coming by any vehicle?
					</th>
					<td>{{@$gatepassv->visitor_any_vehicle}}</td>
				</tr>
				@if(@$gatepassv->visitor_any_vehicle != 'No')
					<tr>
						<th>Driving Mode
						</th>
						<td>{{@$gatepassv->driving_mode}}</td>
					</tr>
					@if(@$gatepassv->driving_mode != 'self')
						<tr>
							<th>Driver Name
							</th>
							<td>{{@$gatepassv->driver_name}}</td>
						</tr>
					@endif
					<tr>
						<th>Vehicle No</th>
						<td>{{@$gatepassv->vehicle_no}}</td>
					</tr>
					<tr>
						<th>Driving licence No

						</th>
						<td>{{@$gatepassv->dl_no}}</td>
				@endif
				</tr>
				<tr>
					<th>Photo </th>



					<td>


						<a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_photo}}" target="_blank">
							<button class="btn"><i class="fa fa-download"></i> View File</button>
						</a>
					</td>
				</tr>

				<?php
	$safety_question = DB::table('vms_safety_answer_id')->where('vms_safety_question_id', '4')

		->orderBy('id', 'ASC')->get();
	//dd($safety_question);
															 ?>


				@if(@$gatepassv->any_material != 'No')
							<div class="form-group row">
								<label for="form-control-label" class="col-sm-2 col-form-label"></label>
								<div class="col-sm-9">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th>Material Name</th>
												<th>Material Identification No</th>
												<th>Returnable/Non Returnable</th>
												<th>Purpose of Material Entry</th>

											</tr>
										</thead>

										<tbody>
											<?php
					$test = unserialize($gatepassv->material_name);
					$test_id = unserialize($gatepassv->material_identification_no);
					$returnable = unserialize($gatepassv->returnable);
					$propose_of_entry = unserialize($gatepassv->propose_of_entry);
					$count = count($test);

					for ($i = 0; $i < $count; $i++) {
																																																														?>

											<tr class="gatepass" id="gatepass">
												<td><input type="text" class="form-control" name="" value="<?=$test[$i];?>" readonly>
												</td>
												<td><input type="text" class="form-control" name="" value="<?=$test_id[$i];?>" readonly>
												</td>
												<td><input type="text" class="form-control" name="" value="<?=$returnable[$i];?>"
														readonly></td>
												<td><input type="text" class="form-control" name="" value="<?=$propose_of_entry[$i];?>"
														readonly></td>
											</tr>
											<?php
					}
																																																													?>
										</tbody>
									</table>
								</div>

							</div>
				@endif


			</table>




		</div>

		@if(@$gatepassv->approver_decision == 'approve')
			<div class="card-body">
				<table class="table table-bordered ">
					<tr>
						<th style="background-color: #4DA5DF ;" colspan="10">
							Approver Details
						</th>
					</tr>
					<tr>
						<th>Approver Name</th>
						<td class="col-7"> {{@$approver->name}}</td>
					</tr>
					<tr>
						<th>Approver Remarks</th>
						<td class="col-7">{{ @$gatepassv->approver_remarks}}</td>
					</tr>
					<tr>
						<th>Approved Date</th>
						<td class="col-7">{{date('d-m-Y ', strtotime(@$gatepassv->approver_datetime))}}</td>
					</tr>
			</div>

		@elseif(@$gatepassv->approver_decision == 'reject')

			<div class="card-body">
				<table class="table table-bordered ">
					<tr>
						<th style="background-color: #4DA5DF ;" colspan="10">
							Approver Details
						</th>
					</tr>
					<tr>
						<th>Rejected By</th>
						<td class="col-7"> {{@$approver->name}}</td>
					</tr>
					<tr>
						<th> Remarks</th>
						<td class="col-7">{{ @$gatepassv->approver_remarks}}</td>
					</tr>
					<tr>
						<th>Rejected Date</th>
						<td class="col-7">{{date('d-m-Y ', strtotime(@$gatepassv->approver_datetime))}}</td>
					</tr>
			</div>


		@endif

		@if(@$gatepassv->status == 'Completed')
			<div class="card-body">


				<table class="table table-bordered table-hover table-condensed">


					<tr>
						<th style="background-color: #4DA5DF ;" colspan="10">
							Returned
						</th>
					</tr>
					<form class="form-horizontal" method="POST" autocomplete="off"
						action="{{route('admin.gatepassv.update_security')}}">
						@csrf

						<tr>
							<th>Returned By</th>
							<td class="col-7">{{$approver_security->name}} </td>

						</tr>

						<tr>
							<th>Returned Remarks</th>
							<td class="col-7"> {{$gatepassv->security_print_remarks}}</td>

						</tr>
						<tr>
							<th>Returned Datetime</th>
							<td class="col-7"> {{date('d-m-Y H:i:s', strtotime(@$gatepassv->security_print_datetime))}} </td>

						</tr>
					</form>
				</table>
			</div>

		@endif

		@if(@$gatepassv->status == 'Pending_to_approve' && @$gatepassv->approver == Session::get('user_idSession'))
			<br>
			<div class="card-body">
				<div class="card card-primary card text-white bg-primary mb-0">

					<h5 class="card-title">Approver Decision </h5>
				</div>

				<form class="form-horizontal" method="POST" autocomplete="off" action="{{route('admin.gatepassv.update_vms')}}">
					@csrf

					<table class="table table-bordered table-hover table-condensed">
						<tr>
							<th>
								Decision
							</th>
							<td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"
									value="approve">
								<label>Approve</label>
								<input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"
									value="reject">
								<label>Reject</label>
							</td>

						</tr>

						@if(@$gatepassv->days != 'Single')
							<tr>
								<th>
									Edit From Date
								</th>
								<td><input type="date" class="form-control" name="edit_from_date" id="" autocomplete="off" required>
								</td>

							</tr>
							<tr>
								<th>
									Edit To Date
								</th>
								<td><input type="date" class="form-control" name="edit_to_date" id="" autocomplete="off" required>
								</td>

							</tr>
						@endif
						<tr>
							<th class="col-4">
								Remarks
							</th>
							<td>
								<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"
									value="{{$gatepassv->id}}">
								<input type="hidden" class="form-control rec" name="from_date" id="" autocomplete="off"
									value="{{$gatepassv->from_date}}">
								<input type="hidden" class="form-control rec" name="to_date" id="" autocomplete="off"
									value="{{$gatepassv->to_date}}">
								<input type="hidden" class="form-control rec" name="days" id="" autocomplete="off"
									value="{{$gatepassv->days}}">
								<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
								<textarea name="approver_remarks" rows="4" cols="120" class="form-control rec"
									placeholder="Approver Remarks" value="" required></textarea>
							</td>
						</tr>
					</table>
					<center> <button type="submit" class="btn btn-primary">Submit</button> </center>
				</form>
			</div>
		@endif


		@if((Session::get('vms_role') == 'Security' && @$gatepassv->status == 'issued') || (Session::get('vms_role') == 'Approver' && @$gatepassv->status == 'issued'))
			<div class="card-body">


				<table class="table table-bordered table-hover table-condensed2">


					<tr>
						<th style="background-color: #4DA5DF ;" colspan="10">
							Security/Approver Returned
						</th>
					</tr>
					<form class="form-horizontal" method="POST" autocomplete="off"
						action="{{route('admin.gatepassv.update_security')}}">
						@csrf
						<tr>
							<th>Security/Approver Remarks</th>
							<input type="hidden" class="form-control rec" name="ida" id="" autocomplete="off"
								value="{{$gatepassv->id}}">
							<td><textarea name="security_remarks" rows="4" cols="120" class="form-control rec"
									placeholder="Security/Approver Remarks" required></textarea> </td>

						</tr>

				</table>

				<center> <button type="submit" class="btn btn-primary">Return</button> </center>
				</form>
			</div>
		@endif
	</div>
	</div>
@endsection