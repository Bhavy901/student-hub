<?php
// ====================================================================
// Practical 11: Update Student Details Page (UPDATE)
// Student Name: Bhavy Gol
// Student ID: 25CS016
// Course: ITUE203 - WEB DEVELOPMENT FRAMEWORKS
// Academic Year: 2026-27 | Semester: 3rd
// Institute: Chandubhai S. Patel Institute of Technology (CSPIT)
// Department: Department of Computer Science & Engineering
// Date: 14/10/2026
// ====================================================================

include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch existing record
$select_sql = "SELECT * FROM students WHERE id = $id";
$fetch_result = mysqli_query($conn, $select_sql);
$student = mysqli_fetch_assoc($fetch_result);

if (!$student) {
    die("Student record not found.");
}

$error = '';

// Handle Form Update (UPDATE)
if (isset($_POST['update_student'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $course = mysqli_real_escape_string($conn, trim($_POST['course']));
    $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, trim($_POST['status'])) : 'Active';

    $update_sql = "UPDATE students 
                   SET name = '$name', email = '$email', course = '$course', gender = '$gender', status = '$status' 
                   WHERE id = $id";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: index.php?msg=" . urlencode("Student details updated successfully!"));
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Practical 11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper" style="max-width: 600px;">
    <div class="card">
        <div class="card-header">
            <h3>Edit Student Details</h3>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <div class="form-group" style="margin-bottom: 14px;">
                <label>Full Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label>Email Address:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label>Course:</label>
                <select name="course" required>
                    <option value="Computer Science" <?php if ($student['course'] === 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                    <option value="Web Development" <?php if ($student['course'] === 'Web Development') echo 'selected'; ?>>Web Development</option>
                    <option value="Data Science" <?php if ($student['course'] === 'Data Science') echo 'selected'; ?>>Data Science</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label>Gender:</label>
                <select name="gender" required>
                    <option value="Male" <?php if ($student['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($student['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label>Status:</label>
                <select name="status" required>
                    <option value="Active" <?php if ($student['status'] === 'Active') echo 'selected'; ?>>Active</option>
                    <option value="Inactive" <?php if ($student['status'] === 'Inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" name="update_student" class="btn btn-primary">Update Details</button>
                <a href="index.php" class="btn btn-reset" style="text-align: center; line-height: 20px;">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
