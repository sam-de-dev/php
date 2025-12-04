<?php
// QrHelper.php

use Endroid\QrCode\Builder\Builder;

class QrHelper
{
    /**
     * Generates a QR Code PNG binary string from an array or string.
     */
    public static function generate(string|array $data, int $size = 150): string
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $qr = Builder::create()
            ->data($data)
            ->size($size)
            ->build();

        return $qr->getString(); // raw PNG binary
    }

    /**
     * Attaches an embedded QR code image to PHPMailer and returns the CID.
     */
    public static function attachToMailer(PHPMailer\PHPMailer\PHPMailer $mail, string $qrPng, string $cid = 'qr_code'): string
    {
        $mail->addStringEmbeddedImage(
            $qrPng,           // raw PNG binary
            $cid,             // CID identifier
            'qrcode.png',
            'base64',
            'image/png'
        );

        return $cid;
    }
}
