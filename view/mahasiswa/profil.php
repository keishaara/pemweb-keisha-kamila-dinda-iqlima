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
            trim($_POST['nama'] ?? ''),
            trim($_POST['email'] ?? ''),
            trim($_POST['program_studi'] ?? ''),
            trim($_POST['wa'] ?? ''),
            trim($_POST['semester'] ?? ''),
            $_FILES['foto_profil'] ?? null,  
            $user['foto_profil'] ?? null     
        );

        $msg = $success ? "Profil berhasil diperbarui." : "Gagal update profil.";
        $msgType = $success ? "success" : "error";

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
            $success = $controller->changePassword($uid, $new);
            $msg = $success ? "Kata sandi berhasil diubah." : "Gagal mengubah sandi.";
            $msgType = $success ? "success" : "error";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout-mhs">
        <aside class="sidebar-mhs">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item active"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content-mhs">
            <div class="page-header"><h2>Profil Saya</h2></div>

            <?php if ($msg): ?>
                <div class="profile-message profile-<?= $msgType; ?>"><?= htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-avatar" style="overflow: hidden; padding: 0; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0;">
                    <?php if (!empty($user['foto_profil'])): ?>
                        <img id="previewImg" src="../../assets/profiles/<?= htmlspecialchars($user['foto_profil']); ?>" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                        <div id="defaultAvatar" style="display: none;"><?= strtoupper(substr($user['nama_lengkap'], 0, 2)); ?></div>
                    <?php else: ?>
                        <img id="previewImg" src="" alt="Foto" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <div id="defaultAvatar"><?= strtoupper(substr($user['nama_lengkap'], 0, 2)); ?></div>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h2><?= htmlspecialchars($user['nama_lengkap']); ?></h2>
                    <p>NPM: <?= htmlspecialchars($user['npm']); ?> | <?= htmlspecialchars($user['program_studi'] ?? '-'); ?> | Semester <?= htmlspecialchars($user['semester'] ?? '-'); ?></p>
                </div>
            </div>

            <div class="card mb-3">
                <h3 class="section-title">Informasi Saya</h3>
                <form method="POST" action="" enctype="multipart/form-data">
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Ubah Foto Profil</label>
                        <input type="file" name="foto_profil" id="inputFoto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Format: JPG, JPEG, PNG.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPM</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['npm']); ?>" readonly>
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
                    <div class="form-actions justify-start">
                        <button type="submit" name="ganti_sandi" class="btn btn-outline">Ubah Sandi</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('inputFoto').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('previewImg');
                const defaultAvatar = document.getElementById('defaultAvatar');
                
                previewImg.src = e.target.result;
                previewImg.style.display = 'block'; 
                if (defaultAvatar) {
                    defaultAvatar.style.display = 'none'; 
                }
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>