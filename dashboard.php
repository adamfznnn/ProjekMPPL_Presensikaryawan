<?php
session_start();
include "db.php";
include "functions.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'karyawan') {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$today = date('Y-m-d');

$err = '';
$info = '';

// --- ambil presensi hari ini ---
$stmt = $conn->prepare("SELECT id, jam_masuk, jam_keluar, lat, lng, lokasi_id 
                        FROM presensi 
                        WHERE user_id = ? AND tanggal = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$stmt->bind_result($pres_id, $jam_masuk, $jam_keluar, $plat, $plng, $presLokasiId);
$hasToday = $stmt->fetch();
$stmt->close();

// --- ambil daftar lokasi presensi dari admin ---
$lokasi = $conn->query("SELECT * FROM lokasi_presensi");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $lat = isset($_POST['lat']) ? floatval($_POST['lat']) : null;
    $lng = isset($_POST['lng']) ? floatval($_POST['lng']) : null;

    if ($lat === null || $lng === null) {
        $err = "Gagal mendapat lokasi. Pastikan izin GPS aktif.";
    } else {

        // ---------- CEK JARAK DENGAN SEMUA LOKASI ----------
        $lokasiValid = null;

        foreach ($lokasi as $row) {
            $jarakKm = haversine_km($row['lat'], $row['lng'], $lat, $lng);
            $jarakMeter = $jarakKm * 1000;

            if ($jarakMeter <= $row['radius']) {
                $lokasiValid = $row;
                break;
            }
        }

        if (!$lokasiValid) {
            $err = "Anda berada di luar area presensi.";
        } else {
            // ---- Lokasi valid, lakukan Masuk / Keluar ----
            if ($action === 'masuk') {
                if ($hasToday && $jam_masuk) {
                    $err = "Anda sudah Check In hari ini.";
                } else {
                    $stmt = $conn->prepare("
                        INSERT INTO presensi (user_id, tanggal, jam_masuk, lat, lng, lokasi_id)
                        VALUES (?, ?, NOW(), ?, ?, ?)
                    ");
                    $stmt->bind_param("isddi", $user_id, $today, $lat, $lng, $lokasiValid['id']);

                    if ($stmt->execute()) $info = "Check In berhasil di lokasi: ".$lokasiValid['nama_lokasi'];
                    else $err = "Gagal Check In: ".$stmt->error;

                    $stmt->close();
                }
            }

            elseif ($action === 'keluar') {
                if (!$hasToday || !$jam_masuk) {
                    $err = "Belum Check In hari ini.";
                } elseif ($jam_keluar) {
                    $err = "Anda sudah Check Out hari ini.";
                } else {
                    $stmt = $conn->prepare("
                        UPDATE presensi 
                        SET jam_keluar = NOW(), lat = ?, lng = ? 
                        WHERE id = ?
                    ");
                    $stmt->bind_param("ddi", $lat, $lng, $pres_id);

                    if ($stmt->execute()) $info = "Check Out berhasil.";
                    else $err = "Gagal Check Out: ".$stmt->error;

                    $stmt->close();
                }
            }
        }
    }

    // refresh data presensi hari ini
    $stmt = $conn->prepare("SELECT id, jam_masuk, jam_keluar, lat, lng, lokasi_id 
                            FROM presensi WHERE user_id = ? AND tanggal = ?");
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $stmt->bind_result($pres_id, $jam_masuk, $jam_keluar, $plat, $plng, $presLokasiId);
    $hasToday = $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard Presensi</title>
<link rel="stylesheet" href="css/nav-icons.css">
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="container">

    <a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i>
    </a>

<div class="navbar">
    <div class="nav-left">
        <img src="assets\LOGO_UPN.png" class="logo">
    </div>

    <div class="nav-center">
        <a href="dashboard.php"><i class="fa fa-home"></i> Home</a>
        <a href="ajukan_izin.php"><i class="fa fa-calendar-check"></i> Ajukan Izin</a>
        <a href="riwayat_izin.php"><i class="fa fa-file-alt"></i> Riwayat Izin</a>
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
    <h2>Halo, <?=htmlspecialchars($username)?></h2>
    <p class="tanggal">Tanggal: <?=date('d-m-Y')?></p>

    <div class="grid-box">

        <!-- CARD PRESENSI MASUK -->
        <div class="card-presensi">
            <h3>Presensi Masuk</h3>
            <p><?= $jam_masuk ? $jam_masuk : '-' ?></p>

            <?php if(!$jam_masuk): ?>
                <button onclick="doPresensi('masuk')" class="btn-primary">CHECK IN</button>
            <?php else: ?>
                <button disabled class="btn-disabled">Sudah Check In</button>
            <?php endif; ?>
        </div>

        <!-- CARD PRESENSI MASUK -->
        <div class="card-presensi">
    <h3>Presensi Keluar</h3>

    <?php if(!$jam_masuk): ?>
        <!-- Belum check in -->
        <p class="info-text">Belum waktunya pulang</p>

    <?php elseif(!$jam_keluar): ?>
        <!-- Sudah check in, belum check out -->
        <button onclick="doPresensi('keluar')" class="btn-danger">CHECK OUT</button>

    <?php else: ?>
        <!-- Sudah check out -->
        <p><?= $jam_keluar ?></p>
        <button disabled class="btn-disabled">Sudah Check Out</button>

    <?php endif; ?>

</div>



    </div>
</div>

 <form id="formPresensi" method="POST">
    <input type="hidden" name="lat" id="lat">
    <input type="hidden" name="lng" id="lng">
    <input type="hidden" name="action" id="action">
  </form>

<script>
function doPresensi(action) {
  if (!navigator.geolocation) {
    alert('Geolocation tidak didukung oleh browser Anda.');
    return;
  }
  document.getElementById('action').value = action;
  navigator.geolocation.getCurrentPosition(function(position) {
    document.getElementById('lat').value = position.coords.latitude;
    document.getElementById('lng').value = position.coords.longitude;
    document.getElementById('formPresensi').submit();
  }, function(err) {
    alert('Gagal mendapatkan lokasi: ' + err.message);
  }, { enableHighAccuracy: true, maximumAge: 0, timeout: 10000 });
}
</script>
</body>
</html>
