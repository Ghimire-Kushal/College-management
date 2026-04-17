<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("
    SELECT students.name, attendance.date, attendance.status
    FROM attendance
    JOIN students ON attendance.student_id = students.id
    ORDER BY attendance.date DESC
");
?>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
<h2>Attendance Records</h2>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
    <th>Name</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['date']; ?></td>
    <td><?= ucfirst($row['status']); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>