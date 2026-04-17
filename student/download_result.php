<?php
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;

session_start();
include("../config/db.php");

// Get email
$email = $_SESSION['user']['email'];

// Get student id
$stmt = $conn->prepare("SELECT id,name FROM students WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();

$student_id = $student['id'];
$name = $student['name'];

// Get results
$result = $conn->query("SELECT * FROM results WHERE student_id='$student_id'");

$html = "<h2>Result Report</h2>";
$html .= "<p>Name: $name</p>";

$html .= "<table border='1' width='100%' cellpadding='5'>
<tr><th>Subject</th><th>Marks</th></tr>";

$total = 0;
$count = 0;

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>{$row['subject']}</td>
        <td>{$row['marks']}</td>
    </tr>";
    $total += $row['marks'];
    $count++;
}

$percentage = $count > 0 ? $total / $count : 0;

$html .= "</table>";
$html .= "<h3>Percentage: $percentage%</h3>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("result.pdf");