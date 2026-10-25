# Practical 12: Event Management CRUD Web Application with Secure Poster File Upload & Multi-Tier Validation

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  
**Semester:** 3rd  
**Department:** Department of Computer Science & Engineering  
**Institute:** Chandubhai S. Patel Institute of Technology (CSPIT)  
**Date:** 19/10/2026  

---

## 🎯 Problem Statement
Design and implement a complete Event Management CRUD Web Application in PHP and MySQL with Secure Poster File Upload and Multi-Tier Validation. The system must support administrative authentication using BCRYPT hashing and session regeneration, complete CRUD operations (Create, Read, Update, Delete) for event details (title, date, location, category, status), dynamic multi-criteria search and filtering (by category and open/closed status), robust server-side file upload validation for event posters (verifying allowed extensions: JPG/PNG/WEBP, enforcing a strict 2MB maximum file size limit, and validating MIME types with `finfo_file()` and `getimagesize()`), directory security with unique random file renaming and `.htaccess` execution prevention, flash notification alerts for user feedback, and strict access control protecting administrative endpoints from unauthorized users.

---

## 📌 Objective
The objective of this practical is to implement an enterprise-grade Event Management CRUD system with secure file upload architecture:
- **Administrative Authentication:** Implement secure administrative authentication using BCRYPT password hashing (`password_verify()`) and session fixation prevention with `session_regenerate_id(true)`.
- **Prepared Statement CRUD Operations:** Build complete CRUD (Create, Read, Update, Delete) database workflows with prepared SQL statements to guarantee protection against SQL injection.
- **Multi-Layer File Upload Validation:** Implement multi-tier file upload validation: extension checking (JPG, JPEG, PNG, WEBP), file size caps (max 2MB), and real MIME-type verification (`finfo_file()` and `getimagesize()`).
- **Upload Directory Hardening:** Protect upload directories against arbitrary file execution and path traversal attacks using safe random hashing (`bin2hex(random_bytes(16))`) and `.htaccess` configuration.
- **Flash Feedback Messaging:** Design session-based flash messaging alerts (`flash()`, `show_flash()`) for comprehensive user feedback on successful operations and validation errors.
- **Role-Based Access Restrictions:** Implement route protection guards (`require_admin()`) guarding administrative dashboards from unauthorized visitors.
- **Dynamic Search & Filtering:** Provide dynamic event search (by title or location) and category/status filtering capabilities.

---

## 🔑 Key Architecture, Security & CRUD Analysis

| Feature / Mechanism | Implementation Strategy | Functional / Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **Admin Authentication** | `password_verify($password, $admin['password_hash'])` against BCRYPT hash | Prevents plaintext credential exposure and brute-force vulnerabilities | ✅ Implemented |
| **Session Fixation Prevention** | `session_regenerate_id(true)` upon successful login | Eliminates session hijacking risks by refreshing session identifiers | ✅ Implemented |
| **Route Protection Guard** | `require_admin()` checking `$_SESSION['admin_id']` | Enforces strict access control on all administrative dashboards | ✅ Implemented |
| **File Extension Whitelisting** | Verifies extension against `['jpg', 'jpeg', 'png', 'webp']` | Rejects malicious files masquerading under executable extensions | ✅ Implemented |
| **Strict File Size Cap** | Enforces `MAX_POSTER_MB = 2` (2MB) limit via server-side check | Prevents denial of service and server disk exhaustion | ✅ Implemented |
| **Deep MIME Inspection** | `finfo_file(FILEINFO_MIME_TYPE)` & `@getimagesize()` verification | Detects and blocks disguised executable scripts (.php renamed to .jpg) | ✅ Implemented |
| **Cryptographic Random Renaming** | `time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext` | Prevents directory traversal, file overwrites, and predictable URLs | ✅ Implemented |
| **Upload Directory Hardening** | `.htaccess` with `<FilesMatch>` denying execution of `.php`, `.phar`, etc. | Neutralizes Remote Code Execution (RCE) even if an upload slips through | ✅ Implemented |
| **Prepared SQL Statements** | Parameterized queries via `mysqli_prepare()` and `mysqli_stmt_bind_param()` | Complete immunity against SQL Injection attacks across all CRUD queries | ✅ Implemented |
| **Orphan File Prevention** | Automatically unlinks file if database insert fails or event is deleted | Eliminates dangling orphaned files on disk and preserves clean storage | ✅ Implemented |
| **Dynamic Search & Filtering** | Multi-parameter SQL builder combining `LIKE ?` and exact matches | Supports combined filtering by search keyword, category, and status | ✅ Implemented |
| **Status Toggle Endpoint** | `UPDATE events SET status = IF(status = 'Open', 'Closed', 'Open')` | Enables instant one-click status transitions with preserved query state | ✅ Implemented |
| **Cross-Site Scripting (XSS) Guard** | `htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8')` | Prevents stored and reflected XSS when outputting event metadata | ✅ Implemented |
| **Session Flash Feedback** | `$_SESSION['flash']` persisted across HTTP 302 redirects | Immediate, modern dismissible Bootstrap alert banners for user feedback | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 12/
├── db.php             # MySQL Database Connection Script (mysqli_report & utf8mb4)
├── functions.php      # Helpers: Sessions, Flash Alerts, Admin Guard & Multi-Tier Poster Upload Validation
├── login.php          # Admin Login with BCRYPT password_verify & session_regenerate_id
├── index.php          # Main Dashboard: Create, Read, Search, Filter, Status Toggle & Delete
├── edit.php           # Event Modification Form with Optional Poster Replacement & Cleanup
├── logout.php         # Secure Administrative Logout & Complete Session Destruction
├── schema.sql         # MySQL Database DDL & Seed Records (Admins & Events Tables)
├── uploads/           # Dedicated Event Poster Upload Directory
│   ├── .htaccess      # Upload Directory Hardening (Blocks Script Execution / RCE Prevention)
│   └── .gitkeep       # Git Directory Persistence Placeholder
└── README.md          # Comprehensive Practical Documentation & Reference Manual
```

---

## 🔑 Key Components & Code Implementation

### 1. Database Schema & Seed Data (`schema.sql`)
Defines the `event_db` database, the `admins` table with BCRYPT hashes, and the `events` table with category, status, and poster storage:
```sql
CREATE DATABASE IF NOT EXISTS event_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE event_db;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    status ENUM('Open','Closed') NOT NULL DEFAULT 'Open',
    poster VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin credentials: username admin / password admin123
