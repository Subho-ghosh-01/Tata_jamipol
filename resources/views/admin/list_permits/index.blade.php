<?php 
use App\UserLogin;
use App\ChangeRequest;
use App\Permit;
use App\Job;
use App\PowerCutting;

?>
@extends('admin.app')
@section('breadcrumbs')
<style>
	
	.ui-autocomplete {
	z-index:2147483647!important;
		background-color:#fff;
		height: 400px;
		overflow: auto;
	}
	li.ui-menu-item{
		list-style-type: none;
		padding-top: 8px;
		padding-left: 4px;
	}
	li.ui-menu-item:hover{
		background-color: #3490dc;
		color: #fff;
		padding-left: 8px;
	}
	</style>
    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List Permits </a></li>
@endsection
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List Permits </h1>
</div>
    <!-- Show Message Success -->
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
            @endif
        </div>
    </div>
    <!-- Error List -->
    <div class="form-group-row">
        <div class="col-sm-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Tab Details -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">My Permits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Permits For Approval</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="permit-tab" data-toggle="tab" href="#permit" role="tab" aria-controls="permit" aria-selected="false">Issued Permits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="permit-return-tab" data-toggle="tab" href="#return-tab" role="tab" aria-controls="return" aria-selected="false">Pending for Return</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="permit-renew-tab" data-toggle="tab" href="#renew-tab" role="tab" aria-controls="renew" aria-selected="false">Pending for Renew</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="permit-exp-tab" data-toggle="tab" href="#exp-tab" role="tab" aria-controls="exp" aria-selected="false">Expired Permits</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="power-cutting" data-toggle="tab" href="#cutting-tab" role="tab" aria-controls="cutting-tab" aria-selected="false">Permits Pending for Power Cutting</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="getting-power" data-toggle="tab" href="#power-getting" role="tab" aria-controls="power-getting" aria-selected="false">Permits Pending for Power Getting</a>
        </li>
    </ul>
    
    <!-- MY Permit -->
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="my-permit"> 
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Sl. No.</th>
                            <th>Work Order No.</th>
                            <th>Job Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($my_pending_permits->count() > 0)
                        @php $count=1 @endphp
                            <!-- we r geting permit deatils "my_pending_permit" -->
                            @foreach($my_pending_permits as $my_pending_permit)
                            <?php
                                $user_data = DB::table('userlogins')->where('id',$my_pending_permit->entered_by)->get();
                            ?>
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{date('d/m/Y ', strtotime($my_pending_permit->created_at ?? '' )) }} </td>
                                    <td>
                                        <?php
                                            $cc=$my_pending_permit->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',$my_pending_permit->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{$month}}/{{$my_pending_permit->serial_no}}
                                    </td>
                                    <td>{{$my_pending_permit->order_no}}</td> 
                                    <?php 
                                        $job = Job::where('id',$my_pending_permit->job_id)->first();
                                    ?>
                                    <td>{{@$job->job_title}}</td>  

                                    <td>
                                        @if($my_pending_permit->status == "Cancel")
                                            {{"Cancel Permit"}}
                                        @elseif($my_pending_permit->status == "Issued")
                                            {{"Issued"}}
                                            @if($my_pending_permit->return_status == 'Pending') 
                                                {{"/Return Pending at Executing Agency"}} 
                                            @elseif($my_pending_permit->return_status == "Pending_area") 
                                                {{"/Return Pending at Owner Agency"}}
                                            @elseif($my_pending_permit->return_status == "Power_Getting") 
                                                {{"/Return Pending Power Getting User"}}
                                            @elseif($my_pending_permit->return_status == 'PPg') 
                                                {{"/Return Pending at Power Getting User"}} 
                                            @endif                                           
                                        @elseif($my_pending_permit->status == "Requested")
                                        <?php $issuer = UserLogin::where('id',$my_pending_permit->issuer_id)->get(); ?>
                                            {{"Pending with Executing Agency"}}({{@$issuer[0]->name}})
                                        @elseif($my_pending_permit->status == "Parea")
                                        <?php $area_clearance = UserLogin::where('id',$my_pending_permit->area_clearence_id)->get(); ?>
                                            {{"Pending with Owner Agency"}} ({{@$area_clearance[0]->name}})
                                        @elseif(($my_pending_permit->status == "Returned")) 
                                            {{'Permit Returned'}}
                                        @elseif(($my_pending_permit->status == "PPc")) 
                                            <?php $pcutname = UserLogin::where('id',@$my_pending_permit->ppc_userid)->first(); ?>
                                            {{'Permit Pending at Power Cutting'}}({{@$pcutname->name}})
                                        @endif    
                                    </td>
                                    <td>
                                        @if($my_pending_permit->status == "Issued")
                                            <a class="btn btn-info btn-sm" href="{{ URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($my_pending_permit->id))  }}">Download Permit</a> 

                                        @elseif($my_pending_permit->status == "Return_Requester")
                                            {{-- <a class="btn btn-info btn-sm" href="{{ route('admin.issuerChange',\Crypt::encrypt($my_pending_permit->id)) }}">Edit</a> --}}
                                        @endif

                                        @if($my_pending_permit->status == "Issued")
                                            @if($my_pending_permit->status != "Returned" && ($my_pending_permit->renew_id_1 == "" || $my_pending_permit->renew_id_2 == "")) 
                                                <a class="btn btn-info btn-sm" data-id="{{$my_pending_permit->id}}" data-toggle="modal" data-target="#RenewPermit" href="">Renew</a>
                                            @endif
                                        @endif

                                        @if($my_pending_permit->status == "Issued" && $my_pending_permit->return_status != "Pending" && $my_pending_permit->return_status != "Pending_area" && $my_pending_permit->return_status != "PPg")    
                                            <a class="btn btn-info btn-sm" href="{{ route('admin.lp.return',\Crypt::encrypt($my_pending_permit->id)) }}">Return</a>
                                        @endif 
                                        <!-- Return Code -->
                                        @if(($my_pending_permit->status == "Returned")) 
                                            <a class="btn btn-info btn-sm" href="{{ URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($my_pending_permit->id)) }}">Download Permit</a>
                                        @endif   
                                    </td>
                                </tr>
                            @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                        <tr>
                    @endif
                    </tbody>
                </table>
            </div>        
        </div>

        <!-- Permits For Approval -->
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="permits-for-approval">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Serial No.</th>
                            <th>Name</th>          
                            <th>P No./Vendor Code</th>
                            <th>Type</th>
                            <th>Job Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($issuer_datas->count() > 0)
                            @php $count=1 @endphp
                                @foreach($issuer_datas as $issuer_data)
                                    <?php
                                        $user_data2 = DB::table('userlogins')->where('id',$issuer_data->entered_by)->first();

                                    ?>
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{date('d/m/Y', strtotime($issuer_data->created_at ?? '')) }}</td>
                                        <td>
                                        <?php
                                            $cc=$issuer_data->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',$issuer_data->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{ $month }}/{{$issuer_data->serial_no}}</td>

                                        <td>{{@$user_data2->name}}</td>
                                        <td>{{@$user_data2->vendor_code}}</td>
                                        <td>{{(@$user_data2->user_type == 1) ? 'Employee' : 'Vendor' }}   
                                        </td>        
                                        <?php 
                                            $job = Job::where('id',$issuer_data->job_id)->first();
                                        ?>
                                        <td>{{@$job->job_title}}</td>                           
                                        <td>
                                            @if($issuer_data->status == "Parea") 
                                                <?php $area_clearance = UserLogin::where('id',$issuer_data->area_clearence_id)->first(); ?>
                                                {{'Pending with Owner Agency'}}  ({{@$area_clearance->name}})
                                            @else
                                                {{'Requested'}}
                                            @endif
                                        </td>
                                        <td>                                        
                      <a class="btn btn-info btn-sm" href="{{ route('admin.list_permit.edit',\Crypt::encrypt($issuer_data->id)) }}">Issue</a>
			    	<a class="btn btn-danger btn-sm"  data-id="{{$issuer_data->id}}"  data-toggle="modal" data-target="#c_permit"> Reject</a>
											
                                        </td>                                   
                                    </tr>
                                @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif
                    </tbody>
                </table>
            </div>           
        </div>

        <!-- Issued Permits -->
        <div class="tab-pane fade" id="permit" role="tabpanel" aria-labelledby="permit-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="issued-Permits">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Serial No.</th>
                            <th>Name</th>          
                            <th>P No./Vendor Code</th>          
                            <th>Type</th>
                            <th>Job Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($issued_permits->count() > 0)
                            @php $count=1 @endphp
                                @foreach($issued_permits as $issuer2)
                                    <?php
                                        $user_data2 = DB::table('userlogins')->where('id',$issuer2->entered_by)->get();
                                    ?>
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{date('d/m/Y', strtotime($issuer2->created_at ?? '')) }}</td>
                                        <td>
                                        <?php
                                            $cc=$issuer2->created_at;
                                            $month = date('m-Y', strtotime($cc));

                                            $abb = DB::table('divisions')->where('id',$issuer2->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{ $month }}/{{$issuer2->serial_no}}</td>

                                        <td>{{$user_data2[0]->name}}</td>
                                        <td>{{$user_data2[0]->vendor_code}}</td>
                                        <td>@if($user_data2[0]->user_type == 1)
                                                {{'Employee'}}
                                            @else
                                                {{'Vendor'}}    
                                            @endif
                                        </td>            

                                        <?php 
                                            $job = Job::where('id',$issuer2->job_id)->first();
                                        ?>
                                        <td>{{@$job->job_title}}</td>

                                        <td>@if($issuer2->status == "Requested") {{"Pending with Executing Agency"}} 
                                            @elseif($issuer2->status == "Parea") {{"Pending with Area Clearance Officer"}}
                                            @elseif($issuer2->status == "Issued") {{'Permit Issued'}}
                                            @elseif($issuer2->status == "Returned") {{'Permit Returned'}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($issuer2->status == "Issued") 
                                                <a class="btn btn-danger btn-sm"  data-id="{{$issuer2->id}}"  data-toggle="modal" data-target="#c_permit"> Cancel</a>&nbsp;
                                            @endif
                                            <a class="btn btn-success btn-sm" href="{{ URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($issuer2->id))  }}">Download Permit</a>
                                        </td>                                   
                                    </tr>
                                @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif                      
                    </tbody>
                </table>
            </div>  
        </div> 
        
        <!-- Permit-Return-tab -->
        <div class="tab-pane fade" id="return-tab" role="tabpanel" aria-labelledby="permit-return-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="Permit-Return-tab">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Serial No.</th>
                            <th>Name</th>       
                            <th>P No./Vendor Code</th>   
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pending_for_returns->count() > 0)
                            @php $count=1 @endphp
                                @foreach($pending_for_returns as $pending_for_return)
                                    <?php
                                        $permit_id = DB::table('permits')->where('id',@$pending_for_return->id)->get();
                                        $username = DB::table('userlogins')->where('id',@$pending_for_return->entered_by)->get();
                                    ?>
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{date('d/m/Y', strtotime($pending_for_return->created_at)) }}</td>
                                        <td>
                                        <?php
                                            $cc= $permit_id[0]->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',@$permit_id[0]->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{ $month }}/{{@$permit_id[0]->serial_no}}
                                        </td>
                                        <td>{{$username[0]->name ?? ''}}</td>
                                        <td>{{$username[0]->vendor_code ?? ''}}</td>                 
                                        <td>{{$pending_for_return->status}}
                                        @if($pending_for_return->return_status == 'Pending_area')
                                         {{"/Return Pending at Owner Agency"}} 
                                        @elseif($pending_for_return->return_status == 'Power_Getting') 
                                            {{"/Return Pending at Power Getting"}}
                                        @else {{"/Return Pending at Executing Agency"}} @endif</td>
                                        <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('admin.lp.return',\Crypt::encrypt($pending_for_return->id)) }}">Approve</a>
                                        </td>
                                    </tr>
                                @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif
                    </tbody>
                </table>
            </div>           
        </div>

        <!-- Permit Renew Tab-->
        <div class="tab-pane fade" id="renew-tab" role="tabpanel" aria-labelledby="permit-renew-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="Renew-tab">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Serial No.</th>
                            <th>Name</th>       
                            <th>P No./Vendor Code</th>   
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($renew_lists->count() > 0)
                            @php $count=1 @endphp
                            @foreach($renew_lists as $renew_list)
                                <?php
                                    $permit_date = DB::table('permits')->where('id',@$renew_list->permit_id)->get();
                                    $username    = DB::table('userlogins')->where('id',@$permit_date[0]->entered_by)->get();
                                ?>
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{date('d/m/Y H:i:s', strtotime($renew_list->datetime_apply)) }}</td>
                                    <td><?php
                                            $cc= $permit_date[0]->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',@$permit_date[0]->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{ $month }}/{{ @$permit_date[0]->serial_no }}
                                    </td>
                                    <td>{{$username[0]->name ?? ''}}</td>
                                    <td>{{$username[0]->vendor_code ?? ''}}</td>                 
                                    <td>{{$renew_list->status}} </td>
                                    <td><a class="btn btn-info btn-sm" href="{{ route('admin.renew_view',\Crypt::encrypt($renew_list->id)) }}">Issue</a></td>
                                                                    
                                </tr>
                            @endforeach 
                        @else
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif
                    </tbody>
                </table>
            </div>           
        </div>

        <!-- Expiry Permits Tab-->
        <div class="tab-pane fade" id="exp-tab" role="tabpanel" aria-labelledby="permit-exp-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="expired-tab">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Serial No.</th>
                            <th>Name</th>       
                            <th>P No./Vendor Code</th>   
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($expiryPermits->count() > 0)
                            @php $count=1 @endphp
                                @foreach($expiryPermits as $expiryPermit)
                                    <?php
                                        $permit_id = DB::table('permits')->where('id',@$expiryPermit->id)->get();
                                        $username  = DB::table('userlogins')->where('id',@$expiryPermit->entered_by)->get();
                                    ?>
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{date('d/m/Y', strtotime($expiryPermit->created_at)) }}</td>
                                        <td>
                                        <?php
                                            $cc= $permit_id[0]->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',@$permit_id[0]->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{ $month }}/{{@$permit_id[0]->serial_no}}
                                        </td>
                                        <td>{{$username[0]->name ?? ''}}</td>
                                        <td>{{$username[0]->vendor_code ?? ''}}</td>                 
                                        <td>{{$expiryPermit->status}} </td>
                                        <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('admin.expnotify',$expiryPermit->id) }}" onclick="return confirm('Are you sure to send email to requester')">Notify</a>
                                        </td>                                   
                                    </tr>
                                @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif

                    </tbody>
                </table>
            </div>           
        </div>

        <!-- CUTTING Permits Tab-->
        <div class="tab-pane fade" id="cutting-tab" role="tabpanel" aria-labelledby="power-cutting">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="cutting-tab-list">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date</th>
                            <th>Permit Serial No.</th>
                            <th>Name</th>       
                            <th>P No./Vendor Code</th>   
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($powerCuttings->count() > 0)
                            @php $count=1 @endphp
                            @foreach($powerCuttings as $powerCutting)
                                <tr>
                                <?php
                                    $enterBy = DB::table('userlogins')->where('id',$powerCutting->entered_by)->first();
                                ?>
                                    <td>{{$count++}}</td>
                                    <td>{{date('d/m/Y', strtotime($powerCutting->created_at)) }}</td>
                                    <td>
                                    <?php
                                        $month = date('m-Y', strtotime($powerCutting->created_at));
                                        $abb = DB::table('divisions')->where('id',$powerCutting->division_id)->first();
                                        echo @$abb->abbreviation .'/'. @$month .'/' .@$powerCutting->serial_no;
                                    ?>
                                    </td>
                                        <td>{{@$enterBy->name}}</td>
                                        <td>{{@$enterBy->vendor_code}}</td>
                                        <td>
                                            @if($powerCutting->status == "PPc")
                                                {{'Pending at Power Cutting'}}
                                            @endif
                                        </td>
                                        <td><a class="btn btn-info btn-sm" href="{{ route('admin.viewPower',\Crypt::encrypt($powerCutting->id)) }}">View</a>
                                        </td>                                 
                                    </tr>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif
                    </tbody>
                </table>
            </div>           
        </div>


        <!-- GETTING Permits Tab-->
        <div class="tab-pane fade" id="power-getting" role="tabpanel" aria-labelledby="power-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm" id="power-getting-list">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Date Time</th>
                            <th>Permit Sl. No.</th>
                            <th>Name</th>
                            <th>Vendor Code</th>
                            <th>User Type</th>
                            <th>Job Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($powerGettings->count() > 0)
                            @php $count=1 @endphp
                                @foreach($powerGettings as $powerGetting)
                                    <?php
                                        $enterByUser = DB::table('userlogins')->where('id',$powerGetting->entered_by)->first();
                                    ?>
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{date('d/m/Y', strtotime($powerGetting->created_at ?? '')) }}</td>
                                        <td>
                                        <?php
                                            $month = date('m-Y', strtotime($powerGetting->created_at));
                                            $abb = DB::table('divisions')->where('id',$powerGetting->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/{{ $month }}/{{$powerGetting->serial_no}}</td>

                                        <td>{{@$enterByUser->name}}</td>
                                        <td>{{@$enterByUser->vendor_code}}</td>
                                        <td>{{ (@$enterByUser->user_type == 1) ? 'Employee' : 'Vendor' }}  </td>        
                                        <?php $job = Job::where('id',$powerGetting->job_id)->first(); ?>
                                        <td>{{@$job->job_title}}</td>                           
                                        <td><a class="btn btn-info btn-sm" href="{{ route('admin.viewGetting',\Crypt::encrypt($powerGetting->id)) }}">View</a></td>
                                    </tr>                                    
                                @endforeach
                        @else
                            <tr>
                                <td colspan="8" style="color:red;text-align:center;">NA</td>
                            <tr>
                        @endif
                    </tbody>
                </table>
            </div>           
        </div>
        

    </div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>


    $(document).ready(function() {
        $('#my-permit').DataTable();
    });
    $(document).ready(function() {
        $('#permits-for-approval').DataTable();
    });
    $(document).ready(function() {
        $('#issued-Permits').DataTable();
    });
    $(document).ready(function() {
        $('#Permit-Return-tab').DataTable();
    });
    $(document).ready(function() {
        $('#Renew-tab').DataTable();
    });
    $(document).ready(function() {
        $('#expired-tab').DataTable();
    });
    $(document).ready(function() {
        $('#cutting-tab-list').DataTable();
    });
    $(document).ready(function() {
        $('#power-getting-list').DataTable();
    });
    

</script>

<script>
    // this Jquery used for open change the time of requester
    $(document).ready(function(){
        $('#RenewPermit').on('show.bs.modal', function (e) {  
            var id = $(e.relatedTarget).data('id');
            //alert(id);
            $('#executingAgency').html("");
            $('#start_date').html("");
            $('#permitID').val(id); 
            $.ajax({
                type:'GET',
                url:"{{route('admin.getenddate')}}/" + id,
                contentType:'application/json',
                success:function(data){
                    // console.log(data);
                    $('#enddate').val(data.end);
                    $.each(data.issuer1, function(i, val) {
                        $('#executingAgency').append('<option value="'+val.id+'" >'+val.name+'</option>');
                    });
                }
            });
        });
        $('#start_date').datetimepicker(); 


        $('#c_permit').on('show.bs.modal', function (e) {      
            var id = $(e.relatedTarget).data('id');
            $('#pid').val(id);
        });
    });

    function reset(){
        console.log("tets");
        $('#executingAgency').val("");
    }
</script>
<!-- RENEW Permit -->
<div class="modal fade" id="RenewPermit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Renew</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.renew')}}" method="POST" enctype="multipart/form-data">
                @csrf
               <div class="modal-body">
                    <div class="form-group">
                        <label for="Date" class="col-form-label">Old Time:</label>
                        <input type="text" class="form-control" readonly required  name="old_time" id="enddate">
                        <input type="hidden" class="form-control" readonly name="permitID" id="permitID">
                    </div>
                    <div class="form-group">
                        <label for="form-control-label" class="col-form-label">New Time</label>
                        <input type="text" class="form-control" name="req_new_time" id="start_date" required autocomplete="off"> 
                    </div>
                    <div class="form-group">
                        <label for="form-control-label" class="col-form-label">Executing Agency</label>
                        <select class="form-control" id="executingAgency" name="executingAgency">
                        </select>
                    </div>
				   <div class="form-group">
                        <label for="form-control-label" class="col-form-label">Change In Manpower</label>
                        <select class="form-control" id="manpower_yes_no" name="manpower_yes_no" required>
                           <option>--Select--</option>
                            <option value="Yes">Yes</option> 
                            <option value="No">No</option>
                        </select>
                    </div>
				   <div class="form-group"   id="gatepass_details" style="display: none;">
        <label for="form-control-label" class="col-form-label">Gate Pass Details</label>
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead> 
                    <tr> 
                        <th>Employee Name</th>
                        <th>Gate Pass No.</th>
                        <th>Designation</th>
                        <th>Age</th>   
                        <th>Gatepass Expiry</th>   
                        <th>Intime</th>        
                    </tr>
                </thead>
                <!-- FOR Vendor already insert their Employee Details -->
                <tbody id="append_gatepass">
                    <tr class='tr_input'>
                        <td><input type='text' name="employee_name[]"  class='username form-control' id='username_1' placeholder='Enter Employee'></td>
                        <td><input type='text' name="gate_pass_no[]"   class='gatepass form-control' id='gatepass_1'></td>
                        <td><input type='text' name="designation[]"  class='desig form-control' id='desig_1'></td>
                        <td><input type='text' name="age[]"  class='age form-control' id='age_1'></td>
                        <td><input type='date' name="expiry_date[]"  class='exp form-control' id='exp_1'></td>
                         <td><input type='time' name="intime[]"  class='exp form-control' id='intime_1'></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12" style="">
            <button type="button" id="btn-add" class="btn btn-primary btn-sm">+</button>&nbsp;
            <button type="button" id="btn-remove" class="btn btn-danger btn-sm">-</button>
        </div> 
    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reset();">Close</button>
                    <input type="submit" class="btn btn-primary" value="Renew">
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
$("#manpower_yes_no").on('change',function (){
      var modeval =$(this).val();
        // alert(modeval);
      if(modeval == 'Yes'){
           // $('#s1').show(); 
            $('#gatepass_details').show(); 
            
       }
      else {
          $('#gatepass_details').hide(); 
            
           
       }
    }); 
</script>   
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
// Add more
  $(document).ready(function(){
    $(document).on('keydown', '.username', function() {
        var id = this.id;
        var valname =  $(this).val();
        // console.log(valname);
        var splitid = id.split('_');
        var index = splitid[1];
        //alert(index);
        // Initialize jQuery UI autocomplete
        $('#'+id ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                url: "{{route('admin.autocomplete_gate_pass')}}/" +  <?php echo Session::get('user_idSession') ?>  + "/" + valname,
                type: 'get',                                     
                dataType: "json",
                    success: function(data){
                            var array = data.error ? [] : $.map(data.list,function(m){
                                return{
                                    label:m.employee,
                                    gatepass:m.gatepass,
                                    des:m.designation,
                                    age:m.age,
                                    expi:m.expiry
                                };
                            });
                        response(array);  
                    }

                });
            },   
            select: function (event, ui) {
                $('#'+id).val(ui.item.label);
                $('#'+id).closest('tr').find('.gatepass').val(ui.item.gatepass);
                $('#'+id).closest('tr').find('.desig').val(ui.item.des);
                $('#'+id).closest('tr').find('.age').val(ui.item.age);
                $('#'+id).closest('tr').find('.exp').val(ui.item.expi);
                $('#'+id).closest('tr').find('.intime').val(ui.item.intime);

            }
        });
    });
 
    // Add more
    $('#btn-add').click(function(){
        var count = $(".tr_input").length + 1;
        // Get last id 

        var lastname_id = $('.tr_input input[type=text]:nth-child(1)').last().attr('id');
        var split_id = lastname_id.split('_');
        // New index
        var index = Number(split_id[1]) + 1;
        // Create row with input elements
        var html = "<tr class='tr_input'>";
            html += "<td><input type='text'  name='employee_name[]' class='username form-control' id='username_"+index+"' placeholder='Enter Employee'></td>";
            html += "<td><input type='text' name='gate_pass_no[]' class='gatepass form-control' id='gatepass_"+index+"'></td>";
            html += "<td><input type='text' name='designation[]' class='desig form-control' id='desig_"+index+"'></td>";
            html += "<td><input type='text' name='age[]' class='age form-control' id='age_"+index+"'></td>";
            html += "<td><input type='date' name='expiry_date[]' class='exp form-control' id='exp_"+index+"'></td>";

            html += "<td><input type='time' name='intime[]' class='exp form-control' id='intime_"+index+"'></td></tr>";
        $('#append_gatepass').append(html);
    });
    $("#btn-remove").on("click", function (e) {
        alert("Are You Sure You want to Remove");
        if($('.tr_input').length > 1){
            $(".tr_input:last").remove();
       }
    });
});

    $('#OTHER').click(function(){
        if($(this).is(':checked'))
        {
            $('#show_specify').show();
            $('#specify_others').prop('required', true);
        }
        else
        {
            $('#specify_others').prop('required', false);
            $('#show_specify').hide();
        }
    });

    
</script>




<!-- cancel permits -->
<div class="modal fade" id="c_permit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reject/Cancel the Permit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.cancelpermit') }}" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="pid" name="pid">
                    @csrf
                    <div class="form-group">
                        <label for="Violation" class="col-form-label">Remarks (Example-Violation Details etc.)</label>
                        <textarea class="form-control" name="violations_details" id="Violation" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="Upload 1" class="col-form-label">File Upload 1:</label>
                        <input type="file" class="form-control" name="img1" id="Upload 1" >
                    </div>
                    <div class="form-group">
                        <label for="Upload 2" class="col-form-label">File Upload 2:</label>
                        <input type="file" class="form-control" name="img2" id="Upload 2" >
                    </div>
                    <div class="form-group">
                        <label for="Upload 3" class="col-form-label">File Upload 3:</label>
                        <input type="file" class="form-control" name="img3" id="Upload 3" >
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

