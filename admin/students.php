<?php
session_start();

// Only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Fetch students
$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<!-- Navbar -->
<?php include("../includes/navbar.php"); ?>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-success">🎓 Student List</h3>
        <a href="add_student.php" class="btn btn-success">
            ➕ Add Student
        </a>
    </div>

    <div class="card shadow p-3">

        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td class="fw-semibold"><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['course']; ?></td>
                    <td>
                        <a href="edit_student.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
    Edit
</a>

                        <a href="delete_student.php?id=<?= $row['id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this student?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>