<?php
$servername = "localhost";
$username = "root";   // your MySQL username
$password = "";        // your MySQL password

try {
    // Connect without selecting a database (so we can create one)
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1) Create the database
    $sql = "CREATE DATABASE IF NOT EXISTS my_simple_db
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_general_ci";
    $conn->exec($sql);
    echo "Database created!<br>";

    // 2) Select the database
    $conn->exec("USE my_simple_db");

    // 3) Create a table
    $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB";
    $conn->exec($sql);

    echo "Table 'users' created!";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
