<?php 
session_start();
include 'controller/config.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



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
    $mail->Subject="New verification code";
    $mail->Body="<p>Dear user, </p> <h3>Your new verification OTP code is $otp <br></h3>";

    return $mail->send();
}

if(isset($_POST["verify"])){
    if (isset($_SESSION['otp'], $_SESSION['mail'])) {
        $otp = $_SESSION['otp'];
        $email = $_SESSION['mail'];
        $otp_code = $_POST['otp_code'];

        if($otp != $otp_code){
            alert("Invalid OTP code");
        } else {
            $stmt = $connect->prepare("UPDATE login SET status = 1 WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            if($stmt->affected_rows > 0){
                alert("Verify account done, you may sign in now", "index.php");
            }
        }
    } else {
        alert("Session variables not set. Please try again.");
    }
}

if(isset($_POST["resend"])){
    if (isset($_SESSION['otp'], $_SESSION['mail'])) {
        $newOtp = rand(100000, 999999);
        $_SESSION['otp'] = $newOtp;
        $email = $_SESSION['mail'];

        if(!sendOTP($email, $newOtp)){
            alert("Failed to resend OTP. Please try again.");
        } else {
            alert("New OTP sent successfully.");
        }
    } else {
        alert("Session variables not set. Please try again.");
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>Verification</title>
</head>
<body>

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
                                    <input type="number" id="otp" class="form-control" name="otp_code"  autofocus placeholder="Enter OTP code">
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success mr-2" value="Verify" name="verify">Verify</button>
                                <button type="submit" class="btn btn-secondary" name="resend"> Resend OTP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>