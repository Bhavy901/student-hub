# Practical 15: Final Integration, Testing, Documentation, GitHub Submission, and Local Deployment

**Student Name:** Bhavy Gol  
**Student ID:** 25CS016  
**Course Code:** ITUE203 - WEB DEVELOPMENT FRAMEWORKS  
**Academic Year:** 2026-27  
**Semester:** 3rd  
**Department:** Department of Computer Science & Engineering  
**Institute:** Chandubhai S. Patel Institute of Technology (CSPIT)  
**Date:** 25/10/2026  

---

## 🎯 Problem Statement
Integrate all modules into a single deployable StudentHub portal. Verify navigation, responsiveness, authentication, database operations, security checks, AJAX functionality, and documentation. Deploy locally using XAMPP/Apache virtual host and submit GitHub repository.

---

## 📌 Objective
The objective of this final practical is to synthesize all 15 semester laboratory components into an enterprise-grade, cohesive web portal:
- **Comprehensive Full-Stack Integration:** Connect all 15 practical modules (Core HTML5/CSS3, DOM Events, Form Validation, Persistent Storage, Authentication, Sessions, CRUD, File Uploads, REST JSON APIs, RBAC Admin Panels) into a unified StudentHub portal.
- **Automated Integration Testing:** Build an automated test suite (`test_suite.php`) validating environment extensions, BCRYPT encryption, session regeneration, JSON APIs, file upload whitelisting, and XSS sanitization.
- **Enterprise Security Compliance:** Complete an exhaustive security audit guaranteeing protection against SQL injection, cross-site scripting (XSS), session fixation, directory traversal, and remote code execution (RCE).
- **Production-Style Local Deployment:** Document and configure Apache Virtual Hosts (`studenthub.conf`) mapping `studenthub.local` to the integrated workspace.
- **GitHub Version Control & Delivery:** Maintain a pristine Git repository structure with modular documentation and version-controlled commits.

---

## 🔑 Key Architecture, Security & CRUD Analysis

| Feature / Module | Integration Strategy | Functional / Security Benefit | Status |
| :--- | :--- | :--- | :---: |
| **Unified Portal Routing** | Single entry point (`index.php`) linking all 15 practicals | Centralized user experience with seamless cross-module navigation | ✅ Implemented |
| **Automated Test Runner** | Standalone verification script (`test_suite.php`) | Instant programmatic verification of system security & functions | ✅ Implemented |
| **Apache Virtual Host** | `studenthub.conf` defining `ServerName studenthub.local` | Production-grade deployment architecture with clean domain mapping | ✅ Implemented |
| **Master Database DDL** | Consolidated schema (`studenthub_db` & `modules_registry`) | Centralized data model documenting all integrated components | ✅ Implemented |
| **Authentication Defense** | BCRYPT algorithmic hashing + `session_regenerate_id(true)` | Complete elimination of plain-text credentials and fixation attacks | ✅ Implemented |
| **Input & Upload Security** | Whitelist extension check + MIME inspection + `.htaccess` | Multi-layered defense against malicious payload execution and RCE | ✅ Implemented |
| **Database Hygiene** | Prepared statements across MySQLi and PDO workflows | Complete protection against SQL Injection vulnerabilities | ✅ Implemented |
| **Responsive Design System** | Bootstrap 5 grid layout with mobile breakpoints | Seamless visual experience across desktop, tablet, and mobile views | ✅ Implemented |

---

## 📁 File Structure

```text
Practical 15/
├── index.php          # StudentHub Master Portal Interface with Module Matrix & Metric Cards
├── test_suite.php     # Automated Integration, Security & Unit Test Suite Runner
├── studenthub.conf    # Apache Virtual Host Deployment Configuration File
├── schema.sql         # Consolidated Database Schema & Modules Registry DDL
└── README.md          # Comprehensive Practical Documentation & Final Submission Manual
```

---

## 🔑 Key Components & Code Implementation

### 1. Automated Integration & Security Test Suite (`test_suite.php`)
```php
// Test 2: BCRYPT Password Verification
$pwd = 'SecureStudentHub2026!';
$hash = password_hash($pwd, PASSWORD_BCRYPT);
$verified = password_verify($pwd, $hash);

// Test 3: Session ID Regeneration
session_start();
$old_sid = session_id();
session_regenerate_id(true);
$new_sid = session_id();

// Test 5: File Upload Whitelisting
$allowed = ['jpg', 'jpeg', 'png', 'webp'];
$ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
$is_allowed = in_array($ext, $allowed, true);
```

### 2. Apache Virtual Host Configuration (`studenthub.conf`)
```apache
<VirtualHost *:80>
    ServerName studenthub.local
    ServerAlias www.studenthub.local
    DocumentRoot "/Users/bhavygol/Downloads/sp/Practical 15"
    <Directory "/Users/bhavygol/Downloads/sp/Practical 15">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

## 🧪 Verified Test Cases & Output Scenarios

1. **A. Test Case 1 – StudentHub Master Portal Overview:**
   - Navigating to `index.php` displays top metrics, quick action controls, and module directory.
2. **B. Test Case 2 – Complete Module Matrix (Practicals 1–15):**
   - Renders 15 modular cards with category badges, technology tags, and direct launch URLs.
3. **C. Test Case 3 – Automated Test Suite Execution:**
   - Launching `test_suite.php` verifies 7 automated unit and security tests, all returning green `PASS` badges.
4. **D. Test Case 4 – Cross-Module Navigation:**
   - Clicking through each practical link navigates seamlessly to the respective module without broken paths.
5. **E. Test Case 5 – Local Virtual Host Deployment:**
   - Resolves `http://studenthub.local` via Apache vhost configuration.
6. **F. Test Case 6 – GitHub Repository Verification:**
   - Validates that all files and README manuals are committed and synchronized on GitHub.

---

## ⚙️ How to Setup & Run

### 1. Database Setup
```bash
mysql -u root -p < "Practical 15/schema.sql"
```

### 2. Start Local PHP Development Server
```bash
php -S localhost:8000 -t "Practical 15"
```

### 3. Access in Browser
- **Portal Master:** [http://localhost:8000/index.php](http://localhost:8000/index.php)
- **Automated Test Runner:** [http://localhost:8000/test_suite.php](http://localhost:8000/test_suite.php)

---

## 🎓 Learning Outcomes
- **Enterprise Web System Synthesis:** Successfully synthesized 15 distinct web development modules into a unified, coherent portal.
- **Automated Security & Unit Verification:** Designed programmatic test suites to validate encryption, session lifecycle, and payload integrity.
- **Production Virtual Host Deployment:** Configured Apache Virtual Hosts with domain aliases, directory permissions, and logging.
- **Full-Cycle Documentation & Source Control:** Maintained comprehensive academic and technical documentation synchronized with GitHub.

---

## 🔗 Repository Reference
- **GitHub Repository:** [https://github.com/Bhavy901/student-hub](https://github.com/Bhavy901/student-hub)
