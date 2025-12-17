<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Tambah Lokasi Presensi</title>
<link rel="stylesheet" href="css/t_lokasi.css">
</head>
<body>
<div class="card">
    <h2>Tambah Lokasi Presensi</h2>

    <form action="proses_tambah_lokasi.php" method="POST">

        <label>Nama Lokasi:</label><br>
        <input type="text" name="nama_lokasi" required><br><br>

        <label>Latitude:</label><br>
        <input type="text" name="lat" required><br><br>

        <label>Longitude:</label><br>
        <input type="text" name="lng" required><br><br>

        <label>Radius (meter):</label><br>
        <input type="number" name="radius" value="100" required><br><br>

        <button type="submit">Simpan</button>
        <a href="admin.php" style="margin-left:8px;">Kembali</a>
    </form>
</div>
</body>
</html>
