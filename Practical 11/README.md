# Practical 11: Student Management System (Procedural MySQLi CRUD with Search & Filter)

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
Create a student management module to add, view, edit, delete, search and filter student records. All database operations must use MySQLi statements and clear success/failure messages.

---

## 📌 Objective
The objective of this practical is to implement a robust, production-style Student Management System using procedural PHP and MySQLi functions:
- Establish database connections using procedural `mysqli_connect()` and handle connection errors with `mysqli_connect_error()`.
- Implement complete CRUD (Create, Read, Update, Delete) database workflows with SQL queries.
- Build multi-criteria dynamic SQL filtering combining `LIKE` searches and `WHERE` clauses.
- Sanitize user inputs against SQL injection using `mysqli_real_escape_string()`.
- Implement safe Soft Deletion preserving database audit trails by updating status flags.
- Provide user feedback with alert notifications and JavaScript delete confirmation dialogs.
- Generate and stream dynamically formatted CSV spreadsheets using PHP's `fputcsv()` function.

---

## 🔑 Key Architecture, Security & CRUD Analysis

| Feature / Mechanism | Implementation Strategy | Functional / Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **Database Connection** | Procedural `mysqli_connect()` with `mysqli_connect_error()` check | Clean connection handling with robust fallback error logging | ✅ Implemented |
| **Create (Add Student)** | `INSERT INTO students ...` with validated POST input | Validates required fields before executing database insertion | ✅ Implemented |
| **Read & Dynamic Filter** | Dynamic SQL combining `LIKE '%$search%'` & `course = '$course_filter'` | Multi-parameter search supporting combined criteria filtering | ✅ Implemented |
| **Update Student Details** | `UPDATE students SET ... WHERE id = $id` with pre-filled inputs | Allows safe in-place updates of name, email, course, gender & status | ✅ Implemented |
| **Soft Deletion** | `UPDATE students SET status = 'Inactive' WHERE id = $id` | Preserves relational integrity and audit history without permanent data loss | ✅ Implemented |
| **Input Sanitization** | `mysqli_real_escape_string()` and `htmlspecialchars()` | Protects against SQL Injection and Cross-Site Scripting (XSS) | ✅ Implemented |
| **Dynamic CSV Export** | Stream headers `text/csv` + `fputcsv()` to `php://output` | Low memory footprint dynamic report generation for Excel / Calc | ✅ Implemented |
| **User Feedback Alerts** | Query string banners (`?msg=...` & `?error=...`) | Provides immediate visual confirmation of operations | ✅ Implemented |
| **Delete Confirmation** | Client-side JavaScript `confirm('Delete this record? (Soft Delete)')` | Prevents accidental record deletion | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 11/
├── db.php             # Procedural MySQLi Database Connection Script
├── index.php          # Main Dashboard: Create, Read, Search, Filter & Soft Delete
├── edit.php           # Update Student Details Form with Status Control
├── export_csv.php     # Dynamic CSV Spreadsheet Generation & Streaming Script
├── style.css          # Responsive Modern Dashboard Design System & Styling
├── schema.sql         # MySQL Database DDL & Seed Records
└── README.md          # Comprehensive Practical Documentation & Reference
```

---

## 🔑 Key Components & Code Implementation

### 1. Database Schema & Seed Data (`schema.sql`)
Defines the `student_db` database and `students` table equipped with auto-increment primary key, default timestamps, and status flags:
```sql
CREATE DATABASE IF NOT EXISTS `student_db`;
USE `student_db`;

CREATE TABLE IF NOT EXISTS `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `course` VARCHAR(50) NOT NULL,
    `gender` VARCHAR(10) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. Procedural MySQLi Connection (`db.php`)
Establishes the connection using `mysqli_connect()` and checks for errors with `mysqli_connect_error()`:
```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "student_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
```

### 3. Main Dashboard & Dynamic Query Engine (`index.php`)
Handles Create, Read, Multi-Criteria Dynamic Search, and Soft Deletion:
- **Create:** Validates inputs, sanitizes strings via `mysqli_real_escape_string()`, and inserts active student record.
- **Dynamic Search & Filtering:**
```php
$conditions = [];
if (!empty($search)) {
    $conditions[] = "(name LIKE '%$search%' OR email LIKE '%$search%')";
}
if (!empty($course_filter)) {
    $conditions[] = "course = '$course_filter'";
}

$query_sql = "SELECT * FROM students";
if (count($conditions) > 0) {
    $query_sql .= " WHERE " . implode(' AND ', $conditions);
}
$query_sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query_sql);
```
- **Soft Deletion Workflow:**
```php
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $delete_sql = "UPDATE students SET status = 'Inactive' WHERE id = $id";
    if (mysqli_query($conn, $delete_sql)) {
        header("Location: index.php?msg=" . urlencode("Student record soft-deleted (status set to Inactive) successfully!"));
        exit();
    }
}
```

### 4. Record Modification Module (`edit.php`)
Fetches the existing student record by ID, displays pre-selected fields, and executes the `UPDATE` query:
```php
$update_sql = "UPDATE students 
               SET name = '$name', email = '$email', course = '$course', gender = '$gender', status = '$status' 
               WHERE id = $id";
