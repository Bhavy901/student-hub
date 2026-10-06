# Practical 8: StudentHub Database Design and PDO Database Connection

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  

---

## 🎯 Problem Statement
Design the database for StudentHub with `students`, `events`, and `registrations` tables. Create PHP database connection using MySQL statements and PDO (PHP Data Objects).

## 📌 Objective
The objective of this practical is to create a `studenthub` database using MySQL, design relational 3NF tables (`students`, `events`, `registrations`), establish a secure connection with PHP using PDO (PHP Data Objects) with try-catch error handling, and execute data operations using Prepared Statements to prevent SQL Injection vulnerabilities.

---

## 📁 File Structure
```text
Practical 8/
├── db.php                 # Database Connection Script (PDO)
├── test_connection.php    # Data Retrieval & Display Test Script
├── insert.php             # Data Insertion using Prepared Statements
├── students.php           # Student List Presentation Script
└── README.md              # Laboratory Documentation
```

---

## 🔑 Key Components & Code Files

### 1. Database Connection (`db.php`)
Establishes connection to MySQL database via PDO with exception handling (`PDO::ERRMODE_EXCEPTION`).

```php
<?php
// Practical 8: StudentHub Database - Database Connection using PDO
// Student ID: 25CS016 | Name: Bhavy Gol
$host = "localhost";
$dbname = "studenthub";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "Database Connected Successfully";
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
```

### 2. Data Retrieval Test (`test_connection.php`)
Renders students and event registrations in structured HTML tables.

### 3. Insert Data using Prepared Statements (`insert.php`)
Executes `INSERT` queries securely using positional placeholders (`?`) to prevent SQL Injection.

### 4. Student List (`students.php`)
Renders formatted list of registered students.

---

## 🧪 Output Screenshots / Result Verification

1. **Database Connection Test (`db.php`):** Output shows `Database Connected Successfully`.
2. **Display Student Data Output (`students.php`):** Lists student ID, name, email, and course.
3. **Prepared Statements & Relational JOIN Output (`test_connection.php`):** Displays structured tables for student records and event registrations.
4. **Data Insertion (`insert.php`):** Confirms database connection and displays `Student Added Successfully`.

GitHub Repository Link: [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)

---

## 🎓 Learning Outcomes
- Understood how to design a relational database (`studenthub`) with 3NF compliant tables (`students`, `events`, `registrations`) and establish Primary Key / Foreign Key constraints.
- Learned to connect PHP with MySQL using PDO (PHP Data Objects), configuring UTF-8 charset, error modes, and handling connection exceptions via try-catch blocks.
- Implemented Prepared Statements (`$pdo->prepare()` and `$stmt->execute()`) with positional placeholders (`?`) to prevent SQL Injection security vulnerabilities.
- Mastered retrieving data into associative arrays using `PDO::FETCH_ASSOC` and rendering dynamic database records in HTML.
- Understood relational JOIN queries (`registrations` linking students and events) to manage Many-to-Many entity relationships efficiently.
- Understood the principle of separation of concerns — separating DB connection logic (`db.php`) from data insertion (`insert.php`) and data presentation (`students.php`) for a clean, maintainable web architecture.
