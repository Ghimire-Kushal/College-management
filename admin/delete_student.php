<?php
session_start();

// Only admin allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Check if ID is provided
if (isset($_GET['id'])) {

    $id = intval($_GET['id']); // secure (convert to integer)

    // Delete student
    $conn->query("DELETE FROM students WHERE id = $id");
}

// Redirect back
header("Location: students.php");
exit();
?>