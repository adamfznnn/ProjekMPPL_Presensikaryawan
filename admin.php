<?php
// admin.php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// ambil username dari session (jika login menyimpannya)
$username = $_SESSION['username'] ?? '';

$filter_date = $_GET['date'] ?? '';

$sql = "
SELECT p.*, 
       u.username,
       l.nama_lokasi
FROM presensi p
JOIN users u ON p.user_id = u.id
LEFT JOIN lokasi_presensi l ON p.lokasi_id = l.id
";

if ($filter_date) {
    $sql .= " WHERE p.tanggal = ?";
}

$sql .= " ORDER BY p.tanggal DESC, u.username ASC";

$stmt = $conn->prepare($sql);

if ($filter_date) {
    $stmt->bind_param("s", $filter_date);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Rekap Presensi</title>
<link rel="stylesheet" href="css/nav-icons.css">
<link rel="stylesheet" href="css/admin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
</head>
<body>

<div class="admin-navbar">
    <div class="nav-left">
        <img src="assets\LOGO_UPN.png" class="logo">
    </div>

    <div class="nav-center">
        <a href="admin.php"><i class="fa fa-home"></i> Home</a>
        <a href="list_izin.php"><i class="fa fa-calendar-check"></i> Ketidakhadiran</a>
        <a href="list_karyawan.php"><i class="fa fa-file-alt"></i> List Karyawan</a>
        <a href="list_lokasi.php"><i class="fa fa-file-alt"></i> Cabang</a>
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="nav-right">
        <i class="fa fa-bell icon notif"></i>

        <div class="profile-box">
            <!-- <img src="profile.jpg" class="profile-img">-->
            <div>
                <p class="profile-name"><?=htmlspecialchars($username)?></p>
                <p class="profile-role"><?= htmlspecialchars($_SESSION['role']) ?></p>
            </div>
        </div>
    </div>
</div>
<br>

<div class="admin-card">
  <h2>Rekap Presensi (Admin)</h2>

  <form method="GET" style="margin-bottom:10px;">
    <label>Filter tanggal: <input type="date" name="date" value="<?=htmlspecialchars($filter_date)?>"></label>
    <button class=" btn-primary" type="submit">Filter</button>
    <a href="admin.php" class="btn-danger" style="margin-left:10px;">Reset</a>
  </form>

  <div class="admin-menu-box">
    <a href="add_karyawan.php">➕ Tambah Karyawan</a>
    <a href="tambah_lokasi.php">➕ Tambah Lokasi Presensi</a>
</div>


  <table border="admin-table" cellpadding="6" style="width:100%; border-collapse: collapse;">
    <tr>
      <th>No</th>
      <th>Username</th>
      <th>Lokasi Presensi</th>
      <th>Tanggal</th>
      <th>Jam Masuk</th>
      <th>Jam Keluar</th>
      <th>Maps</th>
    </tr>

    <?php $i=1; while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= $row['nama_lokasi'] ? htmlspecialchars($row['nama_lokasi']) : '-' ?></td>
      <td><?= htmlspecialchars($row['tanggal']) ?></td>
      <td><?= $row['jam_masuk'] ?: '-' ?></td>
      <td><?= $row['jam_keluar'] ?: '-' ?></td>
      
      <td>
        <?php if ($row['lat'] && $row['lng']): ?>
          <a href="https://www.google.com/maps?q=<?=htmlspecialchars($row['lat'])?>,<?=htmlspecialchars($row['lng'])?>" target="_blank">Lihat</a>
        <?php else: ?>
          -
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

</div>
</body>
</html>
