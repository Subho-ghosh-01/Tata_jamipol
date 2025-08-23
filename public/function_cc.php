<?php

##########################################################################################################################
include "Classes/class.phpmailer.php"; // include the class name

function sendSMTP($to, $from_mail, $from_name, $replyto, $replyname, $subject, $body, $cc = [])
{

	// $email = $to;
	$mail = new PHPMailer;
	$mail->IsSMTP();
	$mail->Host = 'smtp.office365.com'; //Hostname of the mail server
	$mail->Port = 587; //Port of the SMTP like to be 25, 80, 465 or 587
	$mail->SMTPAuth = true; //Whether to use SMTP authentication
	$mail->Username = 'web@jamipol.com'; //Username for SMTP authentication any valid email created in your domain
	$mail->Password = 'JamJSR#45!';
	$mail->AddReplyTo($replyto, $replyname); //reply-to address


	$mail->SetFrom($from_mail, $from_name); //From address of the mail
	// put your while loop here like below,
	$mail->Subject = $subject; //Subject od your mail

	$mail->AddAddress($to); //To address who will receive this email
	foreach ((array) $cc as $cc_email) {
		if (!empty($cc_email)) {
			$mail->addCC($cc_email);
		}
	}
	$mail->MsgHTML($body); //Put your body of the message you can place html code here
	//$mail->AddAttachment("images/asif18-logo.png"); //Attach a file here if any or comment this line, 
	$send = $mail->Send(); //Send the mails
	if ($send) {
		return true;
	} else {
		return false;
	}

}
?>