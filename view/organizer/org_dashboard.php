<?php

session_start();

require_once __DIR__ . '/../../controllers/OrganizerController.php';
require_once __DIR__ . '/../../models/OrganizerModel.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisasi') {
    header("Location: ../auth/login.php");
    exit;
}
$organizerModel = new OrganizerModel();
$dataAkun = $organizerModel->getOrganizerById($_SESSION['user_id']);

if (($dataAkun['status'] ?? 'Aktif') === 'Nonaktif') {
    session_destroy();
    header("Location: ../auth/login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="org-layout">

    <aside class="org-sidebar">

        <a href="index.php" class="org-logo">
            <i class="fa-solid fa-calendar-check" style="font-size: 24px;"></i>
            <span>Evently</span>
        </a>

        <div class="org-menu-category">
            Menu Organisasi
        </div>

        <a href="org_dashboard.php" class="org-menu-item active">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="org_kelola_acara.php" class="org-menu-item">
            <i class="fa-solid fa-ticket"></i>
            <span>Kelola Acara</span>
        </a>

        <a href="org_data_peserta.php" class="org-menu-item">
            <i class="fa-solid fa-users"></i>
            <span>Data Peserta</span>
        </a>

        <a href="org_buat_acara.php" class="org-menu-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Buat Acara</span>
        </a>

        <div class="org-menu-category">
            Akun
        </div>

        <a href="org_profile.php" class="org-menu-item">
            <i class="fa-solid fa-user-tie"></i>
            <span>Profil Organisasi</span>
        </a>

        <a href="../auth/logout.php" class="org-menu-item">
            <i class="fa-solid fa-right-from-bracket"></i>
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

                <div class="org-stat-card clickable-card" onclick="window.location.href='org_data_peserta.php'">

                    <div class="org-stat-icon">
                        <i class="fa-solid fa-users" style="font-size: 24px;"></i>
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['total_peserta']) ?>
                        </h3>
                        <p>Total Peserta</p>
                    </div>

                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='org_kelola_acara.php'">

                    <div class="org-stat-icon">
                        <i class="fa-solid fa-calendar-day" style="font-size: 24px;"></i>
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['event_aktif']) ?>
                        </h3>
                        <p>Event Aktif</p>
                    </div>

                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='org_kelola_acara.php'">

                    <div class="org-stat-icon">
                        <i class="fa-solid fa-clock-rotate-left" style="font-size: 24px;"></i>
                    </div>

                    <div class="org-stat-info">
                        <h3>
                            <?= htmlspecialchars($statistik['menunggu_verifikasi']) ?>
                        </h3>
                        <p>Menunggu Verif</p>
                    </div>

                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='org_kelola_acara.php'">

                    <div class="org-stat-icon">
                        <i class="fa-solid fa-calendar-check" style="font-size: 24px;"></i>
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