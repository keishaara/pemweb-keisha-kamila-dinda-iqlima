<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Disimpan - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout-mhs">
        <aside class="sidebar-mhs">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="index.php?module=mahasiswa&action=dashboard" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?module=mahasiswa&action=kegiatan" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="index.php?module=mahasiswa&action=etiket" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="index.php?module=mahasiswa&action=profil" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="view/auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
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
                                <span class="mhs-event-tag"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')) ?></span>
                            </div>
                            <div class="mhs-event-details">
                                <h4 class="mhs-event-title"><?= htmlspecialchars($ev['judul_event']) ?></h4>
                                <p class="mhs-event-meta"><i class="fa-solid fa-building"></i> <?= htmlspecialchars($ev['penyelenggara']) ?></p>
                                <div class="mhs-event-footer">
                                    <form method="POST" action="index.php?module=mahasiswa&action=hapusSavedEvent" class="d-inline">
                                        <input type="hidden" name="event_id" value="<?= intval($ev['id']) ?>">
                                        <button type="submit" name="unsave_event" class="btn btn-outline btn-small btn-unsave" onclick="return confirm('Hapus dari disimpan?')"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
                                    <a href="index.php?module=mahasiswa&action=detail&id=<?= intval($ev['id']) ?>" class="mhs-btn-primary">Detail</a>
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
