<?php
// Practical 14: PDO Database Connection with JSON Error Handling & SQLite Fallback
$host = 'localhost';
$db   = 'dashboard_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    try {
        $sqlitePath = __DIR__ . '/dashboard.sqlite';
        $isNew = !file_exists($sqlitePath);
        $pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        if ($isNew) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS menu_items (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, link TEXT NOT NULL, min_role TEXT DEFAULT 'user');");
            $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL, email TEXT NOT NULL, role TEXT DEFAULT 'user', status TEXT DEFAULT 'Active', created_at DATETIME DEFAULT CURRENT_TIMESTAMP);");
            $pdo->exec("CREATE TABLE IF NOT EXISTS students (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL, department TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);");
            $pdo->exec("CREATE TABLE IF NOT EXISTS events (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, event_date TEXT NOT NULL, location TEXT NOT NULL, status TEXT DEFAULT 'Open');");
            $pdo->exec("CREATE TABLE IF NOT EXISTS registrations (id INTEGER PRIMARY KEY AUTOINCREMENT, student_name TEXT NOT NULL, event_title TEXT NOT NULL, registered_at DATETIME DEFAULT CURRENT_TIMESTAMP);");
            $pdo->exec("CREATE TABLE IF NOT EXISTS items (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, category TEXT NOT NULL, price REAL NOT NULL);");
            $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (id INTEGER PRIMARY KEY AUTOINCREMENT, user_role TEXT DEFAULT 'admin', action TEXT NOT NULL, details TEXT NOT NULL, ip_address TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);");

            // Seeds
            $pdo->exec("INSERT INTO menu_items (title, link, min_role) VALUES ('Dashboard', '#dashboard', 'user'), ('View Inventory', '#inventory', 'user'), ('Manage Content', '#content', 'editor'), ('Audit Logs', '#audit', 'admin'), ('User Management', '#users', 'admin'), ('System Settings', '#settings', 'admin');");
            $pdo->exec("INSERT INTO users (username, email, role, status) VALUES ('admin_bhavy', 'bhavy.admin@charusat.edu', 'admin', 'Active'), ('editor_patel', 'editor.patel@charusat.edu', 'editor', 'Active'), ('student_rahul', 'rahul.student@charusat.edu', 'user', 'Active'), ('guest_user', 'guest@charusat.edu', 'user', 'Inactive');");
            $pdo->exec("INSERT INTO students (name, email, department) VALUES ('Bhavy Gol', '25cs016@charusat.edu', 'CSE'), ('Aarav Mehta', 'aarav@charusat.edu', 'IT'), ('Diya Shah', 'diya@charusat.edu', 'CSE'), ('Rohan Joshi', 'rohan@charusat.edu', 'ECE'), ('Ananya Dave', 'ananya@charusat.edu', 'AI & ML');");
            $pdo->exec("INSERT INTO events (title, event_date, location, status) VALUES ('Tech Fest 2026', '2026-11-28', 'Main Auditorium', 'Open'), ('Web Dev Hackathon', '2026-12-05', 'CSE Lab 3', 'Open'), ('AI Conference', '2026-12-15', 'Seminar Hall A', 'Closed');");
            $pdo->exec("INSERT INTO registrations (student_name, event_title) VALUES ('Bhavy Gol', 'Web Dev Hackathon'), ('Aarav Mehta', 'Tech Fest 2026'), ('Diya Shah', 'Web Dev Hackathon'), ('Ananya Dave', 'AI Conference');");
            $pdo->exec("INSERT INTO items (name, category, price) VALUES ('Laptop Pro', 'Electronics', 1200.00), ('Wireless Mouse', 'Accessories', 25.50), ('Mechanical Keyboard', 'Accessories', 85.00);");
            $pdo->exec("INSERT INTO audit_logs (user_role, action, details, ip_address) VALUES ('admin', 'SYSTEM_INIT', 'Database schema and seed records initialized', '127.0.0.1'), ('admin', 'LOGIN', 'Admin authenticated into control panel', '127.0.0.1'), ('admin', 'ROLE_SWITCH', 'Switched view context to administrative mode', '127.0.0.1');");
        }
    } catch (Exception $fallbackErr) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
}
