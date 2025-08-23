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

// Date range for June 2025
$dateFrom = date('Y-m-01 00:00:00');
$dateTo = date('Y-m-30 23:59:59');
$lastMonth = date('F Y', strtotime('first day of last month'));
file_put_contents("debug_mail_log.txt", "=== MAIL LOG: " . date('Y-m-d H:i:s') . " ===\n");

// Get all vendors
$vendor_list = sqlsrv_query($sqlcon, "SELECT id, name, email FROM userlogins WHERE user_type = 2 and id='38'");

while ($vendor = sqlsrv_fetch_array($vendor_list, SQLSRV_FETCH_ASSOC)) {
    $vendor_id = $vendor['id'];
    $vendor_name = $vendor['name'];
    $vendor_email = $vendor['email'];

    $check_executing = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT pending_excueting_by FROM Clms_gatepass WHERE created_by = ? ORDER BY id DESC", [$vendor_id]));
    $executing_by = $check_executing['pending_excueting_by'] ?? null;

    $executing_mail_cc = '';
    if (!empty($executing_by)) {
        $executing_find = sqlsrv_fetch_array(sqlsrv_query($sqlcon, "SELECT email FROM userlogins WHERE id = ?", [$executing_by]));
        $executing_mail_cc = $executing_find['email'] ?? '';
    }



    if (!empty($executing_mail_cc)) {
        file_put_contents("debug_mail_log.txt", "📬 Executing user CC found: $executing_mail_cc for vendor ID $vendor_id\n", FILE_APPEND);
    } else {
        file_put_contents("debug_mail_log.txt", "⚠️ No executing user CC found for vendor ID $vendor_id\n", FILE_APPEND);
    }

    // Check ESIC entry
    $esic_stmt = sqlsrv_query(
        $sqlcon,
        "SELECT id FROM vendor_esic WHERE created_date BETWEEN ? AND ? AND vendor_id = ?",
        [$dateFrom, $dateTo, $vendor_id]
    );
    $esic = sqlsrv_fetch_array($esic_stmt);
    $has_esic = !empty($esic['id']);

    // Check PF entry
    $pf_stmt = sqlsrv_query(
        $sqlcon,
        "SELECT id FROM vendor_pf_ecr WHERE created_date BETWEEN ? AND ? AND vendor_id = ?",
        [$dateFrom, $dateTo, $vendor_id]
    );
    $pf = sqlsrv_fetch_array($pf_stmt);
    $has_pf = !empty($pf['id']);

    // SEND MAIL if ANY ONE is MISSING
    if (!$has_esic || !$has_pf) {
        $status_rows = '';
        $status_rows .= statusRow("ESIC Document", $has_esic);
        $status_rows .= statusRow("PF Document", $has_pf);
        $status_rows .= statusRow("ESIC Challan", $has_esic);
        $status_rows .= statusRow("ECR Document", $has_pf);

        $body = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Document Reminder</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f4f4f4; padding: 30px;">
<div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 12px rgba(0,0,0,0.08);">
    <div style="background: #800000; color: white; text-align: center; padding: 25px 20px;">
        <h2 style="margin: 0; font-size: 20px;">Vendor Document Submission Reminder</h2>
        
    </div>
    <div style="padding: 25px; color: #333;">
        <p>Dear <strong>$vendor_name</strong>,</p>
        <p>This is a reminder to submit the required compliance documents for the month of <strong>$lastMonth </strong>. Below is the current submission status:</p>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px;">
            <thead>
                <tr style="background-color: #f5f5f5;">
                    <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Document</th>
                    <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Status</th>
                </tr>
            </thead>
            <tbody>
                $status_rows
            </tbody>
        </table>
        <p style="margin-top: 20px;">Please upload the missing documents via the SURAKSHA portal at your earliest convenience.</p>
        <p>If you have already submitted the documents, kindly ignore this message.</p>
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
        $cc = 'audit@jamipol.com';
        $lastMonth = date('F Y', strtotime('first day of last month'));
        $subject = "Reminder: Missing Documents - $lastMonth";
        $all_cc = array_filter([$executing_mail_cc]);
        sendSMTP($vendor_email, $from, 'HR Dept', $from, 'HR Dept', $subject, $body, $all_cc);


        file_put_contents("debug_mail_log.txt", ($sent ? "✅" : "❌") . " Sent to: $vendor_email (ID: $vendor_id)\n", FILE_APPEND);
    } else {
        file_put_contents("debug_mail_log.txt", "⏭️ No mail: $vendor_email (all submitted)\n", FILE_APPEND);
    }
}

// ✅ HTML Status Row


function statusRow($doc, $submitted)
{
    $icon = $submitted ? "&#10003;" : "&#10007;";
    $color = $submitted ? "#28a745" : "#dc3545";
    $label = $submitted ? "Submitted" : "Not Submitted";
    return "<tr>
            <td style='border: 1px solid #ddd; padding: 12px;'>$doc</td>
            <td style='border: 1px solid #ddd; padding: 12px;'>
                <span style='display: inline-block; width: 22px; height: 22px; line-height: 22px; text-align: center; border-radius: 50%; font-size: 13px; font-weight: bold; color: #fff; background-color: $color;'>$icon</span> $label
            </td>
        </tr>";
}
?>