<?php

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
        alert("Email does not exist. Please register first!", "register.php");
    }else if (password_verify($password, $user["password"])) {
        if ($user["status"] == 0) {
            alert("Please verify email account before login.","verification.php");
            $_SESSION['otp'] = $user["id"];
            $_SESSION['mail'] = $email;
        }else{
            alert("Login successfuly!", "index.php");
            $_SESSION['id'] = $user["id"];
            $_SESSION['loggedin'] = true;
        }
    } else {
        alert("Email or password invalid, please try again.");
    }
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

            if(!sendOTP($email, $otp, $mailUsername, $mailPassword)){
                alert("Register Failed, Invalid Email");
            } else {
                alert("Register Successfully, OTP sent to $email", 'verification.php');
            }
        }
    }
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

        if(!sendOTP($email, $otp, $mailUsername, $mailPassword)){
            alert("Invalid Email");
        } else {
            alert("To recover you account, check the OTP sent to $email", 'reset_psw.php');
        }
    }
}



if(isset($_POST["verify-account"])){
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
                unset($_SESSION['otp']);
                unset($_SESSION['mail']);
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

        if(!sendOTP($email, $newOtp, $mailUsername, $mailPassword)){
            alert("Failed to resend OTP. Please try again.");
        } else {
            alert("New OTP sent successfully.");
        }
    } else {
        alert("Session variables not set. Please try again.");
    }
}

?>