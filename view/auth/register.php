<?php
/**
 * @var string $msg
 * @var string $msgType
 */
// Logic has been moved to AuthController
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page-v2">

    <div class="login-wrapper">
        <div class="login-card-v2 login-card-v2-max"> <a href="index.php?module=public&action=index" class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</a>
            <h2>Buat akun baru</h2>
            <p class="text-muted mb-3">Bergabung dengan kami di Evently</p>

            <?php if ($msg): ?>
                <div class="auth-message <?= ($msgType === 'success' ? 'auth-success' : 'auth-error'); ?>"><?= htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="index.php?module=auth&action=register">
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
                        <input type="text" name="program_studi" class="form-control" placeholder="Contoh: Ilmu Komputer">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Whatsapp</label>
                        <input type="text" name="wa" class="form-control" placeholder="08xxxxxxxx">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kata Sandi*</label>
                        <div class="relative">
                            <input type="password" name="password" id="regPasswordField" class="form-control auth-password-input" placeholder="Min. 8 karakter" required>
                            <i class="fa-solid fa-eye auth-eye-icon" id="toggleRegPassword"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Sandi*</label>
                        <div class="relative">
                            <input type="password" name="konfirmasi_password" id="regConfirmField" class="form-control auth-password-input" placeholder="Ulangi Sandi" required>
                            <i class="fa-solid fa-eye auth-eye-icon" id="toggleRegConfirm"></i>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Buat Akun</button>
                <p class="auth-footer">Sudah punya akun? <a href="index.php?module=auth&action=login">Masuk</a></p>
            </form>
        </div>
    </div>
    
    <script src="assets/js/register.js"></script>
</body>
</html>
