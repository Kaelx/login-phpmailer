<?php 
session_start();
include 'controller/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


if (isset($_SESSION['loggedin']) && $_SESSION['id'] == true) {
    header("location: welcome.php");
    exit();
}

function alert($message, $redirect = null) {
    echo "<script>alert('$message');";
    if ($redirect) {
        echo "window.location.replace('$redirect');";
    }
    echo "</script>";
}

function sendOTP($email, $otp) {
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host='smtp.gmail.com';
    $mail->Port=587;
    $mail->SMTPAuth=true;
    $mail->SMTPSecure='tls';

    $mail->Username='000phpmailer@gmail.com';
    $mail->Password='qbrz dvmt otmf sjly';

    $mail->setFrom('sample@gmail.com', 'OTP Verification');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject="Verification code";
    $mail->Body="<p>Dear user, </p> <h3>Your verification OTP code is $otp <br></h3>";

    return $mail->send();
}

if(isset($_POST["register"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];

    if (!preg_match("/^[a-zA-Z-' ]*$/",$fname) || !preg_match("/^[a-zA-Z-' ]*$/",$lname)) {
        alert("Please enter a valid name.");
        return;
    }

    $stmt = $connect->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if(empty($email) || empty($password)){
        alert("Email and password cannot be empty.");
    } else if($user){
        alert("User with email already exist!");
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $connect->prepare("INSERT INTO login (email, password, fname, lname) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $password_hash, $fname, $lname);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $otp = rand(100000,999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['mail'] = $email;

            if(!sendOTP($email, $otp)){
                alert("Register Failed, Invalid Email");
            } else {
                alert("Register Successfully, OTP sent to $email", 'verification.php');
            }
        }
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <title>Register Form</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Register Form</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" alt="Menu"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php" style="font-weight:bold; color:black; text-decoration:underline">Register</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">
                            <form action="#" method="POST" name="register">
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="email" id="email_address" class="form-control" name="email" required autofocus placeholder="Enter your email">
                                        <small class="form-text text-muted">Please enter a valid email to send the OTP code.</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="password" id="password" class="form-control" name="password" required placeholder="Enter your password">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="togglePassword"><i class="bi bi-eye-slash"></i></span>
                                            </div>
                                        </div>
                                        <small id="passwordHelp" class="form-text text-muted">Do not input personal password, just random.</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="firstname" class="form-control" name="fname" required placeholder="Enter your name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="lastname" class="form-control" name="lname" required placeholder="Enter your lastname">
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success mr-2" value="Register" name="register">Register</button>
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
    const toggle = document.querySelector('#togglePassword i');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function(){
        if(password.type === "password"){
            password.type = 'text';
            this.classList.remove('bi-eye-slash');
            this.classList.add('bi-eye');
        }else{
            password.type = 'password';
            this.classList.remove('bi-eye');
            this.classList.add('bi-eye-slash');
        }
    });
</script>
