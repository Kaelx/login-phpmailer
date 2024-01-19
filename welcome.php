<?php
include 'views/header.php';

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

<body>
    <div class="container">
        <div class="jumbotron mt-5">
            <h1 class="display-4">Welcome to My Website <?php echo $user['fname'].' '.$user['lname']?>!</h1>
            <p class="lead">Thank you for testing my login function with phpmailer!</p>
        </div>
        <div class="text-right">
            <a href="controller/logout.php" class="btn btn-danger btn-lg">Logout</a>
        </div>
    </div>
</body>

<?php
include 'views/footer.php';
?>