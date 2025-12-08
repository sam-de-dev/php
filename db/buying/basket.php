<?php
session_start();
if (!isset($_SESSION["user_email"])) {
    header("Location: ../sign/sign-in.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=my_simple_db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = "";

// Handle Checkout
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["checkout"])) {
    if (!empty($_SESSION["basket"])) {
        $receipt_number = uniqid("REC-");

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_email, receipt_number) VALUES (?, ?)");
        $stmt->execute([$_SESSION["user_email"], $receipt_number]);

        // Insert into order_items table and update stock
        foreach ($_SESSION["basket"] as $item_id => $qty) {
            // Check stock
            $stmt = $conn->prepare("SELECT stock FROM items WHERE id=?");
            $stmt->execute([$item_id]);
            $stock = $stmt->fetchColumn();
            if ($stock < $qty) {
                $message .= "Not enough stock for item ID $item_id.<br>";
                continue;
            }

            $stmt = $conn->prepare("INSERT INTO order_items (receipt_number, item_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$receipt_number, $item_id, $qty]);

            // Reduce stock
            $stmt = $conn->prepare("UPDATE items SET stock = stock - ? WHERE id=?");
            $stmt->execute([$qty, $item_id]);
        }

        $message .= "Checkout successful! Receipt: $receipt_number";
        $_SESSION["basket"] = []; // Clear basket
    } else {
        $message = "Basket is empty.";
    }
}

// Fetch items in basket
$basket_items = [];
if (!empty($_SESSION["basket"])) {
    $ids = implode(',', array_keys($_SESSION["basket"]));
    $stmt = $conn->query("SELECT * FROM items WHERE id IN ($ids)");
    $basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Your Basket</h2>
<a href="items.php">Continue Shopping</a> | <a href="logout.php">Logout</a>

<?php if ($message) echo "<p>$message</p>"; ?>

<?php if (!empty($basket_items)): ?>
<form method="POST">
<table border="1" cellpadding="5">
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    <?php $total = 0; foreach ($basket_items as $item): 
        $qty = $_SESSION["basket"][$item["id"]];
        $subtotal = $item["price"] * $qty;
        $total += $subtotal;
    ?>
    <tr>
        <td><?= htmlspecialchars($item["product_name"]) ?></td>
        <td><?= $qty ?></td>
        <td><?= $item["price"] ?></td>
        <td><?= $subtotal ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td><?= $total ?></td>
    </tr>
</table>
<br>
<button type="submit" name="checkout">Checkout</button>
</form>
<?php else: ?>
<p>Your basket is empty.</p>
<?php endif; ?>
