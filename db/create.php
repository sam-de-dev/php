<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Connect to MySQL
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database
    $conn->exec("
        CREATE DATABASE IF NOT EXISTS my_simple_db
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_general_ci
    ");
    echo "Database created!<br>";

    // Select database
    $conn->exec("USE my_simple_db");

    // USERS TABLE
    $conn->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // ITEMS TABLE
    $conn->exec("
        CREATE TABLE IF NOT EXISTS items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_name VARCHAR(100) NOT NULL,
            stock INT NOT NULL,
            price INT NOT NULL
        ) ENGINE=InnoDB
    ");

    // ORDERS TABLE
    $conn->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_email VARCHAR(100),
            receipt_number VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_email) REFERENCES users(email)
                ON DELETE SET NULL
        ) ENGINE=InnoDB
    ");

    // ORDER ITEMS TABLE
    $conn->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            receipt_number VARCHAR(100),
            item_id INT NOT NULL,
            quantity INT NOT NULL,
            FOREIGN KEY (receipt_number) REFERENCES orders(receipt_number)
                ON DELETE CASCADE,
            FOREIGN KEY (item_id) REFERENCES items(id)
                ON DELETE CASCADE
        ) ENGINE=InnoDB
    ");

    echo "All tables created successfully!";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
