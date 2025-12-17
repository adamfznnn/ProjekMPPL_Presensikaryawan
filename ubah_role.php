<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Tidak boleh ubah role diri sendiri
if ($id == $_SESSION['user_id']) {
    die("Tidak bisa mengubah role akun sendiri.");
}

// Ambil user
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if (!$role) {
    die("User tidak ditemukan.");
}

// Tentukan role baru
$newRole = ($role === 'admin') ? 'karyawan' : 'admin';

// Update role
$stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("si", $newRole, $id);
$stmt->execute();

header("Location: list_karyawan.php");
