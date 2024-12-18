<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    // Proses upload foto profil
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if ($file_size <= 2000000 && in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            $profile_picture = uniqid() . '_' . $file_name;
            move_uploaded_file($file_tmp, "uploads/" . $profile_picture);
        }
    }

    // Menyimpan data ke database
    $sql = "INSERT INTO users (fullname, email, password, phone, address, profile_picture, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $fullname, $email, $password, $phone, $address, $profile_picture, $role);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Gagal mendaftarkan pengguna baru.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Registerasikan data anda sebagai karyawan</title>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="fullname">Nama Lengkap:</label>
            <input type="text" name="fullname" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="phone">Telepon:</label>
            <input type="text" name="phone">

            <label for="address">Alamat:</label>
            <textarea name="address"></textarea>

            <label for="role">Role:</label>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <label for="profile_pic">Upload Foto Profil:</label>
            <input type="file" name="profile_pic" id="profile_pic" required>
            <button type="submit">Register</button>
        </form>
        <a href="login.php">Sudah punya akun? Login di sini</a>
    </div>
</body>
</html>
