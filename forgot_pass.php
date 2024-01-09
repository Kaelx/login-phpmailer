<?php 
session_start();
include 'controller/config.php';
require 'phpmailer/PHPMailerAutoload.php';

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

    $mail->Username='';
    $mail->Password='';

    $mail->setFrom('sample@gmail.com', 'OTP Verification');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject="Verification code";
    $mail->Body="<p>Dear user, </p> <h3>Your forgot password OTP code is $otp <br></h3>";

    return $mail->send();
}

if(isset($_POST["recover"])){
    $email = $_POST["email"];

    $stmt = $connect->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        alert("Email does not exist.");
    } else if($user["status"] == 0){
        alert("Sorry, your account must verify first, before you recover your password !", "index.php");
    } else {
        $otp = rand(100000,999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['mail'] = $email;

        if(!sendOTP($email, $otp)){
            alert("Invalid Email");
        } else {
            alert("To recover you account, check the OTP sent to $email", 'reset_psw.php');
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
    <title>Login Form</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
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
                                    <input type="text" id="email_address" class="form-control" name="email" required autofocus placeholder="Enter your email">
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
</body>
</html>
