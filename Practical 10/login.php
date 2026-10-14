<?php
// Practical 10: Secure Login Form
// Student Name: Bhavy Gol | Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Secure Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Secure Login</h2>
    <?php
    if (isset($_GET["error"])) {
        echo "<p class='error'>" . htmlspecialchars($_GET["error"]) . "</p>";
    }
    if (isset($_GET["message"])) {
        echo "<p class='success'>" . htmlspecialchars($_GET["message"]) . "</p>";
    }
    ?>
    <form action="authenticate.php" method="POST">
        <label>Email:</label>
        <input 
            type="email"
            name="email"
            required
        >
        <label>Password:</label>
        <input
            type="password"
            name="password"
            required
        >
        <div style="margin-top: 12px; display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" name="remember_me" id="remember_me" style="width: auto; margin-top: 0;">
            <label for="remember_me" style="margin-top: 0; display: inline;">Remember Me</label>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
