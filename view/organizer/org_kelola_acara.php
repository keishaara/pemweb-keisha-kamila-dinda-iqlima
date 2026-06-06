<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisasi') {
    header("Location: ../auth/index.php");
    exit;
}

require_once __DIR__ . '/../../controllers/OrganizerController.php';
require_once __DIR__ . '/../../config/session.php';

$controller = new OrganizerController();

$controller->hapusAcara();

$data = $controller->dashboard();
$organizer = $data['organizer'];
$events = $controller->getKelolaAcara() ?? [];
$keyword = $_GET['search'] ?? '';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Acara - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="org-layout">

    <aside class="org-sidebar">
        <a href="index.php" class="org-logo">
            <i class="fa-solid fa-calendar-check"></i>
            <span>Evently</span>
        </a>

        <div class="org-menu-category">Menu Organisasi</div>

        <a href="org_dashboard.php" class="org-menu-item">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="org_kelola_acara.php" class="org-menu-item active">
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

        <div class="org-menu-category">Akun</div>

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

            <div class="org-page-header">
                <h1>Kelola Acara</h1>
                <p>Daftar acara yang sedang dan pernah dijalankan organisasi.</p>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
                <div class="org-alert org-alert-success">
                    <strong>Sukses!</strong> Acara telah berhasil dihapus secara permanen.
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'failed'): ?>
                <div class="org-alert org-alert-danger">
                    <strong>Gagal!</strong> Terjadi kesalahan server saat mencoba menghapus acara.
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'action_blocked'): ?>
                <div class="org-alert org-alert-danger">
                    <strong>Akses Ditolak!</strong> Acara yang sudah berstatus <b>Disetujui</b> tidak dapat diubah atau dihapus kembali demi validitas data peserta.
                </div>
            <?php endif; ?>

            <section class="org-card">
                <div class="org-table-top">
                    <form method="GET" class="org-search-box">
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari acara..."
                            value="<?= htmlspecialchars($keyword) ?>"
                        >
                    </form>

                    <a href="org_buat_acara.php" class="org-btn org-btn-primary">+ Buat Acara</a>
                </div>

                <table class="org-table">
                    <thead>
                        <tr>
                            <th>Nama Acara</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr>
                                <td colspan="6" class="org-no-data-cell">
                                    Belum ada acara yang terdaftar.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <?php
                                $namaEvent = $event['judul_event'] ?? $event['nama_event'] ?? 'Tanpa Judul';
                                if ($keyword && stripos($namaEvent, $keyword) === false) {
                                    continue;
                                }
                                ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($namaEvent) ?></strong></td>
                                    <td><?= htmlspecialchars($event['kategori'] ?? $event['kategori_id'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($event['tanggal'] ?? '-') ?></td>
                                    <td>
                                        <?= htmlspecialchars($event['jumlah_peserta'] ?? '0') ?> / <?= htmlspecialchars($event['kuota'] ?? '0') ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = strtolower($event['status'] ?? 'pending');
                                        if ($status === 'approved' || $status === 'disetujui'): 
                                        ?>
                                            <span class="org-pill org-pill-success">Disetujui</span>
                                        <?php else: ?>
                                            <span class="org-pill org-pill-warning">
                                                <?= htmlspecialchars($event['status'] ?? 'Pending') ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $statusAksi = strtolower($event['status'] ?? 'pending');

                                        if ($statusAksi === 'approved' || $statusAksi === 'disetujui'): 
                                        ?>
                                            <span></span>
                                        <?php else: ?>
                                            <a href="org_buat_acara.php?id=<?= $event['id'] ?? '' ?>" class="org-btn org-btn-small org-btn-outline">Edit</a>
                                        <?php endif; ?>
                                        
                                        <a href="org_kelola_acara.php?action=hapus&id=<?= $event['id'] ?? '' ?>"
                                           class="org-btn org-btn-small org-btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus event ini?')">
                                           Hapus
                                        </a>
                                    </td>
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