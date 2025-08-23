<?php 
use App\Department;
use App\UserLogin;
use App\Division;

$gatepassv = DB::table('Clms_gatepass')->where('id',$id)->first();
//echo $id;
//exit;
@$department_p = Department::where('id',@$gatepassv->department)->first();
@$division_p = Division::where('id',@$gatepassv->division_id)->first();
@$approver = UserLogin::where('id',@$gatepassv->created_by)->first();
@$approver_security = UserLogin::where('id',@$gatepassv->security_print_id)->first();
//@$work = DB::table('work_order')->where('id',@$gatepassv->work_order_no)->first();
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
<div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
				<div class="flex justify-content-between">
                    <div class="card card-primary">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-1 pb-1 mb-0">
        <h1 class="h3" >{{@$gatepassv->full_sl}}</h1>
       
    </div>
                       <div class="card-body">
                            <table id="example1" class="table table-bordered ">
                                <thead>
										<tr>
                                        <th>Vendor Name</th>
                                        <td class="col-7"> {{@$approver->name}}</td>
										</tr>
										<tr>
                                        <th>Work Order No</th>
                                        <td>{{@$gatepassv->work_order_no}}</td>
										</tr>
										<tr>
                                        <th>Work Order Validity</th>
                                        <td>{{date('d-m-Y', strtotime(@$gatepassv->work_order_validity))}}</td>
										</tr>
										<tr>
                                        <th>Name</th>
                                        <td>{{@$gatepassv->name}}</td>
										</tr>
									
										<tr>
                                        <th>Son/Daughter/Wife of</th>
                                        <td>{{@$gatepassv->son_of}}</td>
										</tr>
										<tr>
                                        <th>Gender</th>
                                        <td>{{ucfirst(@$gatepassv->gender)}}</td>
										</tr>
										
										
										<tr>
                                        <th>Date of Birth</th>

                                        <td>	{{date('d-F-Y', strtotime(@$gatepassv->date_of_birth))}}</td>
										</tr>
										<tr>
                                        <th>Mobile Number</th>

                                        <td> {{@$gatepassv->mobile_no}}</td>
										</tr>
										<tr>
                                        <th>Identity Proof</th>

                                        <td> {{@$gatepassv->identity_proof}}</td>
										</tr>
										<tr>
                                        <th>Identity Proof No</th>

                                        <td>  {{@$gatepassv->unique_id_no}}</td>
										</tr>
										<tr>

											<!--<td>  <a href="https://docs.google.com/gview?url=aiplbaradwari.ddns.net:5002/jamipol_vms/public/documents/clm_pics/{{$gatepassv->upload_id_proof}}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> Download File</button> </a></td>-->
                                        <th>Identity Proof Photo Front </th>
                           
                                        <td>  <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_id_proof}}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> View File</button> </a></td>
										</tr>
										<tr>
                                        <th>Identity Proof Photo Back </th>
                           
                                        <td>  <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_id_proof_back}}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> View File</button> </a></td>
										</tr>
										<tr>
                                        <th>Education</th>

                                        <td> {{@$gatepassv->education}}</td>
										</tr>
										@if($gatepassv->education != "Below-Matric")
										<tr>
                                        <th>Board Name</th>

                                        <td> {{@$gatepassv->board_name}}</td>
										</tr>
										<tr>
                                        <th>Result</th>
                           
                                        <td>  <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_result}}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> View File</button> </a></td>
										</tr>
										@endif
										<tr>
                                        <th>UAN / PF</th>

                                        <td> {{@$gatepassv->uan_no}}</td>
										</tr>

										<tr>
                                        <th>UAN Document</th>

                                        <td> <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_pf_copy}}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> View File</button> </a></td>
										</tr>
										<tr>
                                        <th>ESIC</th>

                                        <td>  {{@$gatepassv->esic}}</td>
										</tr>
										<tr>
                                        <th>ESIC Document</th>

                                        <td> <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->esic_document}}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> View File</button> </a></td>
										</tr>
									         <tr>
                                        <th>Blood Group</th>

                                        <td> {{ucfirst(@$gatepassv->blood_group)}}</td>
										</tr>
										  <tr>
                                        <th>Medical Examination Date</th>

                                        <td>{{date('d-F-Y', strtotime(@$gatepassv->medical_examination_date))}}</td>
										</tr>
										<tr>
                                        <th>Medical Fitness Copy</th>

                                        <td>  <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_fittenss_copy}}" target="_blank">
     <button class="btn"><i class="fa fa-download"></i> View File</button>
 </a></td>
										</tr>
									@if($gatepassv->police_verification_copy !='')	
										<tr>
                                        <th>Police Verification Date</th>

                                        <td>{{date('d-F-Y', strtotime(@$gatepassv->police_verification_date))}}</td>
										</tr>
										<tr>
                                        <th>Police Verification Copy</th>

                                        <td>  <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->police_verification_copy}}" target="_blank">
     <button class="btn"><i class="fa fa-download"></i> View File</button>
 </a></td>
										</tr>
										
										@endif
										@if($gatepassv->passport_no !='')	
