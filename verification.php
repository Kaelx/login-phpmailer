<?php
$page = 'VERIFICATION';
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
    $mail->Subject = "New verification code";
    $mail->Body = "<p>Dear user, </p> <h3>Your new verification OTP code is $otp <br></h3>";

    return $mail->send();
}

include 'controller/functions.php';

?>


<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">Verification Account</a>
    </div>
</nav>

<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card">
                    <div class="card-header">Verification Account</div>
                    <div class="card-body">
                        <form action="#" method="POST">
                            <div class="form-group row">
                                <label for="otp" class="col-md-4 col-form-label text-md-right">OTP Code</label>
                                <div class="col-md-6">
                                    <input type="number" id="otp" class="form-control" name="otp_code" autofocus placeholder="Enter OTP code">
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success mr-2" value="Verify" name="verify-account">Verify</button>
                                <button type="submit" class="btn btn-secondary" name="resend"> Resend OTP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include 'views/footer.php';

?>