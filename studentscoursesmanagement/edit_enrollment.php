<?php
// edit_enrollment.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentid = $_GET['studentid'] ?? null;
$courseid = $_GET['courseid'] ?? null;

if (!$studentid || !$courseid) {
    header("Location: enrollments.php");
    exit();
}


$sql = "SELECT e.studentid, e.courseid, s.firstname, s.lastname, c.coursename, e.enrollmentdate, e.grade 
        FROM enrollments e
        INNER JOIN students s ON e.studentid = s.studentid
        INNER JOIN courses c ON e.courseid = c.courseid
        WHERE e.studentid = ? AND e.courseid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $studentid, $courseid);
$stmt->execute();
$result = $stmt->get_result();
$enrollment = $result->fetch_assoc();

if (!$enrollment) {
    header("Location: enrollments.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollmentdate = $_POST['enrollmentdate'];
    $grade = $_POST['grade'];

 
    $errors = [];

    if (!filter_var($grade, FILTER_VALIDATE_FLOAT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
        $errors[] = "Grade must be a number between 1 and 10.";
    }

    if (empty($errors)) {
        $sql = "UPDATE enrollments SET enrollmentdate = ?, grade = ? WHERE studentid = ? AND courseid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdii", $enrollmentdate, $grade, $studentid, $courseid);

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
    <title>Edit Enrollment</title>
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
        <h1 class="mb-4 text-center">Edit Enrollment</h1>
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
            <form method="POST" action="edit_enrollment.php?studentid=<?php echo $studentid; ?>&courseid=<?php echo $courseid; ?>">
                <div class="mb-3">
                    <label for="student" class="form-label">Student</label>
                    <input type="text" class="form-control" id="student" value="<?php echo htmlspecialchars($enrollment['studentid']. ' - ' .$enrollment['firstname'] . ' ' . $enrollment['lastname']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="course" class="form-label">Course</label>
                    <input type="text" class="form-control" id="course" value="<?php echo htmlspecialchars($enrollment["courseid"]. ' - ' .$enrollment['coursename']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="enrollmentdate" class="form-label">Enrollment Date</label>
                    <input type="date" class="form-control" id="enrollmentdate" name="enrollmentdate" value="<?php echo htmlspecialchars($enrollment['enrollmentdate']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="grade" class="form-label">Grade</label>
                    <input type="number" step="0.1" class="form-control" id="grade" name="grade" value="<?php echo htmlspecialchars($enrollment['grade']); ?>" required>
                </div>
                <button type="submit" class="btn btn-success mb-5">Update Enrollment</button>
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
