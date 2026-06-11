<!DOCTYPE html>
<html lang="id">
<?php
/**
 * @var string $nama_user
 * @var array $stats
 * @var array $saved
 * @var array $res_event
 */
?>
<head>
    <meta charset="UTF-8">
    <title>Beranda - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout-mhs">
        <aside class="sidebar-mhs">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="index.php?module=mahasiswa&action=dashboard" class="menu-item active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?module=mahasiswa&action=kegiatan" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="index.php?module=mahasiswa&action=etiket" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="index.php?module=mahasiswa&action=profil" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="index.php?module=auth&action=logout" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content-mhs">
            <div class="page-header">
                <h2>Selamat Siang, <?= htmlspecialchars($nama_user) ?>!</h2>
                <p><?= date('l, d F Y') ?></p>
            </div>

            <div class="mhs-stats-grid">
               <div class="mhs-stat-card" onclick="window.location.href='index.php?module=mahasiswa&action=etiket'">
                    <div class="mhs-stat-icon bg-gradient-blue"><i class="fa-solid fa-ticket"></i></div>
                    <div class="mhs-stat-info">
                        <h3><?= $stats['total_terdaftar'] ?? 0 ?></h3>
                        <p>Event Terdaftar</p>
                    </div>
                </div>

                <div class="mhs-stat-card" onclick="window.location.href='index.php?module=mahasiswa&action=etiket&status=selesai'">
                    <div class="mhs-stat-icon bg-gradient-cyan"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="mhs-stat-info">
                        <h3><?= $stats['total_selesai'] ?? 0 ?></h3>
                        <p>Event Selesai</p>
                    </div>
                </div>

                <div class="mhs-stat-card" onclick="window.location.href='index.php?module=mahasiswa&action=savedEvents'">
                    <div class="mhs-stat-icon bg-gradient-purple"><i class="fa-solid fa-star"></i></div>
                    <div class="mhs-stat-info">
                        <h3><?= $saved['total_saved']; ?></h3>
                        <p>Disimpan</p>
                    </div>
                </div>

                <div class="mhs-stat-card" onclick="window.location.href='index.php?module=mahasiswa&action=etiket&status=mendatang'">
                    <div class="mhs-stat-icon bg-gradient-indigo"><i class="fa-solid fa-clock"></i></div>
                    <div class="mhs-stat-info">
                        <h3><?= $stats['total_mendatang'] ?? 0 ?></h3>
                        <p>Event Mendatang</p>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <h3 class="section-title">Event Mendatang</h3>
                <a href="index.php?module=mahasiswa&action=kegiatan" class="btn btn-outline btn-small">Lihat Semua</a>
            </div>

            <div class="card-grid">
                <?php if (!empty($res_event)): ?>
                    <?php foreach($res_event as $ev): ?>
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
                            <h4 class="mhs-event-title"><?= htmlspecialchars($ev['judul_event']) ?></h4>
                            <p class="mhs-event-meta"><i class="fa-solid fa-building"></i> <?= htmlspecialchars($ev['penyelenggara']) ?></p>
                            <div class="mhs-event-footer">
                                <span class="price <?= $ev['harga'] == 0 ? 'free' : '' ?>">
                                    <?= $ev['harga'] == 0 ? 'Gratis' : 'Rp '.number_format($ev['harga'], 0, ',', '.') ?>
                                </span>
                                <?php if (($ev['status'] ?? '') === 'locked'): ?>
                                    <button class="mhs-btn-primary btn-disabled-suspended" disabled>
                                        Ditangguhkan
                                    </button>
                                <?php else: ?>
                                    <a href="index.php?module=mahasiswa&action=detail&id=<?= $ev['id'] ?>&from=dashboard"
                                        class="mhs-btn-primary">
                                        Detail
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-event-message">
                        Belum ada event mendatang.
                    </p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        history.pushState(null, null, window.location.href);

        window.addEventListener('popstate', function (event) {
            const yakinLogout = confirm("Apakah Anda ingin logout?");
            
            if (yakinLogout) {
                window.location.href = 'index.php?module=auth&action=logout'; 
            } else {
                history.pushState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>
