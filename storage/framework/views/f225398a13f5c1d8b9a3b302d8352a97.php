

Hi <?php echo e($data['name']); ?>,<br><br>
Pending For Approval  :<br>
Sl No: <?php echo e($data['sl']); ?><br>
Visitor Name: <?php echo e($data['visitor_name']); ?>,<br>
Visitor Mobile No:<?php echo e($data['visitor_mobile_no']); ?><br>
Visitor Company :<?php echo e($data['visitor_company']); ?><br>
From Date :<?php echo e($data['From_date']); ?><br>
To Date :<?php echo e($data['To_date']); ?><br>
From Time :<?php echo e($data['from_time']); ?><br>
To Time :<?php echo e($data['to_time']); ?><br>
<!--id :<?php echo e($data['id']); ?><br>-->
<!--<a href="<?php echo e(route('admin.edit.edit',\Crypt::encrypt($data['id']))); ?>">Click here to Approve<br></a>-->

<a  href="https://wps.jamipol.com/api_ap.php?token=123456&api=vms_approve&id=<?php echo e($data['id']); ?>">Approve<br></a>
<br>
<a href="https://wps.jamipol.com/api_ap.php?token=123456&api=vms_reject&id=<?php echo e($data['id']); ?>">Reject<br></a>
<br><br>
Thank You,<br>
JAMIPOL
<hr>
This is an Automatic Generated Email Do Not Reply.<br>
Powered by Anmoul Infomatics Pvt. Ltd.
<br><br>

 


<?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/send_pwd_vms.blade.php ENDPATH**/ ?>