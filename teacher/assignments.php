<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Get all assignments with student name
$result = $conn->query("
    SELECT assignments.*, students.name 
    FROM assignments 
    JOIN students ON assignments.student_id = students.id
    ORDER BY submitted_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>All Assignments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="text-success">📄 Student Assignments</h2>

<table class="table table-bordered">

<thead class="table-dark">
<tr>
    <th>Student</th>
    <th>Title</th>
    <th>File</th>
    <th>Date</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['title']; ?></td>
    <td>
        <a href="../uploads/<?= $row['file']; ?>" target="_blank">View</a>
    </td>
    <td><?= $row['submitted_at']; ?></td>
</tr>
<?php } ?>

</tbody>

</table>

</div>

</body>
</html>