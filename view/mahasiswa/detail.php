<?php
/**
 * @var array $event
 * @var bool $isSaved
 * @var bool $isRegistered
 * @var string $backUrl
 * @var bool $isFromTicket
 * @var string $kodeBooking
 */
// Logic has been moved to MahasiswaController
$event = $event ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Event - Evently</title>
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
       <?php if(isset($_SESSION['success'])): ?>

        <div class="profile-message profile-success">
            <?= $_SESSION['success']; ?>
        </div>

        <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <div class="detail-card">
            <div class="detail-header">
                <?php
                if (isset($_GET['from'])) {

                if ($_GET['from'] === 'ticket') {
                    $backUrl = 'index.php?module=mahasiswa&action=etiket';
                }

                elseif ($_GET['from'] === 'dashboard') {
                    $backUrl = 'index.php?module=mahasiswa&action=dashboard';
                }

                else {
                    $backUrl = 'index.php?module=mahasiswa&action=kegiatan';
                }

            } else {

                $backUrl = 'index.php?module=mahasiswa&action=kegiatan';
            }
                ?>

                <a href="<?= $backUrl ?>"class="btn-outline">
                    Kembali
                </a>
                <button onclick="copyLink()" class="btn-outline">
                    Bagikan
                </button>

            </div>

            <div class="detail-banner">
                <?php 
                if (!empty($event['poster']) && file_exists(__DIR__ . '/../../assets/poster/' . $event['poster'])): 
                ?>
                    <img src="assets/poster/<?= htmlspecialchars($event['poster']); ?>" alt="Poster <?= htmlspecialchars($event['judul_event']); ?>">
                <?php else: ?>
                    <img src="assets/poster/default.png" alt="Default Poster">
                <?php endif; ?>
            </div>

            <div class="tags">

                <span>
                    <?= htmlspecialchars(strtoupper($event['nama_kategori'] ?? 'UMUM')) ?>
                </span>

                <span class="green">
                    Sertifikat
                </span>

            </div>

            <h2>
                <?= htmlspecialchars($event['judul_event']) ?>
            </h2>

            <div class="detail-grid">
                <div class="left">
                    <div class="info-box">

                        <div>📅</div>
                        <div>
                            <p>Tanggal dan Waktu</p>
                            <strong>
                                <?= htmlspecialchars($event['tanggal']) ?><br>
                                <?= htmlspecialchars($event['waktu']) ?>
                            </strong>

                        </div>

                    </div>

                    <div class="info-box">
                        <div>📍</div>
                        <div>
                            <p>Lokasi</p>
                            <strong>
                                <?= htmlspecialchars($event['lokasi']) ?>
                            </strong>
                        </div>

                    </div>

                    <div class="info-box">
                        <div>👥</div>
                        <div>
                            <p>Peserta</p>
                            <strong>
                                Maks 50 Orang
                            </strong>
                        </div>
                    </div>

                    <div class="info-box">
                        <div>🎓</div>
                        <div>
                            <p>Penyelenggara</p>
                            <strong>
                                <?= htmlspecialchars($event['penyelenggara']) ?>
                            </strong>
                        </div>

                    </div>
                    <div class="description">
                        <h4>Tentang Event</h4>
                        <p>
                            <?= nl2br(htmlspecialchars($event['deskripsi'])) ?>
                        </p>

                    </div>
                </div>

                <?php
                $isFromTicket = isset($_GET['from']) && $_GET['from'] === 'ticket';
                $kodeBooking = $_GET['kode'] ?? '';
                ?>

                <div class="right">

                    <?php if (!$isFromTicket): ?>

                        <div class="price-box">
                            <p>HARGA PENDAFTARAN</p>

                            <h2>
                                Rp <?= number_format($event['harga'], 0, ',', '.') ?>
                            </h2>

                            <div class="progress">
                                <div class="bar"></div>
                            </div>

                            <?php 
                                $isExpired = strtotime($event['tanggal']) < strtotime(date('Y-m-d'));
                            ?>
                            <?php if($isRegistered): ?>
                                <button class="btn-primary btn-disabled" disabled>
                                    Sudah Terdaftar
                                </button>
                            <?php elseif($isExpired): ?>
                                <button class="btn-primary btn-disabled" disabled style="background: #e2e8f0; color: #64748b; cursor: not-allowed; opacity: 0.8;">
                                    Event Telah Berakhir
                                </button>
                            <?php else: ?>
                                <a href="index.php?module=mahasiswa&action=dataDiri&id=<?= $event['id'] ?>" class="btn-primary">
                                    Daftar Sekarang
                                </a>
                            <?php endif; ?>

                           <?php if ($isSaved): ?>

                            <button class="btn-secondary" disabled>
                                ✓ Tersimpan
                            </button>

                        <?php else: ?>

                            <form method="POST" action="index.php?module=mahasiswa&action=simpanEventAction">
                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                <input type="hidden" name="from" value="detail">
                                <button type="submit" name="simpan_event" class="btn-secondary">
                                    Simpan Event
                                </button>
                            </form>

                        <?php endif; ?>
                        </div>

                        <?php else: ?>

                            <div class="price-box">
                                <p>STATUS TIKET</p>

                                <h2 class="status-verified-title">
                                    Terverifikasi
                                </h2>

                                <div class="progress">
                                    <div class="bar"></div>
                                </div>

                                <p class="kode-booking-label">KODE BOOKING</p>

                                <strong>
                                    <?= htmlspecialchars($kodeBooking) ?>
                                </strong>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
    <script src="assets/js/mahasiswa_detail.js"></script>
</body>
</html>
