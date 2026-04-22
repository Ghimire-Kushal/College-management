<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");
include("../includes/navbar.php");

$date = $_GET['date'] ?? date("Y-m-d");

/* =========================
   FETCH STUDENTS
========================= */
$students = $conn->query("SELECT * FROM students");

/* =========================
   FETCH ATTENDANCE
========================= */
$attendanceData = [];

$stmt = $conn->prepare("SELECT student_id, status FROM attendance WHERE date=?");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $attendanceData[$row['student_id']] = $row['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn-group button {
            min-width: 100px;
        }
        .card {
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <h2 class="text-success mb-3">📅 Attendance (<?= $date ?>)</h2>

    <!-- DATE SELECT -->
    <div class="mb-3">
        <input type="date" id="dateInput" class="form-control" value="<?= $date ?>">
        <button onclick="changeDate()" class="btn btn-primary mt-2">Load Attendance</button>
    </div>

    <!-- FORM -->
    <form id="attendanceForm">

        <input type="hidden" name="date" value="<?= $date ?>">

        <div class="card shadow p-4">

            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:50%">Student Name</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php while($row = $students->fetch_assoc()) { 

                    $status = $attendanceData[$row['id']] ?? 'present';
                ?>
                <tr>
                    <td class="fw-semibold">
                        <?= htmlspecialchars($row['name']); ?>
                    </td>

                    <td>
                        <input type="hidden" name="status[<?= $row['id']; ?>]" value="<?= $status ?>">

                        <div class="btn-group">

                            <button type="button"
                                class="btn btn-sm <?= ($status == 'present') ? 'btn-success' : 'btn-outline-success' ?>"
                                onclick="setStatus(this, <?= $row['id']; ?>, 'present')">
                                ✔ Present
                            </button>

                            <button type="button"
                                class="btn btn-sm <?= ($status == 'absent') ? 'btn-danger' : 'btn-outline-danger' ?>"
                                onclick="setStatus(this, <?= $row['id']; ?>, 'absent')">
                                ✖ Absent
                            </button>

                        </div>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="text-end mt-3">
                <button type="button" onclick="saveAttendance()" class="btn btn-success px-4">
                    Save / Update Attendance
                </button>
            </div>
            <div class="mb-3">
    <a href="index.php" class="btn btn-outline-secondary">
        ← Back to Dashboard
    </a>
</div>

        </div>
    </form>
</div>

<script>

// Change date
function changeDate() {
    let date = document.getElementById("dateInput").value;
    window.location.href = "?date=" + date;
}

// Toggle status
function setStatus(btn, id, status) {
    let td = btn.parentElement;

    let presentBtn = td.children[0];
    let absentBtn = td.children[1];
    let input = td.parentElement.querySelector("input");

    // Reset
    presentBtn.classList.remove("btn-success");
    presentBtn.classList.add("btn-outline-success");

    absentBtn.classList.remove("btn-danger");
    absentBtn.classList.add("btn-outline-danger");

    // Apply
    if (status === "present") {
        presentBtn.classList.add("btn-success");
    } else {
        absentBtn.classList.add("btn-danger");
    }

    input.value = status;
}

// Save attendance
function saveAttendance() {

    let form = document.getElementById("attendanceForm");
    let formData = new FormData(form);

    fetch("save_attendance.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message);
    });
}

// Toast message
function showToast(message) {
    let toast = document.createElement("div");
    toast.innerText = message;

    toast.style.position = "fixed";
    toast.style.top = "20px";
    toast.style.right = "20px";
    toast.style.background = "#198754";
    toast.style.color = "#fff";
    toast.style.padding = "10px 20px";
    toast.style.borderRadius = "5px";
    toast.style.zIndex = "9999";

    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 2000);
}

</script>

</body>
</html>