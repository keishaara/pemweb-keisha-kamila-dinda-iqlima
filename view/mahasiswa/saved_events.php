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
    <div class="dashboard-layout-mhs">
        <aside class="sidebar-mhs">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content-mhs">
            <div class="page-header">
                <h2>Disimpan</h2>
                <p>Event yang kamu tandai untuk disimpan.</p>
            </div>

            <div class="card-grid">
                <?php if (empty($saved)): ?>
                    <p class="grid-empty-state">Belum ada event yang disimpan.</p>
                <?php else: ?>
                    <?php foreach($saved as $ev): ?>
                        <div class="mhs-event-card">
                            <div class="mhs-event-banner overflow-hidden relative">
                                <?php 
                                if (!empty($ev['poster']) && $ev['poster'] !== 'default.png' && file_exists(__DIR__ . '/../../assets/poster/' . $ev['poster'])): 
                                ?>
                                    <img src="../../assets/poster/<?= htmlspecialchars($ev['poster']); ?>" alt="Poster <?= htmlspecialchars($ev['judul_event']); ?>">
                                <?php else: 
                                    $kategori = strtoupper($ev['nama_kategori'] ?? 'UMUM');
                                    $iconClass = 'fa-calendar-days';
                                    if (strpos($kategori, 'MUSIK') !== false) $iconClass = 'fa-music';
                                    elseif (strpos($kategori, 'SEMINAR') !== false) $iconClass = 'fa-chalkboard-user';
                                    elseif (strpos($kategori, 'WORKSHOP') !== false) $iconClass = 'fa-tools';
                                    elseif (strpos($kategori, 'KOMPETISI') !== false) $iconClass = 'fa-trophy';
                                    elseif (strpos($kategori, 'VOLUNTEER') !== false) $iconClass = 'fa-hand-holding-heart';
                                ?>
                                    <div style="width: 100%; height: 100%; min-height: 150px; display: flex; align-items: center; justify-content: center; background-color: #f0f4f8; color: #7f8c8d; font-size: 4rem;">
                                        <i class="fa-solid <?= $iconClass; ?>"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="mhs-event-tag"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')) ?></span>
                            </div>
                            <div class="mhs-event-details">
                                <h4 class="mhs-event-title"><?= htmlspecialchars($ev['judul_event']) ?></h4>
                                <p class="mhs-event-meta"><i class="fa-solid fa-building"></i> <?= htmlspecialchars($ev['penyelenggara']) ?></p>
                                <div class="mhs-event-footer">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="event_id" value="<?= intval($ev['id']) ?>">
                                        <button type="submit" name="unsave_event" class="btn btn-outline btn-small btn-unsave" onclick="return confirm('Hapus dari disimpan?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
                                    <a href="detail.php?id=<?= intval($ev['id']) ?>" class="mhs-btn-primary">Detail</a>
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
