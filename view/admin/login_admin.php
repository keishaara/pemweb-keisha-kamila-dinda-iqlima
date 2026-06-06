<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    $target = '../admin/dashboard.php';
    header("Location: $target");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']);
    $password   = $_POST['password'];
    $role       = 'admin';

    if (!empty($identifier) && !empty($password)) {
        $stmt = mysqli_prepare($conn, "SELECT id, nama_lengkap, email, npm, password, tipe_akun, status FROM users WHERE (email = ? OR npm = ?) AND tipe_akun = ?");
        mysqli_stmt_bind_param($stmt, "sss", $identifier, $identifier, $role);
        mysqli_stmt_execute($stmt);
        $res  = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($res)) {
            if (($user['status'] ?? 'Aktif') === 'Nonaktif') {
                $error = "Akun Anda telah dinonaktifkan.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id']      = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['role']         = 'admin';
                $_SESSION['last_activity'] = time();

                header("Location: ../admin/dashboard.php");
                exit;
                } else {
                    $error = "Akun tidak ditemukan atau role tidak sesuai.";
                }
            } else {
                $error = "Akun tidak ditemukan atau role tidak sesuai.";
            }
    } else {
        $error = "Semua field wajib diisi.";
    }    
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="split-screen">
        <div class="form-side">
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
        <div class="img-side"></div>
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