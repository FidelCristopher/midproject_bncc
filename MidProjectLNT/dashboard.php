<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$admin_id = $_SESSION['user']['id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = "SELECT * FROM users WHERE id != '$admin_id'";
if (!empty($search)) {
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR email LIKE '%$search%')";
}

$users = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<header>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profil</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2>Dashboard</h2>

    <!-- Search Bar -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Cari user..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($users)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <?php 
                        $photoPath = !empty($row['photo']) && file_exists("uploads/" . $row['photo']) 
                            ? "uploads/" . htmlspecialchars($row['photo']) 
                            : "default.png"; 
                    ?>
                    <img src="<?= $photoPath; ?>" alt="Foto Profil" width="50">
                </td>
                <td><?= htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td>
                    <a href="view_user.php?id=<?= $row['id']; ?>" class="btn btn-view">👁 View</a>
                    <a href="update_user.php?id=<?= $row['id']; ?>" class="btn btn-edit">✏ Edit</a>
                    <a href="delete_user.php?id=<?= $row['id']; ?>" class="btn btn-delete" 
                        onclick="return confirm('Yakin ingin menghapus user ini?');">🗑 Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="create_user.php" class="btn btn-add">➕ Tambah User</a>
</main>

<footer>
    <p>Hak Cipta &copy; 2025 | Sosial Media: <a href="#">Instagram</a> | <a href="#">LinkedIn</a></p>
</footer>

</body>
</html>
