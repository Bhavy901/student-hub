<?php
// Practical 10: Main Dashboard with Session Validation & Inactivity Timeout
// Student Name: Bhavy Gol | Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

session_start();
$timeout = 300;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}

if (
    isset($_SESSION["last_activity"]) &&
    (time() - $_SESSION["last_activity"] > $timeout)
) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit;
}

$_SESSION["last_activity"] = time();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
    <p>
        Your role:
        <strong>
            <?php echo htmlspecialchars($_SESSION["role"]); ?>
        </strong>
    </p>
    <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 10px; margin: 15px 0; text-align: left;">
        <span style="color: #166534; font-weight: bold; font-size: 13px;">✔ Session Security: session_regenerate_id(true) Active</span><br>
        <span style="font-size: 12px; color: #15803d; word-break: break-all;">Current Session ID: <code><?php echo session_id(); ?></code></span>
    </div>
    <?php if ($_SESSION["role"] === "admin"): ?>
        <h3>Admin Dashboard</h3>
        <p>Welcome to the Admin Dashboard.</p>
        <a href="admin_dashboard.php">Go to Admin Dashboard</a>
    <?php elseif ($_SESSION["role"] === "student"): ?>
        <h3>Student Dashboard</h3>
        <p>Welcome to the Student Dashboard.</p>
        <a href="student_dashboard.php">Go to Student Dashboard</a>
    <?php endif; ?>
    <br><br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
