<style>

    body {
                background: #f4f7fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
.dropdown-menu.show {
 top: 78px !important;
 left: 133px!important;
}
@media screen and (max-width: 700px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }
  .sidebar a {float: left;}
  div.content {margin-left: 0;}
}

/* On screens that are less than 400px, display the bar vertically, instead of horizontally */
@media screen and (max-width: 400px) {
  .sidebar a {
    text-align: center;
    float: none;
  }
}
</style>

<p style="margin-left:10%;margin-top:35%"><b>Hi {{ Session::get('user_nameSession') }} </b></p>
<ul class="nav flex-column mt-1">
        <li class="nav-item">
            <a class="nav-link @if(request()->url() == route('admin.dashboard')) {{'active'}} @endif" href="{{url('/admin/dashboard')}}">
            Dashboard <span class="sr-only">(current)</span>
            </a>
        </li>
    @if(Session::get('user_sub_typeSession') != 2)
    <li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('admin.job.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Masters  
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @if( (Session::get('user_sub_typeSession') == 1 ) || Session::get('user_sub_typeSession') == 3)
            <a class="dropdown-item" href="{{route('admin.job.index')}}">Job Category</a>
            <a class="dropdown-item" href="{{route('admin.user.index')}}">Users</a>
            @endif
            @if(Session::get('user_sub_typeSession') == 3)
                <a class="dropdown-item" href="{{route('admin.division.index')}}">Divisions</a>
                <a class="dropdown-item" href="{{route('admin.department.index')}}">Department</a>
            @endif
            
            <a class="dropdown-item" href="{{route('admin.work-order.index')}}">Work Order</a>
            <a class="dropdown-item" href="{{route('admin.gate_pass')}}">Gate Pass</a>
            <a class="dropdown-item" href="{{route('admin.skill.index')}}">Skill</a>
            <a class="dropdown-item" href="{{route('admin.settings_master.index')}}">Setting</a>
            <a class="dropdown-item" href="{{route('admin.silo_master.index')}}">Silo Master</a>
        </div>
    </li>
    @endif
    @if(Session::get('vms_role')!='Security' && Session::get('user_sub_typeSession') != 2 || Session::get('wps') == 'Yes')
    <li class="nav-item">
        <a class="nav-link @if(request()->url() == route('admin.request_permit.create')) {{'active'}} @endif" href="{{url('admin/request_permit/create')}}">Request Permit to Work</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(request()->url() == route('admin.list_permit.index')) {{'active'}} @endif" href="{{url('admin/list_permit')}}"> List Permit to Work </a>
    </li>
    @endif
     @if(Session::get('user_sub_typeSession') != 2 )
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Permit to Work Report
    </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            @if(Session::get('user_sub_typeSession') == 1 || Session::get('user_sub_typeSession') == 3)
                <a class="dropdown-item @if(request()->url() == route('admin.report_list')) {{'active'}} @endif" href="{{url('admin/report')}}">Permits</a>
                <a class="dropdown-item" href="{{url('admin/expired-download')}}">Expired Gate Pass</a>
            
            @endif
        </div>
    </li>
     @endif

@if(Session::get('vms_yes_no') == 'Yes' || Session::get('user_sub_typeSession') == 3)
<li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('admin.job.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Visitor Gatepass
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            
            @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && Session::get('vms_role') !='Approver') ) 
           <a class="nav-link " href="{{URL::to('RequestVGatepass')}}">Request Visitor Gatepass</a>
            @endif
             
            @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))) 
   
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/approve')}}">List Of Visitor's GatePass </a>
    
     @endif
@if(Session::get('user_sub_typeSession') == '3' || Session::get('vms_admin') == 'Yes')
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/vms_report')}}">VMS Report </a>
  @endif

        </div>
    </li>

@endif


    <!-- @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && Session::get('vms_role') !='Approver') ) 
	<li class="nav-item"> 
        <a class="nav-link " href="{{URL::to('RequestVGatepass')}}">Request Visitor Gatepass</a>
    </li>
      @endif
       @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))) 
	<li class="nav-item"> 
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/approve')}}">List Of Visitor's GatePass </a>
    </li>
     @endif
  
     @if(Session::get('user_sub_typeSession') == '3' || Session::get('vms_admin') == 'Yes')
  <li class="nav-item"> 
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/vms_report')}}">VMS Report </a>
    </li>
      @endif-->
   
