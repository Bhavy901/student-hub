# Practical 13: PHP JSON API and AJAX/Fetch-Based CRUD without Page Reload

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  
**Semester:** 3rd  
**Department:** Department of Computer Science & Engineering  
**Institute:** Chandubhai S. Patel Institute of Technology (CSPIT)  
**Date:** 21/10/2026  

---

## 🎯 Problem Statement
Create simple PHP JSON API endpoints for events or students and consume them using Fetch API. Perform list, add, update, and delete operations asynchronously without full page reload.

---

## 📌 Objective
The objective of this practical is to implement an asynchronous single-page web architecture using a PHP JSON REST API and modern JavaScript `fetch()`:
- **RESTful PHP JSON Endpoints:** Build clean backend API endpoints responding with standardized JSON payloads (`status`, `message`, `data`) and proper HTTP status codes.
- **Asynchronous CRUD (Zero Page Reloads):** Execute complete Create, Read, Update, and Delete operations using JavaScript `fetch()` and `async/await` without browser refreshes.
- **Dynamic Role-Based Access Control (RBAC):** Dynamically serve navigation menus from the database based on the active user role (`user`, `editor`, `admin`).
- **Backend JSON Payload Processing:** Safely parse incoming JSON request bodies using `php://input` and `json_decode()`.
- **Prepared Database Transactions:** Protect all database operations against SQL injection via PDO prepared statements.
- **Interactive UI State Management:** Implement real-time DOM manipulation, inline edit form population, cancel handlers, and confirmation dialogs.

---

## 🔑 Key Architecture, Security & CRUD Analysis

| Feature / Mechanism | Implementation Strategy | Functional / Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **RESTful JSON API** | `header("Content-Type: application/json; charset=UTF-8")` + `json_encode()` | Standardized machine-readable data exchange for SPAs | ✅ Implemented |
| **Zero Page Reloads** | `event.preventDefault()` with asynchronous `fetch()` API calls | Seamless user experience without full browser page refreshes | ✅ Implemented |
| **JSON Payload Ingestion** | `json_decode(file_get_contents('php://input'), true)` | Directly processes structured raw JSON POST payloads | ✅ Implemented |
| **Dynamic RBAC Menu** | SQL `min_role IN (...)` parameterized with role hierarchy | Renders authorized navigation links matching role privileges | ✅ Implemented |
| **Prepared SQL Statements** | Parameterized PDO statements (`$pdo->prepare()`, `execute()`) | Complete immunity against SQL Injection attacks across all queries | ✅ Implemented |
| **CRUD Read Workflow** | `GET api.php?action=read` fetching items descending by ID | Real-time tabular rendering directly updated in the DOM | ✅ Implemented |
| **CRUD Create Workflow** | `POST api.php?action=create` with JSON payload | Asynchronously inserts new item and triggers instant UI refresh | ✅ Implemented |
| **CRUD Update Workflow** | `POST api.php?action=update` with item ID & new values | Populates form into edit state and updates row without refresh | ✅ Implemented |
| **CRUD Delete Workflow** | `POST api.php?action=delete` with item ID & browser confirm | Deletes record from database with immediate DOM cleanup | ✅ Implemented |
| **Error Handling & Feedback** | HTTP 500 error handling + descriptive JSON status feedback | Structured client-side alerts on invalid inputs or server errors | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 13/
├── schema.sql         # MySQL Database DDL & Seed Data (dashboard_db, menu_items, items)
├── db.php             # PDO Database Connection Script with JSON Error Handling
├── api.php            # RESTful PHP JSON API (RBAC Menu & Asynchronous CRUD Endpoints)
├── index.html         # Single-Page Application (SPA) UI with Fetch API & Role Switcher
└── README.md          # Comprehensive Practical Documentation & Reference Manual
```

---

## 🔑 Key Components & Code Implementation

### 1. Database Schema (`schema.sql`)
Defines the `dashboard_db` database, the RBAC `menu_items` table, and the inventory `items` table:
```sql
CREATE DATABASE IF NOT EXISTS dashboard_db;
USE dashboard_db;

CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    link VARCHAR(100) NOT NULL,
    min_role ENUM('user', 'editor', 'admin') DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);
