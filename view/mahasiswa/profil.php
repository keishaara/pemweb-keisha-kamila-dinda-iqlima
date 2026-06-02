<?php
session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new MahasiswaController();

$uid = $_SESSION['user_id'];

$user = $controller->getProfile($uid);

$msg = '';
$msgType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['update_profil'])) {

        $success = $controller->updateProfile(
            $uid,
            trim($_POST['nama']),
            trim($_POST['email']),
            trim($_POST['program_studi']),
            trim($_POST['wa']),
            trim($_POST['semester'])
        );

        $msg = $success
            ? "Profil berhasil diperbarui."
            : "Gagal update profil.";

        $msgType = $success
            ? "success"
            : "error";

        $user = $controller->getProfile($uid);
    }

    elseif (isset($_POST['ganti_sandi'])) {

        $old  = $_POST['pass_lama'];
        $new  = $_POST['pass_baru'];
        $conf = $_POST['konfirmasi'];

        if ($new !== $conf) {

            $msg = "Konfirmasi sandi tidak cocok.";
            $msgType = "error";

        } elseif (!password_verify($old, $user['password'])) {

            $msg = "Sandi lama salah.";
            $msgType = "error";

        } else {

            $success = $controller->changePassword(
                $uid,
                $new
            );

            $msg = $success
                ? "Kata sandi berhasil diubah."
                : "Gagal mengubah sandi.";

            $msgType = $success
                ? "success"
                : "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item active"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header"><h2>Profil Saya</h2></div>

            <?php if ($msg): ?>
                <div class="profile-message profile-<?= $msgType; ?>"><?= htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-avatar"><?= strtoupper(substr($user['nama_lengkap'], 0, 2)); ?></div>
                <div class="profile-info">
                    <h2><?= htmlspecialchars($user['nama_lengkap']); ?></h2>
                    <p>NPM: <?= htmlspecialchars($user['npm']); ?> | <?= htmlspecialchars($user['program_studi'] ?? '-'); ?> | Semester <?= htmlspecialchars($user['semester'] ?? '-'); ?></p>
                </div>
            </div>

            <div class="card mb-3">
                <h3 class="section-title">Informasi Saya</h3>
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPM</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['npm']); ?>" readonly style="background:#f8fafc; color:#64748b;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Kampus</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Whatsapp</label>
                            <input type="text" name="wa" class="form-control" value="<?= htmlspecialchars($user['no_whatsapp'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="program_studi" class="form-control" value="<?= htmlspecialchars($user['program_studi'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?= htmlspecialchars($user['semester'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_profil" class="btn-simpan">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 class="section-title">Ganti Kata Sandi</h3>
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sandi Lama</label>
                            <input type="password" name="pass_lama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="pass_baru" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Sandi Baru</label>
                        <input type="password" name="konfirmasi" class="form-control" required>
                    </div>
                    <div class="form-actions" style="justify-content: flex-start;">
                        <button type="submit" name="ganti_sandi" class="btn btn-outline">Ubah Sandi</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
