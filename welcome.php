<?php
session_start();
include 'controller/config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['id'] == true) {
    $id = $_SESSION['id'];
    $query = "SELECT fname, lname FROM login WHERE id = $id";

    if ($result = $connect->query($query)) {
        $user = $result->fetch_assoc();
    }

}else{
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to My Website</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-5">
            <h1 class="display-4">Welcome to My Website <?php echo $user['fname'].' '.$user['lname']?>!</h1>
            <p class="lead">Thank you for testing my login function with phpmailer!</p>
        </div>
        <div class="text-right">
            <a href="controller/logout.php" class="btn btn-primary btn-lg">Logout</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>