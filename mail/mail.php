<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\Builder\Builder;

echo "start";
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'vendor/autoload.php';


    $mail = new PHPMailer(true);

    echo "run";

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        #$mail->Host       = '127.0.0.1';     // From ProtonMail Bridge SMTP settings
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sender'; # swich to your own email
        $mail->Password = file_get_contents('00-gmail_password.txt');
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        #$mail->Port       = 1025;            //for Bridge

        // Recipient & sender
        $mail->setFrom('sender', 'test');# swich to your own email and what you wnat the name to be

        $mail->addAddress('recpicant', 'name'); # who you sending it to

        $mail->addReplyTo('sender', 'test');# swich to your own email and what you wnat the name to be

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'test 123';
        // HTML template

        $htmlBody = file_get_contents('html.html');#uses this page to make it with html

        $mail->Body = $htmlBody; 

        $mail->send();

        echo "success";
    } catch (Exception $e) {
        echo "fail" . $e->getMessage();
    }

?>