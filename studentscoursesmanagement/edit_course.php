<?php
// edit_course.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$courseid = $_GET['id'] ?? null;
if (!$courseid) {
    header("Location: courses.php");
    exit();
}


$sql = "SELECT * FROM courses WHERE courseid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseid);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header("Location: courses.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coursename = $_POST['coursename'];
    $credits = $_POST['credits'];
    $coursetype = $_POST['coursetype'];

    $errors = [];

    if (!preg_match('/^[a-zA-Z0-9ăâîșțĂÂÎȘȚ]+(?:[- ][a-zA-Z0-9ăâîșțĂÂÎȘȚ]+)*$/', $coursename)) {
        $errors[] = "Course name can only contain letters and numbers.";
    }

    if (!filter_var($credits, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
        $errors[] = "Credits must be a positive integer less than 10.";
    }

    if (!in_array($coursetype, ['mandatory', 'optional'])) {
        $errors[] = "Course type must be 'mandatory' or 'optional'.";
    }

    if (empty($errors)) {
        $sql = "UPDATE courses SET coursename = ?, credits = ?, coursetype = ? WHERE courseid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisi", $coursename, $credits, $coursetype, $courseid);

        if ($stmt->execute()) {
            header("Location: courses.php");
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
    <title>Edit Course</title>
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
            <a class="navbar-brand" href="courses.php">Edu Track</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <h1 class="mb-4 text-center">Edit Course</h1>
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
            <form method="POST" action="edit_course.php?id=<?php echo $courseid; ?>">
                <div class="mb-3">
                    <label for="coursename" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="coursename" name="coursename" value="<?php echo htmlspecialchars($course['coursename']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" class="form-control" id="credits" name="credits" value="<?php echo htmlspecialchars($course['credits']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="coursetype" class="form-label">Course Type</label>
                    <select class="form-select" id="coursetype" name="coursetype" required>
                        <option value="mandatory" <?php echo ($course['coursetype'] === 'mandatory') ? 'selected' : ''; ?>>Mandatory</option>
                        <option value="optional" <?php echo ($course['coursetype'] === 'optional') ? 'selected' : ''; ?>>Optional</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mb-5">Update Course</button>
                <a href="courses.php" class="btn btn-secondary mb-5">Cancel</a>
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
