<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'teacher') {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM students");
?>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
<h2>Student List</h2>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Course</th>
</tr>
</thead>

<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['name']; ?></td>
    <td><?= $row['email']; ?></td>
    <td><?= $row['course']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>