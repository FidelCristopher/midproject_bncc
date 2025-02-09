<?php
session_start();
include "db.php";

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']); 
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('User tidak ditemukan!'); window.location='dashboard.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

    if (!empty($_FILES['photo']['name'])) {
        $photo_name = basename($_FILES['photo']['name']);
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_size = $_FILES['photo']['size'];
        $photo_ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (!in_array($photo_ext, $allowed_ext) || $photo_size > 2 * 1024 * 1024) {
            echo "<script>alert('File harus JPG/PNG dan maksimal 2MB!');</script>";
        } else {
            $new_photo_name = "user_" . time() . ".$photo_ext";
            move_uploaded_file($photo_tmp, "uploads/$new_photo_name");

            $query = "UPDATE users SET first_name=?, last_name=?, bio=?, photo=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $bio, $new_photo_name, $id);
        }
    } else {
        $query = "UPDATE users SET first_name=?, last_name=?, bio=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $first_name, $last_name, $bio, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui user!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profil</title>
    <link rel="stylesheet" href="update_user.css">
</head>
<body>

<div class="container">
    <h2>Update Profil</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Depan:</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>

        <label>Nama Belakang:</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>

        <label>Bio:</label>
        <textarea name="bio"><?= htmlspecialchars($user['bio']); ?></textarea>

        <label>Foto Profil (Opsional):</label>
        <input type="file" name="photo" accept="image/*">

        <?php if (!empty($user['photo']) && file_exists("uploads/" . $user['photo'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['photo']); ?>" alt="Foto Profil" width="100">
        <?php endif; ?>

        <button type="submit" name="update">Update</button>
        <a href="dashboard.php" class="cancel-btn">Batal</a>
    </form>
</div>

</body>
</html>
