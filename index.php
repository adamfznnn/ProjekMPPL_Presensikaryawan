<?php
// index.php
session_start();
include "db.php";

// Jika sudah login → arahkan ke dashboard/admin
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? 'admin.php' : 'dashboard.php'));
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $msg = "Isi username & password.";
    } else {

        // --- FIX: inisialisasi variabel agar tidak NULL ---
        $id = 0;
        $hash = "";
        $role = "";

        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $hash, $role);

        if ($stmt->fetch()) {
            if (password_verify($password, $hash)) {

                // Set session
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                header("Location: " . ($role === 'admin' ? 'admin.php' : 'dashboard.php'));
                exit;
            } else {
                $msg = "Username atau password salah.";
            }
        } else {
            $msg = "Username atau password salah.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login Presensi</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="card">
    <h2>Login Presensi</h2>

    <?php if ($msg): ?>
        <p class="error"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
