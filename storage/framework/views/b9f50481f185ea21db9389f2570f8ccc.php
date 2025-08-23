<?php 
use App\Department;
use App\UserLogin;
use App\ChangeRequest;
?>


<?php $__env->startSection('breadcrumbs'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.approve.index')); ?>">List Of Visitor's GatePass</a></li>
<?php $__env->stopSection(); ?>                        
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List of Visitor's GatePass </h1>
</div>
<?php if(Session::get('vms_role') == 'Approver'): ?>
 <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> Pending For Approval</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Approved/Rejected</a>
        </li>
        
 </ul>
 <?php endif; ?>
 <?php if(Session::get('vms_role') == 'Security'): ?>
 <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab_sec" data-toggle="tab" href="#home_security" role="tab" aria-controls="home_security" aria-selected="true">Issued GatePass</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab_sec" data-toggle="tab" href="#profile_security" role="tab" aria-controls="profile_security" aria-selected="false">Returned GatePass</a>
        </li>
        
 </ul>
 <?php endif; ?>
<?php if(Session::get('vms_role') == 'Requester'): ?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home_requester" role="tab" aria-controls="home" aria-selected="true"> Pending For Approval</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " id="home-tab" data-toggle="tab" href="#home_requester_returned" role="tab" aria-controls="home" aria-selected="true">Approved/Rejected</a>
        </li>
        
 </ul>
<?php endif; ?>
 
<form action="<?php echo e(route('admin.approve.index')); ?>" method="POST" enctype="multipart/form-data">
	<?php if(Session::get('vms_role') == 'Approver'): ?>
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
                    <?php if($gatepasss->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                        <?php $__currentLoopData = $gatepasss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatepass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td><?php echo e($count ++); ?></td>
                               
                                  <td><?php echo e($gatepass->full_sl); ?></td>
                                  <td><?php echo e($gatepass->visitor_name); ?></td>
                                  <td><?php echo e($gatepass->visitor_mobile_no); ?></td>
                                  <td><?php echo e($gatepass->visitor_company); ?></td>
                                  <td><?php echo e($gatepass->from_date); ?></td>
                                  <td><?php echo e($gatepass->to_date); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepass->from_time))); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepass->to_time))); ?></td>
                                  <!--<td><?php echo e($gatepass->status); ?></td>-->
								  
								  
								 <td> <?php if($gatepass->status == "Pending_to_approve"): ?>
                                            <?php echo e("Pending To Approve"); ?>

                                        <?php elseif($gatepass->status == "issued"): ?>
                                            <?php echo e("Issued"); ?>

								  <?php elseif($gatepass->status == "Rejected"): ?>
                                            <?php echo e("Rejected"); ?>

								  <?php elseif($gatepass->status == "Completed"): ?>
                                            <?php echo e("Completed"); ?>

											<?php endif; ?> 
								  </td>
								  
                                <td>

                                <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($gatepass->id))); ?>" title="Edit">Details</a>
                               <?php if($gatepass->status=='Approved' ): ?> 
							   <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.printg.printg',\Crypt::encrypt($gatepass->id))); ?>" title="Edit">Print</a>
						    <?php endif; ?> 
                                <!--<a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',$gatepass->id)); ?>" title="Edit">Details</a>-->
                                <!--<a class="btn btn-success btn-sm" onclick="deleteRecord('<?php echo e($gatepass->id); ?>')">Approve</a>
                                <a class="btn btn-danger btn-sm" onclick="deleteRecord('<?php echo e($gatepass->id); ?>')">Reject</a>-->
                                   
                                
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    <?php endif; ?> 
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
                    <?php if($gatepassss->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                        <?php $__currentLoopData = $gatepassss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatepasst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td><?php echo e($count ++); ?></td>
                               
                                  <td><?php echo e($gatepasst->full_sl); ?></td>
                                  <td><?php echo e($gatepasst->visitor_name); ?></td>
                                  <td><?php echo e($gatepasst->visitor_mobile_no); ?></td>
                                  <td><?php echo e($gatepasst->visitor_company); ?></td>
                                  <td><?php echo e($gatepasst->from_date); ?></td>
                                    <td><?php echo e($gatepasst->to_date); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepasst->from_time))); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepasst->to_time))); ?></td>
                                
								  <td> <?php if($gatepasst->status == "Pending_to_approve"): ?>
                                            <?php echo e("Pending To Approve"); ?>

                                        <?php elseif($gatepasst->status == "issued"): ?>
                                            <?php echo e("Issued"); ?>

											<?php elseif($gatepasst->status == "Rejected"): ?>
                                            <?php echo e("Rejected"); ?>

											<?php elseif($gatepasst->status == "Completed"): ?>
                                            <?php echo e("Completed"); ?>

											<?php endif; ?> 
											</td>
                                <td>

                                <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($gatepasst->id))); ?>" title="Edit">Details</a>
                               <?php if($gatepasst->status=='Approved'): ?> 
							   <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.printg.printg',\Crypt::encrypt($gatepasst->id))); ?>" title="Edit">Print</a>
						    <?php endif; ?> 
                              
                                   
                                
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    <?php endif; ?> 
                </tbody>
                </table>
            </div>           
        </div>

    </div>
