<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Edu Track</a>
        </div>
    </nav>

    <header class="bg-light py-4">
        <div class="container text-center">
            <h1 class="display-5">Students Courses Management</h1>
        </div>
    </header>

    <div class="container mt-4">
        <h2 class="mb-3 text-center">Choose table:</h2>
        <div class="d-flex flex-column align-items-center">
            <a href="students.php" class="btn btn-primary btn-lg mb-2 w-30" style="width: 30%;">Students</a>
            <a href="enrollments.php" class="btn btn-secondary btn-lg mb-2 w-30" style="width: 30%;">Enrollments</a>
            <a href="courses.php" class="btn btn-success btn-lg w-30" style="width: 30%;">Courses</a>
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
