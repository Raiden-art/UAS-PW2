<?php
$servername = "localhost";
$username = "root"; // Username default untuk XAMPP
$password = "";     // Password default untuk XAMPP (kosong)
$dbname = "karyawan_db"; // Nama database

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

<?php
$koneksi = mysqli_connect("localhost","root","", "karyawan_db");

if (isset($_POST['proses'])){

$direktori = "profile_picture/";
$file_name=$_FILES['namafile']['name'];
move_uploaded_file($_FILES['namafile']['tmp_name'],$direktori.file_name);

mysqli_query($koneksi, "insert into profile_picture set file= '$file_name'");
}
?>