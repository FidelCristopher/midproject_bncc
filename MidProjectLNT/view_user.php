<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID pengguna tidak valid!");
}

$user_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT first_name, last_name, email, bio, photo FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Pengguna tidak ditemukan!");
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="view_user.css">
</head>
<body>

<div class="profile-container">
    <h2>Profil Pengguna</h2>
    <img src="<?= !empty($user['photo']) && file_exists("uploads/" . $user['photo']) ? "uploads/" . htmlspecialchars($user['photo']) : "default.png"; ?>" alt="Foto Profil" class="profile-photo">
    
    <p><strong>Nama:</strong> <?= htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
    <p><strong>Bio:</strong> <?= !empty($user['bio']) ? htmlspecialchars($user['bio']) : "Tidak ada bio"; ?></p>
    
    <a href="dashboard.php" class="btn">⬅ Kembali ke Dashboard</a>
</div>

</body>
</html>
