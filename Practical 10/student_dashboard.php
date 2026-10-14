<?php
// Practical 10: Student Role-Protected Dashboard
// Student Name: Bhavy Gol | Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}

if ($_SESSION["role"] !== "student") {
    http_response_code(403);
    die("Unauthorized Access: Students only.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Student Dashboard</h2>
    <p>Welcome, Student!</p>
    <p>You have access to student features.</p>
    <a href="dashboard.php">Back to Dashboard</a>
    <br><br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
