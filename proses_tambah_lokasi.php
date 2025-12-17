<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$nama = $_POST['nama_lokasi'] ?? '';
$lat = $_POST['lat'] ?? '';
$lng = $_POST['lng'] ?? '';
$radius = $_POST['radius'] ?? '';

if ($nama === '' || $lat === '' || $lng === '' || $radius === '') {
    die("Input tidak lengkap.");
}

$stmt = $conn->prepare("INSERT INTO lokasi_presensi (nama_lokasi, lat, lng, radius) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sddi", $nama, $lat, $lng, $radius);

if ($stmt->execute()) {
    header("Location: list_lokasi.php?added=1");
} else {
    echo "Gagal menyimpan lokasi: " . $stmt->error;
}
