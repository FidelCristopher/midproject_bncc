<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="profile-container">
    <h2>Profile Admin</h2>
    <p><strong>Nama:</strong> <?= $user['first_name'] . " " . $user['last_name']; ?></p>
    <p><strong>Email:</strong> <?= $user['email']; ?></p>
    <p><strong>Bio:</strong> <?= $user['bio']; ?></p>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
