<?php

session_start();

require_once __DIR__ . '/../../controllers/OrganizerController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisasi') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new OrganizerController();

$data = $controller->dashboard();

$organizer = $data['organizer'];

$statistik = $data['statistik'];

$events = $data['events'];

$agenda = $data['agenda'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Organisasi - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="org-layout">

    <aside class="org-sidebar">

        <a href="index.php" class="org-logo">
            <img src="../../assets/img/icon.png" alt="Evently">
            <span>Evently</span>
        </a>

        <div class="org-menu-category">
            Menu Organisasi
        </div>

        <a href="org_dashboard.php" class="org-menu-item active">
            <img src="../../assets/img/icon-home3.png" alt="Dashboard">
            <span>Dashboard</span>
        </a>

        <a href="org_kelola_acara.php" class="org-menu-item">
            <img src="../../assets/img/icon-ticket.png" alt="Kelola Acara">
            <span>Kelola Acara</span>
        </a>

        <a href="org_data_peserta.php" class="org-menu-item">
            <img src="../../assets/img/icon-user2.png" alt="Data Peserta">
            <span>Data Peserta</span>
        </a>

        <a href="org_buat_acara.php" class="org-menu-item">
            <img src="../../assets/img/icon-kegiatan.png" alt="Buat Acara">
            <span>Buat Acara</span>
        </a>

        <div class="org-menu-category">
            Akun
        </div>

        <a href="org_profile.php" class="org-menu-item">
            <img src="../../assets/img/icon-profil-organisasi.png" alt="Profil">
            <span>Profil Organisasi</span>
        </a>

        <a href="../auth/logout.php" class="org-menu-item">
            <img src="../../assets/img/icon-logout.png" alt="Keluar">
            <span>Keluar</span>
        </a>

    </aside>

    <main class="org-main">

        <div class="org-container">

            <div class="org-banner">

                <div class="org-banner-badge">
                    ORGANISASI PANEL
                </div>

                <h1>
                    Halo,
                    <?= htmlspecialchars($organizer['nama_lengkap']) ?>!
                </h1>

                <p>
                    Ada
                    <?= count($agenda) ?>
                    event yang perlu kamu pantau hari ini.
                </p>

            </div>

            <div class="org-stats">

                <div class="org-stat-card">

                    <div class="org-stat-icon">
                        <img src="../../assets/img/icon-peserta.png" alt="Peserta">
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['total_peserta']) ?>
                        </h3>
                        <p>Total Peserta</p>
                    </div>

                </div>

                <div class="org-stat-card">

                    <div class="org-stat-icon">
                        <img src="../../assets/img/icon-time.png" alt="Event">
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['event_aktif']) ?>
                        </h3>
                        <p>Event Aktif</p>
                    </div>

                </div>

                <div class="org-stat-card">

                    <div class="org-stat-icon">
                        <img src="../../assets/img/icon-clock.png" alt="Pending">
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['menunggu_verifikasi']) ?>
                        </h3>
                        <p>Menunggu Verif</p>
                    </div>

                </div>

                <div class="org-stat-card">

                    <div class="org-stat-icon">
                        <img src="../../assets/img/icon-check.png" alt="Selesai">
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['event_selesai']) ?>
                        </h3>
                        <p>Event Selesai</p>
                    </div>

                </div>

            </div>

            <div class="org-dashboard-grid">

                <section class="org-card org-card-table">

                    <div class="org-card-header">
                        <h2>Performa Event Terbaru</h2>
                    </div>

                    <table class="org-table">

                        <thead>
                            <tr>
                                <th>Nama Event</th>
                                <th>Peserta</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($event['judul_event'] ?? 'Tanpa Judul') ?>
                                        </strong> 
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($event['jumlah_peserta'] ?? '0') ?> / 
                                        <?= htmlspecialchars($event['kuota'] ?? '100') ?>
                                    </td>
                                    <td>
                                        <?php if (($event['status'] ?? '') == 'approved'): ?>
                                            <span class="org-pill org-pill-success">Disetujui</span>
                                        <?php else: ?>
                                            <span class="org-pill org-pill-warning">
                                                <?= htmlspecialchars($event['status'] ?? 'Pending') ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>

                <aside class="org-card org-agenda-card">
                    <h2>Jadwal Terdekat</h2>

                    <?php foreach ($agenda as $item): ?>
                        <div class="org-agenda-item">
                            <div class="org-agenda-date">
                                <?= htmlspecialchars(date('d M Y', strtotime($item['tanggal']))) ?>
                            </div>
                            <div class="org-agenda-title">
                                <?= htmlspecialchars($item['judul_event'] ?? $item['nama_event'] ?? 'Event') ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <a href="org_kelola_acara.php"
                       class="org-btn org-btn-outline org-btn-full">

                        Lihat Semua

                    </a>
                </aside>
            </div>
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