<?php

session_start();
$conn = mysqli_connect("localhost","root","","tutorials");

if(!isset($_SESSION['USER_ID'])) {
   header("location:login_form.php");
   die();
    echo "<script> location.replace('login_form.php') </script>";
}


?>
<h1><?php echo "Welcome" . $_SESSION['USER_ID']; ?></h1>
<a href="logout.php">Logout</a>