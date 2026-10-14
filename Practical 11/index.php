<?php
// ====================================================================
// Practical 11: Main Management Dashboard (CRUD, Search, Filter & CSV Export)
// Student Name: Bhavy Gol
// Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
// Academic Year: 2026-27 | Semester: 3rd
// Institute: Chandubhai S. Patel Institute of Technology (CSPIT)
// Department: Department of Computer Science & Engineering
// Date: 14/10/2026
// ====================================================================

include 'db.php';

// Handle Student Creation (CREATE)
if (isset($_POST['add_student'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $course = isset($_POST['course']) ? trim($_POST['course']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

    if (empty($name) || empty($email) || empty($course) || empty($gender)) {
        header("Location: index.php?error=All fields are required.");
        exit();
    }

    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $course = mysqli_real_escape_string($conn, $course);
    $gender = mysqli_real_escape_string($conn, $gender);

    $insert_sql = "INSERT INTO students (name, email, course, gender, status) 
                   VALUES ('$name', '$email', '$course', '$gender', 'Active')";

    if (mysqli_query($conn, $insert_sql)) {
        header("Location: index.php?msg=Student record added successfully!");
    } else {
        header("Location: index.php?error=" . urlencode(mysqli_error($conn)));
    }
    exit();
}

// Handle Student Deletion (DELETE / SOFT DELETE)
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    
    // Check if hard delete is explicitly requested; default is safe Soft Delete
    if (isset($_GET['action']) && $_GET['action'] === 'hard') {
        $delete_sql = "DELETE FROM students WHERE id = $id";
        $success_msg = "Student record permanently deleted!";
    } else {
        $delete_sql = "UPDATE students SET status = 'Inactive' WHERE id = $id";
        $success_msg = "Student record soft-deleted (status set to Inactive) successfully!";
    }

    if (mysqli_query($conn, $delete_sql)) {
        header("Location: index.php?msg=" . urlencode($success_msg));
    } else {
        header("Location: index.php?error=" . urlencode(mysqli_error($conn)));
    }
    exit();
}

// Build Search and Filter Query (READ)
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$course_filter = isset($_GET['course_filter']) ? mysqli_real_escape_string($conn, trim($_GET['course_filter'])) : '';

$conditions = [];
if (!empty($search)) {
    $conditions[] = "(name LIKE '%$search%' OR email LIKE '%$search%')";
}
if (!empty($course_filter)) {
    $conditions[] = "course = '$course_filter'";
}

$query_sql = "SELECT * FROM students";
if (count($conditions) > 0) {
    $query_sql .= " WHERE " . implode(' AND ', $conditions);
}
$query_sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $query_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
    <!-- Header Banner -->
    <div class="header-box">
        <h2>Student Management System</h2>
        <p>PHP &amp; MySQL Procedural MySQLi Implementation (CRUD with Search &amp; Filter)</p>
    </div>

    <!-- Alert Notifications -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">✔ <?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">✖ <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- Add New Student Card -->
    <div class="card">
        <div class="card-header">
            <h3>Add New Student</h3>
        </div>
        <form method="POST" action="index.php">
            <div class="form-grid" style="margin-bottom: 16px;">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="name" placeholder="e.g. John Doe" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" placeholder="e.g. john@example.com" required>
                </div>
                <div class="form-group">
                    <label>Course:</label>
                    <select name="course" required>
                        <option value="">Select Course</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Web Development">Web Development</option>
                        <option value="Data Science">Data Science</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gender:</label>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="add_student" class="btn btn-primary">+ Add Student</button>
        </form>
    </div>

    <!-- Student Records Card -->
    <div class="card">
        <div class="card-header">
            <h3>Student Records</h3>
            <a href="export_csv.php" class="btn btn-export">📥 Export to CSV</a>
        </div>

        <!-- Search and Filter Form -->
        <form method="GET" action="index.php" class="form-inline">
            <input type="text" name="search" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search); ?>">
            <select name="course_filter">
                <option value="">All Courses</option>
                <option value="Computer Science" <?php if ($course_filter === 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                <option value="Web Development" <?php if ($course_filter === 'Web Development') echo 'selected'; ?>>Web Development</option>
                <option value="Data Science" <?php if ($course_filter === 'Data Science') echo 'selected'; ?>>Data Science</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="index.php" class="btn btn-reset">Reset</a>
        </form>

        <!-- Table Displaying Results -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Active'): ?>
                                <span class="badge badge-active">Active</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="action-link edit-link">Edit</a> |
                            <a href="index.php?delete=<?php echo $row['id']; ?>" class="action-link delete-link" onclick="return confirm('Delete this record? (Soft Delete)');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--muted); padding: 24px;">No records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
