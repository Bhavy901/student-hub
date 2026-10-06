<?php
// Practical 8: Insert Student Data using Prepared Statements
// Student Name: Bhavy Gol
// Student ID: 25CS016

require_once "db.php";

echo "<br>";

$name = "Neha";
$email = "neha@gmail.com";
$course = "Computer";

// SQL query with positional placeholders (?)
$sql = "INSERT INTO students (name, email, course) VALUES (?, ?, ?)";

// Prepare statement to prevent SQL Injection
$stmt = $pdo->prepare($sql);

// Execute statement with parameter array
$stmt->execute([
    $name,
    $email,
    $course
]);

echo "<br>Student Added Successfully";
?>
