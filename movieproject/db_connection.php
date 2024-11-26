<?php
$host = "localhost";
$username = "moviehub_user";
$password = "password"; // Use your MySQL password if set, or leave it blank for XAMPP default
$database = "moviehub"; // Update this with your actual database name

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
