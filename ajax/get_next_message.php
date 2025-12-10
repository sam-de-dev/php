<?php

$message = [
    ["id"=>0, "msg" => "Hello, this is the first message."],
    ["id"=>1, "msg" => "This is the second message."],
    ["id"=>2, "msg" => "And here is the third message."]
];

$msg = $message[0];

if (isset($_GET['id'])){
    $msg = $message[$_GET['id']];
}

echo json_encode($msg);