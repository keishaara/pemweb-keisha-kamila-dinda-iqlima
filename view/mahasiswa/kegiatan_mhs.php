<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

$search  = isset($_GET['q']) ? trim($_GET['q']) : '';
$cat_id  = isset($_GET['cat_id']) ? trim($_GET['cat_id']) : '';
$is_free = isset($_GET['free']) ? true : false;

$sql = "SELECT e.*, c.nama_kategori 
        FROM events e 
        LEFT JOIN categories c ON e.kategori_id = c.id 
        WHERE e.status = 'approved'";

if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (e.judul_event LIKE '%$search_safe%' OR e.penyelenggara LIKE '%$search_safe%')";
}

if ($cat_id) {
    $cat_id_safe = mysqli_real_escape_string($conn, $cat_id);
    $sql .= " AND e.kategori_id = '$cat_id_safe'";
}

if ($is_free) {
    $sql .= " AND e.harga = 0";
}

$sql .= " ORDER BY e.tanggal DESC";

$res    = mysqli_query($conn, $sql);
$events = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kegiatan - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item active"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <a href="saved_events.php" class="menu-item">
            <img src="../../assets/img/icon-star.png" alt="Disimpan"> Disimpan</a>
            <div class="menu-category">Akun</div>
            <a href="../mahasiswa/profil.php" class="menu-item"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Jelajahi Event</h2>
                <p>Temukan kegiatan sesuai minatmu</p>
            </div>
            <div class="search-bar">
                <form method="GET" action="" style="display:flex; gap:10px; flex:1;">
                    <input type="text" name="q" placeholder="Cari event . ." value="<?= htmlspecialchars($search); ?>">
                    <button type="submit">Cari</button>
                </form>
                <button>Semua Tanggal ⌄</button>
            </div>

            <div class="filter-tags">
                <a href="kegiatan_mhs.php" class="btn-filter <?= (!$cat_id && !$is_free) ? 'active' : ''; ?>">Semua</a>
                <a href="?cat_id=2" class="btn-filter <?= $cat_id == '2' ? 'active' : ''; ?>">Workshop</a>
                <a href="?cat_id=4" class="btn-filter <?= $cat_id == '4' ? 'active' : ''; ?>">Musik</a>
                <a href="?cat_id=5" class="btn-filter <?= $cat_id == '5' ? 'active' : ''; ?>">Volunteer</a>
                <a href="?free=1" class="btn-filter <?= $is_free ? 'active' : ''; ?>">Gratis</a>
            </div>

            <div class="card-grid">
                <?php if (empty($events)): ?>
                    <p style="color:#64748b; grid-column:1/-1; text-align:center;">Belum ada kegiatan yang tersedia.</p>
                <?php else: ?>
                    <?php foreach($events as $ev): ?>
                    <div class="event-card">
                        <span class="badge">Populer</span>
                        <div class="icon"><img src="../../assets/img/icon-music.png" alt="Icon"></div>
                        <div class="event-body">
                            <p class="category"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')); ?></p>
                            <h4><?= htmlspecialchars($ev['judul_event']); ?></h4>
                            <p class="organizer"><?= htmlspecialchars($ev['penyelenggara']); ?></p>
                            <div class="event-footer">
                                <a href="../mahasiswa/detail.php?id=<?= $ev['id']; ?>" class="btn">Detail</a>
                                <span class="price <?= $ev['harga'] == 0 ? 'free' : ''; ?>">
                                    <?= $ev['harga'] == 0 ? 'Gratis' : 'Rp '.number_format($ev['harga'],0,',','.'); ?>
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