INSERT INTO admins (username, password_hash) VALUES 
('admin', '$2y$10$neDfjdlh.ZYxOKso1FNwaOyoFi.vW.n.jRLyr4tzdW6.kDD3Stxhu');
```

### 2. Database Connection (`db.php`)
Connects to MySQL using procedural `mysqli_connect()`, disables automatic exception throwing to handle errors cleanly, and sets `utf8mb4`:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "event_db";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
```

### 3. Multi-Layer File Upload & Security Engine (`functions.php`)
Performs 4 distinct layers of validation before saving any file to disk:
1. **Extension Whitelist:** Rejects anything other than `jpg`, `jpeg`, `png`, and `webp`.
2. **File Size Enforcement:** Rejects files exceeding `2MB`.
3. **MIME Type & Real Content Check:** Uses `finfo_file()` and `@getimagesize()` to inspect file magic bytes.
4. **Cryptographic Renaming:** Generates unguessable unique filenames (`time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext`).

```php
function save_poster($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['name' => null, 'error' => null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['name' => null, 'error' => 'Poster upload failed.'];
    }

    // 1. Extension check
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXT, true)) {
        return ['name' => null, 'error' => 'Invalid file type. Only JPG, JPEG, PNG and WEBP are allowed.'];
    }

    // 2. File size check
    if ($file['size'] > MAX_POSTER_MB * 1024 * 1024) {
        return ['name' => null, 'error' => 'Poster is too large. Maximum allowed size is 2 MB.'];
    }

    // 3. Real MIME inspection
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ALLOWED_MIME, true) || @getimagesize($file['tmp_name']) === false) {
        return ['name' => null, 'error' => 'The file is not a valid image.'];
    }

    // 4. Random renaming and move
    $new_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $new_name)) {
        return ['name' => null, 'error' => 'Could not save the poster.'];
    }
    return ['name' => $new_name, 'error' => null];
}
```

### 4. Administrative Login & Session Regeneration (`login.php`)
Authenticates against stored BCRYPT hashes and regenerates the session ID to neutralize fixation attacks:
```php
$stmt = mysqli_prepare($conn, "SELECT id, password_hash FROM admins WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($admin && password_verify($password, $admin['password_hash'])) {
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $username;
    flash('success', 'Welcome, ' . $username . '!');
    header('Location: index.php');
    exit();
}
```

### 5. Main Dashboard & Dynamic Prepared Queries (`index.php`)
Handles CRUD workflows, multi-criteria dynamic filtering, and safe physical poster unlinking:
```php
$sql = "SELECT * FROM events";
$where = [];
$types = '';
$params = [];

if ($search !== '') {
    $where[] = "(title LIKE ? OR location LIKE ?)";
    $like = '%' . $search . '%';
    $types .= 'ss';
    array_push($params, $like, $like);
}
if ($category_filter !== '') {
    $where[] = "category = ?";
    $types .= 's';
    $params[] = $category_filter;
}
if ($status_filter !== '') {
    $where[] = "status = ?";
    $types .= 's';
    $params[] = $status_filter;
}
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY event_date ASC, id DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```

### 6. Event Modification & Poster Replacement (`edit.php`)
Replaces event metadata and swaps posters cleanly—deleting the old image file only when the database transaction succeeds:
```php
if ($new_poster) {
    delete_poster($event['poster']); // old file removed only AFTER update succeeded
}
```

