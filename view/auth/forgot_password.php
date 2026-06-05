<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

$msg = ''; $msgType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']);
    $role = $_POST['role'] ?? '';

    if (empty($identifier) || empty($role)) {
        $msg = 'Semua field wajib diisi.'; $msgType = 'error';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, email, npm FROM users WHERE (email = ? OR npm = ?) AND tipe_akun = ?");
        mysqli_stmt_bind_param($stmt, "sss", $identifier, $identifier, $role);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($res)) {
            $create = "CREATE TABLE IF NOT EXISTS password_resets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                token VARCHAR(128) NOT NULL,
                verification_code VARCHAR(10) NULL,
                expires_at DATETIME NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )";
            mysqli_query($conn, $create);

            $colCheck = mysqli_query($conn, "SHOW COLUMNS FROM password_resets LIKE 'verification_code'");
            if (mysqli_num_rows($colCheck) === 0) {
                mysqli_query($conn, "ALTER TABLE password_resets ADD COLUMN verification_code VARCHAR(10) NULL AFTER token");
            }

            $token = bin2hex(random_bytes(16));
            $verificationCode = '123456';
            $expires = date('Y-m-d H:i:s', time() + 3600);

            $ins = mysqli_prepare($conn, "INSERT INTO password_resets (user_id, token, verification_code, expires_at) VALUES (?,?,?,?)");
            mysqli_stmt_bind_param($ins, "isss", $user['id'], $token, $verificationCode, $expires);
            if (mysqli_stmt_execute($ins)) {

            $resetLink = 'reset_password.php?token=' . $token;
                $msg = "Permintaan reset kata sandi berhasil. Silakan buka link berikut (contoh): <a href=\"$resetLink\">$resetLink</a><br>Kode verifikasi: <strong>123456</strong>";
                $msgType = 'success';
            } else {
                $msg = 'Gagal membuat permintaan reset.'; $msgType = 'error';
            }
        } else {
            $msg = 'Akun tidak ditemukan untuk role yang dipilih.'; $msgType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Kata Sandi - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="split-screen">
        <div class="form-side">
            <a href="../public/index.php" class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</a>
            <h2>Lupa Kata Sandi</h2>
            <p class="text-muted mb-3">Masukkan Email dan pilih role untuk menerima link reset.</p>

            <?php if ($msg): ?>
                <div class="auth-message <?= ($msgType === 'success' ? 'auth-success' : 'auth-error'); ?>"><?= $msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="identifier" class="form-control" placeholder="npm@unila.ac.id" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pilih role</label>
                    <select name="role" class="form-control" required>
                        <option value="" disabled selected>Pilih role</option>
                        <option value="organisasi">Organisasi</option>
                        <option value="mahasiswa">Mahasiswa</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Kirim Link Reset</button>
                <p class="auth-footer">Kembali ke <a href="login.php">Masuk</a></p>
            </form>
        </div>
        <div class="img-side"></div>
    </div>
</body>
</html>