<?php endif; ?>
	<?php if(Session::get('vms_role') == 'Security'): ?>
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
                    <?php if($gatepasss_sec->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                        <?php $__currentLoopData = $gatepasss_sec; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatepass_secc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td><?php echo e($count ++); ?></td>
                               
                                  <td><?php echo e($gatepass_secc->full_sl); ?></td>
                                  <td><?php echo e($gatepass_secc->visitor_name); ?></td>
                                  <td><?php echo e($gatepass_secc->visitor_mobile_no); ?></td>
                                  <td><?php echo e($gatepass_secc->visitor_company); ?></td>
                                  <td><?php echo e($gatepass_secc->from_date); ?></td>
                                  <td><?php echo e($gatepass_secc->to_date); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepass_secc->from_time))); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepass_secc->to_time))); ?></td>
                                  <!--<td><?php echo e($gatepass_secc->status); ?></td>-->
								  <td>
								  <?php if($gatepass_secc->status == "Pending_to_approve"): ?>
                                  <?php echo e("Pending To Approve"); ?>

								<?php elseif($gatepass_secc->status == "issued"): ?>
                                   <?php echo e("Issued"); ?>

								 <?php endif; ?>
										</td>
                                <td>

                                <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($gatepass_secc->id))); ?>" title="Edit">Details</a>
                               <?php if($gatepass_secc->status=='issued'): ?> 
							   <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.printg.printg',\Crypt::encrypt($gatepass_secc->id))); ?>" title="Edit">Print</a>
						    <?php endif; ?> 
                              
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    <?php endif; ?> 
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
                    <?php if($gatepasss_sec_com->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                        <?php $__currentLoopData = $gatepasss_sec_com; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatepass_complete): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td><?php echo e($count ++); ?></td>
                               
                                  <td><?php echo e($gatepass_complete->full_sl); ?></td>
                                  <td><?php echo e($gatepass_complete->visitor_name); ?></td>
                                  <td><?php echo e($gatepass_complete->visitor_mobile_no); ?></td>
                                  <td><?php echo e($gatepass_complete->visitor_company); ?></td>
                                  <td><?php echo e($gatepass_complete->from_date); ?></td>
                                  <td><?php echo e($gatepass_complete->to_date); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepass_complete->from_time))); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepass_complete->to_time))); ?></td>
                                  <!--<td><?php echo e($gatepass_complete->status); ?></td>-->
								  <td><?php if($gatepass_complete->status == "Pending_to_approve"): ?>
                                  <?php echo e("Pending To Approve"); ?>

							   <?php elseif($gatepass_complete->status == "issued"): ?>
                                   <?php echo e("Issued"); ?>

								   <?php elseif($gatepass_complete->status == "Rejected"): ?>
                                   <?php echo e("Rejected"); ?>

								   <?php elseif($gatepass_complete->status == "Completed"): ?>
                                   <?php echo e("Completed"); ?>

							        <?php endif; ?>
							  </td>
                                <td>

                                <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($gatepass_complete->id))); ?>" title="Edit">Details</a>
                               
							  <!-- <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.printg.printg',\Crypt::encrypt($gatepass_complete->id))); ?>" title="Edit">Print</a>-->
						    
                                 
                                
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    <?php endif; ?> 
                </tbody>
                </table>
            </div>           
        </div>

    </div>
