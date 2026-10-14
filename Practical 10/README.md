# Practical 10: Secure User Authentication and Session Management with PHP & MySQL

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  
**Semester:** 3rd  
**Department:** Department of Computer Science & Engineering  
**Institute:** Chandubhai S. Patel Institute of Technology (CSPIT)  
**Date:** 14/10/2026  

---

## 🎯 Problem Statement
Implement secure login/logout system with PHP sessions, role-based access control (RBAC), password verification using `password_verify()`, session fixation prevention with session ID regeneration, inactivity timeout handling, unauthorized access protection, and remember-me token functionality.

---

## 📌 Objective
The objective of this practical is to implement a secure user authentication and session management module using PHP and MySQL. It includes:
- Password verification using `password_verify()` to validate credentials against stored BCRYPT hashes.
- Secure session management and session ID regeneration (`session_regenerate_id(true)`) to prevent session fixation attacks.
- Role-based access control (RBAC) directing users to dedicated Admin and Student dashboards.
- Restricting unauthorized access with HTTP 403 Forbidden responses.
- Automatic session termination after 5 minutes of inactivity (`timeout.php`).
- Secure logout destroying session data and clearing browser session cookies (`logout.php`).
- Implementation of cryptographically secure Remember-Me functionality using random tokens and SHA-256 hashing.

---

## 🔑 Key Security & Architecture Analysis

| Feature / Mechanism | Implementation Strategy | Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **Password Verification** | `password_verify($password, $user['password'])` against BCRYPT hashes | Eliminates plain-text password exposure and brute-force risks | ✅ Implemented |
| **Session Fixation Prevention** | `session_regenerate_id(true)` called upon successful authentication | Invalidates previous session ID and assigns a new one to prevent hijacking | ✅ Implemented |
| **Role-Based Access Control (RBAC)** | Role checking via `$_SESSION['role']` ('admin' vs 'student') | Enforces principle of least privilege for application routes | ✅ Implemented |
| **Unauthorized Access Restriction** | `http_response_code(403)` with termination (`die()`) on mismatch | Blocks privilege escalation attempts with standardized HTTP error | ✅ Implemented |
| **Inactivity Timeout** | Verification of `(time() - $_SESSION['last_activity'] > 300)` (5 minutes) | Mitigates unauthorized access on unattended client workstations | ✅ Implemented |
| **Secure Logout Cleanup** | `$_SESSION = []`, cookie expiration with `time() - 42000`, `session_destroy()` | Ensures complete server and client-side session invalidation | ✅ Implemented |
| **Cryptographic Remember-Me** | `bin2hex(random_bytes(32))` token hashed with SHA-256 in DB | Protects persistent authentication tokens even if database is compromised | ✅ Implemented |
| **Prepared Statements** | MySQLi parameterized queries (`$conn->prepare()`, `bind_param()`) | Immune to SQL Injection attacks across all authentication workflows | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 10/
├── db.php                    # MySQLi Database Connection Script
├── login.php                 # Login Form with Email, Password & Remember-Me Option
├── authenticate.php          # Authentication Logic, password_verify() & Session Regeneration
├── dashboard.php             # Main Dashboard with Inactivity Timeout & Role Routing
├── admin_dashboard.php       # Admin Role-Protected Dashboard (HTTP 403 Protection)
├── student_dashboard.php     # Student Role-Protected Dashboard (HTTP 403 Protection)
├── logout.php                # Complete Session Destruction & Cookie Clearance Script
├── timeout.php               # Standalone Inactivity Timeout Handler Script
├── style.css                 # Clean, Responsive UI Stylesheet
├── schema.sql                # MySQL Schema DDL with Role & Token Columns + Seed Records
└── README.md                 # Comprehensive Practical Documentation
```

---

## 🔑 Key Components & Code Implementation

### 1. Database Connection (`db.php`)
Connects to the MySQL database `user_registration` using MySQLi and enforces `utf8mb4` encoding:
```php
$host = "localhost";
$username = "root";
$password = "";
$database = "user_registration";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
```

### 2. Login Form (`login.php`)
Provides the login interface with email validation, password field, and a Remember-Me checkbox. Redirects active authenticated sessions straight to `dashboard.php`.
```php
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}
```

### 3. Authentication & Security Handling (`authenticate.php`)
Performs the core security pipeline:
1. Validates POST method, non-empty fields, and email format (`FILTER_VALIDATE_EMAIL`).
2. Retrieves credentials using prepared statements.
3. Validates password via `password_verify()`.
4. **Prevents Session Fixation** by calling `session_regenerate_id(true)`.
5. Establishes session state (`user_id`, `username`, `email`, `role`, `last_activity`).
6. Updates `last_login = NOW()`.
7. Generates cryptographically secure 32-byte Remember-Me tokens with SHA-256 hash storage and secure HttpOnly cookies.

### 4. Main User Dashboard (`dashboard.php`)
Validates that the session is active and enforces the 5-minute inactivity timeout:
```php
$timeout = 300;
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php?error=Please login first.");
    exit;
}
if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Session expired. Please login again.");
    exit;
}
$_SESSION["last_activity"] = time();
```
Dynamically displays the regenerated session ID and renders navigation links based on user role (`admin` or `student`).

### 5. Role-Protected Dashboards (`admin_dashboard.php` & `student_dashboard.php`)
Enforces strict Role-Based Access Control (RBAC). If an unauthorized user attempts to open a dashboard outside their role, the server responds with HTTP 403 Forbidden:
```php
// admin_dashboard.php
if ($_SESSION["role"] !== "admin") {
    http_response_code(403);
    die("Unauthorized Access: Admins only.");
}

