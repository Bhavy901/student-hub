<?php
// ====================================================================
// Practical 11: Dynamic CSV File Export & Streaming Script
// Student Name: Bhavy Gol
// Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
// Academic Year: 2026-27 | Semester: 3rd
// Institute: Chandubhai S. Patel Institute of Technology (CSPIT)
// Department: Department of Computer Science & Engineering
// Date: 14/10/2026
// ====================================================================

include 'db.php';

// Set HTTP headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=students_export.csv');

// Open the output stream for writing
$output = fopen('php://output', 'w');

// Output CSV column headers
fputcsv($output, ['ID', 'Name', 'Email', 'Course', 'Gender', 'Status', 'Registration Date']);

// Fetch all student records in ascending order of ID
$query = "SELECT id, name, email, course, gender, status, created_at FROM students ORDER BY id ASC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id'],
            $row['name'],
            $row['email'],
            $row['course'],
            $row['gender'],
            $row['status'],
            $row['created_at']
        ]);
    }
}

fclose($output);
exit();
?>