<?php endif; ?>



<?php if(Session::get('vms_role') == 'Requester'): ?>
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
                    <?php if($gatepasss_requester->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                        <?php $__currentLoopData = $gatepasss_requester; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatepasss_requester_r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td><?php echo e($count ++); ?></td>
                               
                                  <td><?php echo e($gatepasss_requester_r->full_sl); ?></td>
                                  <td><?php echo e($gatepasss_requester_r->visitor_name); ?></td>
                                  <td><?php echo e($gatepasss_requester_r->visitor_mobile_no); ?></td>
                                  <td><?php echo e($gatepasss_requester_r->visitor_company); ?></td>
                                  <td><?php echo e($gatepasss_requester_r->from_date); ?></td>
                                  <td><?php echo e($gatepasss_requester_r->to_date); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepasss_requester_r->from_time))); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepasss_requester_r->to_time))); ?></td>
                                
                                  <td>
                                  <?php if($gatepasss_requester_r->status == "Pending_to_approve"): ?>
                                  <?php echo e("Pending To Approve"); ?>

                                <?php elseif($gatepasss_requester_r->status == "issued"): ?>
                                   <?php echo e("Issued"); ?>

                                 <?php endif; ?>
                                        </td>
                                <td>

                                <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($gatepasss_requester_r->id))); ?>" title="Edit">Details</a>
                              
                              
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    <?php endif; ?> 
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
                    <?php if($gatepasss_requester_returned->count() > 0 ): ?> 
                    <?php $count =  1 ;?>
                        <?php $__currentLoopData = $gatepasss_requester_returned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gatepasss_requester_retu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <tr>
                                <td><?php echo e($count ++); ?></td>
                               
                                  <td><?php echo e($gatepasss_requester_retu->full_sl); ?></td>
                                  <td><?php echo e($gatepasss_requester_retu->visitor_name); ?></td>
                                  <td><?php echo e($gatepasss_requester_retu->visitor_mobile_no); ?></td>
                                  <td><?php echo e($gatepasss_requester_retu->visitor_company); ?></td>
                                  <td><?php echo e($gatepasss_requester_retu->from_date); ?></td>
                                  <td><?php echo e($gatepasss_requester_retu->to_date); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepasss_requester_retu->from_time))); ?></td>
                                  <td><?php echo e(date('h:i A', strtotime(@$gatepasss_requester_retu->to_time))); ?></td>
                                
                                  <td>
                                  <?php if($gatepasss_requester_retu->status == "Pending_to_approve"): ?>
                                  <?php echo e("Pending To Approve"); ?>

                                <?php elseif($gatepasss_requester_retu->status == "issued"): ?>
                                   <?php echo e("Issued"); ?>

							<?php elseif($gatepasss_requester_retu->status == "Completed"): ?>
                                   <?php echo e("Completed"); ?>

                                 <?php endif; ?>
                                        </td>
                                <td>

                                <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($gatepasss_requester_retu->id))); ?>" title="Edit">Details</a>
                              
                              
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>            
                    <tr>
                        <td colspan="10" class="" style="color:red;text-align:center;">No GatePass Found !!!</td>
                    </tr>
                    <?php endif; ?> 
                </tbody>
                </table>
            </div>           
        </div>


        <div class="tab-pane fade" id="profile_security" role="tabpanel" aria-labelledby="profile-tab_sec">
                
        </div>

    </div>
<?php endif; ?>


</form>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#my-permit').DataTable();
    });
    $(document).ready(function() {
        $('#my-permit1').DataTable();
    });
    
    

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/approve.blade.php ENDPATH**/ ?>