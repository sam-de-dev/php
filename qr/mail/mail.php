<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

require 'vendor/autoload.php';
require '../QrHelper.php';   // <--- include the common helper

echo "start";

$mail = new PHPMailer(true);

echo "run";

try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sender';
    $mail->Password   = file_get_contents('00-gmail_password.txt');
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Sender & recipient
    $mail->setFrom('sender', 'test');
    $mail->addAddress('recipcant', 'name');
    $mail->addReplyTo('sender', 'test');

    // HTML email
    $mail->isHTML(true);
    $mail->Subject = 'test 123';

    // Generate a QR code with whatever data you want
    $orderNumber = "hamburger"; // or from DB, GET, POST, etc

    $qrBinary = QrHelper::generate(['orderNumber' => $orderNumber]);

    // Attach QR and get CID
    $cid = QrHelper::attachToMailer($mail, $qrBinary);

    // Replace placeholder in your template with the QR image tag

    $mail->Body = <<<HTML
    <table width="400" height="300" cellpadding="0" cellspacing="0" border="0" style="border-spacing:15px;">
        <td>
         <img src="cid:$cid" alt="QR Code" style="width:150px;height:150px;">
        </td>
    </table>
    HTML;

    $mail->send();

    echo "success";
} catch (Exception $e) {
    echo "fail: " . $e->getMessage();
}
?>
