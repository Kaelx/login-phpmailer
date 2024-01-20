<?php
$page = 'SIGN UP';
include 'controller/credentials.php'; //create a file name credentials.php and put your email($mailUsername = 'youremail@gmail.com') and password($mailPassword = '16 keys') for sending OTP
include 'views/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendOTP($email, $otp, $mailUsername, $mailPassword)
{
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = $mailUsername; //variable from credentials.php
    $mail->Password = $mailPassword; //variable from credentials.php

    $mail->setFrom('sample@gmail.com', 'OTP Verification');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Verification code";
    $mail->Body = "<p>Dear user, </p> <h3>Your verification OTP code is $otp <br></h3>";

    return $mail->send();
}


if (!isset($_SESSION['loggedin']) || $_SESSION['id'] != true) {
    include 'controller/functions.php';
?>


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
<?php
}else{
    header("location: index.php");
    exit();

}

?>

<?php
include 'views/footer.php';

?>

<script>
    const toggle = document.querySelector('#togglePassword i');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function() {
        if (password.type === "password") {
            password.type = 'text';
            this.classList.remove('bi-eye-slash');
            this.classList.add('bi-eye');
        } else {
            password.type = 'password';
            this.classList.remove('bi-eye');
            this.classList.add('bi-eye-slash');
        }
    });
</script>