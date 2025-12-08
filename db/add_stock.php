<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_email"])) {
    header("Location: sign/sign-in.php");
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST["product_name"] ?? "";
    $stock        = $_POST["stock"] ?? "";
    $price        = $_POST["price"] ?? "";

    if ($product_name && $stock !== "" && $price !== "") {

        $stmt = $conn->prepare("
            INSERT INTO items (product_name, stock, price)
            VALUES (?, ?, ?)
        ");

        if ($stmt->execute([$product_name, $stock, $price])) {
            $message = "Item added successfully!";
        } else {
            $message = "Failed to add item.";
        }

    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Add New Item</h2>
<p>Logged in as: <?php echo htmlspecialchars($_SESSION["user_email"]); ?></p>

<form method="POST">
    <label>Product Name:</label><br>
    <input type="text" name="product_name" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Price:</label><br>
    <input type="number" name="price" required><br><br>

    <button type="submit">Add Item</button>
</form>

<p><?php echo $message; ?></p>

<br>
<a href="logout.php">Logout</a>

</body>
</html>
