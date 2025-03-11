<?php
// courses.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';


$conn = new mysqli($db_host, $db_user, $db_password, $db_name);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_courses'])) {
    $selected_courses = $_POST['selected_courses'] ?? [];
    if (!empty($selected_courses)) {
        foreach ($selected_courses as $course_id) {

            $stmt = $conn->prepare("DELETE FROM courses WHERE courseid = ?");
            $stmt->bind_param('i', $course_id);


            if (!$stmt->execute()) {
                echo "Error deleting course with ID $course_id: " . $stmt->error;
            }

            $stmt->close(); 
        }


        header("Location: courses.php");
        exit();
    } 
}


$sql = "SELECT courseid, coursename, credits, coursetype FROM courses";
$result = $conn->query($sql);


$courses = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table td, table th {
            padding: 0.5rem !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/studentscoursesmanagement/index.php">Edu Track</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Courses</h1>
        <div class="mb-3">
            <a href="add_course.php" class="btn btn-success">Add Course</a>
        </div>
        <form method="POST" action="courses.php">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Course Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_courses[]" value="<?php echo $course['courseid']; ?>"></td>
                                <td><?php echo htmlspecialchars($course['courseid']); ?></td>
                                <td><?php echo htmlspecialchars($course['coursename']); ?></td>
                                <td><?php echo htmlspecialchars($course['credits']); ?></td>
                                <td><?php echo htmlspecialchars($course['coursetype']); ?></td>
                                <td>
                                    <a href="edit_course.php?id=<?php echo $course['courseid']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No courses found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" name="delete_courses" class="btn btn-danger">Delete Selected</button>
        </form>
    </div>

    <footer class="bg-dark text-white py-3 mt-5 fixed-bottom">
        <div class="container text-center">
            <p>&copy; 2025 Edu Track. All rights reserved.</p>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
