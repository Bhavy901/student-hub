<?php
// Practical 14: Admin Dashboard RESTful JSON API with RBAC, Aggregates & Audit Logging
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db.php';

$action = $_GET['action'] ?? '';
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

// Helper function to record audit log entries
function record_audit($pdo, $role, $action, $details) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_role, action, details, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$role, $action, $details, $ip]);
    } catch (Exception $e) {
        // Silently continue if audit log insert fails
    }
}

switch ($action) {
    // -------------------------------------------------------------
    // 1. DYNAMIC NAVIGATION MENU (RBAC)
    // -------------------------------------------------------------
    case 'get_menu':
        $role = $_GET['role'] ?? 'user';
        $rolesAllowed = ['user'];
        if ($role === 'editor') {
            $rolesAllowed = ['user', 'editor'];
        } elseif ($role === 'admin') {
            $rolesAllowed = ['user', 'editor', 'admin'];
        }
        $placeholders = implode(',', array_fill(0, count($rolesAllowed), '?'));
        $stmt = $pdo->prepare("SELECT title, link FROM menu_items WHERE min_role IN ($placeholders)");
        $stmt->execute($rolesAllowed);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    // -------------------------------------------------------------
    // 2. DASHBOARD METRICS / AGGREGATE COUNTS
    // -------------------------------------------------------------
    case 'stats':
        $userCount = $pdo->query("SELECT COUNT(*) AS total FROM users")->fetch()['total'] ?? 0;
        $studentCount = $pdo->query("SELECT COUNT(*) AS total FROM students")->fetch()['total'] ?? 0;
        $eventCount = $pdo->query("SELECT COUNT(*) AS total FROM events")->fetch()['total'] ?? 0;
        $regCount = $pdo->query("SELECT COUNT(*) AS total FROM registrations")->fetch()['total'] ?? 0;

        echo json_encode([
            'status' => 'success',
            'data' => [
                'users'         => (int)$userCount,
                'students'      => (int)$studentCount,
                'events'        => (int)$eventCount,
                'registrations' => (int)$regCount,
            ]
        ]);
        break;

    // -------------------------------------------------------------
    // 3. AUDIT LOGS RETRIEVAL
    // -------------------------------------------------------------
    case 'get_audit_logs':
        $stmt = $pdo->query("SELECT * FROM audit_logs ORDER BY id DESC LIMIT 15");
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    // -------------------------------------------------------------
    // 4. CRUD: READ ITEMS
    // -------------------------------------------------------------
    case 'read':
        $stmt = $pdo->query("SELECT * FROM items ORDER BY id DESC");
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    // -------------------------------------------------------------
    // 5. CRUD: CREATE ITEM (WITH AUDIT LOG)
    // -------------------------------------------------------------
    case 'create':
        if (!empty($input['name']) && !empty($input['category']) && isset($input['price'])) {
            $stmt = $pdo->prepare("INSERT INTO items (name, category, price) VALUES (?, ?, ?)");
            $stmt->execute([$input['name'], $input['category'], $input['price']]);
            $newId = $pdo->lastInsertId();
            record_audit($pdo, $input['role'] ?? 'admin', 'CREATE_ITEM', "Created item: {$input['name']} ($newId) in {$input['category']}");
            echo json_encode(['status' => 'success', 'message' => 'Item created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        }
        break;

    // -------------------------------------------------------------
    // 6. CRUD: UPDATE ITEM (WITH AUDIT LOG)
    // -------------------------------------------------------------
    case 'update':
        if (!empty($input['id']) && !empty($input['name']) && !empty($input['category']) && isset($input['price'])) {
            $stmt = $pdo->prepare("UPDATE items SET name = ?, category = ?, price = ? WHERE id = ?");
            $stmt->execute([$input['name'], $input['category'], $input['price'], $input['id']]);
            record_audit($pdo, $input['role'] ?? 'admin', 'UPDATE_ITEM', "Updated item #{$input['id']}: {$input['name']} to \${$input['price']}");
            echo json_encode(['status' => 'success', 'message' => 'Item updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or missing data']);
        }
        break;

    // -------------------------------------------------------------
    // 7. CRUD: DELETE ITEM (WITH AUDIT LOG)
    // -------------------------------------------------------------
    case 'delete':
        if (!empty($input['id'])) {
            $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
            $stmt->execute([$input['id']]);
            record_audit($pdo, $input['role'] ?? 'admin', 'DELETE_ITEM', "Deleted item #{$input['id']} from inventory");
            echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Item ID required']);
        }
        break;

    // -------------------------------------------------------------
    // 8. USER STATUS TOGGLE (EXTENSION)
    // -------------------------------------------------------------
    case 'toggle_user_status':
        if (!empty($input['id'])) {
            $stmt = $pdo->prepare("UPDATE users SET status = CASE WHEN status = 'Active' THEN 'Inactive' ELSE 'Active' END WHERE id = ?");
            $stmt->execute([$input['id']]);
            record_audit($pdo, 'admin', 'TOGGLE_STATUS', "Toggled status for user #{$input['id']}");
            echo json_encode(['status' => 'success', 'message' => 'User status toggled']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID required']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
