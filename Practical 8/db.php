<?php
// Practical 8: StudentHub Database - Database Connection using PDO
// Student Name: Bhavy Gol
// Student ID: 25CS016

$host = "localhost";
$dbname = "studenthub";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "Database Connected Successfully";
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