@if(Session::get('clm_yes_no') == 'Yes' || Session::get('user_sub_typeSession') == '3' )
<li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('admin.job.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Contractor Gatepass
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="top: 78px !important;"
        >
        @if(Session::get('user_sub_typeSession') == '3'  || (Session::get('clm_yes_no') == 'Yes' && (Session::get('status') != 'pending_clms_vendor'))) 
   
        <a class="nav-link " href="{{URL::to('CLMSGatepass')}}">Request Contractor Gatepass</a>
        @elseif(Session::get('user_sub_typeSession') == '2' && Session::get('clm_yes_no') == 'Yes' && Session::get('status') == 'pending_clms_vendor') 
       <a class="nav-link " href="{{url('admin/vendor_details')}}">Request Contractor Gatepass</a>
         @endif

       @if(Session::get('user_sub_typeSession') == '3' || Session::get('user_sub_typeSession') == '1' || (Session::get('clm_yes_no') == 'Yes'  && (Session::get('status') != 'pending_clms_vendor' || Session::get('status') != 'Pending_for_hr' || Session::get('status') != 'pending_for_safety')))   
        <a class="nav-link @if(request()->url() == route('admin.approve_clms.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/approve_clms')}}">List Of Contractor's GatePass </a>
        @elseif(Session::get('user_sub_typeSession') == '2' && Session::get('clm_yes_no') == 'Yes' && Session::get('status') == 'pending_clms_vendor')
        <a class="nav-link " href="{{url('admin/vendor_details')}}">List Of Contractor's GatePass</a>
        @elseif(Session::get('status') == 'Pending_for_hr' || Session::get('status') == 'pending_for_safety')
        <script>alert('Vendor Registration is pending')</script>
      @endif
    @if(Session::get('user_sub_typeSession') == '3' ||  Session::get('clms_admin') == 'Yes')
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/clms_report')}}">CLMS Report</a>
        @elseif(Session::get('user_sub_typeSession') == '2' && Session::get('clm_yes_no') == 'Yes' && Session::get('status') == 'pending_clms_vendor' &&  Session::get('clms_admin') == 'Yes')
 <a class="nav-link " href="{{url('admin/vendor_details')}}">CLMS Report</a>
     @endif
     <a class="nav-link" href="{{route('admin.daily_attendence_view')}}">Daily Attendence</a>
            <a class="nav-link" href="{{route('admin.vendor_attendance.index')}}">Wage Register</a>
            <a class="nav-link" href="{{route('admin.vendor_esic_details.index')}}">Vendor Esic Challan & Contribution</a>
            <a class="nav-link" href="{{route('admin.vendor_pf_details.index')}}">Vendor PF Challan & ECR</a>
            <a class="nav-link" href="{{route('admin.vendor_ecm.index')}}">Bonus Return / Filling</a>
            <a class="nav-link" href="{{route('admin.vendor_hyr.index')}}">Half Yearly Return</a>
           <a class="nav-link" href="{{route('admin.vendor_holiday.index')}}">Vendor Employee Leave List</a>
 <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/vendor_clms_pending_list')}}">Pending Vendor Registration</a>
        </div>
</li>
@endif

<li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('vms.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Vehicle Gate Pass
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            
           
             
            @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))) 
   
        <a class="nav-link @if(request()->url() == route('vms.index')) {{'active'}} @endif" href="{{url('vms')}}">Vehicle Gate Pass Management System </a>
    
     @endif
     @if(Session::get('user_sub_typeSession') == '3' || Session::get('clm_role') == 'Safety_dept')
    <a class="nav-link {{ request()->is('vms/vms_report/found') ? 'active' : '' }}" 
       href="{{ url('vms/vms_report/found') }}">
        Report/Dashboard
    </a>
