<?php
session_start();
include "db.php";

if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

$id = $_GET['id'];
$act = $_GET['act'];

$status = ($act == 'approve') ? 'approved' : 'rejected';

$stmt = $conn->prepare("
    UPDATE izin 
    SET status = ?, approved_by = ?, approved_at = NOW() 
    WHERE id = ?
");
$stmt->bind_param("sii", $status, $_SESSION['user_id'], $id);
$stmt->execute();
$stmt->close();

header("Location: list_izin.php");
?>
