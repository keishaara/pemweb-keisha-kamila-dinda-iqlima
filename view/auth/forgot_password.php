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
    <title>Lupa Kata Sandi - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dinda-auth-body">
    <div class="dinda-auth-wrapper">
        <div class="dinda-auth-card">
            <a href="index.php?module=public&action=index" class="logo">
                <i class="fa-solid fa-calendar-check"></i> Evently
            </a>
            
            <h2>Lupa Kata Sandi</h2>
            <p class="text-muted">Masukkan Email dan pilih role untuk menerima link reset.</p>

            <?php if ($msg): ?>
                <div class="auth-message <?= ($msgType === 'success' ? 'auth-success' : 'auth-error'); ?>">
                    <?= $msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?module=auth&action=forgotPassword">
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
                <p class="auth-footer">Kembali ke <a href="index.php?module=auth&action=login">Masuk</a></p>
            </form>
        </div>
    </div>
</body>