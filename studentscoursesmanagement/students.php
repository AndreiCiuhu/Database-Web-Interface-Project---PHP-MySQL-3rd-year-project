<?php
// students.php


$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';

// Conexiunea la baza de date
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Verifica conexiunea
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_students'])) {
    $selected_students = $_POST['selected_students'] ?? [];
    if (!empty($selected_students)) {
        foreach ($selected_students as $student_id) {
            // Pregateste statement-ul pentru fiecare student
            $stmt = $conn->prepare("DELETE FROM students WHERE studentid = ?");
            $stmt->bind_param('i', $student_id);

            // Executa instructiunea si verifica daca sunt erori
            if (!$stmt->execute()) {
                echo "Error deleting student with ID $student_id: " . $stmt->error;
            }

            $stmt->close(); // Inchide conexiunea
        }

        // Inapoi catre pagina de vizualizare, dupa stergere
        header("Location: students.php");
        exit();
    }
}
// Query-ul ce da fetch la toti studentii
$sql = "SELECT studentid, firstname, lastname, currentyear, series, groupnr FROM students";
$result = $conn->query($sql);

// Adaugarea randurilor de studenti in lista
$students = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Inchide conexiunea la baza de date
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
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
        <h1 class="mb-4 text-center">Students</h1>
        <div class="mb-3">
            <a href="add_student.php" class="btn btn-success">Add Student</a>
        </div>
        <form method="POST" action="students.php">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Current Year</th>
                        <th>Series</th>
                        <th>Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_students[]" value="<?php echo $student['studentid']; ?>"></td>
                                <td><?php echo htmlspecialchars($student['studentid']); ?></td>
                                <td><?php echo htmlspecialchars($student['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($student['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($student['currentyear']); ?></td>
                                <td><?php echo htmlspecialchars($student['series']); ?></td>
                                <td><?php echo htmlspecialchars($student['groupnr']); ?></td>
                                <td>
                                    <a href="edit_student.php?id=<?php echo $student['studentid']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No students found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" name="delete_students" class="btn btn-danger">Delete Selected</button>
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
