<?php
/**
 * @var string $msg
 * @var string $msgType
 * @var string $token
 */
// Logic has been moved to AuthController
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setel Ulang Kata Sandi - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page-v2">

    <div class="login-wrapper">
        <div class="login-card-v2">
            <a href="index.php?module=auth&action=login" class="logo">
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
                    <a href="index.php?module=auth&action=login" class="btn btn-primary btn-block btn-centered-text">Kembali Ke Login</a>
                </div>
            <?php else: ?>
                <form method="POST" action="index.php?module=auth&action=resetPassword">
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
                    <p class="auth-footer">Kembali ke <a href="index.php?module=public&action=index">Beranda</a></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>