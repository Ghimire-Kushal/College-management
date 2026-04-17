<?php include("../includes/navbar.php"); ?>
<?php
session_start();

// If already logged in → redirect to dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart College Management System</title>
    <!-- <style>
        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
            background: #f4f6f9;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            text-align: center;
            padding: 80px;
        }

        h1 {
            color: #2c3e50;
        }

        p {
            color: #555;
            font-size: 18px;
        }

        .btn {
            padding: 12px 25px;
            background: #3498db;
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background: #2980b9;
        }
    </style> -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <span class="navbar-brand">College Management System</span>
    <a href="login.php" class="btn btn-primary">Login</a>
  </div>
</nav>

<div class="container text-center">
    <h1 class="mb-3">Welcome to Apollo College</h1>
    <p class="text-muted">Manage students, attendance, assignments, and results easily.</p>

    <div class="row mt-5 g-4">

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h5>Students</h5>
                <p>Manage student records</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h5>Attendance</h5>
                <p>Track attendance easily</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h5>Assignments</h5>
                <p>Upload and submit tasks</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h5>Results</h5>
                <p>View academic performance</p>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>