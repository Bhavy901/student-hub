<?php
// Practical 9: User Registration Form with Frontend Validations
// Student Name: Bhavy Gol
// Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Practical 9</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>User Registration</h2>
    <p class="subtitle">Secure Registration with Password Hashing</p>

    <form action="process_registration.php" method="POST">
        <label for="username">Username:</label>
        <input
            type="text"
            id="username"
            name="username"
            placeholder="e.g. student01"
            required
            minlength="3"
            maxlength="50"
            pattern="^[a-zA-Z0-9_]{3,50}$"
            title="Username must contain only letters, numbers, and underscore (3-50 characters)"
        >

        <label for="email">Email:</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="e.g. student01@gmail.com"
            required
        >

        <label for="password">Password:</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="At least 6 characters"
            required
            minlength="6"
        >

        <label for="confirm_password">Confirm Password:</label>
        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            placeholder="Repeat password"
            required
            minlength="6"
        >

        <button type="submit">Register</button>
    </form>

    <div class="footer-links">
        <a href="users.php" class="view-users-btn">🗄️ View Registered Users (DB Verification)</a>
    </div>
</div>
</body>
</html>