// student_dashboard.php
if ($_SESSION["role"] !== "student") {
    http_response_code(403);
    die("Unauthorized Access: Students only.");
}
```

### 6. Secure Logout (`logout.php`)
Ensures no residual authentication state remains:
1. Clears Remember-Me tokens from the database.
2. Invalidates the `remember_token` browser cookie.
3. Flushes `$_SESSION = []`.
4. Invalidates the session cookie in the browser by setting its expiry in the past (`time() - 42000`).
5. Calls `session_destroy()`.
6. Redirects to `login.php?message=You have been logged out successfully.`

### 7. Inactivity Timeout Module (`timeout.php`)
Modular timeout checker verifying `time() - $_SESSION['last_activity'] > 300`. Can be included across protected pages to ensure consistent idle session invalidation.

---

## 🧪 Verified Test Cases & Output Scenarios

The implementation covers all 9 test cases described in the laboratory manual:

1. **UI: Secure Login Form:** Clean login UI with Email, Password, and Remember Me controls.
2. **Test Case 1 – Successful Student Login:** Logs in with student credentials, displays student dashboard link and active regenerated Session ID.
3. **Test Case 2 – Successful Admin Login:** Logs in with admin credentials, displays admin dashboard link and active regenerated Session ID.
4. **Test Case 3 – Invalid Email/Password Message:** Submitting invalid credentials returns red alert `Invalid email or password.`
5. **Test Case 4 – Student Attempting to Access Admin Dashboard:** Student role user requesting `admin_dashboard.php` receives `HTTP 403 Forbidden: Unauthorized Access: Admins only.`
6. **Test Case 5 – Admin Attempting to Access Student Dashboard:** Admin role user requesting `student_dashboard.php` receives `HTTP 403 Forbidden: Unauthorized Access: Students only.`
7. **Test Case 6 – Successful Logout:** Clicking logout cleans session and cookies, redirecting to login page with `You have been logged out successfully.`
8. **Test Case 7 – Dashboard Access Denied After Logout:** Directly requesting `dashboard.php` after logout is blocked with `Please login first.`
9. **Test Case 8 – Session Timeout and Redirection:** Inactivity exceeding 300 seconds (5 minutes) automatically invalidates session and redirects with `Session expired. Please login again.`
10. **Test Case 9 – Session ID Regeneration in Database:** Confirms session security and verifies user table records and last login timestamps in phpMyAdmin.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
Import the database schema and seed accounts using MySQL or phpMyAdmin:
```bash
mysql -u root -p < "Practical 10/schema.sql"
```

### 2. Default Seed Accounts
| Account Type | Email | Password | Role |
| :--- | :--- | :--- | :--- |
| **Student Account** | `student01@gmail.com` | `student123` | `student` |
| **Admin Account** | `admin@gmail.com` | `admin123` | `admin` |

### 3. Start PHP Built-in Server
From the root directory:
```bash
php -S localhost:8000 -t "Practical 10"
```

### 4. Access Application in Browser
- **Login Page:** [http://localhost:8000/login.php](http://localhost:8000/login.php)
- **Main Dashboard:** [http://localhost:8000/dashboard.php](http://localhost:8000/dashboard.php)
- **Admin Dashboard:** [http://localhost:8000/admin_dashboard.php](http://localhost:8000/admin_dashboard.php)
- **Student Dashboard:** [http://localhost:8000/student_dashboard.php](http://localhost:8000/student_dashboard.php)

---

## 🎓 Learning Outcomes
- **Understood how sessions maintain state** across multiple HTTP requests using session cookies and PHP's `$_SESSION` superglobal array.
- **Learned how to protect against Session Fixation attacks** by invoking `session_regenerate_id(true)` immediately upon successful authentication.
- **Implemented secure authentication** using `password_verify()` to validate plain-text passwords against one-way cryptographic BCRYPT hashes.
- **Implemented Role-Based Access Control (RBAC)** by checking `$_SESSION['role']` and returning HTTP 403 Forbidden on unauthorized dashboard requests.
- **Designed inactivity timeout protection** by comparing current timestamps against `$_SESSION['last_activity']` and automatically invalidating idle sessions.
- **Implemented complete logout cleanup** by emptying the `$_SESSION` array, deleting the session cookie via `setcookie()`, and calling `session_destroy()`.
- **Understood the secure implementation of Remember-Me functionality** using cryptographically random tokens (`bin2hex(random_bytes(32))`) and SHA-256 hash storage.

---

## 🔗 Repository Reference
- **GitHub Repository:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
