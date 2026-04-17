<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Navbar</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="/college-management/dashboard.php">
        <img src="/college-management/assets/images/logo.png" width="40" class="me-2">
        Apollo CMS
    </a>

    <!-- Menu -->
    <div>

    <?php if ($user) { ?>

        <!-- ADMIN -->
        <?php if ($user['role'] == 'admin') { ?>
            <a href="/college-management/dashboard.php" class="btn btn-light btn-sm me-1">Dashboard</a>
            <a href="/college-management/admin/students.php" class="btn btn-light btn-sm me-1">Students</a>
            <a href="/college-management/admin/attendance.php" class="btn btn-light btn-sm me-1">Attendance</a>
        <?php } ?>

        <!-- TEACHER -->
        <?php if ($user['role'] == 'teacher') { ?>
            <a href="/college-management/teacher/index.php" class="btn btn-light btn-sm me-1">Dashboard</a>
            <a href="/college-management/teacher/students.php" class="btn btn-light btn-sm me-1">Students</a>
            <a href="/college-management/teacher/attendance.php" class="btn btn-light btn-sm me-1">Attendance</a>
        <?php } ?>

        <!-- STUDENT -->
        <?php if ($user['role'] == 'student') { ?>
            <a href="/college-management/student/index.php" class="btn btn-light btn-sm me-1">Dashboard</a>
            <a href="/college-management/student/attendance.php" class="btn btn-light btn-sm me-1">My Attendance</a>
        <?php } ?>

        <!-- LOGOUT -->
        <a href="/college-management/logout.php" class="btn btn-danger btn-sm">Logout</a>

    <?php } ?>

    </div>

  </div>
</nav>

</body>
</html>