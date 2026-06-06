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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="index.php" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="kegiatan.php" class="menu-item active"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <div class="menu-category">Akun</div>
            <a href="../mahasiswa/profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Jelajahi Event</h2>
                <p>Temukan kegiatan sesuai minatmu</p>
            </div>
            <div class="search-bar">
                <form method="GET" action="">
                    <input type="text" name="q" placeholder="Cari event . ." value="<?= htmlspecialchars($search); ?>">
                    <button type="submit">Cari</button>
                </form>
                <button>Semua Tanggal ⌄</button>
            </div>

            <div class="filter-tags">
                <a href="kegiatan.php" class="btn-filter <?= (!$cat_id && !$is_free) ? 'active' : ''; ?>">Semua</a>
                <a href="?cat_id=2" class="btn-filter <?= $cat_id == '2' ? 'active' : ''; ?>">Workshop</a>
                <a href="?cat_id=4" class="btn-filter <?= $cat_id == '4' ? 'active' : ''; ?>">Musik</a>
                <a href="?cat_id=5" class="btn-filter <?= $cat_id == '5' ? 'active' : ''; ?>">Volunteer</a>
                <a href="?free=1" class="btn-filter <?= $is_free ? 'active' : ''; ?>">Gratis</a>
            </div>

            <div class="card-grid">
                <?php if (empty($events)): ?>
                    <p class="grid-empty-state">Belum ada kegiatan yang tersedia.</p>
                <?php else: ?>
                    <?php foreach($events as $ev):
                        $namaKat = strtolower($ev['nama_kategori'] ?? '');
                        if (strpos($namaKat, 'musik') !== false || strpos($namaKat, 'music') !== false) {
                            $icon = 'fa-solid fa-music';
                        } elseif (strpos($namaKat, 'olahraga') !== false || strpos($namaKat, 'sport') !== false) {
                            $icon = 'fa-solid fa-medal';
                        } elseif (strpos($namaKat, 'teknologi') !== false || strpos($namaKat, 'tech') !== false) {
                            $icon = 'fa-solid fa-laptop-code';
                        } elseif (strpos($namaKat, 'seni') !== false || strpos($namaKat, 'art') !== false) {
                            $icon = 'fa-solid fa-palette';
                        } elseif (strpos($namaKat, 'bisnis') !== false || strpos($namaKat, 'business') !== false) {
                            $icon = 'fa-solid fa-briefcase';
                        } elseif (strpos($namaKat, 'kesehatan') !== false || strpos($namaKat, 'health') !== false) {
                            $icon = 'fa-solid fa-heart-pulse';
                        } elseif (strpos($namaKat, 'volunteer') !== false || strpos($namaKat, 'relawan') !== false) {
                            $icon = 'fa-solid fa-hand-holding-heart';
                        } elseif (strpos($namaKat, 'pendidikan') !== false || strpos($namaKat, 'seminar') !== false) {
                            $icon = 'fa-solid fa-graduation-cap';
                        } elseif (strpos($namaKat, 'workshop') !== false) {
                            $icon = 'fa-solid fa-chalkboard-user';
                        } else {
                            $icon = 'fa-solid fa-layer-group';
                        }
                    ?>
                    <div class="event-card">
                        <div class="event-icon-box">
                            <i class="<?= $icon ?>"></i>
                        </div>
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
