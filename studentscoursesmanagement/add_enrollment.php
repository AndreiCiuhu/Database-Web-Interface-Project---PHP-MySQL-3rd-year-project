<?php
// add_enrollment.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$students = [];
$courses = [];

$student_result = $conn->query("SELECT studentid, firstname, lastname FROM students");
if ($student_result && $student_result->num_rows > 0) {
    while ($row = $student_result->fetch_assoc()) {
        $students[] = $row;
    }
}

$course_result = $conn->query("SELECT courseid, coursename FROM courses");
if ($course_result && $course_result->num_rows > 0) {
    while ($row = $course_result->fetch_assoc()) {
        $courses[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentid = $_POST['studentid'];
    $courseid = $_POST['courseid'];
    $enrollmentdate = $_POST['enrollmentdate'];
    $grade = $_POST['grade'];

  
    $errors = [];

    if (empty($studentid) || empty($courseid)) {
        $errors[] = "Student and Course must be selected.";
    }

    if (!is_null($grade) && (!filter_var($grade, FILTER_VALIDATE_FLOAT, ["options" => ["min_range" => 1, "max_range" => 10]]))) {
        $errors[] = "Grade must be a number between 1 and 10.";
    }

    
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE studentid = ? AND courseid = ?");
    $stmt->bind_param("ii", $studentid, $courseid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "This student is already enrolled in the selected course.";
    }
    $stmt->close();

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO enrollments (studentid, courseid, enrollmentdate, grade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisd", $studentid, $courseid, $enrollmentdate, $grade);

        if ($stmt->execute()) {
            header("Location: enrollments.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Enrollment</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="enrollments.php">Edu Track</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <h1 class="mb-4 text-center">Add New Enrollment</h1>
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="POST" action="add_enrollment.php">
                <div class="mb-3">
                    <label for="studentid" class="form-label">Student</label>
                    <select class="form-select" id="studentid" name="studentid" required>
                        <option value="">Select Student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['studentid']; ?>">
                                <?php echo htmlspecialchars($student['studentid'].' - ' .$student['firstname'] . ' ' . $student['lastname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="courseid" class="form-label">Course</label>
                    <select class="form-select" id="courseid" name="courseid" required>
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['courseid']; ?>">
                                <?php echo htmlspecialchars($course['courseid']. ' - ' .$course['coursename']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="enrollmentdate" class="form-label">Enrollment Date</label>
                    <input type="date" class="form-control" id="enrollmentdate" name="enrollmentdate" required>
                </div>
                <div class="mb-3">
                    <label for="grade" class="form-label">Grade</label>
                    <input type="number" step="0.1" class="form-control" id="grade" name="grade" required>
                </div>
                <button type="submit" class="btn btn-success mb-5">Add Enrollment</button>
                <a href="enrollments.php" class="btn btn-secondary mb-5">Cancel</a>
            </form>
        </div>
    </div>

    <footer class="bg-dark text-white py-3 mt-5 fixed-bottom">
        <div class="container text-center">
            <p>&copy; 2025 Edu Track. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
