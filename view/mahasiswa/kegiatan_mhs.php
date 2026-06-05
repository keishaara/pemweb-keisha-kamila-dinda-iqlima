<?php
session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new MahasiswaController();

$search  = $_GET['q'] ?? '';
$cat_id  = $_GET['cat_id'] ?? '';
$is_free = isset($_GET['free']);

$events = $controller->getEvents(
    $search,
    $cat_id,
    $is_free
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kegiatan - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item active"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="../mahasiswa/profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Jelajahi Event</h2>
                <p>Temukan kegiatan sesuai minatmu</p>
            </div>
            
            <div class="search-bar">
                <form method="GET" action="kegiatan_mhs.php" style="display:flex; gap:10px; flex:1;">
                    <?php if ($cat_id): ?>
                        <input type="hidden" name="cat_id" value="<?= htmlspecialchars($cat_id); ?>">
                    <?php endif; ?>
                    
                    <?php if ($is_free): ?>
                        <input type="hidden" name="free" value="1">
                    <?php endif; ?>

                    <input type="text" name="q" placeholder="Cari event . ." value="<?= htmlspecialchars($search); ?>">
                    <button type="submit">Cari</button>
                </form>
                
                <?php if ($search || $cat_id || $is_free): ?>
                    <a href="kegiatan_mhs.php" class="btn" style="background-color: #ef4444; color: white; text-decoration: none; padding: 10px 15px; border-radius: 6px; font-size: 14px;">Reset Filter</a>
                <?php endif; ?>
            </div>

            <div class="filter-tags">
                <a href="kegiatan_mhs.php" class="btn-filter <?= (!$cat_id && !$is_free) ? 'active' : ''; ?>">Semua</a>
                
                <a href="?cat_id=2<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $cat_id == '2' ? 'active' : ''; ?>">Workshop</a>
                
                <a href="?cat_id=4<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $cat_id == '4' ? 'active' : ''; ?>">Musik</a>
                
                <a href="?cat_id=5<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $cat_id == '5' ? 'active' : ''; ?>">Volunteer</a>
                
                <a href="?free=1<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $is_free ? 'active' : ''; ?>">Gratis</a>
            </div>

            <div class="card-grid">
                <?php if (empty($events)): ?>
                    <p>Belum ada event mendatang.</p>
                <?php else: ?>
                    <?php foreach ($events as $ev): ?>
                    <div class="event-card">
                        <div class="event-banner" style="width: 100%; height: 180px; overflow: hidden; position: relative; background: #f1f5f9;">
                            <?php 
                            if (!empty($ev['poster']) && file_exists(__DIR__ . '/../../assets/poster/' . $ev['poster'])): 
                            ?>
                                <img src="../../assets/poster/<?= htmlspecialchars($ev['poster']); ?>" alt="Poster <?= htmlspecialchars($ev['judul_event']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <img src="../../assets/poster/default.png" alt="Default Poster" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-body">
                            <p class="category"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')); ?></p>
                            <h4><?= htmlspecialchars($ev['judul_event']); ?></h4>
                            <p class="organizer"><i class="fa-solid fa-building-user"></i> <?= htmlspecialchars($ev['penyelenggara'] ?? 'Organisasi'); ?></p>
                            <div class="event-footer">
                                <a href="../mahasiswa/detail.php?id=<?= $ev['id']; ?>" class="btn">Detail</a>
                                <span class="price <?= $ev['harga'] == 0 ? 'free' : ''; ?>">
                                    <?= $ev['harga'] == 0 ? 'Gratis' : 'Rp '.number_format($ev['harga'], 0, ',', '.'); ?>
                                </span>
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