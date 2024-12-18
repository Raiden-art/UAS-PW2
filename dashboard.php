<?php
session_start();
include 'db_connection.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head link rel="stylesheet" href="css/style.css">
>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard karyawan</title>
</head>
<body>
<img src="uploads/<?php echo $profile_pic; ?>" alt="Profile Picture" width="15%" height="auto">

    <div class="dashboard">
        <h2>Data Pribadi Anda</h2>
        <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Foto Profil" width="15%" height="auto">
        <p><strong>Nama Lengkap:</strong> <?php echo $user['fullname']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Telepon:</strong> <?php echo $user['phone']; ?></p>
        <p><strong>Alamat:</strong> <?php echo $user['address']; ?></p>

        <a href="update_profile.php">Edit Profil</a>
        <a href="logout.php">Logout</a>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <h3>Admin Panel</h3>
            <a href="manage_users.php">Kelola Pengguna</a>
        <?php endif; ?>
    </div>
</body>
</html>
