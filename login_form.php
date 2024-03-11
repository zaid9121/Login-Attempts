<?php
session_start();
$servername = "localhost";
$database = "tutorials";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password, $database);
$msg = "";
if (isset($_POST['submit'])) {
    //echo "<per>";
    // print_r($_POST);
    $ip_address = getUserIbAddr();
    $time = time() - 30;

    $check_attmpt = mysqli_fetch_assoc(mysqli_query($conn, "select count(*) as total_count from 
    attempt_count where time_count>$time and ip_address='$ip_address'"));

    $total_count = $check_attmpt['total_count'];
    //print_r($check_attmpt);
    if ($total_count == 3) {
        $msg = "your account blocked. please try after 30 sec";
    } else {

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = ("select * from user_login where username='$username' and password='$password'");
        $user_row = mysqli_query($conn, $sql);
        if (mysqli_num_rows($user_row) > 0) {
            $res = mysqli_fetch_assoc($user_row);
            $_SESSION['USER_ID'] = $res['id'];
            // Delete Date after successflly
            mysqli_query($conn, "delete from attempt_count where ip_address='$ip_address'");
            
            header("location: dashboard.php");
           
        } else {
            $total_count++;
            $time_remain = 3 - $total_count;
            $time = time();

            if ($time_remain == 0) {
                $msg = "your account blocked. please try after 30 sec";
            } else {
                $msg = "please enter valid login details." . $time_remain . "attempts remains";
            }
            // Date insert into attempt_count table 
            mysqli_query($conn, "INSERT INTO attempt_count (
                ip_address, time_count) VALUES ('$ip_address'
                ,'$time')");

            // Error Message display after enter wrong details    

            //$msg = "please enter valid login details.";
        }
    }
}

function getUserIbAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
<style>
 
</style>

</head>

<body>
    <form action="" method="post" class="content">

        <input type="text" name="username" placeholder="Inter Username"><br>

        <input type="password" name="password" placeholder=" Inter Password"><br>

        <button type="submit" name="submit">Login</button><br><br>
        <div class="error" style="color: red;"><?php echo $msg ?></div>

    </form>
</body>

</html>