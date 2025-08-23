<?php 
use App\UserLogin;
use App\ChangeRequest;
use App\Permit;
use App\Job;
use App\PowerCutting;

?>

<?php $__env->startSection('breadcrumbs'); ?>
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
    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">List Permits </a></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">List Permits </h1>
</div>
    <!-- Show Message Success -->
    <div class="form-group-row">
        <div class="col-sm-12" style="text-align:center;">
            <?php if(session()->has('message')): ?>
            <div class="alert alert-success">
                <?php echo e(session('message')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Error List -->
    <div class="form-group-row">
        <div class="col-sm-12">
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
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
                    <?php if($my_pending_permits->count() > 0): ?>
                        <?php $count=1 ?>
                            <!-- we r geting permit deatils "my_pending_permit" -->
                            <?php $__currentLoopData = $my_pending_permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $my_pending_permit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $user_data = DB::table('userlogins')->where('id',$my_pending_permit->entered_by)->get();
                            ?>
                                <tr>
                                    <td><?php echo e($count++); ?></td>
                                    <td><?php echo e(date('d/m/Y ', strtotime($my_pending_permit->created_at ?? '' ))); ?> </td>
                                    <td>
                                        <?php
                                            $cc=$my_pending_permit->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',$my_pending_permit->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e($my_pending_permit->serial_no); ?>

                                    </td>
                                    <td><?php echo e($my_pending_permit->order_no); ?></td> 
                                    <?php 
                                        $job = Job::where('id',$my_pending_permit->job_id)->first();
                                    ?>
                                    <td><?php echo e(@$job->job_title); ?></td>  

                                    <td>
                                        <?php if($my_pending_permit->status == "Cancel"): ?>
                                            <?php echo e("Cancel Permit"); ?>

                                        <?php elseif($my_pending_permit->status == "Issued"): ?>
                                            <?php echo e("Issued"); ?>

                                            <?php if($my_pending_permit->return_status == 'Pending'): ?> 
                                                <?php echo e("/Return Pending at Executing Agency"); ?> 
                                            <?php elseif($my_pending_permit->return_status == "Pending_area"): ?> 
                                                <?php echo e("/Return Pending at Owner Agency"); ?>

                                            <?php elseif($my_pending_permit->return_status == "Power_Getting"): ?> 
                                                <?php echo e("/Return Pending Power Getting User"); ?>

                                            <?php elseif($my_pending_permit->return_status == 'PPg'): ?> 
                                                <?php echo e("/Return Pending at Power Getting User"); ?> 
                                            <?php endif; ?>                                           
                                        <?php elseif($my_pending_permit->status == "Requested"): ?>
                                        <?php $issuer = UserLogin::where('id',$my_pending_permit->issuer_id)->get(); ?>
                                            <?php echo e("Pending with Executing Agency"); ?>(<?php echo e(@$issuer[0]->name); ?>)
                                        <?php elseif($my_pending_permit->status == "Parea"): ?>
                                        <?php $area_clearance = UserLogin::where('id',$my_pending_permit->area_clearence_id)->get(); ?>
                                            <?php echo e("Pending with Owner Agency"); ?> (<?php echo e(@$area_clearance[0]->name); ?>)
                                        <?php elseif(($my_pending_permit->status == "Returned")): ?> 
                                            <?php echo e('Permit Returned'); ?>

                                        <?php elseif(($my_pending_permit->status == "PPc")): ?> 
                                            <?php $pcutname = UserLogin::where('id',@$my_pending_permit->ppc_userid)->first(); ?>
                                            <?php echo e('Permit Pending at Power Cutting'); ?>(<?php echo e(@$pcutname->name); ?>)
                                        <?php endif; ?>    
                                    </td>
                                    <td>
                                        <?php if($my_pending_permit->status == "Issued"): ?>
                                            <a class="btn btn-info btn-sm" href="<?php echo e(URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($my_pending_permit->id))); ?>">Download Permit</a> 

                                        <?php elseif($my_pending_permit->status == "Return_Requester"): ?>
                                            
                                        <?php endif; ?>

                                        <?php if($my_pending_permit->status == "Issued"): ?>
                                            <?php if($my_pending_permit->status != "Returned" && ($my_pending_permit->renew_id_1 == "" || $my_pending_permit->renew_id_2 == "")): ?> 
                                                <a class="btn btn-info btn-sm" data-id="<?php echo e($my_pending_permit->id); ?>" data-toggle="modal" data-target="#RenewPermit" href="">Renew</a>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($my_pending_permit->status == "Issued" && $my_pending_permit->return_status != "Pending" && $my_pending_permit->return_status != "Pending_area" && $my_pending_permit->return_status != "PPg"): ?>    
                                            <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.lp.return',\Crypt::encrypt($my_pending_permit->id))); ?>">Return</a>
                                        <?php endif; ?> 
                                        <!-- Return Code -->
                                        <?php if(($my_pending_permit->status == "Returned")): ?> 
                                            <a class="btn btn-info btn-sm" href="<?php echo e(URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($my_pending_permit->id))); ?>">Download Permit</a>
                                        <?php endif; ?>   
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                        <tr>
                    <?php endif; ?>
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
                        <?php if($issuer_datas->count() > 0): ?>
                            <?php $count=1 ?>
                                <?php $__currentLoopData = $issuer_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issuer_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $user_data2 = DB::table('userlogins')->where('id',$issuer_data->entered_by)->first();

                                    ?>
                                    <tr>
                                        <td><?php echo e($count++); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($issuer_data->created_at ?? ''))); ?></td>
                                        <td>
                                        <?php
                                            $cc=$issuer_data->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',$issuer_data->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e($issuer_data->serial_no); ?></td>

                                        <td><?php echo e(@$user_data2->name); ?></td>
                                        <td><?php echo e(@$user_data2->vendor_code); ?></td>
                                        <td><?php echo e((@$user_data2->user_type == 1) ? 'Employee' : 'Vendor'); ?>   
                                        </td>        
                                        <?php 
                                            $job = Job::where('id',$issuer_data->job_id)->first();
                                        ?>
                                        <td><?php echo e(@$job->job_title); ?></td>                           
                                        <td>
                                            <?php if($issuer_data->status == "Parea"): ?> 
                                                <?php $area_clearance = UserLogin::where('id',$issuer_data->area_clearence_id)->first(); ?>
                                                <?php echo e('Pending with Owner Agency'); ?>  (<?php echo e(@$area_clearance->name); ?>)
                                            <?php else: ?>
                                                <?php echo e('Requested'); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>                                        
                      <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.list_permit.edit',\Crypt::encrypt($issuer_data->id))); ?>">Issue</a>
			    	<a class="btn btn-danger btn-sm"  data-id="<?php echo e($issuer_data->id); ?>"  data-toggle="modal" data-target="#c_permit"> Reject</a>
											
                                        </td>                                   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>
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
                        <?php if($issued_permits->count() > 0): ?>
                            <?php $count=1 ?>
                                <?php $__currentLoopData = $issued_permits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issuer2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $user_data2 = DB::table('userlogins')->where('id',$issuer2->entered_by)->get();
                                    ?>
                                    <tr>
                                        <td><?php echo e($count++); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($issuer2->created_at ?? ''))); ?></td>
                                        <td>
                                        <?php
                                            $cc=$issuer2->created_at;
                                            $month = date('m-Y', strtotime($cc));

                                            $abb = DB::table('divisions')->where('id',$issuer2->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e($issuer2->serial_no); ?></td>

                                        <td><?php echo e($user_data2[0]->name); ?></td>
                                        <td><?php echo e($user_data2[0]->vendor_code); ?></td>
                                        <td><?php if($user_data2[0]->user_type == 1): ?>
                                                <?php echo e('Employee'); ?>

                                            <?php else: ?>
                                                <?php echo e('Vendor'); ?>    
                                            <?php endif; ?>
                                        </td>            

                                        <?php 
                                            $job = Job::where('id',$issuer2->job_id)->first();
                                        ?>
                                        <td><?php echo e(@$job->job_title); ?></td>

                                        <td><?php if($issuer2->status == "Requested"): ?> <?php echo e("Pending with Executing Agency"); ?> 
                                            <?php elseif($issuer2->status == "Parea"): ?> <?php echo e("Pending with Area Clearance Officer"); ?>

                                            <?php elseif($issuer2->status == "Issued"): ?> <?php echo e('Permit Issued'); ?>

                                            <?php elseif($issuer2->status == "Returned"): ?> <?php echo e('Permit Returned'); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($issuer2->status == "Issued"): ?> 
                                                <a class="btn btn-danger btn-sm"  data-id="<?php echo e($issuer2->id); ?>"  data-toggle="modal" data-target="#c_permit"> Cancel</a>&nbsp;
                                            <?php endif; ?>
                                            <a class="btn btn-success btn-sm" href="<?php echo e(URL('admin/permit/text=IUAjJCUmKmFiY2RSb2hpdDE4MTIxOTk2Wlla/'.base64_encode($issuer2->id))); ?>">Download Permit</a>
                                        </td>                                   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>                      
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
                        <?php if($pending_for_returns->count() > 0): ?>
                            <?php $count=1 ?>
                                <?php $__currentLoopData = $pending_for_returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pending_for_return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $permit_id = DB::table('permits')->where('id',@$pending_for_return->id)->get();
                                        $username = DB::table('userlogins')->where('id',@$pending_for_return->entered_by)->get();
                                    ?>
                                    <tr>
                                        <td><?php echo e($count++); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($pending_for_return->created_at))); ?></td>
                                        <td>
                                        <?php
                                            $cc= $permit_id[0]->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',@$permit_id[0]->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e(@$permit_id[0]->serial_no); ?>

                                        </td>
                                        <td><?php echo e($username[0]->name ?? ''); ?></td>
                                        <td><?php echo e($username[0]->vendor_code ?? ''); ?></td>                 
                                        <td><?php echo e($pending_for_return->status); ?>

                                        <?php if($pending_for_return->return_status == 'Pending_area'): ?>
                                         <?php echo e("/Return Pending at Owner Agency"); ?> 
                                        <?php elseif($pending_for_return->return_status == 'Power_Getting'): ?> 
                                            <?php echo e("/Return Pending at Power Getting"); ?>

                                        <?php else: ?> <?php echo e("/Return Pending at Executing Agency"); ?> <?php endif; ?></td>
                                        <td>
                                        <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.lp.return',\Crypt::encrypt($pending_for_return->id))); ?>">Approve</a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>
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
                        <?php if($renew_lists->count() > 0): ?>
                            <?php $count=1 ?>
                            <?php $__currentLoopData = $renew_lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $renew_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $permit_date = DB::table('permits')->where('id',@$renew_list->permit_id)->get();
                                    $username    = DB::table('userlogins')->where('id',@$permit_date[0]->entered_by)->get();
                                ?>
                                <tr>
                                    <td><?php echo e($count++); ?></td>
                                    <td><?php echo e(date('d/m/Y H:i:s', strtotime($renew_list->datetime_apply))); ?></td>
                                    <td><?php
                                            $cc= $permit_date[0]->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',@$permit_date[0]->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e(@$permit_date[0]->serial_no); ?>

                                    </td>
                                    <td><?php echo e($username[0]->name ?? ''); ?></td>
                                    <td><?php echo e($username[0]->vendor_code ?? ''); ?></td>                 
                                    <td><?php echo e($renew_list->status); ?> </td>
                                    <td><a class="btn btn-info btn-sm" href="<?php echo e(route('admin.renew_view',\Crypt::encrypt($renew_list->id))); ?>">Issue</a></td>
                                                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>
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
                        <?php if($expiryPermits->count() > 0): ?>
                            <?php $count=1 ?>
                                <?php $__currentLoopData = $expiryPermits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expiryPermit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $permit_id = DB::table('permits')->where('id',@$expiryPermit->id)->get();
                                        $username  = DB::table('userlogins')->where('id',@$expiryPermit->entered_by)->get();
                                    ?>
                                    <tr>
                                        <td><?php echo e($count++); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($expiryPermit->created_at))); ?></td>
                                        <td>
                                        <?php
                                            $cc= $permit_id[0]->created_at;
                                            $month = date('m-Y', strtotime($cc));
                                            $abb = DB::table('divisions')->where('id',@$permit_id[0]->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e(@$permit_id[0]->serial_no); ?>

                                        </td>
                                        <td><?php echo e($username[0]->name ?? ''); ?></td>
                                        <td><?php echo e($username[0]->vendor_code ?? ''); ?></td>                 
                                        <td><?php echo e($expiryPermit->status); ?> </td>
                                        <td>
                                        <a class="btn btn-info btn-sm" href="<?php echo e(route('admin.expnotify',$expiryPermit->id)); ?>" onclick="return confirm('Are you sure to send email to requester')">Notify</a>
                                        </td>                                   
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>

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
                        <?php if($powerCuttings->count() > 0): ?>
                            <?php $count=1 ?>
                            <?php $__currentLoopData = $powerCuttings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $powerCutting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                <?php
                                    $enterBy = DB::table('userlogins')->where('id',$powerCutting->entered_by)->first();
                                ?>
                                    <td><?php echo e($count++); ?></td>
                                    <td><?php echo e(date('d/m/Y', strtotime($powerCutting->created_at))); ?></td>
                                    <td>
                                    <?php
                                        $month = date('m-Y', strtotime($powerCutting->created_at));
                                        $abb = DB::table('divisions')->where('id',$powerCutting->division_id)->first();
                                        echo @$abb->abbreviation .'/'. @$month .'/' .@$powerCutting->serial_no;
                                    ?>
                                    </td>
                                        <td><?php echo e(@$enterBy->name); ?></td>
                                        <td><?php echo e(@$enterBy->vendor_code); ?></td>
                                        <td>
                                            <?php if($powerCutting->status == "PPc"): ?>
                                                <?php echo e('Pending at Power Cutting'); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><a class="btn btn-info btn-sm" href="<?php echo e(route('admin.viewPower',\Crypt::encrypt($powerCutting->id))); ?>">View</a>
                                        </td>                                 
                                    </tr>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>
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
                        <?php if($powerGettings->count() > 0): ?>
                            <?php $count=1 ?>
                                <?php $__currentLoopData = $powerGettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $powerGetting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $enterByUser = DB::table('userlogins')->where('id',$powerGetting->entered_by)->first();
                                    ?>
                                    <tr>
                                        <td><?php echo e($count++); ?></td>
                                        <td><?php echo e(date('d/m/Y', strtotime($powerGetting->created_at ?? ''))); ?></td>
                                        <td>
                                        <?php
                                            $month = date('m-Y', strtotime($powerGetting->created_at));
                                            $abb = DB::table('divisions')->where('id',$powerGetting->division_id)->first();
                                            echo @$abb->abbreviation;
                                        ?>/<?php echo e($month); ?>/<?php echo e($powerGetting->serial_no); ?></td>

                                        <td><?php echo e(@$enterByUser->name); ?></td>
                                        <td><?php echo e(@$enterByUser->vendor_code); ?></td>
                                        <td><?php echo e((@$enterByUser->user_type == 1) ? 'Employee' : 'Vendor'); ?>  </td>        
                                        <?php $job = Job::where('id',$powerGetting->job_id)->first(); ?>
                                        <td><?php echo e(@$job->job_title); ?></td>                           
                                        <td><a class="btn btn-info btn-sm" href="<?php echo e(route('admin.viewGetting',\Crypt::encrypt($powerGetting->id))); ?>">View</a></td>
                                    </tr>                                    
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="color:red;text-align:center;">NA</td>
                            <tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>           
        </div>
        

    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
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
                url:"<?php echo e(route('admin.getenddate')); ?>/" + id,
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
            <form action="<?php echo e(route('admin.renew')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
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
                url: "<?php echo e(route('admin.autocomplete_gate_pass')); ?>/" +  <?php echo Session::get('user_idSession') ?>  + "/" + valname,
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
            <form action="<?php echo e(route('admin.cancelpermit')); ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="pid" name="pid">
                    <?php echo csrf_field(); ?>
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/list_permits/index.blade.php ENDPATH**/ ?>