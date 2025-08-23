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

<p style="margin-left:10%;margin-top:35%"><b>Hi <?php echo e(Session::get('user_nameSession')); ?> </b></p>
<ul class="nav flex-column mt-1">
        <li class="nav-item">
            <a class="nav-link <?php if(request()->url() == route('admin.dashboard')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('/admin/dashboard')); ?>">
            Dashboard <span class="sr-only">(current)</span>
            </a>
        </li>
    <?php if(Session::get('user_sub_typeSession') != 2): ?>
    <li class="nav-item dropdown">
        <a class="nav-link <?php if(request()->url() == route('admin.job.index')): ?> <?php echo e('active'); ?> <?php endif; ?> dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Masters  
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php if( (Session::get('user_sub_typeSession') == 1 ) || Session::get('user_sub_typeSession') == 3): ?>
            <a class="dropdown-item" href="<?php echo e(route('admin.job.index')); ?>">Job Category</a>
            <a class="dropdown-item" href="<?php echo e(route('admin.user.index')); ?>">Users</a>
            <?php endif; ?>
            <?php if(Session::get('user_sub_typeSession') == 3): ?>
                <a class="dropdown-item" href="<?php echo e(route('admin.division.index')); ?>">Divisions</a>
                <a class="dropdown-item" href="<?php echo e(route('admin.department.index')); ?>">Department</a>
            <?php endif; ?>
            
            <a class="dropdown-item" href="<?php echo e(route('admin.work-order.index')); ?>">Work Order</a>
            <a class="dropdown-item" href="<?php echo e(route('admin.gate_pass')); ?>">Gate Pass</a>
            <a class="dropdown-item" href="<?php echo e(route('admin.skill.index')); ?>">Skill</a>
            <a class="dropdown-item" href="<?php echo e(route('admin.settings_master.index')); ?>">Setting</a>
        </div>
    </li>
    <?php endif; ?>
    <?php if(Session::get('vms_role')!='Security' && Session::get('user_sub_typeSession') != 2 || Session::get('wps') == 'Yes'): ?>
    <li class="nav-item">
        <a class="nav-link <?php if(request()->url() == route('admin.request_permit.create')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/request_permit/create')); ?>">Request Permit to Work</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if(request()->url() == route('admin.list_permit.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/list_permit')); ?>"> List Permit to Work </a>
    </li>
    <?php endif; ?>
     <?php if(Session::get('user_sub_typeSession') != 2 ): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Permit to Work Report
    </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <?php if(Session::get('user_sub_typeSession') == 1 || Session::get('user_sub_typeSession') == 3): ?>
                <a class="dropdown-item <?php if(request()->url() == route('admin.report_list')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/report')); ?>">Permits</a>
                <a class="dropdown-item" href="<?php echo e(url('admin/expired-download')); ?>">Expired Gate Pass</a>
            
            <?php endif; ?>
        </div>
    </li>
     <?php endif; ?>

<?php if(Session::get('vms_yes_no') == 'Yes' || Session::get('user_sub_typeSession') == 3): ?>
<li class="nav-item dropdown">
        <a class="nav-link <?php if(request()->url() == route('admin.job.index')): ?> <?php echo e('active'); ?> <?php endif; ?> dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Visitor Gatepass
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            
            <?php if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && Session::get('vms_role') !='Approver') ): ?> 
           <a class="nav-link " href="<?php echo e(URL::to('RequestVGatepass')); ?>">Request Visitor Gatepass</a>
            <?php endif; ?>
             
            <?php if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))): ?> 
   
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/approve')); ?>">List Of Visitor's GatePass </a>
    
     <?php endif; ?>
<?php if(Session::get('user_sub_typeSession') == '3' || Session::get('vms_admin') == 'Yes'): ?>
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/vms_report')); ?>">VMS Report </a>
  <?php endif; ?>

        </div>
    </li>

