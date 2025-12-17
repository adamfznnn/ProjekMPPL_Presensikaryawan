<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM lokasi_presensi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list_lokasi.php");
