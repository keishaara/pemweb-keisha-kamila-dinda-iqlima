<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket Saya - Evently</title>
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
            <a href="index.php?module=mahasiswa&action=etiket" class="menu-item active"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="index.php?module=mahasiswa&action=profil" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="view/auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content-mhs">
            <div class="page-header">
                <h2><?= htmlspecialchars($judul ?? 'E-Tiket Saya') ?></h2>
                <p>Tunjukkan tiket ini saat melakukan registrasi di lokasi acara.</p>
            </div>

            <div class="ticket-list">

                <?php if (!empty($tiketList)): ?>

                    <?php foreach ($tiketList as $tiket): ?>

                        <div class="verif-card ticket-card">

                            <div class="verif-icon-box ticket-qr overflow-hidden">
                                <?php if (!empty($tiket['poster']) && file_exists(__DIR__ . '/../../assets/poster/' . $tiket['poster'])): ?>
                                    <img src="assets/poster/<?= htmlspecialchars($tiket['poster']); ?>" alt="Poster" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <img src="assets/poster/default.png" alt="Default Poster" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>

                            <div class="verif-info">

                                <div class="verif-tags">
                                    <?php
                                        $status_class = ($tiket['status_booking'] == 'active')
                                            ? 'disetujui'
                                            : 'aktif';

                                        $status_label = ($tiket['status_booking'] == 'active')
                                            ? 'Terverifikasi'
                                            : 'Selesai';
                                    ?>

                                    <span class="status-pill <?= $status_class ?> border-none">
                                        <?= $status_label ?>
                                    </span>

                                    <span class="tag-kategori">
                                        <?= htmlspecialchars($tiket['nama_kategori'] ?? 'Umum') ?>
                                    </span>
                                </div>

                                <div class="verif-title">
                                    <?= htmlspecialchars($tiket['judul_event']) ?>
                                </div>

                                <div class="verif-org">
                                    Oleh <?= htmlspecialchars($tiket['penyelenggara']) ?>
                                </div>

                                <div class="verif-details">
                                    <span>
                                        <i class="fa-solid fa-calendar"></i>
                                        <?= date('d M Y', strtotime($tiket['tanggal'])) ?>
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-clock"></i>
                                        <?= date('H.i', strtotime($tiket['waktu'])) ?> WIB
                                    </span>

                                    <span>
                                        <i class="fa-solid fa-location-dot"></i>
                                        <?= htmlspecialchars($tiket['lokasi']) ?>
                                    </span>
                                </div>

                            </div>

                           <div class="verif-actions">
                                <a href="index.php?module=mahasiswa&action=printTiket&kode=<?= urlencode($tiket['kode_booking']) ?>"
                                target="_blank"
                                class="btn btn-primary btn-small">
                                    Unduh PDF
                                </a>

                                <a href="index.php?module=mahasiswa&action=detail&id=<?= $tiket['event_id'] ?>&from=ticket&kode=<?= urlencode($tiket['kode_booking']) ?>"
                                class="btn btn-outline btn-small">
                                    Lihat Detail
                                </a>
                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="no-data">
                        <p>Kamu belum memiliki tiket.</p>
                    </div>

                <?php endif; ?>

            </div>
        </main>
    </div>

</body>
</html>