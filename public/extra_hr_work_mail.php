<?php
error_reporting(0);
include "function_cc.php";

$serverName = "127.0.0.1";
$connectionInfo = [
    "Database" => "jamipol",
    "UID" => "laravel",
    "PWD" => "987654321",
    "ReturnDatesAsStrings" => true
];
$sqlcon = sqlsrv_connect($serverName, $connectionInfo);
$dateFrom = date('Y-m-01');
$dateTo = date('Y-m-d');
$currentMonthName = date('F Y');
$date = date('Y-m-d');
file_put_contents("debug_mail_log.txt", "=== OVERTIME MAIL LOG: " . date('Y-m-d H:i:s') . " ===\n");

// Get distinct vendors who have gatepass entries this month
$vendor_query = sqlsrv_query($sqlcon, "
    SELECT DISTINCT emp_pno
    FROM Clms_gatepass
    WHERE valid_till > ?  
", [$date]);

while ($row = sqlsrv_fetch_array($vendor_query)) {
    $emp_pno = $row['emp_pno'];



    $exec_row = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "
    SELECT TOP 1 pending_excueting_by  
    FROM Clms_gatepass
    WHERE emp_pno = '$emp_pno'
    ORDER BY id DESC"));

    $executing_by = $exec_row['pending_excueting_by'] ?? null;

    $executing_email = '';
    if (!empty($executing_by)) {
        $exec_user = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT email FROM userlogins WHERE id = ?", [$executing_by]), SQLSRV_FETCH_ASSOC);
        $executing_email = $exec_user['email'] ?? '';
    }




    $extra_query = sqlsrv_query($sqlcon, "
    SELECT PNo, SUM(CAST(extra_hours AS FLOAT)) AS total_extra_hours
    FROM AttendanceLog
    WHERE PNo = '$emp_pno'
      AND Date BETWEEN '$dateFrom' AND '$dateTo'
      AND extra_hours >= '10'
    GROUP BY PNo
");

    $extra_rows = '';
    while ($att = sqlsrv_fetch_array($extra_query)) {

        $extra_rows .= "<tr>
        <td style='border: 1px solid #ddd; padding: 12px;'>{$att['PNo']}</td>
        <td style='border: 1px solid #ddd; padding: 12px;'>{$att['total_extra_hours']} hrs</td>
    </tr>";
    }

    if (!empty($extra_rows)) {

        $body = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Extra Working Hours Alert</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 30px;">
<div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 12px rgba(0,0,0,0.08);">
    <div style="background: #800000; color: white; text-align: center; padding: 25px 20px;">
        <h2 style="margin: 0; font-size: 20px;">Extra Working Hours Alert</h2>
    </div>
    <div style="padding: 25px; color: #333;">
        <p>Dear <strong>Sir,</strong>,</p>
        <p>The following employees have worked more than 10 hours on some days in <strong>$currentMonthName</strong>. Please review the details below:</p>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Employee Pno</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Hours</th>
                </tr>
            </thead>
            <tbody>
                $extra_rows
            </tbody>
        </table>
        <p style="margin-top: 20px;">Please ensure compliance with labor regulations. Contact HR for queries.</p>
        <p style="margin-top: 25px;">Best regards,<br>
        <strong>HR Department</strong><br>
        JAMIPOL LTD</p>
    </div>
    <div style="background: #f0f0f0; padding: 12px; text-align: center; font-size: 12px; color: #777;">
        This is an automated email. Please do not reply.
    </div>
</div>
</body>
</html>
HTML;

        $from = 'web@jamipol.com';
        $subject = "Alert: Extra Working Hours in $currentMonthName";
        $cc = array_filter([$executing_email]);
        $sent = sendSMTP($executing_email, $from, 'HR Dept', $from, 'HR Dept', $subject, $body, $cc);

        file_put_contents("debug_mail_log.txt", ($sent ? "✅" : "❌") . " Overtime Mail to: $vendor_email (ID: $vendor_id)\n", FILE_APPEND);
    } else {
        file_put_contents("debug_mail_log.txt", "⏭️ No overtime found for vendor ID $vendor_id\n", FILE_APPEND);
    }
}
?>