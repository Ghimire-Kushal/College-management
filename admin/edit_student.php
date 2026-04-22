<?php
session_start();
include("../config/db.php");
include("../includes/navbar.php");

// Check login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Get ID
if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$id = $_GET['id'];

/* =========================
   FETCH STUDENT DATA
========================= */
$stmt = $conn->prepare("SELECT * FROM students WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Student not found!";
    exit();
}

$student = $result->fetch_assoc();

/* =========================
   UPDATE STUDENT
========================= */
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, course=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $course, $id);

    if ($stmt->execute()) {
        header("Location: students.php?msg=updated");
        exit();
    } else {
        echo "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-5">

    <div class="card shadow p-4 col-md-6 mx-auto">

        <h3 class="text-success mb-4">✏️ Edit Student</h3>

        <form method="POST">

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($student['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($student['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label>Course</label>
                <input type="text" name="course" class="form-control"
                       value="<?= htmlspecialchars($student['course']) ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="students.php" class="btn btn-secondary">← Back</a>
                <button type="submit" name="update" class="btn btn-success">
                    Update Student
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>