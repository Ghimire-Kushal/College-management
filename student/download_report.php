<?php
session_start();

// Only student allowed
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");
require_once __DIR__ . '/../lib/fpdf/fpdf.php';

$email = $_SESSION['user']['email'];

// Get student ID
$student = $conn->query("SELECT id, name FROM students WHERE email='$email'")->fetch_assoc();
$student_id = $student['id'];
$name = $student['name'];

// Fetch attendance
$result = $conn->query("SELECT * FROM attendance WHERE student_id = '$student_id'");

$total = $result->num_rows;
$present = 0;

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    if ($row['status'] == 'present') {
        $present++;
    }
}

$percentage = ($total > 0) ? round(($present / $total) * 100, 2) : 0;


// ---------------- PDF START ----------------
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Apollo CMS - Attendance Report',0,1,'C');

$pdf->Ln(5);

// Student Info
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Name: $name",0,1);
$pdf->Cell(0,10,"Total Days: $total",0,1);
$pdf->Cell(0,10,"Present: $present",0,1);
$pdf->Cell(0,10,"Attendance: $percentage%",0,1);

$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(90,10,'Date',1);
$pdf->Cell(90,10,'Status',1);
$pdf->Ln();

// Table Data
$pdf->SetFont('Arial','',12);

foreach ($data as $row) {
    $pdf->Cell(90,10,$row['date'],1);
    $pdf->Cell(90,10,ucfirst($row['status']),1);
    $pdf->Ln();
}

$pdf->Output("D", "Attendance_Report.pdf"); // Download
exit;
?>