<tr>
                                        <th>Passport No</th>

                                        <td>{{$gatepassv->passport_no}}</td>
										</tr>

<tr>
                                        <th>Passport Validity</th>

                                        <td>{{date('d-F-Y', strtotime(@$gatepassv->passport_validity))}}</td>
										</tr>
										<tr>
                                        <th>Passport Copy</th>
                                        <td>  <a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->passport_copy}}" target="_blank">
     <button class="btn"><i class="fa fa-download"></i> View File</button>
 </a></td>
										</tr>
@endif

										<tr>
                                        <th>Photo </th>



                                        <td> 


                                        	<a href="https://wps.jamipol.com/documents/clm_pics/{{$gatepassv->upload_photo}}" target="_blank">
     <button class="btn"><i class="fa fa-download"></i> View File</button>
 </a></td>
										</tr>
										<tr>
                                        <th>Valid From</th>

                                        <td>{{date('d-m-Y', strtotime(@$gatepassv->valid_to))}}</td>
										</tr>
										<tr>
                                        <th>Upto</th>

                                        <td>{{date('d-m-Y', strtotime(@$gatepassv->valid_till))}}</td>
										</tr>
									               
                                </thead>
								 
                            </table>
                    
                        </div>

<!-- End Details-->
@if(Session::get('clm_role') =='Executing_agency' && @$gatepassv->status=='Pending_executing')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white"> Executing Agency </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
						
									</tr>

									

									<tr>
									<th class="col-4">
                                               Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										 <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec" placeholder="Executing Agency Remarks"  value="" required ></textarea>   
										</td>
                                         </tr>
							</table>
                           <center>  <button type="submit"  class="btn btn-primary"  name="update" id="store_approvel">Update</button> </center>
                
                        </form>
                        </div>
	</div>
	@endif

@if(@$gatepassv->pending_excueting_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$gatepassv->pending_excueting_by)->first();
 @endphp
                           <h5 style="text-white"> Executing Agency ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$gatepassv->pending_excuting_decision)}}">
                           </td>
						
									</tr>

									
									<tr>
									<th class="col-4">
                                      Executing Agency Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{@$gatepassv->pending_excuting_remarks}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                        Executing Agency Remarks Datetime
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$gatepassv->pending_eccuting_date))}}" autocomplete="off"  readonly>
										</td>
                                         </tr>
							</table>
                           
                
                        </form>
                        </div>
	</div>
	@endif


	@if(Session::get('clm_role') =='Safety_dept' && @$gatepassv->status=='Pending_for_safety' && @$gatepassv->safety_training_by=='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white"> Safety Dept  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
						
									</tr>

									

									<tr>
									<th class="col-4">
                                               Training Date
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									
										  <input type="date" class="form-control rec" name="training_date" id="" autocomplete="off"  > 
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                               Training Time
                                                </th>
												<td>
										
										  <input type="time" class="form-control rec" name="training_time" id="" autocomplete="off"  > 
										</td>
                                         </tr>
							</table>
                           <center>  <button type="submit"  class="btn btn-primary"  name="update" id="store_approvel">Update</button> </center>
                
                        </form>
                        </div>
	</div>

@endif
@if(@$gatepassv->safety_training_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$gatepassv->safety_training_by)->first();
 @endphp
                           <h5 style="text-white"> Schedule of Safety Induction Training   ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$gatepassv->pending_excuting_decision)}}">
                           </td>
						
									</tr>

									
									<tr>
									<th class="col-4">
                                      Training Date
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec"  value="{{date('d-F-Y', strtotime(@$gatepassv->safety_training_date))}}"  autocomplete="off"  readonly>
										</td>
                                         </tr>
