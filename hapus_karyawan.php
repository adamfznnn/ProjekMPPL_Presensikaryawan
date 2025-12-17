<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Cegah admin menghapus dirinya sendiri
if ($id == $_SESSION['user_id']) {
    die("Tidak bisa menghapus akun sendiri.");
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list_karyawan.php");
