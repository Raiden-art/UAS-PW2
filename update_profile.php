<?php
session_start();
include 'db_connection.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Proses upload foto profil
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if ($file_size <= 2000000 && in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            $new_file_name = uniqid() . '_' . $file_name;
            move_uploaded_file($file_tmp, "uploads/" . $new_file_name);

            // Update nama file di database
            $update_picture_sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($update_picture_sql);
            $stmt->bind_param("si", $new_file_name, $user_id);
            $stmt->execute();
        }
    }

    // Update data lainnya
    $sql = "UPDATE users SET fullname = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Gagal memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Update Profil</title>
</head>
<body>
    <div class="form-container">
        <h2>Update Profil</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="fullname">Nama Lengkap:</label>
            <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="phone">Telepon:</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>

            <label for="address">Alamat:</label>
            <textarea name="address" required><?php echo $user['address']; ?></textarea>

            <label for="profile_picture">Foto Profil:</label>
            <input type="file" name="profile_picture">

            <button type="submit">perbarui
                
 