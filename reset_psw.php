<?php
$page = 'RESET PASSWORD';
include 'views/header.php';

?>

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

<?php
include 'views/footer.php';

?>

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