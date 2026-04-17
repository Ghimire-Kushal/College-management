<?php include("../includes/navbar.php"); ?>
<?php
include("config/db.php");

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = "student"; // default role

    $conn->query("INSERT INTO users (name,email,password,role)
                  VALUES ('$name','$email','$password','$role')");

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 shadow mx-auto" style="max-width:400px;">

<h4 class="text-center mb-3">Create Account</h4>

<form method="POST">
    <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

    <button name="register" class="btn btn-success w-100">Register</button>
</form>

</div>
</div>

</body>
</html>