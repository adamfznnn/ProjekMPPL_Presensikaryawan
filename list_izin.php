<?php
include "db.php";
$result = $conn->query("
    SELECT izin.*, users.username 
    FROM izin 
    JOIN users ON izin.user_id = users.id
    ORDER BY izin.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard Presensi</title>
<link rel="stylesheet" href="css/list-izin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<h2>Daftar Pengajuan Izin</h2>
<a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
    </a>
<table>
<tr>
    <th>Karyawan</th>
    <th>Tanggal</th>
    <th>Jenis</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
<?php while ($row = $result->fetch_assoc()) : ?>
<tr>
    <td><?= $row['username'] ?></td>
    <td><?= $row['tanggal'] ?></td>
    <td><?= $row['jenis_izin'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <a href="proses_izin.php?id=<?= $row['id'] ?>&act=approve" class="btn-primary">Approve</a> |
        <a href="proses_izin.php?id=<?= $row['id'] ?>&act=reject" class="btn-danger">Reject</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
<br>
<br>
<a href="admin.php" class="btn-back">Kembali</a>
</body>
</html>
