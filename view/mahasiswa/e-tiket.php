<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql_tiket = "SELECT b.kode_booking, b.status as status_booking, 
                     e.judul_event, e.tanggal, e.waktu, e.lokasi, e.penyelenggara,
                     c.nama_kategori
              FROM bookings b
              JOIN events e ON b.event_id = e.id
              LEFT JOIN categories c ON e.kategori_id = c.id
              WHERE b.user_id = '$user_id'
              ORDER BY e.tanggal ASC";

$res_tiket = mysqli_query($conn, $sql_tiket);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket Saya - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item active"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>E-Tiket Saya</h2>
                <p>Tunjukkan tiket ini saat melakukan registrasi di lokasi acara.</p>
            </div>

            <div class="ticket-list">
                <?php if (mysqli_num_rows($res_tiket) > 0): ?>
                    <?php while($tiket = mysqli_fetch_assoc($res_tiket)): ?>
                    <div class="verif-card ticket-card">
                        <div class="verif-icon-box ticket-qr">
                            <img src="../../assets/img/qr-placeholder.png" alt="QR Code Ticket">
                            <small style="display:block; margin-top:5px; color:#64748b; font-size:10px;">
                                <?= $tiket['kode_booking'] ?>
                            </small>
                        </div>

                        <div class="verif-info">
                            <div class="verif-tags">
                                <?php 
                                    $status_class = ($tiket['status_booking'] == 'active') ? 'disetujui' : 'aktif';
                                    $status_label = ($tiket['status_booking'] == 'active') ? 'Terverifikasi' : 'Selesai';
                                ?>
                                <span class="status-pill <?= $status_class ?>" style="border:none;"><?= $status_label ?></span>
                                <span class="tag-kategori"><?= htmlspecialchars($tiket['nama_kategori'] ?? 'Umum') ?></span>
                            </div>
                            
                            <div class="verif-title"><?= htmlspecialchars($tiket['judul_event']) ?></div>
                            <div class="verif-org">Oleh <?= htmlspecialchars($tiket['penyelenggara']) ?></div>
                            
                            <div class="verif-details">
                                <span><img src="../../assets/img/icon-time.png" style="width:12px;"> <?= date('d M Y', strtotime($tiket['tanggal'])) ?></span>
                                <span><img src="../../assets/img/icon-time.png" style="width:12px;"> <?= date('H.i', strtotime($tiket['waktu'])) ?> WIB</span>
                                <span><img src="../../assets/img/icon-loc.png" style="width:12px;"> <?= htmlspecialchars($tiket['lokasi']) ?></span>
                            </div>
                        </div>

                        <div class="verif-actions">
                            <button class="btn btn-primary btn-small">Unduh PDF</button>
                            <button class="btn btn-outline btn-small">Lihat Detail</button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        <p>Kamu belum memiliki tiket.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>