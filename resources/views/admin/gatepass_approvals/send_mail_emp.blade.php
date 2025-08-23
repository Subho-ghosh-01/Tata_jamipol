@if($data['send_to'] == 'Vendor_full_document')

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Employee Exit Process</title>
    </head>

    <body style="margin:0; padding:0; background-color:#f7f7f7;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f7f7f7; padding:20px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0"
                        style="background-color:#ffffff; border:1px solid #dddddd;">
                        <tr>
                            <td align="center" bgcolor="#004080" style="padding:15px;">
                                <h2 style="margin:0; font-family:Arial, sans-serif; color:#ffffff;">Employee Exit
                                    Notification</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:20px; font-family:Arial, sans-serif; font-size:15px; color:#333333;">
                                <p style="margin-top:0;">Dear {{$data['name']}},</p>

                                <p>You have initiated an exit request for the following employee:</p>

                                <p><strong>Employee Name:</strong> {{$data['employee_name']}}</p>

                                <p><strong>Action Required:</strong> Please login to the portal and upload the Full & Final
                                    settlement documents for the above employee to continue the process.</p>

                                <p>
                                    <a href="https://wps.jamipol.com"
                                        style="display:inline-block; padding:10px 15px; background-color:#007acc; color:#ffffff; text-decoration:none; border-radius:4px;">
                                        Click here to login and upload
                                    </a>
                                </p>

                                <p>If the documents have already been uploaded, please ignore this message.</p>

                                <p>Thank you,<br>HR Deaprtment</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center"
                                style="font-size:12px; color:#777777; font-family:Arial, sans-serif; padding:10px; border-top:1px solid #dddddd;">
                                © 2025 JAMIPOL LTD. All rights reserved.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

    </html>
@elseif($data['send_to'] == 'Vendor_request_executing')

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Employee Exit Process</title>
    </head>

    <body style="margin:0; padding:0; background-color:#f7f7f7;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f7f7f7; padding:20px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0"
                        style="background-color:#ffffff; border:1px solid #dddddd;">
                        <tr>
                            <td align="center" bgcolor="#004080" style="padding:15px;">
                                <h2 style="margin:0; font-family:Arial, sans-serif; color:#ffffff;">Employee Exit
                                    Notification</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:20px; font-family:Arial, sans-serif; font-size:15px; color:#333333;">
                                <p style="margin-top:0;">Dear {{$data['name']}},</p>

                                <p>The vendor has submitted an employee exit request through the system.</p>

                                <p><strong>Employee Name:</strong> {{$data['employee_name']}}</p>

                                <p><strong>Action Required:</strong> Your approval is required to proceed with this exit
                                    request. Please log in to the portal and provide the necessary access or approval to
                                    continue the exit process.</p>

                                <p>
                                    <a href="https://wps.jamipol.com"
                                        style="display:inline-block; padding:10px 15px; background-color:#007acc; color:#ffffff; text-decoration:none; border-radius:4px;">
                                        Click here to login and take action
                                    </a>
                                </p>

                                <p>If action has already been taken, you may ignore this message.</p>

                                <p>Thank you,<br>HR Department</p>
                            </td>
                        </tr>

                        <tr>
                            <td align="center"
                                style="font-size:12px; color:#777777; font-family:Arial, sans-serif; padding:10px; border-top:1px solid #dddddd;">
                                © 2025 JAMIPOL LTD. All rights reserved.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>

    </html>
@elseif($data['send_to'] == 'Vendor_full_document_hr')

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Employee Exit Process</title>
    </head>

    <body style="margin:0; padding:0; background-color:#f7f7f7;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f7f7f7; padding:20px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" border="0"
                        style="background-color:#ffffff; border:1px solid #dddddd;">
                        <tr>
                            <td align="center" bgcolor="#004080" style="padding:15px;">
                                <h2 style="margin:0; font-family:Arial, sans-serif; color:#ffffff;">Employee Exit
                                    Notification</h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:20px; font-family:Arial, sans-serif; font-size:15px; color:#333333;">
                                <p style="margin-top:0;">Dear {{$data['name']}},</p>

                                <p>Vendor have initiated an exit request for the following employee:</p>

                                <p><strong>Employee Name:</strong> {{$data['employee_name']}}</p>

                                <p><strong>Action Required:</strong> Please login to the portal and check the
                                    settlement documents for the above employee to continue the process.</p>

                                <p>
                                    <a href="https://wps.jamipol.com"
                                        style="display:inline-block; padding:10px 15px; background-color:#007acc; color:#ffffff; text-decoration:none; border-radius:4px;">
                                        Click here to login and upload
                                    </a>
                                </p>

                                <p>If the documents have already been uploaded, please ignore this message.</p>

                                <p>Thank you,<br>HR Deaprtment</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center"
                                style="font-size:12px; color:#777777; font-family:Arial, sans-serif; padding:10px; border-top:1px solid #dddddd;">
                                © 2025 JAMIPOL LTD. All rights reserved.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
@endif