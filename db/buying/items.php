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

// Handle Add to Basket
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["item_id"], $_POST["quantity"])) {
    $item_id = (int)$_POST["item_id"];
    $quantity = (int)$_POST["quantity"];

    if ($quantity > 0) {
        if (!isset($_SESSION["basket"])) $_SESSION["basket"] = [];
        if (isset($_SESSION["basket"][$item_id])) {
            $_SESSION["basket"][$item_id] += $quantity;
        } else {
            $_SESSION["basket"][$item_id] = $quantity;
        }
    }
}

// Fetch all items
$stmt = $conn->query("SELECT * FROM items");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Items</h2>
<p>Logged in as: <?php echo htmlspecialchars($_SESSION["user_email"]); ?></p>
<a href="basket.php">View Basket</a> | <a href="logout.php">Logout</a>

<table border="1" cellpadding="5">
    <tr>
        <th>Product</th>
        <th>Stock</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item["product_name"]) ?></td>
        <td><?= $item["stock"] ?></td>
        <td><?= $item["price"] ?></td>
        <td>
            <form method="POST" style="margin:0;">
                <input type="number" name="quantity" value="1" min="1" max="<?= $item["stock"] ?>">
                <input type="hidden" name="item_id" value="<?= $item["id"] ?>">
        </td>
        <td>
                <button type="submit">Add to Basket</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
