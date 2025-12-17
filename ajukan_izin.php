<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $ket = $_POST['keterangan'];

    // upload file
    $fileName = null;
    if (!empty($_FILES['bukti']['name'])) {
        $fileName = time() . "_" . $_FILES['bukti']['name'];
        move_uploaded_file($_FILES['bukti']['tmp_name'], "uploads/izin/" . $fileName);
    }

    $stmt = $conn->prepare("
        INSERT INTO izin (user_id, tanggal, jenis_izin, keterangan, file_bukti)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issss", $user_id, $tanggal, $jenis, $ket, $fileName);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Izin berhasil diajukan'); location.href='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajukan Izin</title>
    <link rel="stylesheet" href="css/ajukan_izin.css">
</head>
<body>
<h2>Ajukan Izin</h2>
<form method="POST" enctype="multipart/form-data">

    <label>Tanggal Izin</label><br>
    <input type="date" name="tanggal" required><br><br>

    <label>Jenis Izin</label><br>
    <select name="jenis" required>
        <option value="sakit">Sakit</option>
        <option value="cuti">Cuti</option>
        <option value="izin">Izin Pribadi</option>
    </select><br><br>

    <label>Keterangan</label><br>
    <textarea name="keterangan"></textarea><br><br>

    <label>Upload Bukti (opsional)</label><br>
    <input type="file" name="bukti"><br><br>

    <button type="submit">Kirim Izin</button>
    <a href="dashboard.php" class="btn-back">← Kembali</a>
</form>
</body>
</html>
