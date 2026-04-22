<?php
session_start();
include("../config/db.php");


// ✅ Check request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
    exit();
}

// ✅ Validate data
$date = $_POST['date'] ?? null;
$statuses = $_POST['status'] ?? null;

if (!$date || !$statuses) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing data"
    ]);
    exit();
}

// ✅ Prepare statements
$checkStmt = $conn->prepare("SELECT id FROM attendance WHERE student_id=? AND date=?");
$updateStmt = $conn->prepare("UPDATE attendance SET status=? WHERE student_id=? AND date=?");
$insertStmt = $conn->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");

// ✅ Loop through students
foreach ($statuses as $student_id => $status) {

    // Check existing
    $checkStmt->bind_param("is", $student_id, $date);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {

        // 🔁 UPDATE
        $updateStmt->bind_param("sis", $status, $student_id, $date);
        $updateStmt->execute();

    } else {

        // ➕ INSERT
        $insertStmt->bind_param("iss", $student_id, $date, $status);
        $insertStmt->execute();
    }
}

// ✅ Success response
echo json_encode([
    "status" => "success",
    "message" => "Attendance saved successfully!"
]);