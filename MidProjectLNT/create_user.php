<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $bio = mysqli_real_escape_string($conn, trim($_POST['bio']));

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($bio)) {
        die("Semua field harus diisi!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format email tidak valid!");
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        die("User dengan email ini sudah terdaftar!");
    }
    
    $photo = $_FILES['photo'];
    $filename = "default.png"; 
    
    if ($photo['error'] == 0) {
        $target_dir = "uploads/";
        $filename = uniqid() . '_' . basename($photo['name']);
        $target_file = $target_dir . $filename;

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedExtensions)) {
            die("Format gambar tidak didukung!");
        }

        if (!move_uploaded_file($photo['tmp_name'], $target_file)) {
            die("Gagal mengupload file!");
        }
    }
    
    $query = "INSERT INTO users (first_name, last_name, email, password, bio, photo) VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$bio', '$filename')";
    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun</title>
    <link rel="stylesheet" href="create_user.css">
</head>
<body>
    <div class="form-container">
        <h2>Buat Akun</h2>
        <form action="create_user.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="first_name" placeholder="Nama Depan" required>
            <input type="text" name="last_name" placeholder="Nama Belakang" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <textarea name="bio" placeholder="Tulis bio singkat Anda" required></textarea>
            <div class="photo-preview">
                <input type="file" name="photo" accept="image/*">
            </div>
            <button type="submit">Daftar</button>
        </form>
        <a href="dashboard.php" class="back-btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>