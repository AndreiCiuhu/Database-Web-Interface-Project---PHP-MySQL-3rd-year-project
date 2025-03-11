<?php
// add_course.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';

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
        
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        
        $stmt = $conn->prepare("INSERT INTO courses (coursename, credits, coursetype) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $coursename, $credits, $coursetype);

        if ($stmt->execute()) {
            header("Location: courses.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
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
        <h1 class="mb-4 text-center">Add New Course</h1>
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
            <form method="POST" action="add_course.php">
                <div class="mb-3">
                    <label for="coursename" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="coursename" name="coursename" required>
                </div>
                <div class="mb-3">
                    <label for="credits" class="form-label">Credits</label>
                    <input type="number" class="form-control" id="credits" name="credits" required>
                </div>
                <div class="mb-3">
                    <label for="coursetype" class="form-label">Course Type</label>
                    <select class="form-select" id="coursetype" name="coursetype" required>
                        <option value="">Select Type</option>
                        <option value="mandatory">Mandatory</option>
                        <option value="optional">Optional</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mb-5">Add Course</button>
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
