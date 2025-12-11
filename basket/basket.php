<?php
session_start();

// init basket
if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}

$action = $_REQUEST['action'] ?? '';
$item = $_POST['item'] ?? '';

if ($item !== '') {
    switch ($action) {
        case 'add':
            if (!isset($_SESSION['basket'][$item])) {
                $_SESSION['basket'][$item] = 0;
            }
            $_SESSION['basket'][$item]++;
            break;
        case 'decrease':
            if (isset($_SESSION['basket'][$item])) {
                $_SESSION['basket'][$item]--;
                if ($_SESSION['basket'][$item] <= 0) {
                    unset($_SESSION['basket'][$item]);
                }
            }
            break;
    }
}

function renderBasketHTML() {
    if (empty($_SESSION['basket'])) {
        return "Basket is empty.";
    }

    $html = "<ul>";
    foreach ($_SESSION['basket'] as $item => $qty) {
        $html .= "<li>$item x $qty 
                  <button class='add-btn' data-item='$item'>+</button> 
                  <button class='decrease-btn' data-item='$item'>-</button>
                  </li>";
    }
    $html .= "</ul>";

    return $html;
}

// total count
$count = array_sum($_SESSION['basket']);

// return JSON
echo json_encode([
    "html" => renderBasketHTML(),
    "count" => $count
]);
