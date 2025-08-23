@if($data['send_to'] == 'Vendor_to_safety')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Request</title>
    </head>

    <body style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">

        <div
            style="background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; max-width: 600px; margin: auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);">

            <div style="font-size: 18px; font-weight: bold; color: #2e6da4; margin-bottom: 20px;">
                Vehicle Entry Pass Request
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">Dear Sir,</p>

            <p style="margin: 10px 0; line-height: 1.6;">
                We would like to request the issuance of a Vehicle Entry Pass for one of our employees as per the following
                details:
            </p>

            <div style="background-color: #f1f1f1; padding: 10px 15px; border-radius: 5px; margin: 10px 0;">
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vendor Name:</strong>
                    {{$data['vendor_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Employee Name:</strong>
                    {{$data['employee_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vehicle Number:</strong>
                    {{$data['vehicle_registration_no']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vehicle Type:</strong>
                    {{$data['vehicle_type']}}</p>
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">
                We confirm that the following mandatory documents are enclosed/uploaded as required:
            </p>

            <ul style="margin-top: 10px; padding-left: 20px;">
                <li style="margin-bottom: 8px;">✅ Valid Vehicle Insurance</li>
                <li style="margin-bottom: 8px;">✅ Valid PUC (Pollution Under Control Certificate)</li>
                <li style="margin-bottom: 8px;">✅ Valid Driving License of the Employee</li>
                <li style="margin-bottom: 8px;">✅ Vehicle Registration Certificate</li>
            </ul>

            <p style="margin: 10px 0; line-height: 1.6;">
                We assure you that the vehicle and our employee will comply with all safety and security norms laid down by
                JAMIPOL. Kindly process this request at your earliest convenience.
            </p>

            <p style="margin-top: 25px; line-height: 1.6;">
                Thanking you,<br><br>
                Best regards,<br>
                <strong>{{$data['vendor_name']}}</strong><br>

                <strong></strong>
            </p>

        </div>

    </body>

    </html>



@elseif($data['send_to'] == 'hr_to_vendor_cancel')
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

                                <h2 style="margin:0 0 20px; font-size:20px;">Hi {{ $data['name'] }},</h2>

                                <p style="line-height:1.6; font-size:14px;">
                                    This is to inform you that your <strong>Effective Compliance Management</strong>
                                    documents
                                    submitted on the
                                    <strong>Suraksha Application</strong> for the month of
                                    <strong>{{ $data['Month'] }}</strong> has been
                                    <span style="color:#d9534f; font-weight:bold;">Rejected</span> by the HR
                                    Department.

                                </p>




                                <p style="line-height:1.6; font-size:14px;">
                                    <strong>Vendor Code:</strong> {{ $data['vendor_code'] }}<br>
                                    <strong>Status:</strong> {{ $data['doc_status'] }}<br>
                                    <strong>Remarks from HR:</strong><br>
                                    <em>{{ $data['remarks'] }}</em>
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
                                    © {{ date('Y') }} JAMIPOL. All rights reserved.
                                </p>

                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </body>

    </html>
@elseif($data['send_to'] == 'Emp_to_safety')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Request</title>
    </head>

    <body style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">

        <div
            style="background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; max-width: 600px; margin: auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);">

            <div style="font-size: 18px; font-weight: bold; color: #2e6da4; margin-bottom: 20px;">
                Vehicle Entry Pass Request
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">Dear Sir,</p>

            <p style="margin: 10px 0; line-height: 1.6;">
                This is to inform you that I am applying for a vehicle entry pass in my name, as per the details given
                below:
            </p>

            <div style="background-color: #f1f1f1; padding: 10px 15px; border-radius: 5px; margin: 10px 0;">

                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Employee Name:</strong>
                    {{$data['vendor_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vehicle Number:</strong>
                    {{$data['vehicle_registration_no']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vehicle Type:</strong>
                    {{$data['vehicle_type']}}</p>
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">
                I confirm that the following mandatory documents are enclosed/uploaded as required:
            </p>

            <ul style="margin-top: 10px; padding-left: 20px;">
                <li style="margin-bottom: 8px;">✅ Valid Vehicle Insurance</li>
                <li style="margin-bottom: 8px;">✅ Valid PUC (Pollution Under Control Certificate)</li>
                <li style="margin-bottom: 8px;">✅ Valid Driving License of the Employee</li>
                <li style="margin-bottom: 8px;">✅ Vehicle Registration Certificate</li>
            </ul>

            <p style="margin: 10px 0; line-height: 1.6;">
                I assure you that the vehicle will comply with all safety and security norms laid down by
                JAMIPOL. Kindly process this request at your earliest convenience.
            </p>

            <p style="margin-top: 25px; line-height: 1.6;">
                Thanking you,<br><br>
                Best regards,<br>
                <strong>{{$data['vendor_name']}}</strong><br>
                <strong>{{$data['vendor_code']}}</strong>
            </p>

        </div>

    </body>

    </html>
@elseif($data['send_to'] == 'Safety_to_emp_approve')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Approved</title>
    </head>

    <body style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">

        <div
            style="background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; max-width: 600px; margin: auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);">

            <div style="font-size: 18px; font-weight: bold; color: #2e6da4; margin-bottom: 20px;">
                Vehicle Entry Pass Approved
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">Dear Colleague,</p>

            <p style="margin: 10px 0; line-height: 1.6;">
                We are pleased to inform you that your request for a Vehicle Entry Pass has been <strong>approved</strong>
                by the Safety Department.
            </p>

            <div style="background-color: #f1f1f1; padding: 10px 15px; border-radius: 5px; margin: 10px 0;">
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Employee Name:</strong>
                    {{$data['vendor_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vendor Code:</strong>
                    {{$data['vendor_code']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 150px;">Vehicle Number:</strong>
                    {{$data['vehicle_registration_no']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 180px;">Type of Vehicle:</strong>
                    @if(strtolower($data['vehicle_type']) == 'two_wheeler')
                        Two Wheeler
                    @elseif(strtolower($data['vehicle_type']) == 'four_wheeler')
                        Car
                    @else
                        {{$data['vehicle_type']}}
                    @endif
                </p>
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">
                You may now collect the physical pass from the <strong>Security Gate</strong>. Please ensure the pass is
                displayed visibly on your vehicle at all times while inside JAMIPOL premises.
            </p>

            <div style="background-color: #fff8e1; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0;">
                <p style="margin: 0 0 10px 0; font-weight: bold;">Important Notes:</p>
                <ol style="margin: 0; padding-left: 20px;">
                    <li style="margin-bottom: 8px;">This pass is valid only when all supporting documents (Insurance, PUC,
                        RC, and Driving License) remain current</li>
                    <li style="margin-bottom: 8px;">Entry may be restricted if any documents expire during the pass validity
                        period</li>
                    <li style="margin-bottom: 8px;">The pass must be returned upon termination of services with JAMIPOL</li>
                </ol>
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">
                For any queries, please contact the Safety Department.
            </p>

            <p style="margin-top: 25px; line-height: 1.6;">
                Best regards,<br>
                <strong>Safety Department</strong><br>
                <strong>JAMIPOL Ltd.</strong>
            </p>

        </div>

    </body>

    </html>
@elseif($data['send_to'] == 'Safety_to_vendor_approve')

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Approved</title>
    </head>

    <body style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">

        <div
            style="background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; max-width: 600px; margin: auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);">

            <div style="font-size: 18px; font-weight: bold; color: #2e6da4; margin-bottom: 20px;">
                Vehicle Entry Pass Approved
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">Dear Vendor Partner,</p>

            <p style="margin: 10px 0; line-height: 1.6;">
                This is to notify you that the request for a <strong>Vehicle Entry Pass</strong> submitted on behalf of your
                organization has been <strong>approved</strong> by the Safety Department.
            </p>

            <div style="background-color: #f1f1f1; padding: 10px 15px; border-radius: 5px; margin: 10px 0;">
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 180px;">Vendor Organization:</strong>
                    {{$data['vendor_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 180px;">Driver/Pass Holder
                        Name:</strong> {{$data['driver_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 180px;">Vehicle Number:</strong>
                    {{$data['vehicle_registration_no']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 180px;">Type of Vehicle:</strong>
                    @if(strtolower($data['vehicle_type']) == 'two_wheeler' || strtolower($data['vehicle_type']) == 'two_wheelr')
                        Two Wheeler
                    @elseif(strtolower($data['vehicle_type']) == 'four_wheeler' || strtolower($data['vehicle_type']) == 'car')
                        Car
                    @else
                        {{$data['vehicle_type']}}
                    @endif
                </p>
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">
                The physical pass can now be collected from the <strong>Security Gate</strong> by the pass holder. Please
                ensure that the pass is prominently displayed while entering JAMIPOL premises.
            </p>

            <div style="background-color: #fff8e1; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0;">
                <p style="margin: 0 0 10px 0; font-weight: bold;">📌 Important:</p>
                <p style="margin: 8px 0;">
                    All required documents (Vehicle Insurance, PUC, RC, Driving License) must remain valid throughout the
                    pass duration. Entry will be denied if any document is found expired at the gate.
                </p>
            </div>

            <p style="margin: 10px 0; line-height: 1.6;">
                We appreciate your cooperation in maintaining safety and compliance standards.
            </p>

            <p style="margin-top: 25px; line-height: 1.6;">
                Thanks & Regards,<br>
                <strong>Safety Department</strong><br>
                <strong>JAMIPOL Ltd.</strong>
            </p>

        </div>

    </body>

    </html>
@elseif($data['send_to'] == 'Emp_to_safety_surander')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Surrender</title>
    </head>

    <body style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">

        <div
            style="background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; max-width: 600px; margin: auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);">

            <!-- Email Heading -->
            <div style="font-size: 18px; font-weight: bold; color: #a94442; margin-bottom: 20px;">
                Vehicle Entry Pass Surrender – {{$data['vendor_name']}} | {{$data['vehicle_registration_no']}}
            </div>

            <!-- Greeting -->
            <p style="margin: 10px 0; line-height: 1.6;">Dear Sir,</p>

            <!-- Main Message -->
            <p style="margin: 10px 0; line-height: 1.6;">
                This is to inform you that I am surrendering the vehicle entry pass issued in my name for the vehicle
                bearing number
                <strong>{{$data['vehicle_registration_no']}}</strong>.
            </p>

            <!-- Details Box -->
            <div style="background-color: #f1f1f1; padding: 10px 15px; border-radius: 5px; margin: 15px 0;">
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Employee Name:</strong>
                    {{$data['vendor_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Department:</strong>
                    {{$data['department']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Vehicle Number:</strong>
                    {{$data['vehicle_registration_no']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Reason for
                        Surrender:</strong> {{$data['reason']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Date of Surrender:</strong>
                    {{$data['surrender_date']}}</p>
            </div>

            <!-- Confirmation -->
            <p style="margin: 10px 0; line-height: 1.6;">
                I confirm that the physical vehicle entry pass has been returned to the Security Office as per the required
                process.
                Kindly update the records accordingly and acknowledge the same.
            </p>

            <!-- Signature -->
            <p style="margin-top: 25px; line-height: 1.6;">
                Thanks & Regards,<br><br>
                <strong>{{$data['vendor_name']}}</strong><br>
                <strong>{{$data['department']}}</strong>
            </p>

        </div>

    </body>

    </html>
@elseif($data['send_to'] == 'vendor_to_safety_surander')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Surrender</title>
    </head>

    <body style="font-family: Arial, sans-serif; color: #333333; padding: 20px; background-color: #f9f9f9;">

        <div
            style="background-color: #ffffff; padding: 20px; border: 1px solid #dddddd; max-width: 600px; margin: auto; box-shadow: 0 0 8px rgba(0,0,0,0.05);">

            <!-- Email Heading -->
            <div style="font-size: 18px; font-weight: bold; color: #a94442; margin-bottom: 20px;">
                Vehicle Entry Pass Surrender – {{$data['vendor_name']}} | {{$data['vehicle_registration_no']}}
            </div>

            <!-- Greeting -->
            <p style="margin: 10px 0; line-height: 1.6;">Dear Sir,</p>

            <!-- Main Message -->
            <p style="margin: 10px 0; line-height: 1.6;">
                This is to inform you that the vehicle pass issued to our employee {{$data['employee_name']}} is being
                surrendered, as
                per the details below:
                <strong>{{$data['vehicle_registration_no']}}</strong>.
            </p>

            <!-- Details Box -->
            <div style="background-color: #f1f1f1; padding: 10px 15px; border-radius: 5px; margin: 15px 0;">
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Vendor Company Name
                        :</strong>
                    {{$data['vendor_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Driver Name:</strong>
                    {{$data['driver_name']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Vehicle Number:</strong>
                    {{$data['vehicle_registration_no']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Reason for
                        Surrender:</strong> {{$data['reason']}}</p>
                <p style="margin: 6px 0;"><strong style="display: inline-block; width: 170px;">Date of Surrender:</strong>
                    {{date('d-m-Y', strtotime($data['surrender_date']))}}</p>
            </div>

            <!-- Confirmation -->
            <p style="margin: 10px 0; line-height: 1.6;">
                The physical vehicle entry pass has been submitted to the security office, and we request you to update your
                records accordingly.
                We thank you for the support and request an acknowledgment of this surrender for our documentation.
            </p>

            <!-- Signature -->
            <p style="margin-top: 25px; line-height: 1.6;">
                Thanks & Regards,<br><br>
                <strong>{{$data['vendor_name']}}</strong><br>

            </p>

        </div>

    </body>

    </html>
@elseif($data['send_to'] == 'Safety_to_emp_surrendor')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Vehicle Entry Pass Surrender Confirmation</title>
    </head>

    <body
        style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

        <div style="background-color: #fff; border: 1px solid #ddd; padding: 25px; border-radius: 4px;">
            <!-- Header -->
            <div style="margin-bottom: 20px;">
                <div style="font-size: 18px; font-weight: bold; color: #d9534f;">Vehicle Entry Pass Surrender Confirmation
                </div>
            </div>

            <!-- Greeting -->
            <p style="margin-bottom: 20px;">Dear Colleague,</p>

            <!-- Main Content -->
            <p style="margin-bottom: 15px;">
                This is to inform you that your request for surrender of the Vehicle Entry Pass for your vehicle bearing
                number <strong>{{$data['vehicle_registration_no']}}</strong> has been successfully processed.
            </p>

            <p style="margin-bottom: 15px;">
                We confirm that the <strong>physical vehicle pass has been received</strong> by the Safety Department on
                <strong>{{$data['surrender_date']}}</strong>, and your pass has now been deactivated in our system.
            </p>

            <!-- Details Section -->
            <div style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #d9534f;">
                <p style="font-weight: bold; margin-top: 0; margin-bottom: 10px;">Summary of Surrender Details:</p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li style="margin-bottom: 5px;"><strong>Employee Name:</strong> {{$data['vendor_name']}}</li>
                    <li style="margin-bottom: 5px;"><strong>Department:</strong> {{$data['department']}}</li>
                    <li style="margin-bottom: 5px;"><strong>Vehicle Number:</strong> {{$data['vehicle_registration_no']}}
                    </li>
                    <li><strong>Date of Surrender:</strong> {{$data['surrender_date']}}</li>
                </ul>
            </div>

            <!-- Important Note -->
            <p style="margin-bottom: 15px;">
                Please note that this pass is no longer valid for entry into the JAMIPOL premises.
            </p>

            <!-- Closing -->
            <p style="margin-bottom: 15px;">
                We appreciate your timely cooperation with our vehicle pass management system. For any further assistance,
                feel free to contact the Safety Department.
            </p>

            <!-- Signature -->
            <div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px;">
                <p style="margin-bottom: 5px;">Thanks & Regards,</p>
                <p style="margin: 0; font-weight: bold;">Safety Department</p>
                <p style="margin: 0; font-weight: bold; color: #d9534f;">JAMIPOL Ltd.</p>
            </div>
        </div>
    </body>

    </html>
@elseif($data['send_to'] == 'Safety_to_vendor_surrendor')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Acknowledgment: Vehicle Entry Pass Surrender Approved</title>
    </head>

    <body
        style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

        <div style="background-color: #fff; border: 1px solid #ddd; padding: 25px; border-radius: 4px;">
            <!-- Header -->
            <div style="margin-bottom: 20px;">
                <div style="font-size: 18px; font-weight: bold; color: #d9534f;">Acknowledgment: Vehicle Entry Pass
                    Surrender Approved</div>
                <div style="font-size: 14px; color: #666; margin-top: 5px;">
                    {{$data['vehicle_registration_no']}} | {{$data['vendor_name']}}
                </div>
            </div>

            <!-- Greeting -->
            <p style="margin-bottom: 20px;">Dear Vendor Partner,</p>

            <!-- Main Content -->
            <p style="margin-bottom: 15px;">
                We acknowledge the surrender of the <strong>Vehicle Entry Pass</strong> issued to your employee
                <strong>{{$data['vendor_name']}}</strong> for the vehicle bearing registration number
                <strong>{{$data['vehicle_registration_no']}}</strong>.
            </p>

            <p style="margin-bottom: 15px;">
                We confirm that the <strong>physical pass has been received</strong> at our end on
                <strong>{{$data['surrender_date']}}</strong>, and the corresponding vehicle entry pass has been marked as
                inactive in our system.
            </p>

            <!-- Details Section -->
            <div style="margin: 20px 0; padding: 15px; background-color: #f9f9f9; border-left: 4px solid #d9534f;">
                <p style="font-weight: bold; margin-top: 0; margin-bottom: 10px;">Surrender Details:</p>
                <ul style="margin: 0; padding-left: 20px;">
                    <li style="margin-bottom: 5px;"><strong>Vendor Organization:</strong> {{$data['vendor_name']}}</li>
                    <li style="margin-bottom: 5px;"><strong>Driver/Pass Holder Name:</strong> {{$data['driver_name']}}</li>
                    <li style="margin-bottom: 5px;"><strong>Vehicle Number:</strong> {{$data['vehicle_registration_no']}}
                    </li>
                    <li style="margin-bottom: 5px;"><strong>Reason for Surrender:</strong> {{$data['reason']}}</li>
                    <li><strong>Date of Surrender:</strong> {{$data['surrender_date']}}</li>
                </ul>
            </div>

            <!-- Important Note -->
            <p style="margin-bottom: 15px;">
                Please note that this vehicle will no longer be allowed entry to JAMIPOL premises using the surrendered
                pass.
            </p>

            <!-- Closing -->
            <p style="margin-bottom: 15px;">
                Thank you for ensuring compliance with JAMIPOL's site access protocols.
            </p>

            <!-- Signature -->
            <div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 15px;">
                <p style="margin-bottom: 5px;">Thanks & Regards,</p>
                <p style="margin: 0; font-weight: bold;">Safety Department</p>
                <p style="margin: 0; font-weight: bold; color: #d9534f;">JAMIPOL Ltd.</p>
            </div>
        </div>
    </body>

    </html>
@elseif($data['send_to'] == 'hr_to_vendor_cancel')
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

                                <h2 style="margin:0 0 20px; font-size:20px;">Hi {{ $data['name'] }},</h2>

                                <p style="line-height:1.6; font-size:14px;">
                                    This is to inform you that your <strong>Effective Compliance Management</strong>
                                    documents
                                    submitted on the
                                    <strong>Suraksha Application</strong> for the month of
                                    <strong>{{ $data['Month'] }}</strong> has been
                                    <span style="color:#d9534f; font-weight:bold;">Rejected</span> by the HR
                                    Department.

                                </p>




                                <p style="line-height:1.6; font-size:14px;">
                                    <strong>Vendor Code:</strong> {{ $data['vendor_code'] }}<br>
                                    <strong>Status:</strong> {{ $data['doc_status'] }}<br>
                                    <strong>Remarks from HR:</strong><br>
                                    <em>{{ $data['remarks'] }}</em>
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
                                    © {{ date('Y') }} JAMIPOL. All rights reserved.
                                </p>

                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </body>

    </html>

@endif