<?php
require_once __DIR__ . '/../../presenter/admin_presenter.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Verifikasi Acara - Evently
    </title>

    <link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body>

    <div class="dashboard-layout">

        <aside class="sidebar">

            <div class="logo">

                <img src="../../assets/img/icon.png" alt="Evently">

                Evently

            </div>

            <div class="menu-category">
                Manajemen
            </div>

            <a href="dashboard.php" class="menu-item">

                <img src="../../assets/img/icon-home2.png" alt="Dashboard">

                Dashboard

            </a>

            <a href="verifikasi.php" class="menu-item active">

                <img src="../../assets/img/icon-ticket2.png" alt="Verifikasi">

                Verifikasi Acara

            </a>

            <a href="pengguna.php" class="menu-item">

                <img src="../../assets/img/icon-user-admin.png" alt="Pengguna">

                Pengguna

            </a>

            <a href="kategori.php" class="menu-item">

                <img src="../../assets/img/icon-kegiatan.png" alt="Kategori">

                Kategori

            </a>

            <div class="menu-category">
                Sistem
            </div>

            <a href="../auth/logout.php" class="menu-item">

                <img src="../../assets/img/icon-logout.png" alt="Logout">

                Keluar

            </a>

        </aside>

        <main class="main-content">

            <div class="page-header">

                <h2>
                    Verifikasi Acara
                </h2>

                <p class="verif-subtitle">

                    <?= count($verifikasiAcara); ?> acara tersedia

                </p>

            </div>

            <!-- LOOP ACARA -->
            <?php foreach($verifikasiAcara as $acara): ?>

            <div class="verif-card">

                <!-- ICON -->
                <div class="verif-icon-box">

                    <img src="../../assets/img/ver.png" alt="Event">

                </div>

                <!-- INFO -->
                <div class="verif-info">

                    <div class="verif-tags">

                        <span class="status-pill menunggu" style="border:none;">

                            <?= ucfirst($acara['status']); ?>

                        </span>

                        <span class="tag-kategori">

                            <?= $acara['nama_kategori']; ?>

                        </span>

                    </div>

                    <div class="verif-title">

                        <?= $acara['judul_event']; ?>

                    </div>

                    <div class="verif-org">

                        <?= $acara['penyelenggara']; ?>

                    </div>

                    <div class="verif-details">

                        <span>
                            <?= $acara['tanggal']; ?>
                        </span>

                        <span>
                            <?= $acara['waktu']; ?>
                        </span>

                        <span>
                            <?= $acara['lokasi']; ?>
                        </span>

                        <span>
                            Rp<?= number_format($acara['harga']); ?>
                        </span>

                    </div>

                </div>

                <!-- ACTION -->
                <div class="verif-actions">

                    <button class="btn-verif btn-tolak">

                        Tolak

                    </button>

                    <button class="btn-verif btn-setujui">

                        Setujui

                    </button>

                </div>

            </div>

            <?php endforeach; ?>

        </main>

    </div>

</body>
</html>