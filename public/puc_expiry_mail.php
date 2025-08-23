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
$query = "SELECT created_by, puc_valid_to, vehicle_registration_no ,apply_by_type,employee_name
          FROM vehicle_pass 
          WHERE status = 'approve' AND DATEDIFF(DAY, GETDATE(), puc_valid_to) BETWEEN 0 AND 15";

$vendor_list = sqlsrv_query($sqlcon, $query);

while ($vendor = sqlsrv_fetch_array($vendor_list, SQLSRV_FETCH_ASSOC)) {

  $created_by = $vendor['created_by'];
  $vehicle_no = $vendor['vehicle_registration_no'];
  $puc_valid_to = date('d-m-Y', strtotime($vendor['puc_valid_to']));
  $apply_by_type = $vendor['apply_by_type'];
  // Fetch user info
  $user = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name, email FROM userlogins WHERE id = ?", [$created_by]));
  $user_name = $user['name'] ?? null;
  $vendor_email = $user['email'] ?? null;
  $emp_name = $vendor['employee_name'] ?? '';

  if ($user_name && $vendor_email) {
    $subject = "PUC Certificate is going to expire Submit Updated Document Before   $puc_valid_to";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    if ($apply_by_type == 1) {
      $body = '
<html>
  <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px; border:1px solid #ddd;">
      <h2 style="color:#B22222; font-size:20px; margin-bottom:20px;">
        PUC Certificate Expiry Reminder – Action Required Before 
        <span style="color:#000000;">' . $puc_valid_to . '</span>
      </h2>
      
      <p style="font-size:14px; color:#333333; line-height:1.5;">
        Dear Colleague,
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        This is to inform you that the PUC (Pollution Under Control) certificate for your registered vehicle 
        <strong>(' . $vehicle_no . ')</strong> is set to expire on 
        <strong>' . $puc_valid_to . '</strong>.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        To maintain our environmental and compliance standards, JAMIPOL requires a valid PUC certificate for all vehicles entering the premises.
      </p>
<p style="font-size:14px; color:#333333; line-height:1.5;">
       Therefore, it is requested Please renew your PUC and upload the latest certificate on our Suraksha Portal.
      </p>
      

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        Thank you for your cooperation.
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

  <p style="font-size: 16px;"><strong>Subject:</strong> PUC Certificate Expiry Reminder for Vehicle ' . $vehicle_no . ' – Action Required Before ' . $puc_valid_to . '</p>

  <p>Dear Vendor Partner,</p>

  <p>This is to inform you that the PUC (Pollution Under Control) certificate for the vehicle <strong>' . $vehicle_no . '</strong> is set to expire on <strong>' . $puc_valid_to . '</strong>.</p>

  <p>This vehicle entry pass belongs to <strong>' . $emp_name . '</strong>.</p>

  <p>
    To ensure environmental compliance, a valid PUC certificate is necessary for vehicle access to the JAMIPOL site.
  </p>

  <p><strong>Please arrange for renewal and upload the updated document at the earliest on Suraksha Portal.</strong></p>

  <p>Failure to comply may result in denial of vehicle entry post-expiry.</p>



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