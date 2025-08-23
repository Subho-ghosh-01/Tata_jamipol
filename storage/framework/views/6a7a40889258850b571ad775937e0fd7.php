<?php if($data['send_to'] == 'Vendor_to_hr'): ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Vendor ECM Notification</title>
    </head>

    <body style="margin:0; padding:20px; background-color:#f6f6f6; font-family: Arial, sans-serif; color:#333;">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f6f6f6">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                        style="border-radius:6px; box-shadow:0 0 10px rgba(0,0,0,0.06); padding:25px; border-collapse: separate;border:1px soild #ffffff color:#333">
                        <tr>
                            <td style="font-size:24px; font-weight:bold; padding-bottom: 20px;">
                                Hi <?php echo e($data['name']); ?>,
                            </td>
                        </tr>

                        <tr>
                            <td style="font-size:16px; line-height:1.5; padding-bottom: 10px;">
                                This is to inform you that vendor <strong><?php echo e($data['vendor_name']); ?></strong> has
                                successfully uploaded
                                their <strong>Effective Compliance Management </strong> details to the <strong>Suraksha
                                    Application</strong> for
                                the month
                                of
                                <strong><?php echo e($data['Month']); ?></strong>.
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; padding-bottom: 10px;">
                                <strong>Vendor Code:</strong> <?php echo e($data['vendor_code']); ?>

                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:16px; padding-bottom: 15px;">
                                <strong>Status:</strong> <?php echo e($data['doc_status']); ?>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 20px;">
                                <a href="https://wps.jamipol.com/"
                                    style="background-color:#005baa; color:#ffffff; text-decoration:none; padding:12px 25px; font-weight:bold; border-radius:4px; display:inline-block;">
                                    Click here to Login
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td style="font-size:16px; line-height:1.5; padding-bottom: 30px;">
                                Please review and approve/reject the submission as appropriate.
                            </td>
                        </tr>

                        <tr>
                            <td style="font-size:16px; line-height:1.5;">
                                Regards,<br />
                                <strong>JAMIPOL Suraksha</strong>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" style="font-size:12px; color:#888888; padding-top: 40px;">
                                © <?php echo e(date('Y')); ?> JAMIPOL. All rights reserved.
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

    </body>

    </html>


<?php elseif($data['send_to'] == 'hr_to_vendor_cancel'): ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8" />
        <title>Vendor ECM Notification</title>
    </head>

    <body style="margin:0; padding:20px; background-color:#f6f6f6; font-family: Arial, sans-serif; color:#333;">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f6f6f6">
            <tr>
                <td align="center">

                    <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                        style="border:1px solid #ddd; padding:25px;">

                        <!-- Faux Watermark row -->


                        <tr>
                            <td style="padding:25px;">

                                <h2 style="margin:0 0 20px; font-size:20px;">Hi <?php echo e($data['name']); ?>,</h2>

                                <p style="line-height:1.6; font-size:14px;">
                                    This is to inform you that your <strong>Effective Compliance Management</strong>
                                    documents
                                    submitted on the
                                    <strong>Suraksha Application</strong> for the month of
                                    <strong><?php echo e($data['Month']); ?></strong> has been
                                    <span style="color:#d9534f; font-weight:bold;">Rejected</span> by the HR
                                    Department.

                                </p>




                                <p style="line-height:1.6; font-size:14px;">
                                    <strong>Vendor Code:</strong> <?php echo e($data['vendor_code']); ?><br>
                                    <strong>Status:</strong> <?php echo e($data['doc_status']); ?><br>
                                    <strong>Remarks from HR:</strong><br>
                                    <em><?php echo e($data['remarks']); ?></em>
                                </p>

                                <p style="line-height:1.6; font-size:14px;">

                                    Kindly make the necessary amendments as per the HR Department's feedback and
                                    resubmit for re-evaluation.

                                    You can access the application using the link below:
                                </p>

                                <p style="margin-top:20px;">
                                    <a href="https://wps.jamipol.com/"
                                        style="display:inline-block; padding:10px 20px; background-color:#005baa; color:#ffffff; text-decoration:none; font-weight:bold; border-radius:4px;">
                                        Click here to Login
                                    </a>
                                </p>

                                <p style="line-height:1.6; font-size:14px;">
                                    For further assistance, please contact the HR team.
                                </p>

                                <p style="font-size:12px; color:#888888; text-align:center; margin-top:40px;">
                                    © <?php echo e(date('Y')); ?> JAMIPOL. All rights reserved.
                                </p>

                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </body>

    </html>
<?php elseif($data['send_to'] == 'hr_to_vendor'): ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8" />
        <title>Vendor ECM Notification</title>
    </head>

    <body style="margin:0; padding:20px; background-color:#f6f6f6; font-family: Arial, sans-serif; color:#333;">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f6f6f6">
            <tr>
                <td align="center">

                    <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"
                        style="border:1px solid #ddd; padding:25px;">

                        <!-- Faux Watermark row -->


                        <tr>
                            <td style="padding:25px;">

                                <h2 style="margin:0 0 20px; font-size:20px;">Hi <?php echo e($data['name']); ?>,</h2>

                                <p style="line-height:1.6; font-size:14px;">
                                    This is to inform you that your <strong>Effective Compliance management </strong>
                                    documents
                                    submitted on the
                                    <strong>Suraksha Application</strong> for the month of
                                    <strong><?php echo e($data['Month']); ?></strong> has been:
                                </p>

                                <ul style="line-height:1.6; font-size:14px; padding-left: 20px;">
                                    <li><span style="color:#5cb85c; font-weight:bold;">Approved</span> by the <strong>HR
                                            Department</strong></li>
                                </ul>


                                <p style="line-height:1.6; font-size:14px;">
                                    <strong>Vendor Code:</strong> <?php echo e($data['vendor_code']); ?><br>
                                    <strong>Status:</strong> <?php echo e($data['doc_status']); ?><br>


                                </p>

                                <p style="line-height:1.6; font-size:14px;">

                                    You can access the application using the link below:
                                </p>

                                <p style="margin-top:20px;">
                                    <a href="https://wps.jamipol.com/"
                                        style="display:inline-block; padding:10px 20px; background-color:#005baa; color:#ffffff; text-decoration:none; font-weight:bold; border-radius:4px;">
                                        Click here to Login
                                    </a>
                                </p>

                                <p style="line-height:1.6; font-size:14px;">
                                    For further assistance, please contact the HR team.
                                </p>

                                <p style="font-size:12px; color:#888888; text-align:center; margin-top:40px;">
                                    © <?php echo e(date('Y')); ?> JAMIPOL. All rights reserved.
                                </p>

                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </body>

    </html>

<?php endif; ?><?php /**PATH D:\8.4 php\htdocs\jamipol\resources\views/admin/vendor_ecm/send_mail.blade.php ENDPATH**/ ?>