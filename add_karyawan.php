<?php
session_start();
include "db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];

    $sql = "INSERT INTO users (username, password, role, nama) VALUES ('$username', '$password', 'karyawan', '$nama')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Karyawan baru berhasil ditambahkan!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan karyawan: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>
<h2>Tambah Karyawan Baru</h2><br>
<form method="POST">
    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Tambah</button>
    <a href="admin.php">Kembali</a>
</form>
</body>
</html>