### 7. Directory Hardening (`uploads/.htaccess`)
Prevents Remote Code Execution (RCE) by completely disabling script execution inside the uploads directory:
```apache
<FilesMatch "\.(php|php[0-9]|phtml|phar|pl|py|jsp|asp|sh|cgi)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
Options -Indexes
```

---

## 🧪 Verified Test Cases & Output Scenarios

The implementation strictly fulfills and passes all test cases established in the laboratory manual:

1. **A. Admin Login and Dashboard:**
   - Navigating to `login.php` renders the administrative login portal with username and password inputs.
   - Successful authentication redirects to the primary Event Dashboard with greeting alert banner.
2. **B. Test Case 1 – Successful Event Creation:**
   - Submitting valid event details (Title, Date, Location, Category, Status) without or with poster.
   - Displays green success flash message: `Event "<title>" was added successfully.`.
3. **C. Test Case 2 – Event List with Uploaded Posters:**
   - Events table renders ID, poster preview (70x90px with `object-fit: cover`), Title, Date, Location, Category, Status badge (`Open` green / `Closed` grey), and Action controls.
   - Events without posters cleanly display a `No poster` placeholder badge.
4. **D. Test Case 3 – Successful Event Update:**
   - Clicking `Edit` loads `edit.php?id=...` with populated inputs and current poster preview.
   - Updating details or uploading a replacement poster modifies the record and displays `Event updated successfully.`.
5. **E. Test Case 4 – Successful Event Deletion:**
   - Clicking `Delete` prompts browser confirmation dialog `Delete this event and its poster?`.
   - On confirmation, removes database record, permanently deletes the corresponding file from disk, and flashes `Event and its poster were deleted.`.
6. **F. Test Case 5 – Valid Poster Upload:**
   - Uploading valid image formats (`.jpg`, `.jpeg`, `.png`, `.webp`) under 2MB stores the file with cryptographic random renaming.
7. **G. Test Case 6 – Invalid File Type Validation:**
   - Attempting to upload unsupported file types (e.g., `.txt`, `.pdf`, `.php`) is caught and blocked with error: `Invalid file type. Only JPG, JPEG, PNG and WEBP are allowed.`.
8. **H. Test Case 7 – File Size Limit Validation (Max 2MB):**
   - Uploading files larger than 2MB is rejected with error: `Poster is too large. Maximum allowed size is 2 MB.`.
9. **I. Test Case 8 – Success and Error Messages:**
   - Session flash alerts render styled Bootstrap dismissible alerts (`alert-success`, `alert-danger`, `alert-warning`, `alert-info`).
   - Omitted required fields render structured error lists.
10. **J. Test Case 9 – Unauthorized User Access Restriction:**
    - Directly requesting `index.php` or `edit.php` without an active admin session invokes `require_admin()`, redirecting to `login.php` with warning alert `Please log in as admin to continue.`.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
Import the database schema and default admin credentials using MySQL CLI or phpMyAdmin:
```bash
mysql -u root -p < "Practical 12/schema.sql"
```

### 2. Permissions for Upload Directory
Ensure the `uploads` directory is writable by the web server process:
```bash
chmod 775 "Practical 12/uploads"
```

### 3. Start Built-in PHP Development Server
Navigate to the project root and launch the server:
```bash
php -S localhost:8000 -t "Practical 12"
```

### 4. Access in Web Browser
- **Admin Login:** [http://localhost:8000/login.php](http://localhost:8000/login.php)
- **Event Dashboard:** [http://localhost:8000/index.php](http://localhost:8000/index.php)

> **Default Admin Credentials:**  
> - **Username:** `admin`  
> - **Password:** `admin123`

---

## 🎓 Learning Outcomes
- **HTTP Multipart File Upload Mechanics:** Understood the mechanics of HTTP multipart/form-data file uploads and processing files using PHP's `$_FILES` superglobal array.
- **Multi-Tier File Upload Validation:** Implemented robust, multi-tier file validation checking file extensions, file size constraints, and verifying authentic MIME types using `finfo_file()` and `getimagesize()`.
- **Upload Directory Hardening & RCE Defense:** Learned how to harden upload directory security against Remote Code Execution (RCE) by assigning cryptographically random names (`bin2hex(random_bytes(16))`) and disabling script execution via `.htaccess`.
- **Secure Authentication & Session Fixation Protection:** Implemented complete administrative authentication with BCRYPT hashing (`password_verify`) and session fixation protection using `session_regenerate_id(true)`.
- **Injection-Proof Prepared Statements:** Constructed complete CRUD (Create, Read, Update, Delete) database workflows with MySQLi prepared statements to prevent SQL injection.
- **Session Flash Feedback Across Redirects:** Designed clean user feedback systems using session flash messaging to persist alert notifications across HTTP redirects.
- **Access Control & Route Protection:** Implemented route protection guards (`require_admin()`) to restrict unauthorized users and enforce role-based access control.

---

## 🔗 Repository Reference
- **GitHub Repository:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