<tr>
									<th class="col-4">
                                      Training Time
                                                </th>
												<td>
										
										  	<input type="text" class="form-control rec"  value="{{date('H:i:s', strtotime(@$gatepassv->safety_training_time))}}"  autocomplete="off"  readonly>
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                        Safety Remarks Datetime
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$gatepassv->pending_eccuting_date))}}" autocomplete="off"  readonly>
										</td>
                                         </tr>
							</table>
                           
                
                        </form>
                        </div>
	</div>
	@endif
<!-- START HR DEPT FORM-->
@if(@$gatepassv->safety_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$gatepassv->safety_by)->first();
 @endphp
                           <h5 style="text-white"> Safety Dept ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
<tr>
									<th class="col-4">
                                      Safety Induction Number
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{@$gatepassv->safety_pass_no}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$gatepassv->safety_decision)}}">
                           </td>
						
									</tr>

									
									<tr>
									<th class="col-4">
                                      Safety Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{@$gatepassv->safety_remarks}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                        Safety Remarks Datetime
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$gatepassv->safety_datetime))}}" autocomplete="off"  readonly>
										</td>
                                         </tr>
							</table>
                           
                
                        </form>
                        </div>
	</div>
	@endif
@if(Session::get('clm_role') =='hr_dept' && @$gatepassv->status=='Pending_for_hr')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white"> HR Dept  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
						
									</tr>

									

									<tr>
									<th class="col-4">
                                               Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										 <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec" placeholder="HR Remarks"  value="" required ></textarea>   
										</td>
                                         </tr>
							</table>
                           <center>  <button type="submit"  class="btn btn-primary"  name="update" id="store_approvel">Update</button> </center>
                
                        </form>
                        </div>
	</div>
	@endif

<!--END HR FORM-->
<!-- START DETAILS HR-->
@if(@$gatepassv->hr_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$gatepassv->hr_by)->first();
 @endphp
                           <h5 style="text-white"> HR Dept ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$gatepassv->hr_decision)}}">
                           </td>
						
									</tr>

									
									<tr>
									<th class="col-4">
                                        HR Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{@$gatepassv->hr_remarks}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                          HR Remarks Datetime
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$gatepassv->hr_datetime))}}" autocomplete="off"  readonly>
										</td>
                                         </tr>
							</table>
                           
                
                        </form>
                        </div>
	</div>
@endif
<!--END DETAILS HR-->
<!--Safety FORM START-->

