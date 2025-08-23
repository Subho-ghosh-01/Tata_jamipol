<?php
error_reporting(0);
include "function_cc.php"; // Contains sendSMTP function

$serverName = "127.0.0.1";
$connectionInfo = [
  "Database" => "jamipol",
  "UID" => "laravel",
  "PWD" => "987654321",
  "ReturnDatesAsStrings" => true
];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);

// Fetch vehicle passes with insurance expiring in the next 15 days
$query = "SELECT created_by, license_valid_to, vehicle_registration_no ,apply_by_type,employee_name
          FROM vehicle_pass 
          WHERE status = 'approve' AND DATEDIFF(DAY, GETDATE(), license_valid_to) BETWEEN 0 AND 15";

$vendor_list = sqlsrv_query($sqlcon, $query);

while ($vendor = sqlsrv_fetch_array($vendor_list, SQLSRV_FETCH_ASSOC)) {

  $created_by = $vendor['created_by'];
  $vehicle_no = $vendor['vehicle_registration_no'];
  $license_valid_to = date('d-m-Y', strtotime($vendor['license_valid_to']));
  $apply_by_type = $vendor['apply_by_type'];
  // Fetch user info
  $user = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name, email FROM userlogins WHERE id = ?", [$created_by]));
  $user_name = $user['name'] ?? null;
  $vendor_email = $user['email'] ?? null;
  $emp_name = $vendor['employee_name'] ?? '';

  if ($user_name && $vendor_email) {
    $subject = "Driving License Expiry Alert –  Renewal Required Before  $puc_valid_to";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    if ($apply_by_type == 1) {
      $body = '
<html>
  <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px; border:1px solid #ddd;">
      <h2 style="color:#B22222; font-size:20px; margin-bottom:20px;">
        PUC Certificate Expiry Reminder – Action Required Before 
        <span style="color:#000000;">' . $license_valid_to . '</span>
      </h2>
      
      <p style="font-size:14px; color:#333333; line-height:1.5;">
        Dear Colleague,
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        This is to inform you that our records show that your driving license is expiring on 
        <strong>' . $license_valid_to . '</strong>.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
      As per JAMIPOL vehicle entry pass guideline, a valid driving license is mandatory for all vehicle operators within the premises.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
       Therefore, it is requested that kindly renew your license and update the details on the suraksha portal before the expiry date.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        If you have already updated your renewed license and updated in the suraksha portal, please disregard this email.
      </p>

      <br>

      <p style="font-size:14px; color:#333333; margin-bottom:5px;">Thanks & Regards,</p>
      <p style="font-size:14px; color:#333333; margin-top:0;">Safety Department<br>JAMIPOL Ltd.</p>
    </div>
  </body>
</html>';

    } else {
      $body = '<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PUC Certificate Expiry Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">

  <p style="font-size: 16px;"><strong>Subject:</strong> PUC Certificate Expiry Reminder for Vehicle ' . $vehicle_no . ' – Action Required Before ' . $license_valid_to . '</p>

  <p>Dear Vendor Partner,</p>

  <p>This is a reminder that one of your employee’s  <strong>' . $emp_name . ' driving license is set to expire on <strong>' . $license_valid_to . '</strong>.</p>

 

  <p>
    A valid driving license is required for operating any vehicle inside JAMIPOL’s premises.
  </p>

  <p><strong> Kindly renew and update your license details in Suraksha Portal to ensure uninterrupted entry access.</strong></p>





  <p style="margin-top: 30px;">
    Thanks & Regards,<br>
    <strong>Safety Department</strong><br>
    JAMIPOL Ltd.
  </p>

</body>
</html>';


    }

    echo $body;
    $from = 'web@jamipol.com';
    $cc = '';
    $all_cc = array_filter([$cc]);

    // Send the email
    sendSMTP($vendor_email, $from, 'Safety Dept', $from, 'Safety Dept', $subject, $body, $all_cc);
  }
}
?>