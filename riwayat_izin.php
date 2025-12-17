<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$result = $conn->prepare("
    SELECT tanggal, jenis_izin, keterangan, status, file_bukti, created_at 
    FROM izin 
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$result->bind_param("i", $user_id);
$result->execute();
$res = $result->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Riwayat Izin</title>
<link rel="stylesheet" href="css/riwayat.css">
<style>
.status-box {
    padding: 5px 10px;
    border-radius: 6px;
    color: #fff;
    font-weight: bold;
}
.pending { background: orange; }
.approved { background: green; }
.rejected { background: red; }
</style>
</head>
<body>

<h2>Riwayat Pengajuan Izin</h2>

<table border="1" cellpadding="8" width="100%">
<tr>
    <th>Tanggal</th>
    <th>Jenis</th>
    <th>Keterangan</th>
    <th>Status</th>
    <th>Bukti</th>
</tr>

<?php while ($row = $res->fetch_assoc()) : ?>
<tr>
    <td><?= $row['tanggal'] ?></td>
    <td><?= ucfirst($row['jenis_izin']) ?></td>

    <td><?= $row['keterangan'] ?: '-' ?></td>

    <td>
        <span class="status-box <?= $row['status'] ?>">
            <?= strtoupper($row['status']) ?>
        </span>
    </td>

    <td>
        <?php if ($row['file_bukti']) : ?>
            <a href="uploads/izin/<?= $row['file_bukti'] ?>" target="_blank">Lihat</a>
        <?php else : ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</table>

<br>
<a href="dashboard.php" class="btn-back">← Kembali</a>

</body>
</html>
