<?php

session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';

$controller = new MahasiswaController();

$event = $controller->detailEvent();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Event - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">

        <div class="logo">
            <img src="../../assets/img/icon.png" alt="Evently">
            Evently
        </div>

        <div class="menu-category">Menu</div>

        <a href="user_dashboard.php" class="menu-item active">
            <img src="../../assets/img/icon-home2.png" alt="Home">
            Beranda
        </a>

        <a href="kegiatan.php" class="menu-item">
            <img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan">
            Kegiatan
        </a>

        <a href="e-tiket.php" class="menu-item">
            <img src="../../assets/img/icon-ticket.png" alt="E-Tiket">
            E-Tiket
        </a>

        <div class="menu-category">Akun</div>

        <a href="profil.php" class="menu-item">
            <img src="../../assets/img/icon-user2.png" alt="Profil">
            Profil Saya
        </a>

        <a href="../../logout.php" class="menu-item">
            <img src="../../assets/img/icon-logout.png" alt="Logout">
            Keluar
        </a>

    </aside>

    <main class="content">

        <div class="detail-card">

            <div class="detail-header">

                <a href="kegiatan.php"
                   class="btn-outline"
                   style="text-decoration: none; display: inline-block;">

                    Kembali

                </a>

                <button class="btn-outline">
                    Bagikan
                </button>

            </div>

            <div class="banner">
                💻
            </div>

            <div class="tags">

                <span>
                    <?= htmlspecialchars($event['kategori']) ?>
                </span>

                <span>
                    <?= htmlspecialchars($event['topik']) ?>
                </span>

                <span class="green">
                    Sertifikat
                </span>

            </div>

            <h2>
                <?= htmlspecialchars($event['nama_event']) ?>
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
                                Maks. <?= htmlspecialchars($event['kuota']) ?> Orang
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

                <div class="right">

                    <div class="price-box">

                        <p>HARGA PENDAFTARAN</p>

                        <h2>
                            Rp <?= number_format($event['harga'], 0, ',', '.') ?>
                        </h2>

                        <div class="progress">
                            <div class="bar"></div>
                        </div>

                        <small>
                            <?= htmlspecialchars($event['jumlah_pendaftar']) ?>
                            orang telah mendaftar
                        </small>

                        <button
                            class="btn-primary"
                            onclick="window.location='data_diri.php?event_id=<?= $event['id'] ?>'">

                            Daftar Sekarang

                        </button>

                        <button class="btn-secondary">
                            Simpan Event
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>