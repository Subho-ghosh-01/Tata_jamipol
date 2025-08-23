<?php 
use App\Department;
use App\UserLogin;
use App\ChangeRequest;
?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.approve.index')}}">List Of Visitor's GatePass</a></li>
@endsection                        
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List of Visitor's GatePass </h1>
</div>
@if(Session::get('vms_role') == 'Approver')
 <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> Pending For Approval</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Approved/Rejected</a>
        </li>
        
 </ul>
 @endif
 @if(Session::get('vms_role') == 'Security')
 <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab_sec" data-toggle="tab" href="#home_security" role="tab" aria-controls="home_security" aria-selected="true">Issued GatePass</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab_sec" data-toggle="tab" href="#profile_security" role="tab" aria-controls="profile_security" aria-selected="false">Returned GatePass</a>
        </li>
        
 </ul>
 @endif
@if(Session::get('vms_role') == 'Requester')
<ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home_requester" role="tab" aria-controls="home" aria-selected="true"> Pending For Approval</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="home-tab" data-toggle="tab" href="#home_requester_returned" role="tab" aria-controls="home" aria-selected="true">Approved/Rejected</a>
        </li>
        
 </ul>
@endif
 
<form action="{{ route('admin.approve.index')}}" method="POST" enctype="multipart/form-data">
	@if(Session::get('vms_role') == 'Approver')
<div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss as $gatepass) 
                            <tr>
                                <td>{{$count ++}}</td>
                               
                                  <td>{{$gatepass->full_sl}}</td>
                                  <td>{{$gatepass->visitor_name}}</td>
                                  <td>{{$gatepass->visitor_mobile_no}}</td>
                                  <td>{{$gatepass->visitor_company}}</td>
                                  <td>{{$gatepass->from_date}}</td>
                                  <td>{{$gatepass->to_date}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepass->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepass->to_time))}}</td>
                                  <!--<td>{{$gatepass->status}}</td>-->
								  
								  
								 <td> @if($gatepass->status == "Pending_to_approve")
                                            {{"Pending To Approve"}}
                                        @elseif($gatepass->status == "issued")
                                            {{"Issued"}}
								  @elseif($gatepass->status == "Rejected")
                                            {{"Rejected"}}
								  @elseif($gatepass->status == "Completed")
                                            {{"Completed"}}
											@endif 
								  </td>
								  
                                <td>

                                <a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">Details</a>
                               @if($gatepass->status=='Approved' ) 
							   <a class="btn btn-info btn-sm" href="{{route('admin.printg.printg',\Crypt::encrypt($gatepass->id))}}" title="Edit">Print</a>
						    @endif 
                                <!--<a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',$gatepass->id)}}" title="Edit">Details</a>-->
                                <!--<a class="btn btn-success btn-sm" onclick="deleteRecord('{{$gatepass->id}}')">Approve</a>
                                <a class="btn btn-danger btn-sm" onclick="deleteRecord('{{$gatepass->id}}')">Reject</a>-->
                                   
                                
                                </td>
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit1"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepassss->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepassss as $gatepasst) 
                            <tr>
                                <td>{{$count ++}}</td>
                               
                                  <td>{{$gatepasst->full_sl}}</td>
                                  <td>{{$gatepasst->visitor_name}}</td>
                                  <td>{{$gatepasst->visitor_mobile_no}}</td>
                                  <td>{{$gatepasst->visitor_company}}</td>
                                  <td>{{$gatepasst->from_date}}</td>
                                    <td>{{$gatepasst->to_date}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepasst->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepasst->to_time))}}</td>
                                
								  <td> @if($gatepasst->status == "Pending_to_approve")
                                            {{"Pending To Approve"}}
                                        @elseif($gatepasst->status == "issued")
                                            {{"Issued"}}
											@elseif($gatepasst->status == "Rejected")
                                            {{"Rejected"}}
											@elseif($gatepasst->status == "Completed")
                                            {{"Completed"}}
											@endif 
											</td>
                                <td>

                                <a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($gatepasst->id))}}" title="Edit">Details</a>
                               @if($gatepasst->status=='Approved') 
							   <a class="btn btn-info btn-sm" href="{{route('admin.printg.printg',\Crypt::encrypt($gatepasst->id))}}" title="Edit">Print</a>
						    @endif 
                              
                                   
                                
                                </td>
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>

    </div>
