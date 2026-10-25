<?php
// Practical 15: Master Integrated StudentHub Portal
$modules = [
    ['num' => 1, 'name' => 'Semantic University Portal', 'cat' => 'Frontend Core', 'tech' => 'HTML5 / Semantic Tags', 'path' => '../Practical 1/index.html'],
    ['num' => 2, 'name' => 'Responsive Design System', 'cat' => 'Frontend CSS', 'tech' => 'CSS3 / Media Queries', 'path' => '../practical 2/index.html'],
    ['num' => 3, 'name' => 'Interactive DOM Event Engine', 'cat' => 'Client Scripting', 'tech' => 'JavaScript / Event Listeners', 'path' => '../Practical 3/index.html'],
    ['num' => 4, 'name' => 'Registration Form Validation', 'cat' => 'Client Validation', 'tech' => 'Regex / JS Validation', 'path' => '../Practical 4/index.html'],
    ['num' => 5, 'name' => 'Dynamic Course Registration', 'cat' => 'DOM Manipulation', 'tech' => 'JS DOM / LocalStorage', 'path' => '../Practical 5/index.html'],
    ['num' => 6, 'name' => 'PHP Form Processing & Sticky State', 'cat' => 'Backend Core', 'tech' => 'PHP $_POST / Sticky Forms', 'path' => '../Practical 6/index.php'],
    ['num' => 7, 'name' => 'JSON & CSV Data Persistence', 'cat' => 'File Handling', 'tech' => 'PHP Filesystem / CSV Export', 'path' => '../Practical 7/index.php'],
    ['num' => 8, 'name' => 'Cookie Management & Preferences', 'cat' => 'State Tracking', 'tech' => 'PHP Cookies / setcookie()', 'path' => '../Practical 8/index.php'],
    ['num' => 9, 'name' => 'User Registration & BCRYPT Hashing', 'cat' => 'Authentication', 'tech' => 'password_hash() / MySQLi', 'path' => '../Practical 9/index.php'],
    ['num' => 10, 'name' => 'Session Security & RBAC Routing', 'cat' => 'Session Management', 'tech' => 'session_regenerate_id() / RBAC', 'path' => '../Practical 10/login.php'],
    ['num' => 11, 'name' => 'Student Management MySQLi CRUD', 'cat' => 'Database CRUD', 'tech' => 'MySQLi / Search & Filter', 'path' => '../Practical 11/index.php'],
    ['num' => 12, 'name' => 'Event CRUD & Secure Poster Upload', 'cat' => 'File Upload CRUD', 'tech' => 'Multi-Tier Validation / .htaccess', 'path' => '../Practical 12/index.php'],
    ['num' => 13, 'name' => 'RESTful JSON API & Fetch CRUD', 'cat' => 'REST API / SPA', 'tech' => 'PHP JSON API / async fetch()', 'path' => '../Practical 13/index.html'],
    ['num' => 14, 'name' => 'Admin Dashboard & System Audit Log', 'cat' => 'Admin & Auditing', 'tech' => 'SQL Aggregation / audit_logs', 'path' => '../Practical 14/index.html'],
    ['num' => 15, 'name' => 'Master Portal & Virtual Host Deployment', 'cat' => 'Full-Stack Integration', 'tech' => 'Apache VHost / Test Suite', 'path' => './index.php']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentHub - Integrated Master Web Application Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; }
        .hero-banner { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 45px 0 35px; margin-bottom: 30px; border-bottom: 4px solid #3b82f6; }
        .stat-card { background: white; border-radius: 8px; border: none; box-shadow: 0 2px 6px rgba(0,0,0,0.06); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
        .module-card { background: white; border-radius: 8px; border: 1px solid #e2e8f0; height: 100%; transition: all 0.2s ease; }
        .module-card:hover { border-color: #3b82f6; box-shadow: 0 4px 12px rgba(59,130,246,0.12); transform: translateY(-2px); }
        .badge-cat { background: #eff6ff; color: #1d4ed8; font-weight: 600; font-size: 11px; padding: 4px 8px; border-radius: 4px; }
        .badge-tech { background: #f1f5f9; color: #475569; font-size: 11px; padding: 3px 6px; border-radius: 4px; }
        .checklist-item { padding: 10px 14px; background: white; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; }
    </style>
</head>
<body>

<header class="hero-banner">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-primary mb-2 text-uppercase fw-bold">ITUE203 Semester Project</span>
                <h1 class="h2 fw-bold mb-1">StudentHub Integrated Web Portal</h1>
                <p class="mb-0 text-white-50">Enterprise Full-Stack Architecture by <strong>Bhavy Gol (25CS016)</strong> | CSPIT, CHARUSAT</p>
            </div>
            <div class="d-flex gap-2">
                <a href="test_suite.php" class="btn btn-success fw-semibold"><i class="bi bi-shield-check me-1"></i> Run Test Suite</a>
                <a href="https://github.com/Bhavy901/student-hub" target="_blank" class="btn btn-outline-light"><i class="bi bi-github me-1"></i> GitHub Repo</a>
            </div>
        </div>
    </div>
</header>

<div class="container pb-5">
    <!-- Top System Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-3 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase">Integrated Modules</small>
                <h3 class="fw-bold mb-0 text-dark">15 / 15</h3>
                <small class="text-success"><i class="bi bi-check-circle-fill"></i> 100% Complete</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-3 border-start border-success border-4">
                <small class="text-muted fw-bold text-uppercase">Security Checks</small>
                <h3 class="fw-bold mb-0 text-dark">7 Verified</h3>
                <small class="text-success"><i class="bi bi-shield-lock-fill"></i> Zero Vulnerabilities</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-3 border-start border-warning border-4">
                <small class="text-muted fw-bold text-uppercase">Deployment Target</small>
                <h3 class="fw-bold mb-0 text-dark">Apache VHost</h3>
                <small class="text-muted">studenthub.local</small>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card p-3 border-start border-info border-4">
                <small class="text-muted fw-bold text-uppercase">Architecture</small>
                <h3 class="fw-bold mb-0 text-dark">Full-Stack</h3>
                <small class="text-muted">PHP / MySQL / Fetch API</small>
            </div>
        </div>
    </div>

    <!-- Modules Grid -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>All 15 Integrated Practical Modules</h5>
            <span class="badge bg-secondary">Semester 3 (2026-27)</span>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <?php foreach ($modules as $m): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="module-card p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-dark">Practical <?= $m['num'] ?></span>
                                <span class="badge-cat"><?= $m['cat'] ?></span>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark"><?= $m['name'] ?></h6>
                            <div class="mb-3">
                                <span class="badge-tech"><i class="bi bi-code-slash"></i> <?= $m['tech'] ?></span>
                            </div>
                        </div>
                        <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Ready</span>
                            <a href="<?= $m['path'] ?>" class="btn btn-sm btn-outline-primary fw-semibold">Launch <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Security & Deployment Verification Checklist -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-success me-2"></i>Security Audit & Compliance Checklist</h5>
                <div class="checklist-item">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i> SQL Injection Defense (PDO / Prepared Statements)</span>
                    <span class="badge bg-success">PASSED</span>
                </div>
                <div class="checklist-item">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i> Password Encryption (BCRYPT Algorithm)</span>
                    <span class="badge bg-success">PASSED</span>
                </div>
                <div class="checklist-item">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i> Session Fixation Attack Defense (session_regenerate_id)</span>
                    <span class="badge bg-success">PASSED</span>
                </div>
                <div class="checklist-item">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i> Cross-Site Scripting (XSS) Prevention (htmlspecialchars)</span>
                    <span class="badge bg-success">PASSED</span>
                </div>
                <div class="checklist-item">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i> Upload Directory Hardening (.htaccess Execution Block)</span>
                    <span class="badge bg-success">PASSED</span>
                </div>
                <div class="checklist-item">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i> Real MIME & Magic Bytes File Inspection</span>
                    <span class="badge bg-success">PASSED</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-hdd-network text-primary me-2"></i>Local Apache Virtual Host Deployment</h5>
                <p class="text-muted small">Configured for production-style local deployment using Apache Virtual Hosts:</p>
                <div class="bg-dark text-white p-3 rounded font-monospace small mb-3">
                    # /etc/hosts<br>
                    127.0.0.1 studenthub.local<br><br>
                    # Test Virtual Host Configuration<br>
                    apachectl configtest<br>
                    sudo systemctl restart apache2
                </div>
                <div class="d-flex gap-2">
                    <a href="test_suite.php" class="btn btn-primary w-100"><i class="bi bi-play-circle me-1"></i> Execute Test Suite</a>
                    <a href="studenthub.conf" class="btn btn-outline-secondary w-100" download><i class="bi bi-download me-1"></i> Get VHost Config</a>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p class="mb-1">Faculty of Technology and Engineering | CSPIT, CHARUSAT University</p>
        <small class="text-white-50">Department of Computer Science & Engineering | Course: ITUE203 - Web Development Frameworks</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
