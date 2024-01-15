<?php
session_start();
include 'controller/config.php';


function alert($message, $redirect = null)
{
    echo "<script>alert('$message');";
    if ($redirect) {
        echo "window.location.replace('$redirect');";
    }
    echo "</script>";
}

if (isset($_POST["verify"])) {
    if (isset($_SESSION['otp'], $_SESSION['mail'])) {
        $otp = $_SESSION['otp'];
        $email = $_SESSION['mail'];
        $otp_code = $_POST['otp_code'];
        $password = $_POST['pass'];
        $confirm_password = $_POST['con-pass'];

        if ($otp != $otp_code) {
            alert("Invalid OTP code. Please try again.");
        } else {
            if ($password === $confirm_password) {
                $hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $connect->prepare("UPDATE login SET password=? WHERE email=?");
                $stmt->bind_param("ss", $hash, $email);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    unset($_SESSION['otp']);
                    unset($_SESSION['mail']);
                    session_destroy();

                    alert("Your password has been successfully reset", "index.php");
                } else {
                    alert("Failed to update password. Please try again.");
                }
            } else {
                alert("Passwords do not match. Please try again.");
            }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <title>Password Reset Form</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Password Reset Form</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Reset Your Password</div>
                    <div class="card-body">
                        <form action="#" method="POST" name="resetPassword">
                            <div class="mb-3">
                                <label for="otp" class="form-label">Enter OTP Code</label>
                                <input type="number" id="otp" name="otp_code" class="form-control" autofocus required placeholder="Enter OTP code">
                            </div>
                            <div class="mb-3">
                                <label for="psw" class="form-label">Enter Password</label>
                                <input type="password" id="psw" name="pass" class="form-control" required placeholder="Enter new password">
                            </div>
                            <div class="mb-3">
                                <label for="con-psw" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="con-psw" name="con-pass" class="form-control" required placeholder="Confirm new password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="verify" class="btn btn-success mt-3">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>

</html>

<script>
    const toggleButton = document.getElementById('togglePassword');
    const password = document.getElementById('psw');
    const confirm_password = document.getElementById('con-psw');

    toggleButton.addEventListener('click', function() {
        const type = password.type === "password" ? 'text' : 'password';
        password.type = type;
        confirm_password.type = type;

        const icon = this.querySelector('i');
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
</script>