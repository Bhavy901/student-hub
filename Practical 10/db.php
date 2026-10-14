<?php
// Practical 10: MySQLi Connection Script
// Student Name: Bhavy Gol | Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

$host = "localhost";
$username = "root";
$password = "";
$database = "user_registration";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
