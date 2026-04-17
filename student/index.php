<?php
session_start();

// Only student allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container text-center mt-5">

    <h2 class="text-success mb-2">
        Welcome <?php echo $user['name']; ?> 🎓
    </h2>
    <p class="text-muted mb-4">Student Dashboard</p>

    <div class="row g-4 justify-content-center">

        <!-- Attendance -->
        <div class="col-md-4">
            <div class="card shadow p-4 h-100">
                <h5 class="text-success">📅 Attendance</h5>
                <p>View your attendance record</p>
                <a href="attendance.php" class="btn btn-success w-100">
                    View Attendance
                </a>
            </div>
        </div>

        <!-- Assignments -->
        <div class="col-md-4">
            <div class="card shadow p-4 h-100">
                <h5 class="text-success">📝 Assignments</h5>
                <p>Submit your assignments</p>
                <a href="assignments.php" class="btn btn-success">Open</a>
            </div>
        </div>
       
        <!-- Results -->
        <div class="col-md-4">
            <div class="card shadow p-4 h-100">
                <h5 class="text-success">📊 Results</h5>
                <p>Check your performance</p>
                <a href="results.php" class="btn btn-success">View Results</a>
            </div>
        </div>
         <a href="download_report.php" class="btn btn-success mt-3">
    Download PDF Report
</a>


    </div>

</div>

</body>
</html>