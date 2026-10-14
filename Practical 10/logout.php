<?php
// Practical 10: Secure Logout & Session Destruction
// Student Name: Bhavy Gol | Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

session_start();
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    $stmt = $conn->prepare("UPDATE users SET remember_token_hash = NULL, remember_token_expiry = NULL WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $stmt->close();
    }
}
$conn->close();

if (isset($_COOKIE["remember_token"])) {
    setcookie("remember_token", "", time() - 3600, "/");
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("Location: login.php?message=You have been logged out successfully.");
exit;
?>
