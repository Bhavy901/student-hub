# Practical 9: Secure User Registration with MySQLi & Password Hashing

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  
**Semester:** 3rd  
**Department:** Department of Computer Science & Engineering  
**Institute:** Chandubhai S. Patel Institute of Technology (CSPIT)  
**Date:** 13/10/2026  

---

## 🎯 Problem Statement
Implement secure user registration by inserting user details into MySQL using MySQLi. Validate input on frontend and backend, check duplicate email/username, hash passwords using `password_hash()`, and display appropriate messages.

---

## 📌 Objective
The objective of this practical is to implement a secure user registration module using PHP and MySQLi Prepared Statements. It includes client-side and server-side validation, checking for duplicate email and username before database insertion, securing credentials using `password_hash()`, implementing CSRF token verification, and recording registration activity in an `audit_logs` table.

---

## 🔑 Key Security & Architecture Analysis

| Feature / Check | Implementation Strategy | Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **Frontend Validation** | HTML5 `required`, `minlength="6"`, `pattern="^[a-zA-Z0-9_]{3,50}$"`, `type="email"` | Immediate feedback before submission | ✅ Implemented |
| **Server-Side Validation** | `trim()`, `preg_match()`, `filter_var(..., FILTER_VALIDATE_EMAIL)` | Prevents bypassed frontend inputs | ✅ Implemented |
| **SQL Injection Prevention** | MySQLi Prepared Statements (`$conn->prepare()`, `bind_param()`) | Separates query code from input parameters | ✅ Implemented |
| **Password Security** | `password_hash($password, PASSWORD_DEFAULT)` (BCRYPT) | Passwords are never stored in plain text | ✅ Implemented |
| **Duplicate Prevention** | Pre-insertion query checking `username` or `email` | Avoids duplicate entity collisions | ✅ Implemented |
| **Audit Logging** | Activity recording in `audit_logs` table with user IP & timestamp | Traceability and audit tracking | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 9/
├── db.php                     # MySQLi Database Connection Script
├── register.php               # User Registration Form with Frontend Validations
├── process_registration.php   # Backend Processing, Hashing & Prepared Statements
├── users.php                  # Registered Users List & Hashed Password Inspector
├── style.css                  # UI Stylesheet & Responsive Layout
├── schema.sql                 # MySQL Database & Table Creation DDL
└── README.md                  # Comprehensive Practical Documentation
```

---

## 🔑 Key Components & Code Files

### 1. Database Connection (`db.php`)
Establishes a connection to MySQL using the object-oriented MySQLi interface and sets character encoding to `utf8mb4`.

```php
<?php
// Practical 9: MySQLi Connection Script
// Student Name: Bhavy Gol | Student ID: 25CS016

$host = "localhost";
$username = "root";
$password = "";
$database = "user_registration";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
```

### 2. Registration Form (`register.php`)
Provides a user-friendly registration form with client-side validations, input placeholders, and password confirmation fields.

- **Username:** `minlength="3"`, `maxlength="50"`, alphanumeric and underscores.
- **Email:** Standard email format checking.
- **Password & Confirm Password:** `minlength="6"`.
- **Link:** Quick access to `users.php` to verify database records.

### 3. Backend Processing & Prepared Statements (`process_registration.php`)
Processes `POST` submissions, validates all inputs on the server, verifies uniqueness of credentials, hashes passwords, executes prepared statements, and records an audit log.

- **Duplicate Verification:**
  ```php
  $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
  $check->bind_param("ss", $username, $email);
  $check->execute();
  $check->store_result();
  if ($check->num_rows > 0) {
      // Username or email already exists
  }
  ```
- **Password Hashing:**
  ```php
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  ```
- **Prepared Insertion:**
  ```php
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashed_password);
  $stmt->execute();
  ```

### 4. Database Verification View (`users.php`)
An interactive dashboard displaying registered accounts, proving that stored passwords are encrypted using BCRYPT (`$2y$10$...`) rather than plain text.

### 5. Database Schema (`schema.sql`)
Defines the `user_registration` schema, `users` table with unique constraints, and `audit_logs` table for tracking events.

---

## 🧪 Output Screenshots & Test Cases

The implementation satisfies all test cases specified in the laboratory manual:

1. **A. Registration Form UI:** Modern registration form interface with input constraints and direct link to users table.
2. **B. Test Case 1 – Valid Registration:** Form submitted with new valid username and password; displays `Registration Successful!` and saves record to database.
3. **C. Test Case 2 – Duplicate Email Error:** Entering an existing email triggers `Registration Failed: Username or email already exists.`
4. **D. Test Case 3 – Duplicate Username Error:** Entering an existing username triggers duplicate prevention error banner.
5. **E. Test Case 4 – Invalid Email Error:** Invalid email formats are caught and trigger `Please enter a valid email address.`
6. **F. Test Case 5 – Password Mismatch Error:** When password and confirm password fields differ, displays `Passwords do not match.`
7. **G. Test Case 6 – Short Password Error:** Entering less than 6 characters triggers client and server length validation.
8. **H. Test Case 7 – Empty Fields Error:** Blank submissions are prevented with `All fields are required.`
9. **I. Test Case 8 – Invalid Username Format:** Usernames with illegal characters are caught by regex validation.
10. **J. MySQL Users Table & Hashed Passwords View:** Shows `users.php` table with encrypted BCRYPT hash strings and timestamps.
11. **K. phpMyAdmin Table:** Confirms structured database persistence in the `user_registration.users` table.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
Import `schema.sql` using phpMyAdmin or the MySQL command line:
```bash
mysql -u root -p < "Practical 9/schema.sql"
```

### 2. Start PHP Built-in Server
Navigate to the repository root directory and run:
```bash
php -S localhost:8000 -t "Practical 9"
```

### 3. Open in Browser
- **Registration Form:** [http://localhost:8000/register.php](http://localhost:8000/register.php)
- **Users Verification Dashboard:** [http://localhost:8000/users.php](http://localhost:8000/users.php)

---

## 🎓 Learning Outcomes
- Learned to develop a secure user registration system using PHP and MySQLi for storing user details in a database.
- Understood how to validate user inputs on both the client side and server side to maintain data accuracy.
- Gained knowledge of checking duplicate usernames and email addresses before creating a new user account.
- Learned to protect user credentials by hashing passwords using `password_hash()`.
- Understood how CSRF token verification helps protect registration forms from unauthorized submissions.
- Gained experience in recording user registration activities in the `audit_logs` table and displaying appropriate success or error messages.

---

## 🔗 Repository Reference
- **GitHub Repository Link:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
