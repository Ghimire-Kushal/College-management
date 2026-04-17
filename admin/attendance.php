<?php
session_start();

// 🔐 Only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$date = $_GET['date'] ?? date("Y-m-d");

// Fetch students
$students = $conn->query("SELECT * FROM students");

// Fetch existing attendance
$attendanceData = [];
$existing = $conn->query("SELECT * FROM attendance WHERE date='$date'");
while ($row = $existing->fetch_assoc()) {
    $attendanceData[$row['student_id']] = $row['status'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .table tbody tr:hover {
            background: transparent !important;
        }
        .btn-group .btn {
            min-width: 100px;
        }
    </style>
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="text-success mb-3">📅 Admin Attendance (<?= $date ?>)</h2>

<!-- Date -->
<div class="mb-3">
    <input type="date" id="dateInput" class="form-control" value="<?= $date ?>">
    <button onclick="changeDate()" class="btn btn-primary mt-2">Load Attendance</button>
</div>

<form id="attendanceForm">

<input type="hidden" name="date" value="<?= $date ?>">

<div class="card shadow p-3">

<table class="table table-bordered text-center align-middle">
<thead class="table-dark">
<tr>
    <th>Name</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php while($row = $students->fetch_assoc()) { 

    $status = $attendanceData[$row['id']] ?? 'present';
?>
<tr id="row_<?= $row['id'] ?>">
    <td class="fw-semibold"><?= $row['name']; ?></td>

    <td>
        <!-- ✅ FIXED hidden input -->
        <input type="hidden"
               name="status[<?= $row['id']; ?>]"
               id="status_<?= $row['id']; ?>"
               value="<?= $status ?>">

        <div class="btn-group">

            <button type="button"
                class="btn btn-sm <?= ($status=='present') ? 'btn-success active' : 'btn-outline-success' ?>"
                onclick="setStatus(<?= $row['id']; ?>, 'present')">
                ✔ Present
            </button>

            <button type="button"
                class="btn btn-sm <?= ($status=='absent') ? 'btn-danger active' : 'btn-outline-danger' ?>"
                onclick="setStatus(<?= $row['id']; ?>, 'absent')">
                ✖ Absent
            </button>

        </div>
    </td>
</tr>
<?php } ?>
</tbody>

</table>

<div class="text-end">
    <button type="button" onclick="saveAttendance()" class="btn btn-success px-4">
        Save / Update Attendance
    </button>
</div>

</div>

</form>

</div>

<script>

// 🔁 Change date
function changeDate() {
    let date = document.getElementById("dateInput").value;
    window.location.href = "?date=" + date;
}

// ✅ FIXED toggle logic
function setStatus(id, status) {

    let hidden = document.getElementById("status_" + id);
    hidden.value = status;

    let row = document.getElementById("row_" + id);
    let buttons = row.querySelectorAll("button");

    // Reset styles
    buttons[0].classList.remove("btn-success", "active");
    buttons[0].classList.add("btn-outline-success");

    buttons[1].classList.remove("btn-danger", "active");
    buttons[1].classList.add("btn-outline-danger");

    // Apply active
    if (status === "present") {
        buttons[0].classList.add("btn-success", "active");
    } else {
        buttons[1].classList.add("btn-danger", "active");
    }
}

// ✅ AJAX save
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
    })
    .catch(err => {
        console.log(err);
        alert("Error saving attendance");
    });
}

// ✅ Toast
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