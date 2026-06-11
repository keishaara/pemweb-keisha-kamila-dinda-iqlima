<?php
/**
 * @var string $error
 */
// Logic has been moved to AuthController
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">

    <div class="login-wrapper">
        <div class="login-card">
            <a href="index.php?module=public&action=index" class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</a>
            <h2>Selamat Datang Kembali</h2>
            <p class="text-muted mb-3">Masuk ke akun kamu untuk menemukan kegiatan kampus terbaru.</p>

            <?php if ($error): ?><div class="auth-error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
            <div id="clientError" class="auth-error"></div>

            <form id="loginForm" method="POST" action="index.php?module=auth&action=login">
                <div class="form-group">
                    <label class="form-label">Email atau NPM</label>
                    <input type="text" name="identifier" class="form-control" placeholder="npm@unila.ac.id / NPM" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordField" class="form-control auth-password-input" placeholder="••••••••" required>
                        <i class="fa-solid fa-eye auth-eye-icon" id="togglePassword"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Masuk sebagai</label>
                    <select name="role" class="form-control" required>
                        <option value="" selected disabled>Pilih role</option>
                        <option value="organisasi">Organisasi</option>
                        <option value="mahasiswa">Mahasiswa</option>
                    </select>
                </div>
                
                <div class="auth-remember">
                    <a href="index.php?module=auth&action=forgotPassword" class="auth-forgot">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block login-submit-btn">Masuk</button>
                <p class="auth-footer">Belum punya akun? <a href="index.php?module=auth&action=register">Daftar gratis</a></p>
            </form>
        </div>
    </div>
    
    <script>
        const loginForm = document.getElementById('loginForm');
        const clientError = document.getElementById('clientError');

        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#passwordField');

        if (togglePassword && passwordField) {
            togglePassword.addEventListener('click', function (e) {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        }

        function showClientError(message) {
            clientError.textContent = message;
            clientError.style.display = 'block';
        }

        function clearClientError() {
            clientError.textContent = '';
            clientError.style.display = 'none';
        }

        loginForm.addEventListener('submit', function (event) {
            clearClientError();

            const identifier = loginForm.elements['identifier'].value.trim();
            const password = loginForm.elements['password'].value;
            const role = loginForm.elements['role'].value;

            if (!identifier) {
                showClientError('Email atau NPM wajib diisi.');
                event.preventDefault();
                return;
            }

            if (!password) {
                showClientError('Kata sandi wajib diisi.');
                event.preventDefault();
                return;
            }

            if (password.length < 8) {
                showClientError('Kata sandi minimal 8 karakter.');
                event.preventDefault();
                return;
            }

            if (!role) {
                showClientError('Pilih role terlebih dahulu.');
                event.preventDefault();
                return;
            }

            if (identifier.includes('@')) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(identifier)) {
                    showClientError('Format email tidak valid.');
                    event.preventDefault();
                    return;
                }
            } else {
                const npmPattern = /^[A-Za-z0-9]+$/;
                if (!npmPattern.test(identifier)) {
                    showClientError('NPM harus diisi tanpa spasi atau karakter khusus.');
                    event.preventDefault();
                    return;
                }
            }
        });
    </script>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'timeout'): ?>
        <script>
            alert('Sesi Anda telah berakhir karena tidak ada aktivitas selama 30 menit. Silakan login kembali.');
        </script>
    <?php endif; ?>
</body>
</html>