```

### 5. Dynamic CSV File Streaming (`export_csv.php`)
Generates downloadable spreadsheets dynamically without saving temporary files to disk:
```php
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Name', 'Email', 'Course', 'Gender', 'Status', 'Registration Date']);

$query = "SELECT id, name, email, course, gender, status, created_at FROM students ORDER BY id ASC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['email'],
        $row['course'],
        $row['gender'],
        $row['status'],
        $row['created_at']
    ]);
}
fclose($output);
exit();
```

---

## 🧪 Verified Test Cases & Output Scenarios

The implementation satisfies all test cases defined in the laboratory manual:

1. **A. Student Records Table Display:**
   - Displays all registered students in a formatted tabular layout with columns `#`, `Name`, `Email`, `Course`, `Gender`, `Status`, and `Actions`.
   - Visual badges render green for `Active` and red for `Inactive`.
2. **B. Test Case 1 – Successful Addition of a New Student:**
   - Entering full details (Name, Email, Course, Gender) and submitting inserts the record.
   - Redirects to `index.php` with green alert: `✔ Student record added successfully!`.
3. **C. Test Case 2 – Successful Update of Student Details:**
   - Clicking `Edit` navigates to `edit.php?id=...` with populated fields.
   - Modifying fields and clicking `Update Details` updates the database and redirects with: `✔ Student details updated successfully!`.
4. **D. Test Case 3 – Search Functionality:**
   - Entering keyword (e.g. `Bhavy`) searches across both `name` and `email` columns using SQL `LIKE '%$search%'`.
5. **E. Test Case 4 – Course-Wise Filtering:**
   - Selecting a specific course (e.g., `Computer Science`) filters records to match the selected department.
   - Can be combined seamlessly with search queries.
6. **F. Test Case 5 – Delete Confirmation Message:**
   - Clicking `Delete` prompts a JavaScript dialog: `Delete this record? (Soft Delete)`.
   - Prevents accidental deletion if user cancels.
7. **G. Test Case 6 – Successful Soft Delete:**
   - On confirmation, executes `UPDATE students SET status = 'Inactive' WHERE id = $id`.
   - Preserves historical records and displays red badge `Inactive`.
   - Flash banner: `✔ Student record soft-deleted (status set to Inactive) successfully!`.
8. **H. Test Case 7 – Success & Error Messages:**
   - Form field omissions or server-side validation triggers red error alerts: `✖ All fields are required.`.
9. **I. Test Case 8 – Exported CSV File in Excel:**
   - Clicking `Export to CSV` triggers instant streaming download `students_export.csv`.
   - Opens seamlessly in Microsoft Excel, Google Sheets, or LibreOffice Calc.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
Create database and seed initial test records using MySQL CLI or phpMyAdmin:
```bash
mysql -u root -p < "Practical 11/schema.sql"
```

### 2. Start PHP Development Server
Navigate to the repository and launch the built-in server:
```bash
php -S localhost:8000 -t "Practical 11"
```

### 3. Access in Browser
- **Management Dashboard:** [http://localhost:8000/index.php](http://localhost:8000/index.php)
- **CSV Export Endpoint:** [http://localhost:8000/export_csv.php](http://localhost:8000/export_csv.php)

---

## 🎓 Learning Outcomes
- **Procedural MySQLi Execution:** Understood how to execute procedural MySQLi database functions (`mysqli_connect`, `mysqli_query`, `mysqli_fetch_assoc`, `mysqli_real_escape_string`) without PDO.
- **Unified CRUD Application Workflow:** Implemented complete CRUD (Create, Read, Update, Delete) operations in a unified PHP application workflow.
- **Dynamic Multi-Criteria Queries:** Constructed dynamic SQL queries supporting multi-criteria search (name and email) combined with course filtering clauses.
- **Data Sanitization & Hygiene:** Applied input sanitization techniques using `mysqli_real_escape_string()` and output escaping with `htmlspecialchars()` to prevent SQL Injection and XSS attacks.
- **Soft Deletion Best Practices:** Understood the advantage of soft deletion (setting `status = 'Inactive'`) over hard physical deletion to preserve database integrity and historical audit trails.
- **CSV Stream Generation:** Learned how to generate and stream dynamically formatted CSV files using PHP's `fputcsv()` and HTTP `Content-Disposition` headers for spreadsheet applications like Excel.

---

## 🔗 Repository Reference
- **GitHub Repository:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
