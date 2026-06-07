<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

$msg = ''; $msgType = '';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $verificationCode = $_POST['verification_code'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['konfirmasi_password'] ?? '';

    if (empty($verificationCode) || empty($password) || empty($confirm)) {
        $msg = 'Semua field wajib diisi.'; $msgType = 'error';
    } elseif ($verificationCode !== '123456') {
        $msg = 'Kode verifikasi salah. Gunakan 123456.'; $msgType = 'error';
    } elseif ($password !== $confirm) {
        $msg = 'Konfirmasi kata sandi tidak cocok.'; $msgType = 'error';
    } elseif (strlen($password) < 8) {
        $msg = 'Kata sandi minimal 8 karakter.'; $msgType = 'error';
    } elseif (empty($token)) {
        $msg = 'Token tidak ditemukan. Silakan minta ulang reset password.'; $msgType = 'error';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT pr.user_id FROM password_resets pr WHERE pr.token = ?");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($res)) {
            $user_id = $row['user_id'];
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $upd = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($upd, "si", $hash, $user_id);
            if (mysqli_stmt_execute($upd)) {
                $del = mysqli_prepare($conn, "DELETE FROM password_resets WHERE user_id = ?");
                mysqli_stmt_bind_param($del, "i", $user_id);
                mysqli_stmt_execute($del);

                $msg = 'Kata sandi berhasil diubah. Silakan login.'; $msgType = 'success';
            } else {
                $msg = 'Gagal memperbarui kata sandi.'; $msgType = 'error';
            }
        } else {
            $msg = 'Token tidak valid atau sudah kadaluarsa. Silakan minta ulang reset password jika perlu.'; $msgType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setel Ulang Kata Sandi - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page-v2">

    <div class="login-wrapper">
        <div class="login-card-v2">
            <a href="../auth/login.php" class="logo">
                <i class="fa-solid fa-calendar-check"></i> Evently
            </a>
            <h2>Setel Ulang Kata Sandi</h2>
            <p class="text-muted">Masukkan kode verifikasi <strong>123456</strong> dan kata sandi baru Anda.</p>

            <?php if ($msg): ?>
                <div class="auth-message <?= ($msgType === 'success' ? 'auth-success' : 'auth-error'); ?>">
                    <?= htmlspecialchars($msg); ?>
                </div>
            <?php endif; ?>

            <?php if ($msgType === 'success'): ?>
                <div class="success-action-container">
                    <a href="../auth/login.php" class="btn btn-primary btn-block btn-centered-text">Kembali Ke Login</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
                    <div class="form-group">
                        <label class="form-label">Kode Verifikasi</label>
                        <input type="text" name="verification_code" class="form-control" placeholder="Masukkan 6 digit kode" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kata Sandi Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Sandi</label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi Sandi" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Setel Ulang Kata Sandi</button>
                    <p class="auth-footer">Kembali ke <a href="../public/index.php">Beranda</a></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>