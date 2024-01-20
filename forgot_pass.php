<?php
$page = 'FORGOT PASSWORD'; 
include 'views/header.php';
include 'controller/credentials.php'; //create a file name credentials.php and put your email($mailUsername = 'youremail@gmail.com') and password($mailPassword = '16 keys') for sending OTP

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
    $mail->Body = "<p>Dear user, </p> <h3>Your forgot password OTP code is $otp <br></h3>";

    return $mail->send();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['id'] != true) {
    include 'controller/functions.php';
?>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Forgot Password</a>
        </div>
    </nav>

    <main class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Password Recovery</div>
                        <div class="card-body">
                            <form action="#" method="POST" name="recover_psw">
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Email</label>
                                    <div class="col-md-6">
                                        <input type="email" id="email_address" class="form-control" name="email" required autofocus placeholder="Enter your email">
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success mr-2" name="recover">Recover</button>
                                    <a href="index.php" class="btn btn-primary">Back to login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
} else {
    header("location: index.php");
    exit();
}

include 'views/footer.php';
?>