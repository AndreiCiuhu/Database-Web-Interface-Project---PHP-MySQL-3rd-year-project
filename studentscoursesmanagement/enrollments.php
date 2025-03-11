<?php
// enrollments.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';


$conn = new mysqli($db_host, $db_user, $db_password, $db_name);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_enrollments'])) {
    $selected_enrollments = $_POST['selected_enrollments'] ?? [];

    if (!empty($selected_enrollments)) {

        $stmt = $conn->prepare("DELETE FROM enrollments WHERE studentid = ? AND courseid = ?");

        foreach ($selected_enrollments as $enrollment) {

            list($studentid, $courseid) = explode('-', $enrollment);


            $stmt->bind_param("ii", $studentid, $courseid);

            if (!$stmt->execute()) {

                echo "Error deleting enrollment for Student ID $studentid and Course ID $courseid: " . $stmt->error;
            }
        }


        $stmt->close();


        header("Location: enrollments.php");
        exit();
    } else {

        echo "No enrollments selected for deletion.";
    }
}


$sql = "SELECT e.studentid, e.courseid, s.firstname, s.lastname, c.coursename, e.enrollmentdate, e.grade 
        FROM enrollments e
        INNER JOIN students s ON e.studentid = s.studentid
        INNER JOIN courses c ON e.courseid = c.courseid
        ORDER BY e.enrollmentdate DESC";
$result = $conn->query($sql);


$enrollments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $enrollments[] = $row;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments</title>
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
        <h1 class="mb-4 text-center">Enrollments</h1>
        <div class="mb-3">
            <a href="add_enrollment.php" class="btn btn-success">Add Enrollment</a>
        </div>
        <form method="POST" action="enrollments.php">
        <table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th></th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Enrollment Date</th>
            <th>Grade</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($enrollments)): ?>
            <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected_enrollments[]" value="<?php echo $enrollment['studentid'] . '-' . $enrollment['courseid']; ?>">
                    </td>
                    <td><?php echo htmlspecialchars($enrollment['studentid']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['firstname'] . ' ' . $enrollment['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['courseid']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['coursename']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['enrollmentdate']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['grade']); ?></td>
                    <td>
                        <a href="edit_enrollment.php?studentid=<?php echo $enrollment['studentid']; ?>&courseid=<?php echo $enrollment['courseid']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No enrollments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
            <button type="submit" name="delete_enrollments" class="btn btn-danger">Delete Selected</button>
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