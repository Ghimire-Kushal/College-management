
<?php

session_start();

// Check login & role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-5 text-center">

    <!-- Welcome -->
    <h2 class="text-success">
        Welcome <?= htmlspecialchars($user['name']); ?> 👨‍🏫
    </h2>
    <p class="text-muted">Teacher Dashboard</p>

    <!-- Cards -->
    <div class="row mt-4 justify-content-center">

        <!-- Students -->
        <div class="col-md-4 mb-3">
            <div class="card p-4 shadow h-100">
                <h5>Students</h5>
                <p>View student list</p>
                <a href="students.php" class="btn btn-primary">Open</a>
            </div>
        </div>

        <!-- Attendance -->
        <div class="col-md-4 mb-3">
            <div class="card p-4 shadow h-100">
                <h5>Take Attendance</h5>
                <p>Mark daily attendance</p>
                <a href="attendance.php" class="btn btn-success">Open</a>
            </div>
        </div>

        <!-- Records -->
        <div class="col-md-4 mb-3">
            <div class="card p-4 shadow h-100">
                <h5>Records</h5>
                <p>View attendance records</p>
                <a href="view_attendance.php" class="btn btn-warning">Open</a>
            </div>
        </div>

        <!-- Assignments -->
        <div class="col-md-4 mb-3">
            <div class="card p-4 shadow h-100">
                <h5>Assignments</h5>
                <p>View student submissions</p>
                <a href="assignments.php" class="btn btn-primary">Open</a>
            </div>
       

    </div>

</div>

</body>
</html>