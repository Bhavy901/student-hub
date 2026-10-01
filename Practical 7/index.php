<?php
/**
 * Practical 7: PHP Form Processing with Server-Side Validation & File Storage
 * Student: Bhavy Gol
 * Roll No: 25CS016
 */

session_start();

// Directory & File Paths
$dataDir = __DIR__ . '/data';
$csvFile = $dataDir . '/registrations.csv';
$jsonFile = $dataDir . '/registrations.json';

// Ensure data directory exists
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Ensure CSV file exists with header
if (!file_exists($csvFile)) {
    $header = ["ID", "Full Name", "Email", "Phone", "Department", "Semester", "Subject", "Message", "Timestamp"];
    $f = fopen($csvFile, 'w');
    if ($f !== false) {
        fputcsv($f, $header);
        fclose($f);
    }
}

// Ensure JSON file exists with empty array
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([], JSON_PRETTY_PRINT));
}

// Generate CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Helper Functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Form State Variables
$errors = [];
$successMessage = '';
$formData = [
    'fullname' => '',
    'email' => '',
    'phone' => '',
    'department' => '',
    'semester' => '',
    'subject' => '',
    'message' => '',
    'storage_format' => 'both'
];

// Handle Form Submission (POST check)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. CSRF Token Validation
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $submittedToken)) {
        $errors['csrf'] = 'Invalid CSRF token. Please refresh the page and try submitting again.';
    }

    // Retain form inputs
    $formData['fullname'] = sanitize_input($_POST['fullname'] ?? '');
    $formData['email'] = sanitize_input($_POST['email'] ?? '');
    $formData['phone'] = sanitize_input($_POST['phone'] ?? '');
    $formData['department'] = sanitize_input($_POST['department'] ?? '');
    $formData['semester'] = sanitize_input($_POST['semester'] ?? '');
    $formData['subject'] = sanitize_input($_POST['subject'] ?? '');
    $formData['message'] = sanitize_input($_POST['message'] ?? '');
    $formData['storage_format'] = sanitize_input($_POST['storage_format'] ?? 'both');

    // 2. Server-Side Validation Rules
    
    // Full Name Validation
    if (empty($formData['fullname'])) {
        $errors['fullname'] = 'Full Name is required.';
    } elseif (strlen($formData['fullname']) < 3) {
        $errors['fullname'] = 'Full Name must be at least 3 characters long.';
    } elseif (!preg_match("/^[a-zA-Z\s\.\'-]+$/", $formData['fullname'])) {
        $errors['fullname'] = 'Full Name can only contain letters, spaces, dots, and hyphens.';
    }

    // Email Validation
    if (empty($formData['email'])) {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address (e.g. name@example.com).';
    }

    // Phone Validation
    if (empty($formData['phone'])) {
        $errors['phone'] = 'Phone number is required.';
    } elseif (!preg_match("/^[0-9\-\+\s\(\)]{10,15}$/", $formData['phone'])) {
        $errors['phone'] = 'Phone number must be between 10 to 15 valid digits/characters.';
    }

    // Department Validation
    $allowedDepartments = ['Computer Engineering', 'Information Technology', 'Civil Engineering', 'Mechanical Engineering', 'Electrical Engineering'];
    if (empty($formData['department'])) {
        $errors['department'] = 'Please select a department.';
    } elseif (!in_array($formData['department'], $allowedDepartments)) {
        $errors['department'] = 'Invalid department selected.';
    }

    // Semester Validation
    $allowedSemesters = ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6', 'Semester 7', 'Semester 8'];
    if (empty($formData['semester'])) {
        $errors['semester'] = 'Please select your semester.';
    } elseif (!in_array($formData['semester'], $allowedSemesters)) {
        $errors['semester'] = 'Invalid semester selected.';
    }

    // Subject Validation
    if (empty($formData['subject'])) {
        $errors['subject'] = 'Subject line is required.';
    } elseif (strlen($formData['subject']) < 5) {
        $errors['subject'] = 'Subject must be at least 5 characters long.';
    }

    // Message Validation
    if (empty($formData['message'])) {
        $errors['message'] = 'Message content is required.';
    } elseif (strlen($formData['message']) < 10) {
        $errors['message'] = 'Message must be at least 10 characters long.';
    }

    // Storage Format Validation
    if (!in_array($formData['storage_format'], ['csv', 'json', 'both'])) {
        $formData['storage_format'] = 'both';
    }

    // 3. File Writing & Storage Handling
    if (empty($errors)) {
        $recordId = 'REG-' . strtoupper(substr(uniqid(), -5));
        $timestamp = date('Y-m-d H:i:s');

        $record = [
            'id' => $recordId,
            'fullname' => $formData['fullname'],
            'email' => $formData['email'],
            'phone' => $formData['phone'],
            'department' => $formData['department'],
            'semester' => $formData['semester'],
            'subject' => $formData['subject'],
            'message' => $formData['message'],
            'timestamp' => $timestamp,
            'csrf_valid' => true
        ];

        $storageSuccess = true;
        $savedFormats = [];

        // Save to CSV with file locking (LOCK_EX)
        if ($formData['storage_format'] === 'csv' || $formData['storage_format'] === 'both') {
            $handle = fopen($csvFile, 'a');
            if ($handle !== false) {
                if (flock($handle, LOCK_EX)) {
                    fputcsv($handle, array_values($record));
                    flock($handle, LOCK_UN);
                    $savedFormats[] = 'CSV';
                } else {
                    $storageSuccess = false;
                    $errors['storage'] = 'Could not acquire lock for CSV storage.';
                }
                fclose($handle);
            } else {
                $storageSuccess = false;
                $errors['storage'] = 'Failed to open CSV storage file.';
            }
        }

        // Save to JSON with atomic file writing / locking
        if ($storageSuccess && ($formData['storage_format'] === 'json' || $formData['storage_format'] === 'both')) {
            $handle = fopen($jsonFile, 'c+');
            if ($handle !== false) {
                if (flock($handle, LOCK_EX)) {
                    $filesize = filesize($jsonFile);
                    $existingData = [];
                    if ($filesize > 0) {
                        $content = fread($handle, $filesize);
                        $existingData = json_decode($content, true) ?: [];
                    }
                    $existingData[] = $record;
                    
                    ftruncate($handle, 0);
                    rewind($handle);
                    fwrite($handle, json_encode($existingData, JSON_PRETTY_PRINT));
                    flock($handle, LOCK_UN);
                    $savedFormats[] = 'JSON';
                } else {
                    $storageSuccess = false;
                    $errors['storage'] = 'Could not acquire lock for JSON storage.';
                }
                fclose($handle);
            } else {
                $storageSuccess = false;
                $errors['storage'] = 'Failed to open JSON storage file.';
            }
        }

        if ($storageSuccess) {
            $formatText = implode(' & ', $savedFormats);
            $successMessage = "Registration submitted successfully! Record ID: <strong>{$recordId}</strong> saved to <strong>{$formatText}</strong> format.";
            // Reset form fields after successful submission
            $formData = [
                'fullname' => '',
                'email' => '',
                'phone' => '',
                'department' => '',
                'semester' => '',
                'subject' => '',
                'message' => '',
                'storage_format' => 'both'
            ];
            // Regenerate CSRF Token after successful form submission
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $csrf_token = $_SESSION['csrf_token'];
        }
    }
}

