<?php

require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;

$orderNumber = isset($_GET['orderNumber']) ? $_GET['orderNumber'] : null;

$qr = Builder::create()
    ->data(json_encode(['orderNumber' => $orderNumber]))
    ->size(150)
    ->build();

header('Content-Type: image/png');
echo $qr->getString();
exit;