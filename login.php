<?php include("../includes/navbar.php"); ?>
<?php
session_start();
include("config/db.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - College Management System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            height: 100vh;
        }

        .login-card {
            border-radius: 15px;
        }
    </style>
</head>

<body>

<div class="d-flex justify-content-center align-items-center vh-100">

    <div class="card p-4 shadow login-card" style="width: 350px;">

        <!-- <h3 class="text-center mb-3">Login</h3> -->
         <h4 class="text-center mb-1 fw-bold text-primary">
    🎓 Welcome to Apollo
</h4>
<p class="text-center text-muted mb-3">Smart College Management System</p>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button name="login" class="btn btn-primary w-100">Login</button>

        </form>
        <div class="text-center mt-3">
    <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
</div>

<div class="text-center mt-2">
    <span>Don't have an account? </span>
    <a href="register.php" class="text-decoration-none fw-bold text-primary">Create Account</a>
</div>

        <p class="text-center mt-3 text-muted" style="font-size: 14px;">
            Smart College Management System
        </p>

    </div>

</div>

</body>
</html>