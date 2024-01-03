<?php
session_start();
include 'controller/config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['id'] == true) {
    header("location: welcome.php");
    exit();
}


function alert($message, $redirect = null)
{
    echo "<script>alert('$message');";
    if ($redirect) {
        echo "window.location.replace('$redirect');";
    }
    echo "</script>";
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $connect->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        alert("Email does not exist. Please register first!");
    }else if (password_verify($password, $user["password"])) {
        if ($user["status"] == 0) {
            alert("Please verify email account before login.","verification.php");
            $_SESSION['otp'] = $user["id"];
            $_SESSION['mail'] = $email;
        }else{
            $_SESSION['id'] = $user["id"];
            $_SESSION['loggedin'] = true;
            alert("Login in successfully", "welcome.php");
        }
    } else {
        alert("Email or password invalid, please try again.");
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <title>Login Form</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="#">Login Form</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" alt="Menu"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php" style="font-weight:bold; color:black; text-decoration:underline">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading text-center">PLEASE TRY OUT MY LOGIN WITH PHPMAILER FUNCTION</h4>
                </div>
            </div>
        </div>
    </div>

    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <form action="#" method="POST" name="login">
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email" required autofocus placeholder="Enter your email">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="password" class="form-control" name="password" required placeholder="Enter your password">
                                        <i class="bi bi-eye-slash" id="togglePassword"></i>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember"> Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success mr-2" name="login" value="Login">Login</button>
                                    <a href="forgot_pass.php" class="btn btn-link">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
<footer class="footer fixed-bottom bg-light py-3 text-center">
    <p class="text-muted">Made by: <span class='text-info'>John Mark Cuyos </span> <a href="https://github.com/Kaelx" target="_blank" class="link-warning">(Github Profile)</a></p>
</footer>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>

</html>
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function() {
        if (password.type === "password") {
            password.type = 'text';
        } else {
            password.type = 'password';
        }
        this.classList.toggle('bi-eye');
    });
</script>