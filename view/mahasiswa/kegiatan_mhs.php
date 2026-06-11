<?php
/**
 * @var array $events
 * @var string $search
 * @var string $cat_id
 * @var bool $is_free
 */
// Logic has been moved to MahasiswaController
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
    
    <div class="dashboard-layout-mhs">
        <aside class="sidebar-mhs">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="index.php?module=mahasiswa&action=dashboard" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?module=mahasiswa&action=kegiatan" class="menu-item active"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="index.php?module=mahasiswa&action=etiket" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="index.php?module=mahasiswa&action=profil" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="view/auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content-mhs">
            <div class="page-header">
                <h2>Jelajahi Event</h2>
                <p>Temukan kegiatan sesuai minatmu</p>
            </div>
            
            <div class="search-bar">
                <form method="GET" action="index.php">
                    <input type="hidden" name="module" value="mahasiswa">
                    <input type="hidden" name="action" value="kegiatan">

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
                    <a href="index.php?module=mahasiswa&action=kegiatan" class="btn btn-reset-filter">Reset Filter</a>
                <?php endif; ?>
            </div>

            <div class="filter-tags">
                <a href="index.php?module=mahasiswa&action=kegiatan" class="btn-filter <?= (!$cat_id && !$is_free) ? 'active' : ''; ?>">Semua</a>
                
                <a href="index.php?module=mahasiswa&action=kegiatan&cat_id=1<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $cat_id == '1' ? 'active' : ''; ?>">Workshop</a>
                
                <a href="index.php?module=mahasiswa&action=kegiatan&cat_id=8<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $cat_id == '8' ? 'active' : ''; ?>">Musik</a>
                
                <a href="index.php?module=mahasiswa&action=kegiatan&cat_id=3<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $cat_id == '3' ? 'active' : ''; ?>">Volunteer</a>
                
                <a href="index.php?module=mahasiswa&action=kegiatan&free=1<?= $search ? '&q='.urlencode($search) : ''; ?>" class="btn-filter <?= $is_free ? 'active' : ''; ?>">Gratis</a>
            </div>

            <div class="card-grid">
                <?php if (empty($events)): ?>
                    <p>Belum ada event mendatang.</p>
                <?php else: ?>
                    <?php foreach ($events as $ev): ?>
                    <div class="mhs-event-card <?= ($ev['status'] ?? '') === 'locked' ? 'event-locked-grayscale' : '' ?>">
                        <div class="mhs-event-banner overflow-hidden relative">
                            <?php 
                            if (!empty($ev['poster']) && $ev['poster'] !== 'default.png' && file_exists(__DIR__ . '/../../assets/poster/' . $ev['poster'])): 
                            ?>
                                <img src="assets/poster/<?= htmlspecialchars($ev['poster']); ?>" alt="Poster <?= htmlspecialchars($ev['judul_event']); ?>">
                            <?php else: 
                                $kategori = strtoupper($ev['nama_kategori'] ?? 'UMUM');
                                $iconClass = 'fa-calendar-days';
                                if (strpos($kategori, 'MUSIK') !== false) $iconClass = 'fa-music';
                                elseif (strpos($kategori, 'SEMINAR') !== false) $iconClass = 'fa-chalkboard-user';
                                elseif (strpos($kategori, 'WORKSHOP') !== false) $iconClass = 'fa-tools';
                                elseif (strpos($kategori, 'KOMPETISI') !== false) $iconClass = 'fa-trophy';
                                elseif (strpos($kategori, 'VOLUNTEER') !== false) $iconClass = 'fa-hand-holding-heart';
                            ?>
                                <div class="mhs-event-empty-state">
                                    <i class="fa-solid <?= $iconClass; ?>"></i>
                                </div>
                            <?php endif; ?>
                            <span class="mhs-event-tag"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')); ?></span>
                        </div>
                        
                        <div class="mhs-event-details">
                            <h4 class="mhs-event-title"><?= htmlspecialchars($ev['judul_event']); ?></h4>
                            <p class="mhs-event-meta"><i class="fa-solid fa-building-user"></i> <?= htmlspecialchars($ev['penyelenggara'] ?? 'Organisasi'); ?></p>
                            <div class="mhs-event-footer">
                                <span class="price <?= $ev['harga'] == 0 ? 'free' : ''; ?>">
                                    <?= $ev['harga'] == 0 ? 'Gratis' : 'Rp '.number_format($ev['harga'], 0, ',', '.'); ?>
                                </span>
                                <?php if (($ev['status'] ?? '') === 'locked'): ?>
                                    <button class="mhs-btn-primary btn-disabled-suspended" disabled>Ditangguhkan</button>
                                <?php else: ?>
                                    <a href="index.php?module=mahasiswa&action=detail&id=<?= $ev['id']; ?>" class="mhs-btn-primary">Detail</a>
                                <?php endif; ?>
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