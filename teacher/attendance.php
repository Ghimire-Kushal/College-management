<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$date = $_GET['date'] ?? date("Y-m-d");

// Fetch students
$students = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    .table tbody tr:hover {
        background-color: transparent !important;
    }
    </style>
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">

    <h2 class="text-success mb-3">📅 Attendance (<?= $date ?>)</h2>

    <!-- DATE SELECT (NO FORM) -->
    <div class="mb-3">
        <input type="date" id="dateInput" class="form-control" value="<?= $date ?>">
        <button onclick="changeDate()" class="btn btn-primary mt-2">Load Attendance</button>
    </div>

    <!-- ONLY ONE FORM -->
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
                <?php while($row = $students->fetch_assoc()) { ?>
                    <tr>
                        <td class="fw-semibold"><?= $row['name']; ?></td>
                        <td>
                            <input type="hidden" name="status[<?= $row['id']; ?>]" value="present">

                            <button type="button" class="btn btn-success btn-sm"
                                onclick="setStatus(this, <?= $row['id']; ?>, 'present')">
                                ✔ Present
                            </button>

                            <button type="button" class="btn btn-outline-danger btn-sm"
                                onclick="setStatus(this, <?= $row['id']; ?>, 'absent')">
                                ✖ Absent
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="text-end">
                <button type="button" onclick="saveAttendance()" class="btn btn-success px-4">
                    Save /Update Attendance
                </button>
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

// Toggle buttons
function setStatus(btn, id, status) {
    let td = btn.parentElement;

    let buttons = td.querySelectorAll("button");
    buttons[0].classList.remove("btn-success");
    buttons[0].classList.add("btn-outline-success");

    buttons[1].classList.remove("btn-danger");
    buttons[1].classList.add("btn-outline-danger");

    if (status === "present") {
        buttons[0].classList.add("btn-success");
    } else {
        buttons[1].classList.add("btn-danger");
    }

    td.querySelector("input").value = status;
}

// AJAX SAVE
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

// Toast
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