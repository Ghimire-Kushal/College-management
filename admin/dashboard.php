<?php
include("config/db.php");

// Total Students
$total_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];

// Total Teachers
$total_teachers = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='teacher'")->fetch_assoc()['total'];

// Total Assignments
$total_assignments = $conn->query("SELECT COUNT(*) as total FROM assignments")->fetch_assoc()['total'];

// Attendance %
$attendance = $conn->query("SELECT 
    COUNT(CASE WHEN status='present' THEN 1 END) as present,
    COUNT(*) as total 
    FROM attendance")->fetch_assoc();

$attendance_percent = ($attendance['total'] > 0) 
    ? round(($attendance['present'] / $attendance['total']) * 100) 
    : 0;
?>