@endif
	@if(Session::get('vms_role') == 'Security')
	<div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home_security" role="tabpanel" aria-labelledby="home-tab_sec">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                             <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss_sec->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss_sec as $gatepass_secc) 
                            <tr>
                                <td>{{$count ++}}</td>
                               
                                  <td>{{$gatepass_secc->full_sl}}</td>
                                  <td>{{$gatepass_secc->visitor_name}}</td>
                                  <td>{{$gatepass_secc->visitor_mobile_no}}</td>
                                  <td>{{$gatepass_secc->visitor_company}}</td>
                                  <td>{{$gatepass_secc->from_date}}</td>
                                  <td>{{$gatepass_secc->to_date}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepass_secc->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepass_secc->to_time))}}</td>
                                  <!--<td>{{$gatepass_secc->status}}</td>-->
								  <td>
								  @if($gatepass_secc->status == "Pending_to_approve")
                                  {{"Pending To Approve"}}
								@elseif($gatepass_secc->status == "issued")
                                   {{"Issued"}}
								 @endif
										</td>
                                <td>

                                <a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($gatepass_secc->id))}}" title="Edit">Details</a>
                               @if($gatepass_secc->status=='issued') 
							   <a class="btn btn-info btn-sm" href="{{route('admin.printg.printg',\Crypt::encrypt($gatepass_secc->id))}}" title="Edit">Print</a>
						    @endif 
                              
                                </td>
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>
        <div class="tab-pane fade" id="profile_security" role="tabpanel" aria-labelledby="profile-tab_sec">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit1"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                             <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss_sec_com->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss_sec_com as $gatepass_complete) 
                            <tr>
                                <td>{{$count ++}}</td>
                               
                                  <td>{{$gatepass_complete->full_sl}}</td>
                                  <td>{{$gatepass_complete->visitor_name}}</td>
                                  <td>{{$gatepass_complete->visitor_mobile_no}}</td>
                                  <td>{{$gatepass_complete->visitor_company}}</td>
                                  <td>{{$gatepass_complete->from_date}}</td>
                                  <td>{{$gatepass_complete->to_date}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepass_complete->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepass_complete->to_time))}}</td>
                                  <!--<td>{{$gatepass_complete->status}}</td>-->
								  <td>@if($gatepass_complete->status == "Pending_to_approve")
                                  {{"Pending To Approve"}}
							   @elseif($gatepass_complete->status == "issued")
                                   {{"Issued"}}
								   @elseif($gatepass_complete->status == "Rejected")
                                   {{"Rejected"}}
								   @elseif($gatepass_complete->status == "Completed")
                                   {{"Completed"}}
							        @endif
							  </td>
                                <td>

                                <a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($gatepass_complete->id))}}" title="Edit">Details</a>
                               
							  <!-- <a class="btn btn-info btn-sm" href="{{route('admin.printg.printg',\Crypt::encrypt($gatepass_complete->id))}}" title="Edit">Print</a>-->
						    
                                 
                                
                                </td>
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>

    </div>
@endif



@if(Session::get('vms_role') == 'Requester')
<div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home_requester" role="tabpanel" aria-labelledby="home-tab_sec">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss_requester->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss_requester as $gatepasss_requester_r) 
                            <tr>
                                <td>{{$count ++}}</td>
                               
                                  <td>{{$gatepasss_requester_r->full_sl}}</td>
                                  <td>{{$gatepasss_requester_r->visitor_name}}</td>
                                  <td>{{$gatepasss_requester_r->visitor_mobile_no}}</td>
                                  <td>{{$gatepasss_requester_r->visitor_company}}</td>
                                  <td>{{$gatepasss_requester_r->from_date}}</td>
                                  <td>{{$gatepasss_requester_r->to_date}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepasss_requester_r->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepasss_requester_r->to_time))}}</td>
                                
                                  <td>
                                  @if($gatepasss_requester_r->status == "Pending_to_approve")
                                  {{"Pending To Approve"}}
                                @elseif($gatepasss_requester_r->status == "issued")
                                   {{"Issued"}}
                                 @endif
                                        </td>
                                <td>

                                <a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($gatepasss_requester_r->id))}}" title="Edit">Details</a>
                              
                              
                                </td>
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>


 <div class="tab-pane fade " id="home_requester_returned" role="tabpanel" aria-labelledby="home-tab_sec">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit1"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>Visitor Name</th>
                            <th>Visitor Mobile No</th>
                            <th>Visitor Company</th>
                            
                            
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>From Time</th>
                            <th>To Time</th>
                           
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss_requester_returned->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss_requester_returned as $gatepasss_requester_retu) 
                            <tr>
                                <td>{{$count ++}}</td>
                               
                                  <td>{{$gatepasss_requester_retu->full_sl}}</td>
                                  <td>{{$gatepasss_requester_retu->visitor_name}}</td>
                                  <td>{{$gatepasss_requester_retu->visitor_mobile_no}}</td>
                                  <td>{{$gatepasss_requester_retu->visitor_company}}</td>
                                  <td>{{$gatepasss_requester_retu->from_date}}</td>
                                  <td>{{$gatepasss_requester_retu->to_date}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepasss_requester_retu->from_time))}}</td>
                                  <td>{{date('h:i A', strtotime(@$gatepasss_requester_retu->to_time))}}</td>
                                
                                  <td>
                                  @if($gatepasss_requester_retu->status == "Pending_to_approve")
                                  {{"Pending To Approve"}}
                                @elseif($gatepasss_requester_retu->status == "issued")
                                   {{"Issued"}}
							@elseif($gatepasss_requester_retu->status == "Completed")
                                   {{"Completed"}}
                                 @endif
                                        </td>
                                <td>

                                <a class="btn btn-info btn-sm" href="{{route('admin.edit.edit',\Crypt::encrypt($gatepasss_requester_retu->id))}}" title="Edit">Details</a>
                              
                              
                                </td>
                            </tr>
                        @endforeach
                    @else            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    @endif 
                </tbody>
                </table>
            </div>           
        </div>


        <div class="tab-pane fade" id="profile_security" role="tabpanel" aria-labelledby="profile-tab_sec">
                
        </div>

    </div>
@endif


</form>


@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#my-permit').DataTable();
    });
    $(document).ready(function() {
        $('#my-permit1').DataTable();
    });
    
    

</script>
@endsection