<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap BootstrapShoppingCart for codecanyon.net
//      
//      File for test your smtp server
//

require_once "BootstrapShoppingCart/config.php";                // load settings file
require_once "BootstrapShoppingCart/BootstrapShoppingCart.php";     // load class BootstrapShoppingCart

$msg = "<html><body>works</body></html>";
$subject = "only a test";

$mail = new PHPMailer; // init obj


 /*$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->SMTPAuth = 'login';
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->Username = 'example@gmail.com';
$mail->Password = 'somepassword';
 * 
 */
$mail->IsHTML(true); // Set email format to HTML
$mail->SetFrom('example@gmail.com', 'Example');
$mail->Subject = $subject;
$mail->Body = $msg;
$mail->AddAddress(EW_EMAIL);
if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
echo "d";
/*
$sendmail = new sendmail(EW_EMAIL, $subject, $msg, EW_EMAIL, EW_EMAIL);
if($sendmail->send()=='true'){
    echo 'Email sent';
}else{
  echo 'Could not send';
}
 * 
 */