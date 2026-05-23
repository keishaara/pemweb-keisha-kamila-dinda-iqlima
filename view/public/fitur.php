<?php
session_start();
require_once __DIR__ . '/../../controllers/MahasiswaController.php';

$controller = new MahasiswaController();
$events = $controller->indexFeatures();

function getKategoriStyle($kategori) {
    $kat = strtolower($kategori);
    if (strpos($kat, 'seminar') !== false) return ['bg' => 'card-bg-blue', 'tag' => 'tag-blue', 'emoji' => '🎤'];
    if (strpos($kat, 'workshop') !== false || strpos($kat, 'bootcamp') !== false) return ['bg' => 'card-bg-green', 'tag' => 'tag-green', 'emoji' => '💻'];
    if (strpos($kat, 'seni') !== false || strpos($kat, 'musik') !== false) return ['bg' => 'card-bg-pink', 'tag' => 'tag-pink', 'emoji' => '🎵'];
    return ['bg' => 'card-bg-yellow', 'tag' => 'tag-yellow', 'emoji' => '⚽'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitur & Layanan - Evently</title>
    <link rel="stylesheet" href="../../assets/css/fitur.css">
</head>
<body>
    <div class="features-wrapper">
        
        <header class="navbar">
            <div class="nav-container">
                <a href="javascript:history.back()" class="logo">
                    <div class="logo-icon">
                        <img src="../../assets/img/icon.png" alt="Evently" onerror="this.src='https://cdn-icons-png.flaticon.com/512/609/609803.png';">
                    </div>
                    <span>Evently</span>
                </a>
                <nav class="nav-menu">
                    <a href="#" class="active">Fitur</a>
                    <a href="#">Kegiatan</a>
                    <a href="#">Tentang</a>
                </nav>
                <div class="nav-auth">
                    <a href="../auth/login.php" class="btn-masuk">Masuk</a>
                    <a href="../auth/register.php" class="btn-daftar">Daftar Gratis</a>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="content-header">
                <span class="sub-title">FITUR UNGGULAN</span>
                <h1>Dirancang untuk Ekosistem Kampus</h1>
                <p>Dari pencarian acara hingga manajemen pendaftar — semua tersedia dalam satu platform yang intuitif.</p>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <div class="icon-box bg-blue">
                        <img src="../../assets/img/icon-search.png" alt="Cari" onerror="this.src='https://cdn-icons-png.flaticon.com/512/622/622669.png';">
                    </div>
                    <h3>Temukan Kegiatan</h3>
                    <p>Filter berdasarkan kategori, tanggal, atau minat. Tidak perlu lagi buka banyak grup WhatsApp dan Instagram.</p>
                </div>

                <div class="feature-item">
                    <div class="icon-box bg-pink">
                        <img src="../../assets/img/icon-ticket.png" alt="E-Ticket" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1950/1950315.png';">
                    </div>
                    <h3>E-Ticket Digital</h3>
                    <p>Daftar dan simpan bukti pendaftaran digital langsung di profil. Praktis, tidak perlu cetak tiket.</p>
                </div>

                <div class="feature-item">
                    <div class="icon-box bg-cyan">
                        <img src="../../assets/img/icon-user-admin.png" alt="Peserta" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png';">
                    </div>
                    <h3>Kelola Peserta</h3>
                    <p>Organisasi dapat memantau peserta dan mengunduh data Excel/CSV untuk keperluan absensi kegiatan.</p>
                </div>

                <div class="feature-item">
                    <div class="icon-box bg-green">
                        <img src="../../assets/img/icon-check.png" alt="Verifikasi" onerror="this.src='https://cdn-icons-png.flaticon.com/512/5290/5290058.png';">
                    </div>
                    <h3>Verifikasi Admin</h3>
                    <p>Setiap kegiatan diverifikasi Admin sebelum tampil, sehingga kualitas informasi selalu terjaga.</p>
                </div>

                <div class="feature-item">
                    <div class="icon-box bg-purple">
                        <img src="../../assets/img/icon-megaphone.png" alt="Promosi" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1793/1793392.png';">
                    </div>
                    <h3>Promosi Luas</h3>
                    <p>Jangkau lebih banyak mahasiswa sekaligus. Informasi acaramu tersebar ke seluruh pengguna platform.</p>
                </div>

                <div class="feature-item">
                    <div class="icon-box bg-orange">
                        <img src="../../assets/img/icon-lock.png" alt="Akses" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3064/3064155.png';">
                    </div>
                    <h3>Hak Akses Berlapis</h3>
                    <p>Sistem role berbeda untuk Mahasiswa, Organisasi, dan Admin menjamin keamanan pengelolaan data.</p>
                </div>
            </div>
        </main>

        <section class="section-events">
            <div class="section-container">
                <div class="content-header">
                    <span class="sub-title">KEGIATAN TERBARU</span>
                    <h1>Jangan Sampai Ketinggalan</h1>
                </div>

                <div class="events-grid">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): 
                            $style = getKategoriStyle($event['nama_kategori'] ?? '');
                            $harga = (isset($event['harga']) && $event['harga'] > 0) ? 'Berbayar' : 'Gratis';
                            $statusClass = ($harga === 'Gratis') ? 'gratis' : 'berbayar';
                        ?>
                            <div class="event-card">
                                <div class="event-banner <?= $style['bg']; ?>">
                                    <span class="emoji-icon"><?= $style['emoji']; ?></span>
                                </div>
                                <div class="event-body">
                                    <span class="event-tag <?= $style['tag']; ?>"><?= strtoupper(htmlspecialchars($event['nama_kategori'] ?? 'UMUM')); ?></span>
                                    <h3><?= htmlspecialchars($event['judul_event']); ?></h3>
                                    <p class="event-org"><?= htmlspecialchars($event['nama_organisasi'] ?? 'Penyelenggara'); ?></p>
                                    <div class="event-footer">
                                        <span class="event-date"><?= date('d M Y', strtotime($event['tanggal'])); ?></span>
                                        <span class="status-badge <?= $statusClass; ?>"><?= $harga; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="grid-column: 1/-1; text-align: center; color: #64748b;">Belum ada kegiatan terbaru saat ini.</p>
                    <?php endif; ?>
                </div>

                <div class="action-center">
                    <a href="#" class="btn-view-all">Lihat Semua Kegiatan →</a>
                </div>
            </div>
        </section>

        <section class="section-roles">
            <div class="section-container">
                <div class="content-header">
                    <span class="sub-title">UNTUK SIAPA</span>
                    <h1>Satu Platform,<br>Tiga Peran</h1>
                </div>

                <div class="roles-grid">
                    <div class="role-card role-mahasiswa">
                        <span class="role-badge-text">MAHASISWA</span>
                        <h2>Peserta Aktif</h2>
                        <ul class="role-features">
                            <li><span class="check-icon">✓</span> Cari & filter kegiatan sesuai minat</li>
                            <li><span class="check-icon">✓</span> Daftar acara langsung via platform</li>
                            <li><span class="check-icon">✓</span> Simpan E-Tiket di profil</li>
                            <li><span class="check-icon">✓</span> Akses info terpusat</li>
                        </ul>
                        <a href="../auth/register.php" class="btn-role btn-blue-dark">Daftar Mahasiswa</a>
                    </div>

                    <div class="role-card role-organisasi">
                        <span class="role-badge-text">ORGANISASI</span>
                        <h2>Penyelenggara</h2>
                        <ul class="role-features">
                            <li><span class="check-icon">✓</span> Unggah poster & detail kegiatan</li>
                            <li><span class="check-icon">✓</span> Pantau daftar peserta real-time</li>
                            <li><span class="check-icon">✓</span> Download data Excel/CSV</li>
                            <li><span class="check-icon">✓</span> Jangkauan promosi lebih luas</li>
                        </ul>
                        <a href="../auth/register.php" class="btn-role btn-blue-dark">Daftar Organisasi</a>
                    </div>

                    <div class="role-card role-admin">
                        <span class="role-badge-text text-light">ADMIN</span>
                        <h2 class="text-light">Pengawas Sistem</h2>
                        <ul class="role-features text-light">
                            <li><span class="check-icon white-check">✓</span> Verifikasi & validasi postingan</li>
                            <li><span class="check-icon white-check">✓</span> Kelola kategori kegiatan</li>
                            <li><span class="check-icon white-check">✓</span> Pantau akun pengguna</li>
                            <li><span class="check-icon white-check">✓</span> Jaga kualitas & keamanan data</li>
                        </ul>
                        <a href="../auth/register.php" class="btn-role btn-admin-outline">Masuk sebagai Admin</a>
                    </div>
                </div>
            </div>
        </section>

    </div>
</body>
</html>