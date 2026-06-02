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
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item active"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Selamat Siang, <?= htmlspecialchars($nama_user) ?>!</h2>
                <p><?= date('l, d F Y') ?></p>
            </div>

            <div class="stats-grid">
               <div class="stat-card">
                    <div class="stat-icon">
                        <img src="../../assets/img/icon-ticket3.png" alt="Event">
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_terdaftar'] ?? 0 ?></h3>
                        <a href="e-tiket.php" class="btn btn-link btn-small">
                            Event Terdaftar
                        </a>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <img src="../../assets/img/icon-check.png" alt="Check">
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_selesai'] ?? 0 ?></h3>
                        <a href="e-tiket.php?status=selesai" class="btn btn-link btn-small">
                            Event Selesai
                        </a>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-yellow">
                        <img src="../../assets/img/icon-star.png" alt="Star">
                    </div>
                    <div class="stat-info">
                        <h3><?= $saved['total_saved']; ?></h3>
                        <a href="saved_events.php" class="btn btn-link btn-small">
                            Disimpan
                        </a>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-blue">
                        <img src="../../assets/img/icon-clock.png" alt="Clock">
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['total_mendatang'] ?? 0 ?></h3>
                        <a href="e-tiket.php?status=mendatang" class="btn btn-link btn-small">
                            Event Mendatang
                        </a>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <h3 class="section-title">Event Mendatang</h3>
                <a href="e-tiket.php" class="btn btn-outline btn-small">Lihat Semua</a>
            </div>

            <div class="card-grid">
                <?php if (mysqli_num_rows($res_event) > 0): ?>
                    <?php while($ev = mysqli_fetch_assoc($res_event)): ?>
                    <div class="event-card">
                        <div class="event-img">
                            <img src="../../assets/img/icon-<?= strtolower($ev['nama_kategori'] ?? 'workshop') ?>.png" 
                                 onerror="this.src='../../assets/img/icon-workshop.png'" alt="Icon">
                        </div>
                        <div class="event-details">
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
