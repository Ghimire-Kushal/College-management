<?php
session_start();
include("../config/db.php");

$date = $_POST['date'];

foreach ($_POST['status'] as $student_id => $status) {

    $check = $conn->query("SELECT * FROM attendance 
                           WHERE student_id='$student_id' AND date='$date'");

    if ($check->num_rows > 0) {

        $conn->query("UPDATE attendance 
                      SET status='$status' 
                      WHERE student_id='$student_id' AND date='$date'");

    } else {

        $conn->query("INSERT INTO attendance (student_id, date, status)
                      VALUES ('$student_id', '$date', '$status')");
    }
}

echo json_encode([
    "status" => "success",
    "message" => "Attendance saved successfully!"
]);