@if(Session::get('clm_role') =='Safety_dept' && @$gatepassv->status=='Pending_for_safety' && @$gatepassv->safety_training_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white"> Safety Dept  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
						
									</tr>

									

									<tr>
									<th class="col-4">
                                               Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										 <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec" placeholder="Safety Dept Remarks"  value="" required ></textarea>   
										</td>
                                         </tr>
							</table>
                           <center>  <button type="submit"  class="btn btn-primary"  name="update" id="store_approvel">Update</button> </center>
                
                        </form>
                        </div>
	</div>

@endif
<!--safety FORM END-->





<!--safety Details Start-->

	<!-- Safety Details END-->
@if(@$gatepassv->shift_incharge_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$gatepassv->shift_incharge_by)->first();
 @endphp
                           <h5 style="text-white"> Shift In Charge ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$gatepassv->shift_incharge_decision)}}">
                           </td>
						
									</tr>

									<tr>
									<th class="col-4">
                                               Safety Training Number
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										   	<input type="text" class="form-control rec" value="{{@$gatepassv->safety_pass_no}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

									<tr>
									<th class="col-4">
                                          Shift Incharge  Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{@$gatepassv->shift_incharge_remarks}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                          Shift Incharge  Remarks Datetime
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$gatepassv->shift_incharge_datetime))}}" autocomplete="off"  readonly>
										</td>
                                         </tr>
							</table>
                           
                
                        </form>
                        </div>
	</div>
	@endif
	@if(Session::get('clm_role') =='Shift_incharge' && @$gatepassv->status=='Pending_for_shift_incharge')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white"> Shift In Charge  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
						<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									</tr>

									<tr class="col-4" style="display: none;">
									<th >
                                               Safety Training Number
                                                </th>
												<td>
										
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										   	<input type="text" class="form-control rec" name="safety_no" id="" autocomplete="off"  >
										</td>
                                         </tr>

									<tr>
									<th class="col-4">
                                               Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										 <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec" placeholder="Shift Incharge Remarks"  value="" required ></textarea>   
										</td>
                                         </tr>
							</table>
                           <center>  <button type="submit"  class="btn btn-primary"  name="update" id="store_approvel">Update</button> </center>
                
                        </form>
                        </div>
	</div>
@endif
<!-- Plant Head Form Start-->
@if(Session::get('clm_role') =='plant_head' && @$gatepassv->status=='Pending_for_plant_head')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              
                           <h5 style="text-white"> Plant Head </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off"  value="approve">
                           <label>Approve</label>
                           <input type="radio" class="btn-check" name="approver_decision" id="" autocomplete="off" value="reject">
                           <label >Reject</label></td>
						
									</tr>

									

									<tr>
									<th class="col-4">
                                               Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										 <textarea name="approver_remarks" rows="4" cols="120" class="form-control rec" placeholder="Plant Head Remarks"  value="" required ></textarea>   
										</td>
                                         </tr>
							</table>
                           <center>  <button type="submit"  class="btn btn-primary"  name="update" id="store_approvel">Update</button> </center>
                        </form>
                        </div>
	</div>
	@endif
<!--Plant Head Form End-->

<!-- Plant Head Details Start-->
@if(@$gatepassv->plant_head_by!='')
<div class="card card-primary">
						<div class="card card-primary card text-white bg-primary mb-1">
                        
                           
                              <div class="card-header" style="height:50px">
                              @php
@$shift = UserLogin::where('id',@$gatepassv->plant_head_by)->first();
 @endphp
                           <h5 style="text-white"> Plant Head ({{ucfirst(@$shift->name)}})  </h5>
                            
                             </div>
                          </div>
                            <div class="card-body">
                            <form class="form-horizontal" role="form" method="POST" autocomplete="off" id="" action="{{route('admin.gatepassv.update1')}}">
                            	 @csrf
                             <table class="table table-bordered table-hover table-condensed">
                             	
										  <tr>
											<th>
											Decision
											</th>
										 <td><input type="text" class="form-control" readonly name="" id="" autocomplete="off"  value="{{ucfirst(@$gatepassv->plant_head_decision)}}">
                           </td>
						
									</tr>

									
									<tr>
									<th class="col-4">
                                    Plant Head Remarks
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{@$gatepassv->plant_head_remarks}}" autocomplete="off"  readonly>
										</td>
                                         </tr>

                                         <tr>
									<th class="col-4">
                                       Plant Head Remarks Datetime
                                                </th>
												<td>
										<input type="hidden" class="form-control rec" name="id" id="" autocomplete="off"  value="{{$gatepassv->id}}">
									<!--	<input type="text" class="form-control"  name="remarks" id="remarks1" placeholder="Dept Head Remarks" required>-->
										  	<input type="text" class="form-control rec" value="{{date('d-F-Y H:i:s A', strtotime(@$gatepassv->plant_head_datetime))}}" autocomplete="off"  readonly>
										</td>
                                         </tr>
							</table>
                           
                
                        </form>
                        </div>
	</div>
@endif
<!--Plant Head Details End-->
					</div>
						
						
					
						 </div>
						
						
						
						
						
					</div>	
                    </div>
                </div>
            </div>


@endsection