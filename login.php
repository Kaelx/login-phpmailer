<?php
$page = 'LOGIN';
include 'views/header.php';
?>

<?php

if (!isset($_SESSION['loggedin']) || $_SESSION['id'] != true) {
?>
<nav class="navbar navbar-expand-lg navbar-light">
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
        <div class="col-md-6">
            <div id="myAlert" class="alert alert-info" role="alert">
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
                                    <input type="email" id="email_address" class="form-control" name="email" required autofocus placeholder="Enter your email">
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




    $(document).ready(function() {
        if (!localStorage.getItem('alertShown')) {
            setTimeout(function() {
                $("#myAlert").fadeOut('slow', function() {
                    localStorage.setItem('alertShown', 'true');
                });
            }, 5000);
        } else {
            $("#myAlert").hide();
        }
    });

    window.onbeforeunload = function() {
        localStorage.clear();
    };
</script>