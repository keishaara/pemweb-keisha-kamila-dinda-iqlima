<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../mahasiswa/profil.php"); exit;
}

$msg = ''; $msgType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipe  = $_POST['tipe'];
    $nama  = trim($_POST['nama']);
    $npm   = trim($_POST['npm']);
    $email = trim($_POST['email']);
    $program_studi = trim($_POST['program_studi']);
    $wa    = trim($_POST['wa']);
    $pass  = $_POST['password'];
    $pass2 = $_POST['konfirmasi_password'];

    if (empty($nama) || empty($npm) || empty($email) || empty($pass)) {
        $msg = "Data bertanda (*) wajib diisi."; $msgType = 'error';
    } elseif ($pass !== $pass2) {
        $msg = "Konfirmasi kata sandi tidak cocok."; $msgType = 'error';
    } else {
        $cek = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? OR npm = ?");
        mysqli_stmt_bind_param($cek, "ss", $email, $npm);
        mysqli_stmt_execute($cek); mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) {
            $msg = "Email atau NPM sudah terdaftar."; $msgType = 'error';
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins  = mysqli_prepare($conn, "INSERT INTO users (tipe_akun, nama_lengkap, npm, email, program_studi, no_whatsapp, password) VALUES (?,?,?,?,?,?,?)");
            mysqli_stmt_bind_param($ins, "sssssss", $tipe, $nama, $npm, $email, $program_studi, $wa, $hash);
            if (mysqli_stmt_execute($ins)) {
                $msg = "Registrasi berhasil! Silakan login."; $msgType = 'success';
            } else {
                $msg = "Gagal mendaftar: " . mysqli_error($conn); $msgType = 'error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="split-screen">
        <div class="form-side">
            <a href="../public/index.php" class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</a>
            <h2>Buat akun baru</h2>
            <p class="text-muted mb-3">Bergabung dengan kami di Evently</p>

            <?php if ($msg): ?>
                <div class="auth-message <?= ($msgType === 'success' ? 'auth-success' : 'auth-error'); ?>"><?= htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Pilih Tipe Akun</label>
                    <select name="tipe" class="form-control" required>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="organisasi">Organisasi</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap*</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Kamu" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NPM / ID Org*</label>
                        <input type="text" name="npm" class="form-control" placeholder="NPM / ID Organisasi" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Kampus*</label>
                    <input type="email" name="email" class="form-control" placeholder="npm@unila.ac.id" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Prodi</label>
                        <input type="text" name="program_studi" class="form-control" placeholder="Pilih Prodi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Whatsapp</label>
                        <input type="text" name="wa" class="form-control" placeholder="08xxxxxxxx">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kata Sandi*</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Sandi*</label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi Sandi" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Buat Akun</button>
                <p class="auth-footer">Sudah punya akun? <a href="login.php">Masuk</a></p>
            </form>
        </div>
        <div class="img-side"></div>
    </div>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            var form = event.target;
            var nama = form.nama.value.trim();
            var npm = form.npm.value.trim();
            var email = form.email.value.trim();
            var password = form.password.value;
            var confirmPassword = form.konfirmasi_password.value;
            var wa = form.wa.value.trim();
            var errorMessage = '';

            if (!nama || !npm || !email || !password || !confirmPassword) {
                errorMessage = 'Data bertanda (*) wajib diisi.';
            } else if (!/^\S+@\S+\.\S+$/.test(email)) {
                errorMessage = 'Format email tidak valid.';
            } else if (password.length < 8) {
                errorMessage = 'Kata sandi harus minimal 8 karakter.';
            } else if (password !== confirmPassword) {
                errorMessage = 'Konfirmasi kata sandi tidak cocok.';
            } else if (wa && !/^\d{10,15}$/.test(wa)) {
                errorMessage = 'Nomor Whatsapp tidak valid.';
            }
            if (errorMessage) {
                event.preventDefault();
                alert(errorMessage);
            }
        });
    </script>
</body>
</html>
