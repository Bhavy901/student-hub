# Practical 14: Admin Dashboard with Role-Based Access Control, Dynamic Menu, and Audit Log

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  
**Semester:** 3rd  
**Department:** Department of Computer Science & Engineering  
**Institute:** Chandubhai S. Patel Institute of Technology (CSPIT)  
**Date:** 23/10/2026  

---

## 🎯 Problem Statement
Develop an admin dashboard that displays user statistics, student count, event count and latest registrations. Restrict access to admin users only and show dynamic menu options based on role.

---

## 📌 Objective
The objective of this practical is to build an enterprise-style administrative monitoring panel equipped with Role-Based Access Control (RBAC), real-time database aggregations, and an immutable audit log trail:
- **Administrative Monitoring & SQL Aggregation:** Calculate and display real-time dashboard statistics (`COUNT(*)` for registered users, students, events, and event registrations).
- **Dynamic Role-Based Navigation (RBAC):** Dynamically serve navigation menus from the database according to the active role hierarchy (`user` $\rightarrow$ `editor` $\rightarrow$ `admin`).
- **Comprehensive Audit Logging:** Automatically record critical administrative actions (`CREATE_ITEM`, `UPDATE_ITEM`, `DELETE_ITEM`, `ROLE_SWITCH`, `SYSTEM_INIT`) into an `audit_logs` table with role, action, details, client IP, and timestamps.
- **Asynchronous AJAX / Fetch CRUD Integration:** Enable inventory management without page refreshes, automatically triggering audit log refreshes in real-time.
- **Access Control & Least Privilege:** Enforce role restrictions ensuring sensitive administrative sections and controls remain guarded.

---

## 🔑 Key Architecture, Security & CRUD Analysis

| Feature / Mechanism | Implementation Strategy | Functional / Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **SQL Aggregations** | `SELECT COUNT(*) AS total FROM users/students/events/registrations` | High-efficiency aggregate metric cards for admin situational awareness | ✅ Implemented |
| **Audit Logging Trail** | Dedicated `audit_logs` table populated on all mutating actions | Enforces non-repudiation, traceability, and historical audit compliance | ✅ Implemented |
| **Role-Based Access Control** | Dynamic menu filtering via `min_role IN ($placeholders)` | Restricts sensitive views and controls according to user privilege level | ✅ Implemented |
| **Asynchronous Fetch CRUD** | Modern `async/await` fetch calls with `application/json` payloads | Interactive single-page experience without browser window refreshes | ✅ Implemented |
| **Prepared SQL Statements** | PDO parameterized queries (`$pdo->prepare()`, `execute()`) | Complete immunity against SQL Injection across metrics and CRUD | ✅ Implemented |
| **Active/Inactive Status Toggle** | `UPDATE users SET status = CASE WHEN ...` | Instant user account state modification without record deletion | ✅ Implemented |
| **Real-Time Client IP Capture** | `$_SERVER['REMOTE_ADDR']` logged during audit events | Captures origin IP addresses for intrusion detection and forensic analysis | ✅ Implemented |
| **Normalized Seed Data** | Relational data connecting users, students, events, and registrations | Provides realistic multi-table environment for evaluation and demonstration | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 14/
├── schema.sql         # Database DDL with Users, Students, Events, Registrations & Audit Logs
├── db.php             # PDO Database Connection Script with JSON Error Handling
├── api.php            # RESTful API: Stats Aggregation, RBAC Menu, CRUD & Audit Logging
├── index.html         # Rich Admin Monitoring Dashboard UI with Metric Cards & Audit Log Table
└── README.md          # Comprehensive Practical Documentation & Reference Manual
```

---

## 🔑 Key Components & Code Implementation

### 1. Database Schema & Audit Log Table (`schema.sql`)
```sql
CREATE DATABASE IF NOT EXISTS dashboard_db;
USE dashboard_db;

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_role VARCHAR(20) NOT NULL DEFAULT 'admin',
    action VARCHAR(50) NOT NULL,
    details TEXT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. Real-Time SQL Aggregations & Audit Recorder (`api.php`)
```php
function record_audit($pdo, $role, $action, $details) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $stmt = $pdo->prepare("INSERT INTO audit_logs (user_role, action, details, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$role, $action, $details, $ip]);
}

case 'stats':
    $userCount    = $pdo->query("SELECT COUNT(*) AS total FROM users")->fetch()['total'] ?? 0;
    $studentCount = $pdo->query("SELECT COUNT(*) AS total FROM students")->fetch()['total'] ?? 0;
    $eventCount   = $pdo->query("SELECT COUNT(*) AS total FROM events")->fetch()['total'] ?? 0;
    $regCount     = $pdo->query("SELECT COUNT(*) AS total FROM registrations")->fetch()['total'] ?? 0;

    echo json_encode(['status' => 'success', 'data' => [
        'users' => (int)$userCount, 'students' => (int)$studentCount,
        'events' => (int)$eventCount, 'registrations' => (int)$regCount
    ]]);
    break;
```

---

## 🧪 Verified Test Cases & Output Scenarios

1. **A. Test Case 1 – Dashboard Overview & Aggregate Stat Cards:**
   - Navigating to `index.html` calculates dynamic totals for Users (4), Students (5), Events (3), and Registrations (4).
2. **B. Test Case 2 – Dynamic RBAC Menu Switching:**
   - Switching dropdown from `Admin` to `Editor` or `User` immediately recalculates accessible routes via `api.php?action=get_menu&role=...`.
3. **C. Test Case 3 – Audit Log Generation on Create Item:**
   - Adding a new inventory item via AJAX automatically inserts an entry in `audit_logs` with action `CREATE_ITEM`.
4. **D. Test Case 4 – Audit Log Generation on Update / Delete:**
   - Editing an item's price or deleting an item creates timestamped audit trail records showing modified values.
5. **E. Test Case 5 – RESTful JSON API Responses:**
   - Network tab verifies clean JSON output for `stats`, `read`, and `get_audit_logs`.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
```bash
mysql -u root -p < "Practical 14/schema.sql"
```

### 2. Launch Local Server
```bash
php -S localhost:8000 -t "Practical 14"
```

### 3. Open in Browser
- Navigate to: [http://localhost:8000/index.html](http://localhost:8000/index.html)

---

## 🎓 Learning Outcomes
- **Dashboard Metric Engineering:** Understood how to execute aggregated SQL queries (`COUNT(*)`) across multiple tables to feed administrative summary dashboards.
- **Audit Logging Architecture:** Designed an immutable system audit trail to record every administrative mutation, capturing timestamps, actor roles, detailed event messages, and client IP addresses.
- **Hierarchical Role-Based Access Control:** Configured dynamic navigation systems where accessible application modules depend strictly on verified authorization levels.
- **Full-Stack Event Synchronization:** Connected frontend asynchronous UI events with backend audit logging pipelines for unified application state tracking.

---

## 🔗 Repository Reference
- **GitHub Repository:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
