<?php
session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new MahasiswaController();

$user_id = $_SESSION['user_id'];

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['unsave_event'])
) {

    $controller->removeSavedEvent(
        $user_id,
        intval($_POST['event_id'])
    );

    header('Location: saved_events.php');
    exit;
}

$saved = $controller->getSavedEvents(
    $user_id
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Disimpan - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Disimpan</h2>
                <p>Event yang kamu tandai untuk disimpan.</p>
            </div>

            <div class="card-grid">
                <?php if (empty($saved)): ?>
                    <p class="grid-empty-state">Belum ada event yang disimpan.</p>
                <?php else: ?>
                    <?php foreach($saved as $ev): ?>
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
                            <i class="<?= $fa_icon; ?>"></i>
                            <div class="event-details">
                                <span class="event-tag"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')) ?></span>
                                <h4 class="event-title"><?= htmlspecialchars($ev['judul_event']) ?></h4>
                                <p class="event-meta"><?= htmlspecialchars($ev['penyelenggara']) ?></p>
                                <div class="event-footer">
                                    <a href="detail.php?id=<?= intval($ev['id']) ?>" class="btn btn-small">Detail</a>
                                    <form method="POST" class="d-inline ml-8">
                                        <input type="hidden" name="event_id" value="<?= intval($ev['id']) ?>">
                                        <button type="submit" name="unsave_event" class="btn-outline btn-small" onclick="return confirm('Hapus dari disimpan?')">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
