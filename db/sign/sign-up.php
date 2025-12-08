<?php
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $email    = $_POST["email"] ?? "";
    $pass     = $_POST["password"] ?? "";

    if ($username && $email && $pass) {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $message = "Email already exists.";
        } else {
            // Hash password
            $hashed = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("
                INSERT INTO users (username, email, password_hash)
                VALUES (?, ?, ?)
            ");

            if ($stmt->execute([$username, $email, $hashed])) {
                $message = "Account created successfully!";
            } else {
                $message = "Error creating account.";
            }
        }

    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Sign Up</h2>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Sign Up</button>
</form>

<p><?php echo $message; ?></p>

</body>
</html>