<?php endif; ?>


    <!-- <?php if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && Session::get('vms_role') !='Approver') ): ?> 
	<li class="nav-item"> 
        <a class="nav-link " href="<?php echo e(URL::to('RequestVGatepass')); ?>">Request Visitor Gatepass</a>
    </li>
      <?php endif; ?>
       <?php if(Session::get('user_sub_typeSession') == 3  || (Session::get('vms_yes_no') == 'Yes' && (Session::get('vms_role') == 'Approver' || Session::get('vms_role') == 'Security' ||  Session::get('vms_role') == 'Requester'))): ?> 
	<li class="nav-item"> 
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/approve')); ?>">List Of Visitor's GatePass </a>
    </li>
     <?php endif; ?>
  
     <?php if(Session::get('user_sub_typeSession') == '3' || Session::get('vms_admin') == 'Yes'): ?>
  <li class="nav-item"> 
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/vms_report')); ?>">VMS Report </a>
    </li>
      <?php endif; ?>-->
   
<?php if(Session::get('clm_yes_no') == 'Yes' || Session::get('user_sub_typeSession') == '3' ): ?>
<li class="nav-item dropdown">
        <a class="nav-link <?php if(request()->url() == route('admin.job.index')): ?> <?php echo e('active'); ?> <?php endif; ?> dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Contractor Gatepass
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="top: 78px !important;"
        >
        <?php if(Session::get('user_sub_typeSession') == '3'  || (Session::get('clm_yes_no') == 'Yes' && (Session::get('status') != 'pending_clms_vendor'))): ?> 
   
        <a class="nav-link " href="<?php echo e(URL::to('CLMSGatepass')); ?>">Request Contractor Gatepass</a>
        <?php elseif(Session::get('user_sub_typeSession') == '2' && Session::get('clm_yes_no') == 'Yes' && Session::get('status') == 'pending_clms_vendor'): ?> 
       <a class="nav-link " href="<?php echo e(url('admin/vendor_details')); ?>">Request Contractor Gatepass</a>
         <?php endif; ?>

       <?php if(Session::get('user_sub_typeSession') == '3' || Session::get('user_sub_typeSession') == '1' || (Session::get('clm_yes_no') == 'Yes'  && (Session::get('status') != 'pending_clms_vendor' || Session::get('status') != 'Pending_for_hr' || Session::get('status') != 'pending_for_safety'))): ?>   
        <a class="nav-link <?php if(request()->url() == route('admin.approve_clms.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/approve_clms')); ?>">List Of Contractor's GatePass </a>
        <?php elseif(Session::get('user_sub_typeSession') == '2' && Session::get('clm_yes_no') == 'Yes' && Session::get('status') == 'pending_clms_vendor'): ?>
        <a class="nav-link " href="<?php echo e(url('admin/vendor_details')); ?>">List Of Contractor's GatePass</a>
        <?php elseif(Session::get('status') == 'Pending_for_hr' || Session::get('status') == 'pending_for_safety'): ?>
        <script>alert('Vendor Registration is pending')</script>
      <?php endif; ?>
    <?php if(Session::get('user_sub_typeSession') == '3' ||  Session::get('clms_admin') == 'Yes'): ?>
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/clms_report')); ?>">CLMS Report</a>
        <?php elseif(Session::get('user_sub_typeSession') == '2' && Session::get('clm_yes_no') == 'Yes' && Session::get('status') == 'pending_clms_vendor' &&  Session::get('clms_admin') == 'Yes'): ?>
 <a class="nav-link " href="<?php echo e(url('admin/vendor_details')); ?>">CLMS Report</a>
     <?php endif; ?>
     <a class="nav-link" href="<?php echo e(route('admin.daily_attendence_view')); ?>">Daily Attendence</a>
            <a class="nav-link" href="<?php echo e(route('admin.vendor_attendance.index')); ?>">Wage Register</a>
            <a class="nav-link" href="<?php echo e(route('admin.vendor_esic_details.index')); ?>">Vendor Esic Challan & Contribution</a>
            <a class="nav-link" href="<?php echo e(route('admin.vendor_pf_details.index')); ?>">Vendor PF Challan & ECR</a>
            <a class="nav-link" href="<?php echo e(route('admin.vendor_ecm.index')); ?>">Bonus Return / Filling</a>
            <a class="nav-link" href="<?php echo e(route('admin.vendor_hyr.index')); ?>">Half Yearly Return</a>
           <a class="nav-link" href="<?php echo e(route('admin.vendor_holiday.index')); ?>">Vendor Employee Leave List</a>
 <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/vendor_clms_pending_list')); ?>">Pending Vendor Registration</a>
        </div>
