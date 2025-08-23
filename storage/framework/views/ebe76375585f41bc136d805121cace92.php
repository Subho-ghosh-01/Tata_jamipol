
<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Reports</a></li>
<?php $__env->stopSection(); ?>

<?php if(Session::get('user_sub_typeSession') == 2): ?>
    return redirect('admin/dashboard');
<?php else: ?>
<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Reports</h1>
    </div>
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
    <form class="form-inline" autocomplete=off action="<?php echo e(route('admin.listall_report')); ?>" method="get">
        <div class="form-group mb-2">
            <select class="form-control" name="divi_id" onchange="getDepartment(this,this.value)">
                <option value="">ALL Division</option>
                <?php if($divisions->count() > 0): ?>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($division->id); ?>"><?php echo e($division->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>
         <div class="form-group mb-2">
            <select class="form-control" name="dept_id" id="department_id">
                <option value="">ALL Department</option>
            </select>
        </div>
        <div class="form-group mx-sm-2 mb-2">
            <input type="text" name="fromdate" class="form-control" placeholder="from Date" id="start_date" required>
        </div>
        <div class="form-group mb-2">
            <input type="text" name="todate" class="form-control" placeholder="To Date" id="end_date" required>
        </div>
        <div class="form-group mx-sm-2 mb-2">
            <input type="submit" name="submit" class="btn btn-primary" value="Find Report">
        </div>
    </form>
 
    <div class="table-responsive">
        <table id="example" class="display table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Sl No.</th>
                    <th>Permit Request Date</th>
                    <th>Permit Start Date</th>
                    <th>Permit End Date</th>
                    <th>Permit Sl. No.</th>
                    <th>Requester Name</th>
                    <th>Issuer Name</th>
                    <th>Division</th>
                    <th>Department</th>
                    <th>Supervisor Name</th>
                    <th>Employees</th>
					<th>Power Cutting Details</th>
					<th>Power Getting Details</th>
                    <th>Status</th>
                    <th>View</th>
                 </tr>
            </thead>
            <tbody>
                                            <?php if($permits): ?>
                            <?php $count=1 ?>
                            <?php $__currentLoopData = $permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                @$user_name = DB::table('userlogins')->where('id',$permit->entered_by)->get();
                                @$issuername = DB::table('userlogins')->where('id',$permit->issuer_id)->get();
                                @$divname   = DB::table('divisions')->where('id',$permit->division_id)->get();
                                @$deptname  = DB::table('departments')->where('id',$permit->department_id)->get();
                                @$supname  = DB::table('vendor_supervisors')->where('id',$permit->permit_req_name)->get();
								@$power_clearances = DB::table('power_clearences')->where('permit_id',$permit->id)->get();
								@$gate_pass_details = DB::table('gate_pass_details')->where('permit_id',$permit->id)->get();
                            ?>
                            <tr>
                                <td><?php echo e($count++); ?></td>
                                <td><?php echo e(date('d/m/Y ', strtotime($permit->created_at ?? '' ))); ?> </td>
								<td><?php echo e(date('d/m/Y H:i:s', strtotime($permit->start_date ?? '' ))); ?></td>
								<td><?php echo e(date('d/m/Y H:i:s', strtotime($permit->end_date ?? '' ))); ?></td>
                                <td>
                                    <?php
                                        @$cc=@$permit->created_at;
                                        @$month = date('m', strtotime($cc));
                                        @$abb = DB::table('divisions')->where('id',$permit->division_id)->first();
                                        echo @$abb->abbreviation;
                                    ?>/<?php echo e(@$month); ?>/<?php echo e(@$permit->serial_no); ?>

                                </td>
                                <td><?php echo e(@$user_name[0]->name); ?></td>  
                                <td><?php echo e(@$issuername[0]->name); ?></td>  
                                <td><?php echo e(@$divname[0]->name); ?></td>  
                                <td><?php echo e(@$deptname[0]->department_name); ?></td>  
								<td><?php echo e(@$supname[0]->supervisor_name); ?> </td>
								<td> <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#000000">
			<tr>
				<td width="360"><font face="Arial" size="2">Name</font></td>
				<td><font face="Arial" size="2">P No./Gate Pass No.</font></td>
				<td><font face="Arial" size="2">Designation</font></td>
				<td><font face="Arial" size="2">Age</font></td>
				<td><font face="Arial" size="2">Expiry Date</font></td>
			</tr>
			<?php if($gate_pass_details->count() > 0): ?>
					<?php $__currentLoopData = $gate_pass_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $value2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<tr>
				<td width="360"><font face="Arial" size="2"><?php echo e($gate_pass_details[$key2]->employee_name); ?></font></td>
				<td><font face="Arial" size="2"><?php echo e($gate_pass_details[$key2]->gate_pass_no); ?></font></td>
				<td><font face="Arial" size="2"><?php echo e($gate_pass_details[$key2]->designation); ?></font></td>
				<td><font face="Arial" size="2"><?php echo e($gate_pass_details[$key2]->age); ?></font></td>
				<td><font face="Arial" size="2"><?php echo e($gate_pass_details[$key2]->expirydate); ?></font></td>
			</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
		</table></td>
								<td><?php if($permit->power_clearance_number !=""): ?>
				Permit no.: <strong><u><?php echo e(@$permit->power_clearance_number); ?> </u></strong><br> 
				Power Cutting Remarks: <strong><u> <?php echo e(@$$permit->power_cutting_remarks); ?></u></strong><br>
			    Power Cutting User:  <strong><u> <?php $pcuser  = DB::table('userlogins')->where('id',@$$permit->ppc_userid)->first(); ?> <?php echo e(@$pcuser->name); ?></u></strong><br>
			    Time: <strong><u>  <?php if(@$permit->power_cutting_user_dt): ?> <?php echo e(date('d-m-Y H:i',@strtotime(@$permit->power_cutting_user_dt))); ?>  <?php endif; ?> </u></strong><br>
		    	Executing Personal Lock Number: <strong><u> <?php echo e(@$permit->executing_lock); ?> </u></strong><br>
		    	Working Personal Lock Number: <strong><u> <?php echo e(@$permit->working_lock); ?> </u></strong><br>
		    	<?php if($power_clearances->count() > 0): ?>

	                <?php $__currentLoopData = $power_clearances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                    (<?php echo e($key+1); ?>) Equipment Lock No.:<strong> <u> <?php echo e($power_clearances[$key]->positive_isolation_no); ?> </u></strong>,
	                    Box No.: <strong><u> <?php echo e($power_clearances[$key]->box_no); ?> </u></strong><br> 
	                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
		    <?php endif; ?> </td>
								<td> <?php if($permit->pg_number !=""): ?>
				Permit no. <strong><u><?php echo e($permit->pg_number); ?></u></strong><br>
				<?php $pg_comment  =  DB::table('power_getting')->where('id', $permit->pg_id)->first(); ?>
				Power Getting Remarks: <strong><u> <?php echo e(@$pg_comment->power_cutting_comment); ?></u> </strong><br>
				Power Getting User:  <strong><u> <?php $pguser  = DB::table('userlogins')->where('id',@$permit->ppg_userid)->first(); ?> <?php echo e(@$pguser->name); ?></u></strong><br>
			    Time: <strong><u> <?php if(@$permit->pg_action_dt): ?> <?php echo e(date('d-m-Y H:i',@strtotime(@$permit->pg_action_dt))); ?>  <?php endif; ?> </u></strong>
			<?php endif; ?></td>
               <td><?php if(@$permit->status == "Prcv"): ?>
                                        <?php echo e("Permit to be Received"); ?>

                                    <?php else: ?>
                                    <?php echo e(@$permit->status); ?>

                                    <?php endif; ?>
                                    </td>  
                                <td><a class="btn btn-info btn-sm" href="<?php echo e(route('admin.report_view',\Crypt::encrypt($permit->id))); ?>">View</a></td>  
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="" style="color:red;text-align:center;">Not Available</td>
                        <tr>
                    <?php endif; ?>
                </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" integrity="sha512-bYPO5jmStZ9WI2602V2zaivdAnbAhtfzmxnEGh9RwtlI00I9s8ulGe4oBa5XxiC6tCITJH/QG70jswBhbLkxPw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script> 
    $('#start_date').datetimepicker({
        format:'Y/m/d'
    });
    $('#end_date').datetimepicker({
        format:'Y/m/d'
    });    
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
            ]
        });
    });
    function getDepartment(th,divisionID) {
        if(divisionID!="")
        {
            $("#department_id").html('<option value="ALL">ALL</option>');
            if(divisionID)
            {
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'GET',
                    url:"<?php echo e(route('admin.job.department')); ?>/" + divisionID,
                    contentType:'application/json',
                    dataType:"json",
                    success:function(data){
                        for(var i=0;i<data.length;i++){
                            $('#department_id').append('<option value="'+data[i].id+'" >'+data[i].department_name+'</option>');
                        }
                    }
                });
            }else{
                $('#department_id').html('<option value="">Select Department</option>');
            }     
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/report/index.blade.php ENDPATH**/ ?>