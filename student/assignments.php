<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Only student allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

$email = $_SESSION['user']['email'];

// ✅ Get student ID safely
$res = $conn->query("SELECT id FROM students WHERE email='$email'");

if (!$res || $res->num_rows == 0) {
    die("❌ Student not found in DB for email: " . $email);
}

$student = $res->fetch_assoc();
$student_id = $student['id'];


// ✅ HANDLE UPLOAD
if (isset($_POST['upload'])) {

    if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
        die("❌ File upload error");
    }

    $title = $_POST['title'];

    $file_name = time() . "_" . basename($_FILES['file']['name']);
    $tmp = $_FILES['file']['tmp_name'];

    $upload_dir = "../uploads/";
    $folder = $upload_dir . $file_name;

    // Create folder if not exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($tmp, $folder)) {

        $sql = "INSERT INTO assignments (student_id, title, file)
                VALUES ('$student_id', '$title', '$file_name')";

        if ($conn->query($sql)) {
            $success = "✅ Upload successful!";
        } else {
            die("❌ DB Error: " . $conn->error);
        }

    } else {
        die("❌ File move failed");
    }
}


// ✅ FETCH ASSIGNMENTS
$result = $conn->query("SELECT * FROM assignments WHERE student_id='$student_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assignments</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="text-success">📄 Assignments</h2>

<?php if (isset($success)) { ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php } ?>

<!-- Upload Form -->
<form method="POST" enctype="multipart/form-data" class="card p-4 shadow mb-4">

    <div class="mb-3">
        <label class="form-label">Assignment Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Upload File</label>
        <input type="file" name="file" class="form-control" required>
    </div>

    <button type="submit" name="upload" class="btn btn-success w-100">Upload</button>

</form>

<!-- Assignment List -->
<table class="table table-bordered shadow">

<thead class="table-dark">
<tr>
    <th>Title</th>
    <th>File</th>
    <th>Date</th>
</tr>
</thead>

<tbody>

<?php if ($result && $result->num_rows > 0) { ?>
    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td>
                <a href="../uploads/<?= $row['file']; ?>" target="_blank" class="btn btn-sm btn-primary">
                    View
                </a>
            </td>
            <td><?= $row['submitted_at'] ?? 'N/A'; ?></td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="3" class="text-center">No assignments uploaded yet</td>
    </tr>
<?php } ?>

</tbody>

</table>

</div>

</body>
</html>