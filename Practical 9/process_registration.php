<?php
// Practical 9: Backend Processing, Hashing & Prepared Statements
// Student Name: Bhavy Gol
// Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS

require_once "db.php";

// Helper function to render styled response matching the lab manual test cases
function renderResponse($isSuccess, $message) {
    $title = $isSuccess ? "Registration Successful!" : "Registration Failed";
    $alertClass = $isSuccess ? "success" : "error";
    $titleClass = $isSuccess ? "success-title" : "error-title";
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?> - Practical 9</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="container response-card">
        <h2 class="<?php echo $titleClass; ?>"><?php echo $title; ?></h2>
        <div class="alert-box <?php echo $alertClass; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <div class="nav-links">
            <a href="register.php">← Back to Registration Form</a> | <a href="users.php">View Users List</a>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    renderResponse(false, "Invalid request method.");
}

// Get form data
$username = trim($_POST["username"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

// Backend validation: Empty fields check
if ($username === "" || $email === "" || $password === "" || $confirm_password === "") {
    renderResponse(false, "All fields are required.");
}

// Validate username format (letters, numbers, underscores, 3-50 characters)
if (!preg_match("/^[a-zA-Z0-9_]{3,50}$/", $username)) {
    renderResponse(false, "Username must contain only letters, numbers, and underscore.");
}

// Validate email address format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    renderResponse(false, "Please enter a valid email address.");
}

// Validate password minimum length
if (strlen($password) < 6) {
    renderResponse(false, "Password must contain at least 6 characters.");
}

// Confirm password match check
if ($password !== $confirm_password) {
    renderResponse(false, "Passwords do not match.");
}

// Check duplicate username or email using MySQLi Prepared Statements
$check = $conn->prepare(
    "SELECT id FROM users WHERE username = ? OR email = ?"
);
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    $conn->close();
    renderResponse(false, "Username or email already exists.");
}
$check->close();

// Secure password using PHP native password_hash() with BCRYPT algorithm
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user using Prepared Statements to prevent SQL Injection
$stmt = $conn->prepare(
    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param(
    "sss",
    $username,
    $email,
    $hashed_password
);

if ($stmt->execute()) {
    // Record registration activity in audit_logs table (if table exists)
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $audit = @$conn->prepare("INSERT INTO audit_logs (username, action, ip_address) VALUES (?, 'USER_REGISTRATION', ?)");
    if ($audit) {
        $audit->bind_param("ss", $username, $ip);
        $audit->execute();
        $audit->close();
    }

    renderResponse(true, "User registered successfully.");
} else {
    renderResponse(false, "Unable to register user.");
}

$stmt->close();
$conn->close();
?>
