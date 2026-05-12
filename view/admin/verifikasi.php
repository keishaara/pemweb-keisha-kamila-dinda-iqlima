<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController();
$verifikasiAcara = $controller->getVerifikasiAcara();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Acara - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo">
                <img src="../../assets/img/icon.png" alt="Evently">
                Evently
            </div>
            <div class="menu-category">Manajemen</div>
            <a href="dashboard.php" class="menu-item">
                <img src="../../assets/img/icon-home2.png" alt="Dashboard">
                Dashboard
            </a>
            <a href="verifikasi.php" class="menu-item active">
                <img src="../../assets/img/icon-ticket2.png" alt="Verifikasi">
                Verifikasi Acara
            </a>
            <a href="pengguna.php" class="menu-item">
                <img src="../../assets/img/icon-user-admin.png" alt="Pengguna">
                Pengguna
            </a>
            <a href="kategori.php" class="menu-item">
                <img src="../../assets/img/icon-kegiatan.png" alt="Kategori">
                Kategori
            </a>
            <div class="menu-category">Sistem</div>
            <a href="../auth/logout.php" class="menu-item">
                <img src="../../assets/img/icon-logout.png" alt="Logout">
                Keluar
            </a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Verifikasi Acara</h2>
                <p class="verif-subtitle">
                    <?= count($verifikasiAcara); ?> acara memerlukan tindakan
                </p>
            </div>

            <?php if (empty($verifikasiAcara)): ?>
                <div class="verif-card" style="justify-content: center;">
                    <p>Tidak ada acara yang perlu diverifikasi saat ini.</p>
                </div>
            <?php else: ?>
                <?php foreach($verifikasiAcara as $acara): ?>
                <div class="verif-card">
                    <div class="verif-icon-box">
                        <img src="../../assets/img/ver.png" alt="Event">
                    </div>

                    <div class="verif-info">
                        <div class="verif-tags">
                            <?php 
                                $statusClass = (strtolower($acara['status']) == 'disetujui') ? 'disetujui' : 'menunggu';
                            ?>
                            <span class="status-pill <?= $statusClass ?>" style="border:none;">
                                <?= htmlspecialchars(ucfirst($acara['status'] ?? 'Menunggu')); ?>
                            </span>
                            <span class="tag-kategori">
                                <?= htmlspecialchars($acara['nama_kategori'] ?? 'Umum'); ?>
                            </span>
                        </div>

                        <div class="verif-title">
                            <?= htmlspecialchars($acara['judul_event']); ?>
                        </div>

                        <div class="verif-org">
                            ID Penyelenggara: <?= htmlspecialchars($acara['user_id']); ?>
                        </div>

                        <div class="verif-details">
                            <span><?= htmlspecialchars($acara['tanggal']); ?></span>
                            <span><?= htmlspecialchars($acara['waktu']); ?></span>
                            <span><?= htmlspecialchars($acara['lokasi']); ?></span>
                            <span>
                                <?= ($acara['harga'] == 0) ? 'Gratis' : 'Rp' . number_format($acara['harga'], 0, ',', '.'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="verif-actions">
                        <a href="proses_verifikasi.php?id=<?= $acara['id']; ?>&action=tolak" class="btn-verif btn-tolak" style="text-decoration: none; text-align: center;">Tolak</a>
                        <a href="proses_verifikasi.php?id=<?= $acara['id']; ?>&action=setuju" class="btn-verif btn-setujui" style="text-decoration: none; text-align: center;">Setujui</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>