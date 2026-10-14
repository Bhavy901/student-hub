<?php
// ====================================================================
// Practical 11: Procedural MySQLi Database Connection Script
// Student Name: Bhavy Gol
// Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
// Academic Year: 2026-27 | Semester: 3rd
// Institute: Chandubhai S. Patel Institute of Technology (CSPIT)
// Department: Department of Computer Science & Engineering
// Date: 14/10/2026
// ====================================================================

$host = "localhost";
$user = "root";
$pass = "";
$db = "student_db";

// Establish database connection using procedural mysqli_connect()
$conn = mysqli_connect($host, $user, $pass, $db);

// Handle connection errors with mysqli_connect_error()
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for full Unicode support
mysqli_set_charset($conn, "utf8mb4");
?>
