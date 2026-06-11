<?php
/**
 * @var array $events
 * @var string $search
 * @var string $cat_id
 * @var bool $is_free
 */
// Logic has been moved to PublicController
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kegiatan - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="features-wrapper features-wrapper-min">
        <a href="index.php?module=public&action=index" class="btn-back-floating">Kembali</a>
        <main class="main-content">
            <div class="page-header public-page-header">
                <h2 class="public-h2-title">Jelajahi Event</h2>
                <p class="public-p-subtitle">Temukan kegiatan sesuai minatmu</p>
            </div>
            <div class="search-bar public-search-container">
                <form method="GET" action="index.php" class="flex gap-8 w-full m-0">
                    <input type="hidden" name="module" value="public">
                    <input type="hidden" name="action" value="kegiatan">
                    <input type="text" name="q" placeholder="Ketik nama event atau penyelenggara..." value="<?= htmlspecialchars($search); ?>" class="public-search-input">
                    <button type="submit" class="public-btn-search">Cari</button>
                </form>
            </div>

            <div class="filter-tags public-filter-tags">
                <a href="index.php?module=public&action=kegiatan" class="btn-filter <?= (!$cat_id && !$is_free) ? 'active' : ''; ?>">Semua</a>
                <a href="index.php?module=public&action=kegiatan&cat_id=1" class="btn-filter <?= $cat_id == '1' ? 'active' : ''; ?>">Workshop</a>
                <a href="index.php?module=public&action=kegiatan&cat_id=8" class="btn-filter <?= $cat_id == '8' ? 'active' : ''; ?>">Musik</a>
                <a href="index.php?module=public&action=kegiatan&cat_id=3" class="btn-filter <?= $cat_id == '3' ? 'active' : ''; ?>">Volunteer</a>
                <a href="index.php?module=public&action=kegiatan&free=1" class="btn-filter <?= $is_free ? 'active' : ''; ?>">Gratis</a>
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
                        <?php if (!empty($ev['poster']) && $ev['poster'] !== 'default.png' && file_exists(__DIR__ . '/../../assets/poster/' . $ev['poster'])): ?>
                            <div class="event-banner public-event-banner">
                                <img src="assets/poster/<?= htmlspecialchars($ev['poster']); ?>" alt="Poster" class="public-event-image">
                            </div>
                        <?php else: ?>
                            <div class="event-icon-box public-event-icon-box">
                                <i class="<?= $icon ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="event-body">
                            <p class="category"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')); ?></p>
                            <h4><?= htmlspecialchars($ev['judul_event']); ?></h4>
                            <p class="organizer"><?= htmlspecialchars($ev['penyelenggara']); ?></p>
                            <div class="event-footer">
                                <a href="index.php?module=auth&action=login" class="btn">Detail</a>
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
