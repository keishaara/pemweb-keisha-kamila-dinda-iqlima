<?php
// Variables such as $error are passed from AdminController
$error = $error ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">

    <div class="login-wrapper">
        <div class="login-card">
            <a href="../public/index.php" class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</a>
            <h2>Login Admin</h2>
            <p class="text-muted mb-3">Silakan masuk ke akun admin Anda.</p>

            <?php if ($error): ?><div class="auth-error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
            <div id="clientError" class="auth-error"></div>

            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="identifier" class="form-control" placeholder="npm@unila.ac.id" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block login-submit-btn">Masuk</button>
            </form>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const clientError = document.getElementById('clientError');

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

            if (!identifier) {
                showClientError('Email wajib diisi.');
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

            if (identifier.includes('@')) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(identifier)) {
                    showClientError('Format email tidak valid.');
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