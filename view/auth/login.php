<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        $target = '../admin/dashboard.php';
    } elseif ($_SESSION['role'] === 'organisasi') {
        $target = '../organizer/org_dashboard.php';
    } else {
        $target = '../mahasiswa/user_dashboard.php';
    }
    header("Location: $target");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']);
    $password   = $_POST['password'];
    $role       = $_POST['role'];

    if (!empty($identifier) && !empty($password) && !empty($role)) {
        $stmt = mysqli_prepare($conn, "SELECT id, nama_lengkap, email, npm, password, tipe_akun, status FROM users WHERE (email = ? OR npm = ?) AND tipe_akun = ?");
        mysqli_stmt_bind_param($stmt, "sss", $identifier, $identifier, $role);
        mysqli_stmt_execute($stmt);
        $res  = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($res)) {
            if (($user['status'] ?? 'Aktif') === 'Nonaktif') {
                $error = "Akun Anda telah dinonaktifkan oleh admin.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id']      = $user['id'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['role']         = $user['tipe_akun'];
                $_SESSION['last_activity'] = time();

                if ($user['tipe_akun'] === 'admin') {
                    $target = '../admin/dashboard.php';
                } elseif ($user['tipe_akun'] === 'organisasi') {
                    $target = '../organizer/org_dashboard.php';
                } else {
                    $target = '../mahasiswa/user_dashboard.php';
                }
                header("Location: $target");
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
<body class="login-page">

    <div class="login-wrapper">
        <div class="login-card">
            <a href="../public/index.php" class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</a>
            <h2>Selamat Datang Kembali</h2>
            <p class="text-muted mb-3">Masuk ke akun kamu untuk menemukan kegiatan kampus terbaru.</p>

            <?php if ($error): ?><div class="auth-error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
            <div id="clientError" class="auth-error"></div>

            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Email atau NPM</label>
                    <input type="text" name="identifier" class="form-control" placeholder="npm@unila.ac.id / NPM" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="passwordField" class="form-control" placeholder="••••••••" required style="padding-right: 40px;">
                        <i class="fa-solid fa-eye" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b;"></i>
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
                    <a href="forgot_password.php" class="auth-forgot">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block login-submit-btn">Masuk</button>
                <p class="auth-footer">Belum punya akun? <a href="register.php">Daftar gratis</a></p>
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