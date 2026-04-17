<?php
session_start();

// Only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Insert student
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO students (name,email,course)
                  VALUES ('$name','$email','$course')");

    header("Location: students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<!-- Navbar -->
<?php include("../includes/navbar.php"); ?>

<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow p-4 mt-5">

                <h3 class="text-center text-success mb-4">➕ Add Student</h3>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <input type="text" name="course" class="form-control" placeholder="Enter course" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="submit" class="btn btn-primary">
                            Add Student
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

</body>
</html>