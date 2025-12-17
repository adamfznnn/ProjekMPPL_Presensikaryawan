<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$lokasi = $conn->query("SELECT * FROM lokasi_presensi ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Daftar Lokasi Presensi</title>
<link rel="stylesheet" href="css/lokasi.css">
</head>
</head>
<body>
<div class="card">
    <h2>Daftar Lokasi Presensi</h2>

    <?php if (isset($_GET['added'])): ?>
        <p class="info">Lokasi berhasil ditambahkan!</p>
    <?php endif; ?>

    <a href="tambah_lokasi.php" class="btn-primary">✚ Tambah Lokasi Baru</a>
    <br><br>

    <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse;">
        <tr>
            <th>ID</th>
            <th>Nama Lokasi</th>
            <th>Lat</th>
            <th>Lng</th>
            <th>Radius (m)</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = $lokasi->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nama_lokasi']) ?></td>
            <td><?= $row['lat'] ?></td>
            <td><?= $row['lng'] ?></td>
            <td><?= $row['radius'] ?></td>
            <td>
                <a href="hapus_lokasi.php?id=<?= $row['id'] ?>" class="btn-danger" onclick="return confirm('Hapus lokasi?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<br>
    <a href="admin.php" class="btn-back">Kembali</a>
</div>
</body>
</html>
