<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("config/db.php");

$user = $_SESSION['user'];

/* =========================
   ADMIN DASHBOARD DATA
========================= */
if ($user['role'] == 'admin') {

    // Total Students
    $total_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];

    // Total Teachers
    $total_teachers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='teacher'")->fetch_assoc()['total'];

    // Total Assignments
    $total_assignments = $conn->query("SELECT COUNT(*) as total FROM assignments")->fetch_assoc()['total'];

    // Attendance %
    $attendance = $conn->query("
        SELECT 
        COUNT(CASE WHEN status='present' THEN 1 END) as present,
        COUNT(*) as total 
        FROM attendance
    ")->fetch_assoc();

    $attendance_percent = ($attendance['total'] > 0)
        ? round(($attendance['present'] / $attendance['total']) * 100)
        : 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="container mt-5 text-center">

    <h2 class="text-success">
        Welcome <?= htmlspecialchars($user['name']); ?> 👋
    </h2>

    <p class="text-muted">Role: <?= $user['role']; ?></p>

    <!-- =========================
         ADMIN DASHBOARD
    ========================= -->
    <?php if ($user['role'] == 'admin') { ?>

        <div class="row mt-4">

            <div class="col-md-3">
                <div class="card shadow p-3">
                    <h5>Total Students</h5>
                    <h2 class="text-primary"><?= $total_students ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow p-3">
                    <h5>Total Teachers</h5>
                    <h2 class="text-success"><?= $total_teachers ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow p-3">
                    <h5>Total Assignments</h5>
                    <h2 class="text-warning"><?= $total_assignments ?></h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow p-3">
                    <h5>Attendance %</h5>
                    <h2 class="text-danger"><?= $attendance_percent ?>%</h2>
                </div>
            </div>

        </div>

    <?php } ?>

    <!-- =========================
         OTHER USERS
    ========================= -->
    <div class="row mt-4 justify-content-center">

        <?php if ($user['role'] == 'teacher') { ?>
            <div class="col-md-4">
                <a href="teacher/index.php" class="btn btn-success w-100 p-3">
                    Teacher Panel
                </a>
            </div>
        <?php } ?>

        <?php if ($user['role'] == 'student') { ?>
            <div class="col-md-4">
                <a href="student/index.php" class="btn btn-warning w-100 p-3">
                    Student Panel
                </a>
            </div>
        <?php } ?>

    </div>

</div>

</body>
</html>