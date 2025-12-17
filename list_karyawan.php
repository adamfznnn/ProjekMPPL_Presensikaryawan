<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Ambil semua user
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>List Karyawan</title>
<link rel="stylesheet" href="css/list_karyawan.css">
</head>
<body>
<div class="card">
    <h2>Daftar Karyawan</h2>

    <a href="add_karyawan.php">➕ Tambah Karyawan</a>
    <br><br>

    <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse;">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>

        <?php while ($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nama'] ?: '-') ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td>
                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                    <a href="hapus_karyawan.php?id=<?= $u['id'] ?>" onclick="return confirm('Hapus karyawan ini?')">Hapus</a>
                    |
                    <a href="ubah_role.php?id=<?= $u['id'] ?>">Ubah Role</a>
                <?php else: ?>
                    (akun anda)
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

    <p style="margin-top:12px;"><a href="admin.php">Kembali</a></p>
</div>
</body>
</html>
