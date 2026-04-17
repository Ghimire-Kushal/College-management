<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Get email
$email = $_SESSION['user']['email'] ?? null;

if (!$email) {
    echo "Session error!";
    exit();
}

/* =========================
   GET STUDENT ID
========================= */
$stmt = $conn->prepare("SELECT id, name FROM students WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo "Student not found!";
    exit();
}

$student = $res->fetch_assoc();
$student_id = $student['id'];
$name = $student['name'];

/* =========================
   SEMESTER FILTER
========================= */
$semester = $_GET['semester'] ?? '';

if ($semester) {
    $stmt2 = $conn->prepare("SELECT * FROM results WHERE student_id=? AND semester=?");
    $stmt2->bind_param("is", $student_id, $semester);
} else {
    $stmt2 = $conn->prepare("SELECT * FROM results WHERE student_id=?");
    $stmt2->bind_param("i", $student_id);
}

$stmt2->execute();
$result = $stmt2->get_result();

/* =========================
   GPA FUNCTION
========================= */
function getGPA($marks) {
    if ($marks >= 80) return 4.0;
    if ($marks >= 70) return 3.6;
    if ($marks >= 60) return 3.2;
    if ($marks >= 50) return 2.8;
    if ($marks >= 40) return 2.0;
    return 0.0;
}

/* =========================
   CALCULATIONS
========================= */
$total_marks = 0;
$total_full = 0;
$total_gpa = 0;
$count = 0;
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;

    $total_marks += $row['marks'];
    $total_full += $row['full_marks'];

    $total_gpa += getGPA($row['marks']);
    $count++;
}

$percentage = ($total_full > 0) ? round(($total_marks / $total_full) * 100, 2) : 0;
$final_gpa = ($count > 0) ? round($total_gpa / $count, 2) : 0;
$pass = $percentage >= 40;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-5">

<h3 class="text-success mb-4">📊 My Results - <?= htmlspecialchars($name) ?></h3>

<!-- 🔽 Semester Filter -->
<form method="GET" class="mb-4">
    <select name="semester" class="form-control">
        <option value="">All Semester</option>
        <option value="Semester 1" <?= ($semester=="Semester 1")?'selected':'' ?>>Semester 1</option>
        <option value="Semester 2" <?= ($semester=="Semester 2")?'selected':'' ?>>Semester 2</option>
    </select>
    <button class="btn btn-primary mt-2">Filter</button>
</form>

<!-- 📊 Summary Cards -->
<div class="row text-center mb-4">

    <div class="col-md-3">
        <div class="card shadow p-4">
            <h6>Total Marks</h6>
            <h2 class="text-primary"><?= $total_marks ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-4">
            <h6>Percentage</h6>
            <h2 class="<?= $pass ? 'text-success' : 'text-danger' ?>">
                <?= $percentage ?>%
            </h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-4">
            <h6>GPA</h6>
            <h2 class="text-info"><?= $final_gpa ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow p-4">
            <h6>Status</h6>
            <h2 class="<?= $pass ? 'text-success' : 'text-danger' ?>">
                <?= $pass ? 'PASS' : 'FAIL' ?>
            </h2>
        </div>
    </div>

</div>

<!-- 📄 Download Button -->
<a href="download_result.php" class="btn btn-success mb-3">
    📄 Download Result PDF
</a>

<!-- 📋 Table -->
<div class="card shadow p-3">

<table class="table text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>Subject</th>
            <th>Marks</th>
            <th>GPA</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($data)) { ?>
            <?php foreach ($data as $row) { 
                $status = ($row['marks'] >= 40) ? "Pass" : "Fail";
                $gpa = getGPA($row['marks']);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= $row['marks'] ?>/<?= $row['full_marks'] ?></td>
                <td><?= $gpa ?></td>
                <td>
                    <span class="badge <?= ($status=='Pass') ? 'bg-success' : 'bg-danger' ?>">
                        <?= $status ?>
                    </span>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4" class="text-muted">No results found</td>
            </tr>
        <?php } ?>
    </tbody>

</table>

</div>

</div>

</body>
</html>