// Fetch stored records for display (Intermediate Extension)
$jsonRecords = [];
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $jsonRecords = json_decode($jsonContent, true) ?: [];
}

$csvRecords = [];
if (file_exists($csvFile)) {
    if (($handle = fopen($csvFile, 'r')) !== false) {
        $csvHeader = fgetcsv($handle, 0, ',', '"', '\\');
        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            if (count($row) === count($csvHeader)) {
                $csvRecords[] = array_combine($csvHeader, $row);
            }
        }
        fclose($handle);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Practical 7: Form Processing & Storage</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header>
    <div class="header-bar">
      <div class="logo">🎓 StudentHub</div>
      <div class="header-right">
        <button class="nav-toggle" id="nav-toggle" aria-label="Toggle navigation">☰ Menu</button>
        <button id="theme-toggle" title="Toggle dark mode">🌙 Dark Mode</button>
      </div>
    </div>
    <nav>
      <ul id="primary-nav">
        <li><a href="index.php" class="active">Submit Registration</a></li>
        <li><a href="records.php">View Stored Records</a></li>
        <li><a href="data/registrations.csv" target="_blank">Download CSV</a></li>
        <li><a href="data/registrations.json" target="_blank">View JSON API</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <!-- Student Header Banner -->
    <section class="student-info-card">
      <div class="info-badge">Practical 7 Solution</div>
      <h2>PHP Form Processing with Server-Side Validation & File Storage</h2>
      <p><strong>Student Name:</strong> Bhavy Gol | <strong>Roll No:</strong> 25CS016</p>
    </section>

    <!-- Success / Global Error Alert -->
    <?php if (!empty($successMessage)): ?>
      <div class="alert alert-success" id="success-alert">
        <span class="alert-icon">✓</span>
        <div>
          <h4>Success!</h4>
          <p><?php echo $successMessage; ?></p>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors['csrf']) || !empty($errors['storage'])): ?>
      <div class="alert alert-danger">
        <span class="alert-icon">⚠️</span>
        <div>
          <h4>Submission Error</h4>
          <p><?php echo htmlspecialchars($errors['csrf'] ?? $errors['storage']); ?></p>
        </div>
      </div>
    <?php endif; ?>

    <!-- Main Registration Form -->
    <section class="form-section">
      <div class="section-title">
        <h3>Contact & Registration Form</h3>
        <span class="badge badge-primary">POST Method + CSRF Protected</span>
      </div>
      <p class="form-desc">Fill out the form below. All inputs will be validated and sanitized on the server side before stored securely into CSV/JSON format.</p>

      <form action="index.php" method="POST" id="registration-form" novalidate>
        <!-- CSRF Hidden Field -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <div class="form-grid">
          <!-- Full Name -->
          <div class="form-group <?php echo isset($errors['fullname']) ? 'has-error' : ''; ?>">
            <label for="fullname">Full Name <span class="required">*</span></label>
            <input type="text" id="fullname" name="fullname" class="form-control" placeholder="e.g. Bhavy Gol" value="<?php echo htmlspecialchars($formData['fullname']); ?>">
            <?php if (isset($errors['fullname'])): ?>
              <span class="error-text"><?php echo $errors['fullname']; ?></span>
            <?php endif; ?>
          </div>

          <!-- Email Address -->
          <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : ''; ?>">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-control" placeholder="e.g. bhavy.gol@example.com" value="<?php echo htmlspecialchars($formData['email']); ?>">
            <?php if (isset($errors['email'])): ?>
              <span class="error-text"><?php echo $errors['email']; ?></span>
            <?php endif; ?>
          </div>

          <!-- Phone Number -->
          <div class="form-group <?php echo isset($errors['phone']) ? 'has-error' : ''; ?>">
            <label for="phone">Phone Number <span class="required">*</span></label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. +91 9876543210" value="<?php echo htmlspecialchars($formData['phone']); ?>">
            <?php if (isset($errors['phone'])): ?>
              <span class="error-text"><?php echo $errors['phone']; ?></span>
            <?php endif; ?>
          </div>

          <!-- Department -->
          <div class="form-group <?php echo isset($errors['department']) ? 'has-error' : ''; ?>">
            <label for="department">Department <span class="required">*</span></label>
            <select id="department" name="department" class="form-control">
              <option value="">-- Select Department --</option>
              <?php 
                $depts = ['Computer Engineering', 'Information Technology', 'Civil Engineering', 'Mechanical Engineering', 'Electrical Engineering'];
                foreach ($depts as $dept) {
                  $selected = ($formData['department'] === $dept) ? 'selected' : '';
                  echo "<option value=\"{$dept}\" {$selected}>{$dept}</option>";
                }
              ?>
            </select>
            <?php if (isset($errors['department'])): ?>
              <span class="error-text"><?php echo $errors['department']; ?></span>
            <?php endif; ?>
          </div>

          <!-- Semester -->
          <div class="form-group <?php echo isset($errors['semester']) ? 'has-error' : ''; ?>">
            <label for="semester">Semester <span class="required">*</span></label>
            <select id="semester" name="semester" class="form-control">
              <option value="">-- Select Semester --</option>
              <?php 
                for ($i = 1; $i <= 8; $i++) {
                  $sem = "Semester {$i}";
                  $selected = ($formData['semester'] === $sem) ? 'selected' : '';
                  echo "<option value=\"{$sem}\" {$selected}>{$sem}</option>";
                }
              ?>
            </select>
            <?php if (isset($errors['semester'])): ?>
              <span class="error-text"><?php echo $errors['semester']; ?></span>
            <?php endif; ?>
          </div>

          <!-- Storage Format Selection -->
          <div class="form-group">
            <label for="storage_format">Storage Destination <span class="required">*</span></label>
            <select id="storage_format" name="storage_format" class="form-control">
              <option value="both" <?php echo ($formData['storage_format'] === 'both') ? 'selected' : ''; ?>>Both CSV & JSON (Recommended)</option>
              <option value="csv" <?php echo ($formData['storage_format'] === 'csv') ? 'selected' : ''; ?>>CSV File Only</option>
              <option value="json" <?php echo ($formData['storage_format'] === 'json') ? 'selected' : ''; ?>>JSON File Only</option>
            </select>
          </div>
        </div>

        <!-- Subject Line -->
        <div class="form-group <?php echo isset($errors['subject']) ? 'has-error' : ''; ?>">
          <label for="subject">Subject / Inquiry Title <span class="required">*</span></label>
          <input type="text" id="subject" name="subject" class="form-control" placeholder="e.g. Inquiry regarding Web Development Workshop" value="<?php echo htmlspecialchars($formData['subject']); ?>">
          <?php if (isset($errors['subject'])): ?>
            <span class="error-text"><?php echo $errors['subject']; ?></span>
          <?php endif; ?>
        </div>

        <!-- Message -->
        <div class="form-group <?php echo isset($errors['message']) ? 'has-error' : ''; ?>">
          <label for="message">Detailed Message / Description <span class="required">*</span></label>
          <textarea id="message" name="message" rows="4" class="form-control" placeholder="Enter your detailed query or registration details..."><?php echo htmlspecialchars($formData['message']); ?></textarea>
          <?php if (isset($errors['message'])): ?>
            <span class="error-text"><?php echo $errors['message']; ?></span>
          <?php endif; ?>
        </div>

        <!-- Form Action Buttons -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary" id="submit-btn">🚀 Submit Registration</button>
          <button type="reset" class="btn btn-secondary" onclick="window.location.href='index.php';">🔄 Clear Form</button>
        </div>
      </form>
    </section>

    <!-- Quick Preview Section of Recent Registrations -->
    <section class="records-summary-section">
      <div class="section-header">
        <h3>Recent Stored Submissions</h3>
        <a href="records.php" class="btn-link">View All Records (<?php echo count($jsonRecords); ?>) →</a>
      </div>
      
      <?php if (empty($jsonRecords)): ?>
        <p class="empty-msg">No submissions recorded yet. Use the form above to submit your first entry!</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Subject</th>
                <th>Timestamp</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $latestRecords = array_slice(array_reverse($jsonRecords), 0, 5);
                foreach ($latestRecords as $row): 
              ?>
                <tr>
                  <td><span class="badge-id"><?php echo htmlspecialchars($row['id']); ?></span></td>
                  <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['email']); ?></td>
                  <td><?php echo htmlspecialchars($row['department']); ?></td>
                  <td><?php echo htmlspecialchars($row['subject']); ?></td>
                  <td class="text-muted"><?php echo htmlspecialchars($row['timestamp']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <script src="js/main.js"></script>
</body>
</html>
