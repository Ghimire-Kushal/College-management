<?php include("../includes/navbar.php"); ?>
<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
?>