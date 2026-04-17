<?php
session_start();

// 🔐 Only student allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// ✅ Get logged-in email
$email = $_SESSION['user']['email'] ?? null;

if (!$email) {
    echo "Session error. Please login again.";
    exit();
}

/* =========================
   GET STUDENT ID FROM EMAIL
========================= */
$stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo "Student not found!";
    exit();
}

$student = $res->fetch_assoc();
$student_id = $student['id'];

/* =========================
   FETCH ATTENDANCE
========================= */
$stmt2 = $conn->prepare("
    SELECT date, status 
    FROM attendance 
    WHERE student_id = ? 
    ORDER BY date DESC
");
$stmt2->bind_param("i", $student_id);
$stmt2->execute();
$result = $stmt2->get_result();

/* =========================
   CALCULATIONS
========================= */
$total = 0;
$present = 0;
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    $total++;

    if ($row['status'] === 'present') {
        $present++;
    }
}

$absent = $total - $present;
$percentage = ($total > 0) ? round(($present / $total) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-5">

    <h3 class="text-success mb-4">📊 My Attendance</h3>

    <!-- Stats -->
    <div class="row text-center mb-4">

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h6 class="text-muted">Total Days</h6>
                <h2 class="text-primary"><?= $total ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h6 class="text-muted">Present</h6>
                <h2 class="text-success"><?= $present ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h6 class="text-muted">Absent</h6>
                <h2 class="text-danger"><?= $absent ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-4">
                <h6 class="text-muted">Attendance %</h6>
                <h2 class="<?= ($percentage >= 75) ? 'text-success' : 'text-danger' ?>">
                    <?= $percentage ?>%
                </h2>
            </div>
        </div>

    </div>

    <!-- Progress -->
    <div class="card shadow p-4 mb-4">
        <h6 class="mb-3">Overall Attendance</h6>
        <div class="progress" style="height: 25px;">
            <div class="progress-bar 
                <?= ($percentage >= 75) ? 'bg-success' : 'bg-danger' ?>"
                 style="width: <?= $percentage ?>%;">
                <?= $percentage ?>%
            </div>
        </div>
    </div>

    <!-- Download -->
    <a href="download_report.php" class="btn btn-success mb-3">
        📄 Download PDF Report
    </a>

    <!-- Table -->
    <div class="card shadow p-3">

        <table class="table text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($data)) { ?>
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td>
                                <?php if ($row['status'] === 'present') { ?>
                                    <span class="badge bg-success">✔ Present</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">✖ Absent</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2" class="text-muted">
                            No attendance records found
                        </td>
                    </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

</div>

</body>
</html>