<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";


    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Email atau password salah!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loginPage.css"> 
</head>
<body>

<form class="login" method="POST" action="">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>


</body>
</html>
