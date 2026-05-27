<?php
session_start();

require_once __DIR__ . '/../../controllers/OrganizerController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisasi') {
    header("Location: ../auth/index.php");
    exit;
}

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$eventId = isset($_GET['event_id']) ? trim($_GET['event_id']) : '';

$controller = new OrganizerController();
$pesertaList = $controller->dataPeserta($keyword, $eventId);
$eventsList = $controller->getEvents();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="org-layout">
        <aside class="org-sidebar">
            <a href="index.php" class="org-logo">
                <img src="../../assets/img/icon.png" alt="Evently">
                <span>Evently</span>
            </a>

            <div class="org-menu-category">Menu Organisasi</div>

            <a href="org_dashboard.php" class="org-menu-item">
                <img src="../../assets/img/icon-home2.png" alt="Dashboard">
                <span>Dashboard</span>
            </a>
            <a href="org_kelola_acara.php" class="org-menu-item">
                <img src="../../assets/img/icon-ticket.png" alt="Kelola Acara">
                <span>Kelola Acara</span>
            </a>
            <a href="org_data_peserta.php" class="org-menu-item active">
                <img src="../../assets/img/icon-user.png" alt="Data Peserta">
                <span>Data Peserta</span>
            </a>
            <a href="org_buat_acara.php" class="org-menu-item">
                <img src="../../assets/img/icon-kegiatan.png" alt="Buat Acara">
                <span>Buat Acara</span>
            </a>

            <div class="org-menu-category">Akun</div>

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
                <div class="org-page-header">
                    <h1>Data Peserta</h1>
                    <p>Daftar peserta untuk event yang sudah terdaftar.</p>
                </div>

                <section class="org-card">
                    <form action="" method="GET" class="org-table-top">
                        <div class="org-search-box">
                            <input type="text" name="search" placeholder="Cari peserta..." value="<?= htmlspecialchars($keyword); ?>">
                        </div>

                        <select name="event_id" class="org-select" onchange="this.form.submit()">
                            <option value="">Semua Event</option>
                            <?php foreach ($eventsList as $event): ?>
                                <option value="<?= $event['id']; ?>" <?= $eventId == $event['id'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($event['judul_event']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" style="display: none;"></button>
                    </form>

                    <table class="org-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NPM</th>
                                <th>Program Studi</th>
                                <th>Email</th>
                                <th>Event</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pesertaList)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 20px;">
                                        Belum ada peserta yang mendaftar pada acara Anda.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pesertaList as $peserta): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($peserta['nama_lengkap'] ?? '-') ?></strong></td>
                                        <td><?= htmlspecialchars($peserta['npm'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($peserta['program_studi'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($peserta['email'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($peserta['judul_event'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>
</body>
</html>