<?php
session_start();
include("../config/db.php");

// ✅ Return JSON always
header('Content-Type: application/json');

// ✅ Check login
if (!isset($_SESSION['user'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access"
    ]);
    exit();
}

// ✅ Validate POST data
if (!isset($_POST['date']) || !isset($_POST['status'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid data received"
    ]);
    exit();
}

$date = $_POST['date'];
$statusData = $_POST['status'];

// ✅ Optional: sanitize date
$date = $conn->real_escape_string($date);

$errors = [];
$successCount = 0;

foreach ($statusData as $student_id => $status) {

    // ✅ sanitize inputs
    $student_id = (int)$student_id;
    $status = $conn->real_escape_string($status);

    // ✅ validate status
    if ($status !== 'present' && $status !== 'absent') {
        $errors[] = "Invalid status for student ID $student_id";
        continue;
    }

    // ✅ check existing record
    $check = $conn->query("SELECT id FROM attendance 
                           WHERE student_id='$student_id' AND date='$date'");

    if (!$check) {
        $errors[] = $conn->error;
        continue;
    }

    if ($check->num_rows > 0) {

        // ✅ UPDATE
        $update = $conn->query("UPDATE attendance 
                                SET status='$status' 
                                WHERE student_id='$student_id' AND date='$date'");

        if ($update) {
            $successCount++;
        } else {
            $errors[] = $conn->error;
        }

    } else {

        // ✅ INSERT
        $insert = $conn->query("INSERT INTO attendance (student_id, date, status)
                                VALUES ('$student_id', '$date', '$status')");

        if ($insert) {
            $successCount++;
        } else {
            $errors[] = $conn->error;
        }
    }
}

// ✅ Final response
if (count($errors) > 0) {

    echo json_encode([
        "status" => "partial",
        "message" => "Some records failed",
        "success_count" => $successCount,
        "errors" => $errors
    ]);

} else {

    echo json_encode([
        "status" => "success",
        "message" => "Attendance saved successfully!",
        "updated" => $successCount
    ]);
}