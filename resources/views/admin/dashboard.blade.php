    @extends('admin.app')
    @section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    @endsection
    @section('content')
    <h2></h2>
    {{-- Session::get('capchaSession') --}}
    {{-- Session::get('user_idSession')  --}}
    {{-- Session::get('user_nameSession') --}}
    {{-- Session::get('user_typeSession') --}}
    {{-- Session::get('user_sub_typeSession')--}}
    {{-- Session::get('user_DivID_Session') --}}
    {{-- Session::get('user_DeptID_Session') --}}
    {{-- Session::get('user_SecID_Session') --}}
    {{-- Session::get('vcode') --}}
   
    @if(Session::get('wps') == 'Yes' || Session::get('user_sub_typeSession')=='3')    
    <fieldset class="border p-4">
        <legend class="w-auto">Work Permit System</legend>
        <div class="row box-shadow">

            <div class="col-md-2" style="padding: 2px">
                <a href="{{route('admin.list_permit.index')}}" style="text-decoration:none;"><div class="card"> 
                    <div class="card-body" style="background: #3472ac">
                        <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$my_permits}}</b></h1></h5>
                        <p class="card-text" style="color: #fff">My Permits</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2" style="padding: 2px">
            <a href="{{url('admin/list_permit')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #d79604">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$permit_approval}}</b></h1></h5>
                    <p class="card-text"style="color: #fff">Permits For Approval</p>
                </div>
            </div></a>
        </div>
        <div class="col-md-2" style="padding: 2px">
            <a href="{{url('admin/list_permit')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #537616">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$issued_permit}}</b></h1></h5>
                    <p class="card-text"style="color: #fff">Issued Permits</p>
                </div>
            </div></a>
        </div>
        <div class="col-md-2" style="padding: 2px">
            <a href="{{url('admin/list_permit')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #fc4a4a">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$pending_for_returns}}</b></h1></h5>
                    <p class="card-text" style="color: #fff">Pending for Return</p>
                </div>
            </div></a>
        </div>
        <div class="col-md-2" style="padding: 2px">
            <a href="{{url('admin/list_permit')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #ff7777">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$renew_lists}}</b></h1></h5>
                    <p class="card-text" style="color: #fff">Pending for Renew</p>
                </div>
            </div></a>
        </div>
        <div class="col-md-2" style="padding: 2px">
            <a href="{{url('admin/list_permit')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #e10a0a;">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$expiryPermits}}</b></h1></h5>
                    <p class="card-text" style="color: #fff">Expired Permits</p>
                </div>
            </div></a>
        </div>
    </div>

    @if(Session::get('user_sub_typeSession') != 2)
    <div class="row box-shadow">
        <div class="col-md-3" style="padding: 2px">
            <a href ="{{route('admin.job.index')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #939393">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$job_count}}</b></h1></h5>
                    <p class="card-text" style="color: #fff">Jobs</p>
                </div>
            </div></a>
        </div>
        <div class="col-md-3" style="padding: 2px">
            <a href ="{{route('admin.user.index')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #939393">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$user_count}}</b></h1></h5>
                    <p class="card-text"style="color: #fff">Users</p>
                </div>
            </div></a>
        </div>
        @if(Session::get('user_sub_typeSession') != 1)
        <div class="col-md-3" style="padding: 2px">
            <a href ="{{route('admin.division.index')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background:#939393">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$division_count}}</b></h1></h5>
                    <p class="card-text"style="color: #fff">Divisions</p>
                </div>
            </div></a>
        </div>
        <div class="col-md-3" style="padding: 2px">
            <a href ="{{route('admin.department.index')}}" style="text-decoration:none;"><div class="card">
                <div class="card-body" style="background: #939393">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{$department_count}}</b></h1></h5>
                    <p class="card-text" style="color: #fff">Department</p>
                </div>
            </div></a>
        </div>
        @endif
    </div>
    @endif
</fieldset>
@endif
<table>
    @if(Session::get('vms_yes_no')=='Yes' || Session::get('user_sub_typeSession')=='3')
    <fieldset class="border p-4">
        <legend class="w-auto">Visitor's Gatepass</legend>
        <div class="row box-shadow">
            <div class="col-md-2" style="padding: 2px">
                <a href="{{route('admin.approve.index')}}" style="text-decoration:none;"><div class="card"> 
                    <div class="card-body" style="background: #3472ac">
                        <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{@$gatepasss}}</b></h1></h5>
                        <p class="card-text" style="color: #fff">Pending For Approval</p>
                    </div>
                </div>
            </a>
        </div>
       
        <div class="col-md-2" style="padding: 2px">
            <a href="{{route('admin.approve_t.index')}}" style="text-decoration:none;"><div class="card"> 
                <div class="card-body" style="background: #3472ac">
                    <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{@$gatepasss2}}</b></h1></h5>
                    <p class="card-text" style="color: #fff">Approved/Rejected</p>
                </div>
            </div>
        </a>
    </div>
   
</div>
</fieldset>
@endif

@if(Session::get('clm_yes_no')=='Yes' || Session::get('user_sub_typeSession')=='3')
<fieldset class="border p-4">
    <legend class="w-auto">Contractor's Gatepass</legend>
    <div class="row box-shadow">
       <div class="col-md-2" style="padding: 2px">
        <a href="{{route('admin.approve_clms.index')}}" style="text-decoration:none;"><div class="card">
            <div class="card-body" style="background: #d79604">
                <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{@$gatepasss_clms}}</b></h1></h5>
                <p class="card-text"style="color: #fff">

                Pending For Approval
          </p>
            </div>
        </div></a>
    </div>
    @if(Session::get('clm_role') !='security' || (Session::get('vms_role') !='Security' && Session::get('vms_role')!=''))
    <div class="col-md-2" style="padding: 2px">
        <a href="{{route('admin.approve_clms_t.index')}}" style="text-decoration:none;"><div class="card">
            <div class="card-body" style="background: #d79604">
                <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{@$gatepasss_clms2}}</b></h1></h5>
                <p class="card-text"style="color: #fff">Approved/Rejected</p>
            </div>
        </div></a>
    </div>
    @endif
    
</div>
</fieldset>
@endif
@if(Session::get('safety_yes_no')=='Yes' || Session::get('user_sub_typeSession')=='3')
<fieldset class="border p-4">
    <legend class="w-auto">Safety Data</legend>
    <div class="col-md-2" style="padding: 2px">
        <a href="{{route('admin.safety_data_view.index')}}" style="text-decoration:none;"><div class="card">
            <div class="card-body" style="background: #537616">
                <h5 class="card-title"><h1 style="color: #fff; font-size:30;"><b>{{@$safety_data}}</b></h1></h5>
                <p class="card-text"style="color: #fff">List of Safety Data View</p>
            </div>
        </div></a>
    </div>
</fieldset>
@endif
@endsection
