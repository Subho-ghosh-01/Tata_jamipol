Hi <?php echo e($data['name']); ?>,<br><br>
Pending For Approval  :<br>
Sl No: <?php echo e($data['full_sl']); ?><br>
Vendor Name: <?php echo e($data['vendor']); ?>,<br>
Work Order No:<?php echo e($data['workorder']); ?><br>
<a href="<?php echo e(route('admin.edit_clms.edit',\Crypt::encrypt($data['id']))); ?>">Click here to Approve<br></a>
<br><br>
Thank You,<br>
JAMIPOL
<hr>
This is an Automatic Generated Email Do Not Reply.<br>
Powered by Anmoul Infomatics Pvt. Ltd.
<br><br>

 


<?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/gatepass_approvals/send_pwd.blade.php ENDPATH**/ ?>