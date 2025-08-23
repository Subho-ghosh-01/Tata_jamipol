<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>OTP Email - Jamipol Suraksha</title>
</head>

<body style="margin: 0; padding: 0; background-color: #eeeeee; font-family: 'Segoe UI', Roboto, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #800000; padding: 24px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">Jamipol Suraksha</h1>
                            <p style="color: #f0dede; margin: 4px 0 0; font-size: 14px;">Secure Login Access</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px 40px; color: #333333;">
                            <p style="margin: 0 0 20px; font-size: 16px;">Hi <strong>{{$data['name']}}</strong>,</p>

                            <p style="margin: 0 0 24px;">Thank you for logging in to <strong>Jamipol Suraksha</strong>.
                                Your One-Time Password (OTP) is:</p>

                            <p
                                style="text-align: center; background-color: #fff0f0; padding: 20px; border-radius: 10px; border: 1px solid #e0b4b4; font-size: 30px; font-weight: bold; color: #800000; letter-spacing: 2px;">
                                {{$data['otp']}}
                            </p>

                            <p style="margin: 32px 0 0; font-size: 15px;">Please use this OTP to complete your login. It
                                will expire shortly. For your safety, never share this code with anyone.</p>

                            <p style="color: #999999; font-size: 13px; margin-top: 40px;">This is an automated message.
                                Please do not reply to this email.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f7f7f7; text-align: center; padding: 20px; font-size: 12px; color: #999999;">
                            &copy; {{ date('Y') }} Jamipol Limited. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>