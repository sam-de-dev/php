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
        <tr>
            <td style="position:relative; border:2px solid #e58005; border-radius: 8px; ">
                <img src="https://www.churchtimes.co.uk/media/5645642/2001-8k-cc-124-20180514122220043_web.jpg" width="400" height="300" style="display:block;">
                <div style="position:absolute; bottom:0; width:100%; height: 20%; background:#ffffff; opacity:0.7; text-align:center; font-family:Arial, sans-serif;">
                    Space Odyssey
                </div>
            </td>
        </tr>
        <td>
         <img src="cid:$cid" alt="QR Code" style="width:150px;height:150px;">
        </td>
        <tr>
            <td style="position:relative; border:2px solid #e58005; border-radius: 8px; ">
                <img src="https://platform.vox.com/wp-content/uploads/sites/2/chorus/uploads/chorus_asset/file/15618476/tfa_poster_wide_header-1536x864-324397389357.0.0.1537961254.jpg?quality=90&strip=all&crop=0%2C3.4613147178592%2C100%2C93.077370564282&w=1200" width="400" height="300" style="display:block;">
                <div style="position:absolute; bottom:0; width:100%; height: 20%; background:#ffffff; opacity:0.7; text-align:center; font-family:Arial, sans-serif;">
                    Star Wars: The Force Awakens
                </div>
            </td>
        </tr>
    </table>
    HTML;

    $mail->send();

    echo "success";
} catch (Exception $e) {
    echo "fail: " . $e->getMessage();
}
?>