```

### 2. Database Connection with JSON Error Handling (`db.php`)
Connects via PDO and returns a structured JSON error response if the connection fails:
```php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
```

### 3. RESTful JSON API (`api.php`)
Processes GET and POST requests, executes prepared statements, and returns standardized JSON:
```php
header("Content-Type: application/json; charset=UTF-8");
$action = $_GET['action'] ?? '';
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($action) {
    case 'get_menu':
        $role = $_GET['role'] ?? 'user';
        $rolesAllowed = ['user'];
        if ($role === 'editor') $rolesAllowed = ['user', 'editor'];
        elseif ($role === 'admin') $rolesAllowed = ['user', 'editor', 'admin'];
        
        $placeholders = implode(',', array_fill(0, count($rolesAllowed), '?'));
        $stmt = $pdo->prepare("SELECT title, link FROM menu_items WHERE min_role IN ($placeholders)");
        $stmt->execute($rolesAllowed);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'read':
        $stmt = $pdo->query("SELECT * FROM items ORDER BY id DESC");
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'create':
        $stmt = $pdo->prepare("INSERT INTO items (name, category, price) VALUES (?, ?, ?)");
        $stmt->execute([$input['name'], $input['category'], $input['price']]);
        echo json_encode(['status' => 'success', 'message' => 'Item created successfully']);
        break;
}
```

### 4. Single-Page Application Frontend (`index.html`)
Interacts with the backend asynchronously using vanilla JavaScript `fetch()`:
```javascript
// Asynchronous Create or Update without page reload
async function saveItem(event) {
    event.preventDefault();
    const id = document.getElementById('itemId').value;
    const payload = {
        id,
        name: document.getElementById('itemName').value,
        category: document.getElementById('itemCategory').value,
        price: document.getElementById('itemPrice').value
    };
    const action = id ? 'update' : 'create';
    const res = await fetch(`api.php?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.status === 'success') {
        resetForm();
        loadItems();
    } else {
        alert(result.message);
    }
}
```

---

## 🧪 Verified Test Cases & Output Scenarios

1. **A. Test Case 1 – Initial Dashboard & Asynchronous Inventory Read:**
   - On page load, `loadItems()` sends `GET api.php?action=read` and populates the table dynamically without page reload.
2. **B. Test Case 2 – Role-Based Dynamic Navigation Menu:**
   - Switching roles (`user` $\rightarrow$ `editor` $\rightarrow$ `admin`) calls `api.php?action=get_menu&role=...`.
   - Admin view displays all 5 links; Editor displays 3 links; User displays 2 links.
3. **C. Test Case 3 – Asynchronous Item Creation (Zero Reload):**
   - Submitting the form fires `POST api.php?action=create` with JSON payload.
   - The new item appears immediately at the top of the table; the form resets cleanly.
4. **D. Test Case 4 – Inline Form Edit & Asynchronous Update:**
   - Clicking `Edit` populates the item data into the form and switches button to `Update Item`.
   - Submitting sends `POST api.php?action=update`, updating the table row dynamically.
5. **E. Test Case 5 – Asynchronous Item Deletion:**
   - Clicking `Delete` prompts a confirmation dialog.
   - On confirmation, sends `POST api.php?action=delete` with `{ id }` and removes the row from the DOM.
6. **F. Test Case 6 – Browser Network Tab Verification:**
   - Browser DevTools Network tab confirms HTTP 200 responses, `application/json` Content-Type, and clean request/response JSON payloads.
7. **G. Test Case 7 – Input Validation & Error Handling:**
   - Missing fields return `{ "status": "error", "message": "Missing required fields" }` and alert the user without crashing.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
```bash
mysql -u root -p < "Practical 13/schema.sql"
```

### 2. Start Built-in PHP Development Server
```bash
php -S localhost:8000 -t "Practical 13"
```

### 3. Open in Browser
- Navigate to: [http://localhost:8000/index.html](http://localhost:8000/index.html)

---

## 🎓 Learning Outcomes
- **JSON API Architecture:** Understood how to build RESTful PHP endpoints returning structured JSON responses for Single-Page Applications.
- **Asynchronous Programming with Fetch API:** Mastered JavaScript `async/await` and `fetch()` to handle background HTTP requests and update the DOM without full page reloads.
- **Raw JSON Ingestion:** Learned to consume and parse raw JSON HTTP POST bodies via `php://input` using `json_decode()`.
- **Dynamic Role-Based Access Control (RBAC):** Built database-driven dynamic navigation menus that adapt dynamically based on user role authorization.
- **Prepared Statements in PDO:** Enforced secure SQL prepared statements across all asynchronous CRUD workflows.

---

## 🔗 Repository Reference
- **GitHub Repository:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