@endif


        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('vendor_mis.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Vendor Safety MIS
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            
           
             
            @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))) 
   
        <a class="nav-link @if(request()->url() == route('vendor_mis.index')) {{'active'}} @endif" href="{{url('vendor_mis')}}">Vendor Safety MIS </a>
    
     @endif
     @if(Session::get('user_sub_typeSession') == '3' || Session::get('clm_role') == 'Safety_dept')
    <a class="nav-link {{ request()->is('vendor_mis/mis_report/found') ? 'active' : '' }}" 
       href="{{ url('vendor_mis/mis_report/found') }}">
        Report/Dashboard
    </a>
@endif


        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('vendor_mis.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Silo Tanker Management
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            
           
             
            @if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))) 
   
        <a class="nav-link @if(request()->url() == route('vendor_silo.index')) {{'active'}} @endif" href="{{url('vendor_silo')}}">Silo Tanker Management</a>
        
<a class="nav-link {{ request()->is('vendor_silo/index_silo/add') ? 'active' : '' }}" 
       href="{{ url('vendor_silo/index_silo/add') }}">
       Silo Tanker Management (Daily Inspection)
    </a>



     @endif
    



        </div>
    </li>


     <!--@if(Session::get('user_sub_typeSession') == '3'  || Session::get('clm_yes_no') == 'Yes') 
    <li class="nav-item"> 
        <a class="nav-link " href="{{URL::to('CLMSGatepass.blade.php')}}">Request Contractor Gatepass</a>
    </li>
    <li class="nav-item"> 
        <a class="nav-link @if(request()->url() == route('admin.approve_clms.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/approve_clms')}}">List Of Contractor's GatePass </a>
    </li>
       @endif
        @if(Session::get('user_sub_typeSession') == '3' ||  Session::get('clms_admin') == 'Yes')
       <li class="nav-item" > 
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/clms_report')}}">CLMS Report</a>
    </li>
     @endif-->
     @if(Session::get('user_sub_typeSession') == '3'  || Session::get('safety_yes_no') == 'Yes')
<li class="nav-item dropdown">
        <a class="nav-link @if(request()->url() == route('admin.job.index')) {{'active'}} @endif dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       Safety Data 
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

 @if(Session::get('user_sub_typeSession') == '3'  || Session::get('safety_yes_no') == 'Yes') 
   
        <a class="nav-link " href="{{URL::to('Safety_data_entry')}}">Safety Data Entry</a>
 
     
  
    
        <a class="nav-link @if(request()->url() == route('admin.safety_data_view.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/safety_data_view')}}">List Of Safety View </a>
    
@endif
 @if(Session::get('user_sub_typeSession') == '3' || Session::get('safety_admin') == 'Yes')

        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/safety_report')}}">Safety Report</a>
  
   @endif

        </div>
</li>
@endif
   <!--    @if(Session::get('user_sub_typeSession') == '3'  || Session::get('safety_yes_no') == 'Yes') 
     <li class="nav-item"> 
        <a class="nav-link " href="{{URL::to('Safety_data_entry')}}">Safety Data Entry</a>
    </li>
     
  
     <li class="nav-item"> 
        <a class="nav-link @if(request()->url() == route('admin.safety_data_view.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/safety_data_view')}}">List Of Safety View </a>
    </li>
@endif
 @if(Session::get('user_sub_typeSession') == '3' || Session::get('safety_admin') == 'Yes')
<li class="nav-item"> 
        <a class="nav-link @if(request()->url() == route('admin.approve.index')) {{'active'}} @endif" href="{{url('admin/gatepass_approvals/safety_report')}}">Safety Report</a>
    </li>
   @endif -->
 
    <li class="nav-item">
        <a class="nav-link @if(request()->url() == route('admin.show_password')) {{'active'}} @endif" href="{{ url('admin/show_password')}}">
        Change Password
        </a>
    </li>
    <li class="nav-item" style="display:none;">
       <!-- <a class="nav-link" href="{{ URL::to('public/documents/user_manualdt29052021.pdf')}}">Download User Manual  </a> -->
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"  onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <span data-feather=""> </span> Logout
        </a>
    </li>
</ul>
<hr>
<center><img src="{{ URL::to('images/footer.png')}}" style="width:65%"></center>
