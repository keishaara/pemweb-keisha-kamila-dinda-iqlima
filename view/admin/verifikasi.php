<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$controller = new AdminController();
$controller->prosesVerifikasiAcara();
$verifikasiAcara = $controller->getVerifikasiAcara();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Acara - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo">
                <i class="fa-solid fa-calendar-check"></i>
                Evently
            </div>
            <div class="menu-category">Manajemen</div>
            <a href="dashboard.php" class="menu-item">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
            <a href="verifikasi.php" class="menu-item active">
                <i class="fa-solid fa-ticket"></i>
                Verifikasi Acara
            </a>
            <a href="semua_acara.php" class="menu-item">
                <i class="fa-solid fa-calendar-days"></i>
                Semua Acara
            </a>
            <a href="pengguna.php" class="menu-item">
                <i class="fa-solid fa-users"></i>
                Pengguna
            </a>
            <a href="kategori.php" class="menu-item">
                <i class="fa-solid fa-layer-group"></i>
                Kategori
            </a>
            <div class="menu-category">Sistem</div>
            <a href="../auth/logout.php" class="menu-item" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="fa-solid fa-right-from-bracket"></i>
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

            <?php if (isset($_SESSION['db_error'])): ?>
                <div style="margin-bottom: 20px; padding: 12px 15px; background-color: #fef2f2; color: #ef4444; border-radius: 6px; border: 1px solid #fee2e2; font-size: 14px; font-weight: bold;">
                    <?= $_SESSION['db_error']; ?>
                </div>
                <?php unset($_SESSION['db_error']); ?>
            <?php endif; ?>

            <?php if (empty($verifikasiAcara)): ?>
                <div class="verif-card" style="justify-content: center;">
                    <p>Tidak ada acara yang perlu diverifikasi saat ini.</p>
                </div>
            <?php else: ?>
                <?php foreach($verifikasiAcara as $acara): ?>
                <div class="verif-card">
                        <div class="verify-icon">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>

                    <div class="verif-info">
                        <div class="verif-tags">
                            <?php 
                                $status = strtolower($acara['status'] ?? 'pending');
                                if ($status === 'approved') {
                                    $statusClass = 'disetujui';
                                    $statusLabel = 'Disetujui';
                                } elseif ($status === 'rejected') {
                                    $statusClass = 'ditolak';
                                    $statusLabel = 'Ditolak';
                                } else {
                                    $statusClass = 'menunggu';
                                    $statusLabel = 'Menunggu';
                                }
                            ?>
                            <span class="status-pill <?= $statusClass ?>" style="border:none;">
                                <?= htmlspecialchars($statusLabel); ?>
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
                        <?php 
                        $statusAksi = strtolower($acara['status'] ?? 'pending');
                        if ($statusAksi === 'approved'): 
                        ?>
                            <span style></span>
                        <?php else: ?>
                            <a href="verifikasi.php?id=<?= $acara['id']; ?>&action=tolak" 
                               class="btn-verif btn-tolak" 
                               style="text-decoration: none; text-align: center;" 
                               onclick="return confirm('Apakah Anda yakin ingin MENOLAK acara ini?')">
                               Tolak
                            </a>
                            
                            <a href="verifikasi.php?id=<?= $acara['id']; ?>&action=setuju" 
                               class="btn-verif btn-setujui" 
                               style="text-decoration: none; text-align: center;" 
                               onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI acara ini?')">
                               Setujui
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>