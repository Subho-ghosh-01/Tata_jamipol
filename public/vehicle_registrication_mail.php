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
$query = "SELECT created_by, vehicle_registration_date, vehicle_registration_no ,apply_by_type,employee_name
          FROM vehicle_pass 
          WHERE status = 'approve' AND DATEDIFF(DAY, GETDATE(), vehicle_registration_date) BETWEEN 0 AND 15";

$vendor_list = sqlsrv_query($sqlcon, $query);

while ($vendor = sqlsrv_fetch_array($vendor_list, SQLSRV_FETCH_ASSOC)) {

  $created_by = $vendor['created_by'];
  $vehicle_no = $vendor['vehicle_registration_no'];
  $vehicle_registration_date = date('d-m-Y', strtotime($vendor['vehicle_registration_date']));
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
        <span style="color:#000000;">' . $vehicle_registration_date . '</span>
      </h2>
      
      <p style="font-size:14px; color:#333333; line-height:1.5;">
        Dear Colleague,
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
        Please be advised that the registration of your vehicle' . $vehicle_no . ' is expiring on 
        <strong>' . $vehicle_registration_date . '</strong>.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
      As per JAMIPOL policy, only vehicles with valid registration are allowed entry into our premises.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
     Therefore, it is requested that kindly renew your vehicle registration and upload the updated certificate in the Suraksha Portal at the earliest.
      </p>

      <p style="font-size:14px; color:#333333; line-height:1.5;">
       If you’ve already renewed your registration certificate and updated in the suraksha portal, please disregard this email.
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

  <p style="font-size: 16px;"><strong>Subject:</strong> PUC Certificate Expiry Reminder for Vehicle ' . $vehicle_no . ' – Action Required Before ' . $vehicle_registration_date . '</p>

  <p>Dear Vendor Partner,</p>

  <p>Our system indicates that the registration of a vehicle<strong>' . $vehicle_no . ' will expire on  <strong>' . $vehicle_registration_date . '</strong>.</p>

 <p>This vehicle belongs to ' . $emp_name . '</p>

  <p>To maintain compliance and access rights at JAMIPOL, valid vehicle registration is necessary.
  </p>

  <p><strong> Please renew the registration and submit the updated certificate at Suraksha Portal.</strong></p>



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