<?php
/**
 * Practical 7: View Stored CSV and JSON Records
 * Student: Bhavy Gol
 * Roll No: 25CS016
 */

$dataDir = __DIR__ . '/data';
$csvFile = $dataDir . '/registrations.csv';
$jsonFile = $dataDir . '/registrations.json';

// Fetch JSON Records
$jsonRecords = [];
if (file_exists($jsonFile)) {
    $jsonContent = file_get_contents($jsonFile);
    $jsonRecords = json_decode($jsonContent, true) ?: [];
}

// Fetch CSV Records
$csvRecords = [];
$csvHeader = [];
if (file_exists($csvFile)) {
    if (($handle = fopen($csvFile, 'r')) !== false) {
        $header = fgetcsv($handle, 0, ',', '"', '\\');
        if ($header) {
            $csvHeader = $header;
            while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                if (count($row) === count($header)) {
                    $csvRecords[] = array_combine($header, $row);
                }
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
  <title>StudentHub - Practical 7: Stored Records</title>
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
        <li><a href="index.php">Submit Registration</a></li>
        <li><a href="records.php" class="active">View Stored Records</a></li>
        <li><a href="data/registrations.csv" target="_blank">Download CSV</a></li>
        <li><a href="data/registrations.json" target="_blank">View JSON API</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <!-- Student Header Card -->
    <section class="student-info-card">
      <div class="info-badge">Intermediate Extension</div>
      <h2>Stored Submissions Viewer (CSV & JSON Storage)</h2>
      <p><strong>Student Name:</strong> Bhavy Gol | <strong>Roll No:</strong> 25CS016</p>
    </section>

    <!-- Records Filter & Tab Controls -->
    <section class="filter-section">
      <div class="filter-bar">
        <div class="search-box">
          <input type="text" id="record-search" placeholder="🔍 Search records by name, email, department, subject..." class="form-control">
        </div>
        <div class="tab-buttons">
          <button class="tab-btn active" id="tab-json" onclick="switchTab('json')">JSON Dataset (<?php echo count($jsonRecords); ?>)</button>
          <button class="tab-btn" id="tab-csv" onclick="switchTab('csv')">CSV File Records (<?php echo count($csvRecords); ?>)</button>
        </div>
      </div>
    </section>

    <!-- JSON Storage Tab -->
    <section id="json-view" class="records-view">
      <div class="section-title">
        <h3>JSON Storage Dataset (`registrations.json`)</h3>
        <span class="badge badge-success">JSON Data Format</span>
      </div>
      
      <?php if (empty($jsonRecords)): ?>
        <p class="empty-msg">No records found in JSON file storage.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table" id="json-table">
            <thead>
              <tr>
                <th>Record ID</th>
                <th>Full Name</th>
                <th>Contact Email</th>
                <th>Phone Number</th>
                <th>Department & Sem</th>
                <th>Subject & Message</th>
                <th>Timestamp</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_reverse($jsonRecords) as $row): ?>
                <tr class="record-row">
                  <td><span class="badge-id"><?php echo htmlspecialchars($row['id']); ?></span></td>
                  <td><strong><?php echo htmlspecialchars($row['fullname']); ?></strong></td>
                  <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                  <td><?php echo htmlspecialchars($row['phone']); ?></td>
                  <td>
                    <div><?php echo htmlspecialchars($row['department']); ?></div>
                    <small class="text-muted"><?php echo htmlspecialchars($row['semester']); ?></small>
                  </td>
                  <td>
                    <strong><?php echo htmlspecialchars($row['subject']); ?></strong>
                    <p class="table-msg"><?php echo htmlspecialchars($row['message']); ?></p>
                  </td>
                  <td class="text-muted small"><?php echo htmlspecialchars($row['timestamp']); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <!-- CSV Storage Tab -->
    <section id="csv-view" class="records-view" style="display: none;">
      <div class="section-title">
        <h3>CSV File Storage (`registrations.csv`)</h3>
        <span class="badge badge-warning">Comma-Separated Values</span>
      </div>

      <?php if (empty($csvRecords)): ?>
        <p class="empty-msg">No records found in CSV file storage.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table" id="csv-table">
            <thead>
              <tr>
                <?php foreach ($csvHeader as $col): ?>
                  <th><?php echo htmlspecialchars($col); ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_reverse($csvRecords) as $row): ?>
                <tr class="record-row">
                  <?php foreach ($row as $val): ?>
                    <td><?php echo htmlspecialchars($val); ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <script src="js/main.js"></script>
  <script>
    function switchTab(type) {
      document.getElementById('json-view').style.display = type === 'json' ? 'block' : 'none';
      document.getElementById('csv-view').style.display = type === 'csv' ? 'block' : 'none';
      document.getElementById('tab-json').classList.toggle('active', type === 'json');
      document.getElementById('tab-csv').classList.toggle('active', type === 'csv');
    }

    // Client-side instant live search filter
    document.getElementById('record-search').addEventListener('input', function(e) {
      const term = e.target.value.toLowerCase();
      const activeTable = document.querySelector('.records-view:not([style*="display: none"]) .data-table');
      if (!activeTable) return;

      const rows = activeTable.querySelectorAll('tbody tr.record-row');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