</li>
<?php endif; ?>


     <!--<?php if(Session::get('user_sub_typeSession') == '3'  || Session::get('clm_yes_no') == 'Yes'): ?> 
    <li class="nav-item"> 
        <a class="nav-link " href="<?php echo e(URL::to('CLMSGatepass.blade.php')); ?>">Request Contractor Gatepass</a>
    </li>
    <li class="nav-item"> 
        <a class="nav-link <?php if(request()->url() == route('admin.approve_clms.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/approve_clms')); ?>">List Of Contractor's GatePass </a>
    </li>
       <?php endif; ?>
        <?php if(Session::get('user_sub_typeSession') == '3' ||  Session::get('clms_admin') == 'Yes'): ?>
       <li class="nav-item" > 
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/clms_report')); ?>">CLMS Report</a>
    </li>
     <?php endif; ?>-->
     <?php if(Session::get('user_sub_typeSession') == '3'  || Session::get('safety_yes_no') == 'Yes'): ?>
<li class="nav-item dropdown">
        <a class="nav-link <?php if(request()->url() == route('admin.job.index')): ?> <?php echo e('active'); ?> <?php endif; ?> dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       Safety Data 
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

 <?php if(Session::get('user_sub_typeSession') == '3'  || Session::get('safety_yes_no') == 'Yes'): ?> 
   
        <a class="nav-link " href="<?php echo e(URL::to('Safety_data_entry')); ?>">Safety Data Entry</a>
 
     
  
    
        <a class="nav-link <?php if(request()->url() == route('admin.safety_data_view.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/safety_data_view')); ?>">List Of Safety View </a>
    
<?php endif; ?>
 <?php if(Session::get('user_sub_typeSession') == '3' || Session::get('safety_admin') == 'Yes'): ?>

        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/safety_report')); ?>">Safety Report</a>
  
   <?php endif; ?>

        </div>
</li>
<?php endif; ?>
   <!--    <?php if(Session::get('user_sub_typeSession') == '3'  || Session::get('safety_yes_no') == 'Yes'): ?> 
     <li class="nav-item"> 
        <a class="nav-link " href="<?php echo e(URL::to('Safety_data_entry')); ?>">Safety Data Entry</a>
    </li>
     
  
     <li class="nav-item"> 
        <a class="nav-link <?php if(request()->url() == route('admin.safety_data_view.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/safety_data_view')); ?>">List Of Safety View </a>
    </li>
<?php endif; ?>
 <?php if(Session::get('user_sub_typeSession') == '3' || Session::get('safety_admin') == 'Yes'): ?>
<li class="nav-item"> 
        <a class="nav-link <?php if(request()->url() == route('admin.approve.index')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/gatepass_approvals/safety_report')); ?>">Safety Report</a>
    </li>
   <?php endif; ?> -->
 
    <li class="nav-item">
        <a class="nav-link <?php if(request()->url() == route('admin.show_password')): ?> <?php echo e('active'); ?> <?php endif; ?>" href="<?php echo e(url('admin/show_password')); ?>">
        Change Password
        </a>
    </li>
    <li class="nav-item" style="display:none;">
       <!-- <a class="nav-link" href="<?php echo e(URL::to('public/documents/user_manualdt29052021.pdf')); ?>">Download User Manual  </a> -->
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('logout')); ?>"  onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
            <span data-feather=""> </span> Logout
        </a>
    </li>
</ul>
<hr>
<center><img src="<?php echo e(URL::to('images/footer.png')); ?>" style="width:65%"></center>
<?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/partials/navbar.blade.php ENDPATH**/ ?>