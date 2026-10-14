<?php
// Practical 10: Session Inactivity Timeout Handling
// Student Name: Bhavy Gol | Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout = 300;

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}

if (
    isset($_SESSION["last_activity"]) &&
    (time() - $_SESSION["last_activity"] > $timeout)
) {
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
    header("Location: login.php?error=Session expired. Please login again.");
    exit;
}

$_SESSION["last_activity"] = time();
?>
