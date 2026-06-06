<?php
session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama_lengkap'] ?? 'User';

$controller = new MahasiswaController();

$stats = $controller->getDashboardStats($user_id);

$saved = $controller->getSavedCount($user_id);

$res_event = $controller->getUpcomingEventsDashboard($user_id);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Selamat Siang, <?= htmlspecialchars($nama_user) ?>!</h2>
                <p><?= date('l, d F Y') ?></p>
            </div>

            <div class="org-stats">
               <div class="org-stat-card clickable-card" onclick="window.location.href='e-tiket.php'">
                    <div class="org-stat-icon"><i class="fa-solid fa-ticket" style="font-size: 24px;"></i></div>
                    <div class="org-stat-info">
                        <h3><?= $stats['total_terdaftar'] ?? 0 ?></h3>
                        <p>Event Terdaftar</p>
                    </div>
                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='e-tiket.php?status=selesai'">
                    <div class="org-stat-icon"><i class="fa-solid fa-circle-check" style="font-size: 24px;"></i></div>
                    <div class="org-stat-info">
                        <h3><?= $stats['total_selesai'] ?? 0 ?></h3>
                        <p>Event Selesai</p>
                    </div>
                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='saved_events.php'">
                    <div class="org-stat-icon"><i class="fa-solid fa-star" style="font-size: 24px;"></i></div>
                    <div class="org-stat-info">
                        <h3><?= $saved['total_saved']; ?></h3>
                        <p>Disimpan</p>
                    </div>
                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='e-tiket.php?status=mendatang'">
                    <div class="org-stat-icon"><i class="fa-solid fa-clock" style="font-size: 24px;"></i></div>
                    <div class="org-stat-info">
                        <h3><?= $stats['total_mendatang'] ?? 0 ?></h3>
                        <p>Event Mendatang</p>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <h3 class="section-title">Event Mendatang</h3>
                <a href="kegiatan_mhs.php" class="btn btn-outline btn-small">Lihat Semua</a>
            </div>

            <div class="card-grid">
                <?php if (mysqli_num_rows($res_event) > 0): ?>
                    <?php while($ev = mysqli_fetch_assoc($res_event)): ?>
                    <div class="event-card">
                            <?php 
                                $nama_kat = strtolower($ev['nama_kategori'] ?? '');
                                $fa_icon = 'fa-solid fa-layer-group';
                                if (strpos($nama_kat, 'music') !== false || strpos($nama_kat, 'musik') !== false) {
                                    $fa_icon = 'fa-solid fa-music';
                                } elseif (strpos($nama_kat, 'olahraga') !== false || strpos($nama_kat, 'sport') !== false) {
                                    $fa_icon = 'fa-solid fa-medal';
                                } elseif (strpos($nama_kat, 'teknologi') !== false || strpos($nama_kat, 'tech') !== false || strpos($nama_kat, 'it') !== false) {
                                    $fa_icon = 'fa-solid fa-laptop-code';
                                } elseif (strpos($nama_kat, 'seni') !== false || strpos($nama_kat, 'art') !== false) {
                                    $fa_icon = 'fa-solid fa-palette';
                                } elseif (strpos($nama_kat, 'pendidikan') !== false || strpos($nama_kat, 'seminar') !== false || strpos($nama_kat, 'education') !== false) {
                                    $fa_icon = 'fa-solid fa-graduation-cap';
                                } elseif (strpos($nama_kat, 'bisnis') !== false || strpos($nama_kat, 'business') !== false) {
                                    $fa_icon = 'fa-solid fa-briefcase';
                                } elseif (strpos($nama_kat, 'kesehatan') !== false || strpos($nama_kat, 'health') !== false) {
                                    $fa_icon = 'fa-solid fa-heart-pulse';
                                } elseif (strpos($nama_kat, 'budaya') !== false || strpos($nama_kat, 'culture') !== false) {
                                    $fa_icon = 'fa-solid fa-masks-theater';
                                }
                            ?>
                        <div class="event-details">
                            <i class="<?= $fa_icon; ?>" style="font-size: 2em; color: #2E4C82; margin-bottom: 15px; display: block;"></i>
                            <span class="event-tag"><?= htmlspecialchars($ev['nama_kategori'] ?? 'Umum') ?></span>
                            <h4 class="event-title"><?= htmlspecialchars($ev['judul_event']) ?></h4>
                            <p class="event-meta"><?= htmlspecialchars($ev['penyelenggara']) ?></p>
                            <div class="event-footer">
                                <a href="detail.php?id=<?= $ev['id'] ?>&from=dashboard"
                                    class="btn btn-primary btn-small">
                                    Detail
                                </a>
                                <span class="price <?= $ev['harga'] == 0 ? 'free' : '' ?>">
                                    <?= $ev['harga'] == 0 ? 'Gratis' : 'Rp '.number_format($ev['harga'], 0, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-event-message">
                        Belum ada event mendatang.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        history.pushState(null, null, window.location.href);

        window.addEventListener('popstate', function (event) {
            const yakinLogout = confirm("Apakah Anda ingin logout?");
            
            if (yakinLogout) {
                window.location.href = '../auth/logout.php'; 
            } else {
                history.pushState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>
