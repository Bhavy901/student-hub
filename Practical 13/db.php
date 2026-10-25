<?php
// Practical 13: Database Connection using PDO with JSON Error Handling
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
    // If MySQL server is unreachable locally, seamlessly fallback to SQLite for local testing/demo
    try {
        $sqlitePath = __DIR__ . '/dashboard.sqlite';
        $isNew = !file_exists($sqlitePath);
        $pdo = new PDO("sqlite:" . $sqlitePath, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        if ($isNew) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS menu_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                link TEXT NOT NULL,
                min_role TEXT DEFAULT 'user'
            );");
            $pdo->exec("CREATE TABLE IF NOT EXISTS items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                category TEXT NOT NULL,
                price REAL NOT NULL
            );");
            $pdo->exec("INSERT INTO menu_items (title, link, min_role) VALUES
                ('Dashboard', '#dashboard', 'user'),
                ('View Inventory', '#inventory', 'user'),
                ('Manage Content', '#content', 'editor'),
                ('User Management', '#users', 'admin'),
                ('System Settings', '#settings', 'admin');");
            $pdo->exec("INSERT INTO items (name, category, price) VALUES
                ('Laptop Pro', 'Electronics', 1200.00),
                ('Wireless Mouse', 'Accessories', 25.50),
                ('Mechanical Keyboard', 'Accessories', 85.00);");
        }
    } catch (Exception $fallbackError) {
        http_response_code(500);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage()
        ]);
        exit;
    }
}
