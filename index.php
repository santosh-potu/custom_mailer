<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'lib/common_functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

date_default_timezone_set('Asia/Kolkata');

$newLineChar = (PHP_SAPI == 'cli')? PHP_EOL: "<br/>";

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = SMTP::DEBUG_OFF;
//Set the hostname of the mail server
$mail->Host = SMTP_HOST;
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = SMTP_PORT;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = SMTP_USER;
//Password to use for SMTP authentication
$mail->Password = SMTP_PWD;

$mail->setFrom(FROM_EMAIL, FROM_NAME);


$templates = glob('xml_mail_templates/*.xml');
$msg = "";

foreach ($templates as $template){
    $mailData = getMailData($template);
    
    foreach(explode(',',(string)$mailData->to) as $to){
        $mail->addAddress($to);
    }
    foreach(explode(',',(string)$mailData->cc) as $cc){
        $mail->addCC($cc);
    }
    foreach((array)$mailData->attachment as $attachment){
        $mail->addAttachment($attachment);
    }
        
    $mail->Subject = (string)$mailData->subject;
    $mail->Body = (string)$mailData->content;
    $mail->isHTML(true);
        try{
            if (!$mail->send()) {
                $msg .= 'Mailer Error: '. $mail->ErrorInfo;
            } else {
                $msg .= 'Message sent!';
            }            
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        echo $msg.$newLineChar;
        $msg = "";
        $mail->clearAllRecipients();
        $mail->clearAttachments();
}
?>        
