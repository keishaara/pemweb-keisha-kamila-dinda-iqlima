<?php

require_once __DIR__ . '/../config/koneksi.php';

class AuthController {

    public function action_login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['role']);
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;
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

                        $this->redirectBasedOnRole($user['tipe_akun']);
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
        
        require_once __DIR__ . '/../view/auth/login.php';
    }

    public function action_register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['role']);
        }

        $msg = ''; $msgType = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;
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
        
        require_once __DIR__ . '/../view/auth/register.php';
    }

    public function action_forgotPassword() {
        $msg = ''; $msgType = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;
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
                        $resetLink = 'index.php?module=auth&action=resetPassword&token=' . $token;
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
        
        require_once __DIR__ . '/../view/auth/forgot_password.php';
    }

    public function action_resetPassword() {
        $msg = ''; $msgType = '';
        $token = $_GET['token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;
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
        
        require_once __DIR__ . '/../view/auth/reset_password.php';
    }

    public function action_logout() {

        session_destroy();
        header("Location: index.php?module=auth&action=login");
        exit;
    }   
    
    private function redirectBasedOnRole($role) {
        if ($role === 'admin') {
            header("Location: index.php?module=admin&action=dashboard");
        } elseif ($role === 'organisasi') {
            header("Location: index.php?module=organizer&action=dashboard");
        } else {
            header("Location: index.php?module=mahasiswa&action=dashboard");
        }
        exit;
    }
}