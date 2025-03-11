<?php
// edit_student.php

$db_host = 'localhost';
$db_user = 'Xg/i2u932tn$*3u$xz$i!D3P&8{K7';
$db_password = 'zP3JNU9kI3i}?z(9&';
$db_name = 'edutrack';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentid = $_GET['id'] ?? null;
if (!$studentid) {
    header("Location: students.php");
    exit();
}

// Fetch la datele studentului curent
$sql = "SELECT * FROM students WHERE studentid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentid);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header("Location: students.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $currentyear = $_POST['currentyear'];
    $series = $_POST['series'];
    $groupnr = $_POST['groupnr'];

    $errors = [];

    if (!preg_match('/^[a-zA-Z]+(?:[- ][a-zA-Z]+)*$/', $firstname)) {
        $errors[] = "First name can only contain letters.";
    }

    if (!preg_match('/^[a-zA-Z]+(?:[- ][a-zA-Z]+)*$/', $lastname)) {
        $errors[] = "Last name can only contain letters.";
    }

    if (!preg_match('/^[A-Z]$/', $series)) {
        $errors[] = "Series must be a single uppercase letter.";
    }

    if (!filter_var($groupnr, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        $errors[] = "Group number must be a positive integer.";
    }

    if (!filter_var($currentyear, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 4]])) {
        $errors[] = "Current year must be an integer between 1 and 4.";
    }
    // daca nu au fost identificate erori - update si redirectionare catre afisare studenti
    if (empty($errors)) {
        $sql = "UPDATE students SET firstname = ?, lastname = ?, currentyear = ?, series = ?, groupnr = ? WHERE studentid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $firstname, $lastname, $currentyear, $series, $groupnr, $studentid);
        //(ssissi-tipurile de date pe care le iau atributele in ordine)
        if ($stmt->execute()) {
            header("Location: students.php");
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
    <title>Edit Student</title>
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
            <a class="navbar-brand" href="students.php">Edu Track</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <h1 class="mb-4 text-center">Edit Student</h1>
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
            <form method="POST" action="edit_student.php?id=<?php echo $studentid; ?>">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="currentyear" class="form-label">Current Year</label>
                    <input type="number" class="form-control" id="currentyear" name="currentyear" value="<?php echo htmlspecialchars($student['currentyear']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="series" class="form-label">Series</label>
                    <input type="text" class="form-control" id="series" name="series" value="<?php echo htmlspecialchars($student['series']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="groupnr" class="form-label">Group</label>
                    <input type="text" class="form-control" id="groupnr" name="groupnr" value="<?php echo htmlspecialchars($student['groupnr']); ?>" required>
                </div>
                <button type="submit" class="btn btn-success mb-5">Update Student</button>
                <a href="students.php" class="btn btn-secondary mb-5">Cancel</a>
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
