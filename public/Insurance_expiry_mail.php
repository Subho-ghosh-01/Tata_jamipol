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
$query = "SELECT created_by, insurance_valid_to, vehicle_registration_no ,apply_by_type
          FROM vehicle_pass 
          WHERE status = 'approve' AND DATEDIFF(DAY, GETDATE(), insurance_valid_to) BETWEEN 0 AND 15";

$vendor_list = sqlsrv_query($sqlcon, $query);

while ($vendor = sqlsrv_fetch_array($vendor_list, SQLSRV_FETCH_ASSOC)) {

  $created_by = $vendor['created_by'];
  $vehicle_no = $vendor['vehicle_registration_no'];
  $insurance_expiry = date('d-m-Y', strtotime($vendor['insurance_valid_to']));
  $apply_by_type = $vendor['apply_by_type'];
  // Fetch user info
  $user = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT name, email FROM userlogins WHERE id = ?", [$created_by]));
  $user_name = $user['name'] ?? null;
  $vendor_email = $user['email'] ?? null;

  if ($user_name && $vendor_email) {
    $subject = "Vehicle Insurance Expiry Reminder Action Required Before  $insurance_expiry";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    if ($apply_by_type == 1) {
      $body = '
<html>
  <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width:600px; margin:auto; background:#ffffff; padding:20px; border-radius:8px; border:1px solid #ddd;">
      <h2 style="color:#B22222; font-size:20px; margin-bottom:20px;">
        Vehicle Insurance Expiry Reminder &#8259; Action Required Before 
        <span style="color:#000000;">' . $insurance_expiry . '</span>
      </h2>
      
      <p style="font-size:14px; color:#333333; line-height:1.5;">
        Dear Colleague,
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        This is a reminder that the insurance for your registered vehicle 
        <strong>(' . $vehicle_no . ')</strong> is expiring on 
        <strong>' . $insurance_expiry . '</strong>.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        As per company policy, a valid insurance document is mandatory for entry into 
        the <strong>JAMIPOL premises</strong>.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        It is requested that please renew your vehicle insurance and upload the updated document on the Suraksha Portal before the expiry date to ensure continued access to the JAMIPOL premises.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        If you have already renewed and updated your insurance on the Suraksha Portal, kindly ignore this message.
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
  <title>Vehicle Insurance Expiry Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">

  <p style="font-size: 16px;"><strong>Subject:</strong> Vehicle Insurance of ' . $vehicle_no . ' Expiry Reminder - Action Required Before ' . $insurance_expiry . '</p>

  <p>Dear Vendor Partner,</p>

  <p>This is to inform you that the vehicle insurance of vehicle <strong>' . $vehicle_no . '</strong> is due to expire on <strong>' . $insurance_expiry . '</strong>.</p>

  <p>This vehicle entry pass belongs to <strong>' . $user_name . '</strong>.</p>

  <p>
    It is requested that you kindly ensure the submission of the updated insurance copy on the 
    <strong>Suraksha Portal</strong>, as a valid insurance document is mandatory for vehicle entry into the 
    <strong>JAMIPOL premises</strong>.
  </p>

  <p><strong>Failure to comply may result in denial of vehicle entry post-expiry.</strong></p>

  <p>If you have any queries, please contact the <strong>JAMIPOL Safety Department</strong>.</p>

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
    $cc = 'audit@jamipol.com';
    $all_cc = array_filter([$cc]);

    // Send the email
    sendSMTP($vendor_email, $from, 'Safety Dept', $from, 'Safety Dept', $subject, $body, $all_cc);
  }
}
?>