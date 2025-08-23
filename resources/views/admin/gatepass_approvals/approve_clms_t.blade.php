<?php 
use App\Department;
use App\UserLogin;
use App\ChangeRequest;

?>

@extends('admin.app')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{route('admin.approve_clms.index')}}">List Of Contractor's GatePass</a></li>
@endsection                        
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List of Contractor's GatePass </h1>
</div>


  @if(Session::get('clm_role') =='Shift_incharge' || Session::get('clm_role') =='hr_dept' || Session::get('clm_role') =='Safety_dept' || Session::get('clm_role') =='plant_head')
 <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Pending For Approval</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="home1-tab" data-toggle="tab" href="#home1" role="tab" aria-controls="home" aria-selected="true">Approve/Rejected</a>
        </li>
 </ul>
 
 @else
 <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link " id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">@if(Session::get('clm_role') =='security')
                Gatepass Approved
                @elseif(Session::get('user_sub_typeSession') == 3)
                ALL Gatepass
               @else  My Gatepass
               @endif</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home1" role="tab" aria-controls="home" aria-selected="true">Approved/Rejected</a>
        </li>
 </ul>
 @endif
<form action="{{ route('admin.approve.index')}}" method="POST" enctype="multipart/form-data">

<div class="tab-content" id="myTabContent">
        <div class="tab-pane fade " id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sl No</th>
                            <th>GP No</th>
                             <th>Name</th>
                            <th>Work Order No</th>
                           <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepasss->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepasss as $gatepass) 

                        @php
                        @$approver = UserLogin::where('id',@$gatepass->created_by)->first();
                        @endphp
                        @php
                         @$work = DB::table('work_order')->where('id',@$gatepass->work_order_no)->first();
                        @endphp
                            <tr>
                                <td>{{$count ++}}</td>
                                  <td>{{$gatepass->full_sl}}</td>
                                  <td>{{@$approver->name}}</td>
                                 <td>{{$gatepass->name}}</td>
                                 <td>{{@$gatepass->work_order_no}}</td>
                                 <td>@if($gatepass->status == "Pending_for_shift_incharge")
                                            {{"Pending For Shift Incharge"}}
                                      @elseif($gatepass->status == "Pending_for_hr")
                                      {{"Pending For HR"}}
                                      @elseif($gatepass->status == "Pending_for_safety")
                                      {{"Pending For Safety"}}
                                      @elseif($gatepass->status == "Pending_for_plant_head")
                                      {{"Pending For Plant Head"}}
                                      @elseif($gatepass->status == "Pending_for_security")
                                      {{"Gatepass Approved"}}
                                      @elseif($gatepass->status == "Rejected")
                                      {{"Rejected"}}
                                   @endif </td>
                                     

                            <td> @if($gatepass->created_datetime >= '2024-03-27')
                           <a class="btn btn-info btn-sm" href="{{route('admin.edit_clms_new.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">Details </a>
                            @else
                                <a class="btn btn-info btn-sm" href="{{route('admin.edit_clms.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">Details</a>
                           @endif
                                @if($gatepass->status == "Pending_for_security" && $gatepass->created_by==Session::get('user_idSession'))
                                <a class="btn btn-info btn-sm" href="{{route('admin.renew_clms.edit',\Crypt::encrypt($gatepass->id))}}" title="Edit">Renew</a>
                                @endif
@if($gatepass->status == "Pending_for_security" && (Session::get('clm_role') =='security' || Session::get('vms_role') =='Security'))
<a class="btn btn-info btn-sm" href="{{route('admin.printg_clms.printg',\Crypt::encrypt($gatepass->id))}}" title="Edit" target="_blank">Print</a>
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
        <div class="tab-pane fade show active" id="home1" role="tabpanel" aria-labelledby="home1-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit1"> 
                    <thead>
                        <tr>
                             <th>Sl No</th>
                            <th>GP No</th>
                           <th>Vendor Name</th>
                             <th>Name</th>
                            <th>Work Order No</th>
                           <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($gatepassss->count() > 0 ) 
                    <?php $count =  1 ;?>
                        @foreach($gatepassss as $gatepasss) 

                        @php
                        @$approver = UserLogin::where('id',@$gatepasss->created_by)->first();
                        @endphp
                        @php
                         @$work = DB::table('work_order')->where('id',@$gatepasss->work_order_no)->first();
                        @endphp
                            <tr>
                                <td>{{$count ++}}</td>
                                  <td>{{$gatepasss->full_sl}}</td>
                                  <td>{{@$approver->name}}</td>
                                 <td>{{$gatepasss->name}}</td>
                                 <td>{{@$gatepasss->work_order_no}}</td>
                                 <td>@if($gatepasss->status == "Pending_for_shift_incharge")
                                            {{"Pending To Shift Incharge"}}
                                      @elseif($gatepasss->status == "Pending_for_hr")
                                      {{"Pending For HR"}}
                                       @elseif($gatepasss->status == "Pending_for_safety")
                                      {{"Pending For Safety"}}
                                      @elseif($gatepasss->status == "Pending_for_plant_head")
                                      {{"Pending For Plant Head"}}
                                       @elseif($gatepasss->status == "Pending_for_security")
                                      {{"Gatepass Approved"}}
                                       @elseif($gatepasss->status == "Rejected")
                                      {{"Rejected"}}
                                   @endif </td>
                                     

                            <td> @if($gatepasss->created_datetime >= '2024-03-27')
                           <a class="btn btn-info btn-sm" href="{{route('admin.edit_clms_new.edit',\Crypt::encrypt($gatepasss->id))}}" title="Edit">Details </a>
                            @else
                                <a class="btn btn-info btn-sm" href="{{route('admin.edit_clms.edit',\Crypt::encrypt($gatepasss->id))}}" title="Edit">Details</a>
                           @endif
@if($gatepasss->status == "Pending_for_security" && (Session::get('clm_role') =='security' || Session::get('vms_role') =='Security'))
<a class="btn btn-info btn-sm" href="{{route('admin.printg.printg',\Crypt::encrypt($gatepasss->id))}}" title="Edit">Print</a>
@endif


@if($gatepasss->status == "Pending_for_security" && $gatepasss->created_by==Session::get('user_idSession'))
                                <a class="btn btn-info btn-sm" href="{{route('admin.renew_clms.edit',\Crypt::encrypt($gatepasss->id))}}" title="Edit">Renew</a>
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