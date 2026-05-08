<?php
require_once __DIR__ . '/../../presenter/admin_presenter.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Kelola Kategori - Evently
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

            <a href="verifikasi.php" class="menu-item">

                <img src="../../assets/img/icon-ticket.png" alt="Verifikasi">

                Verifikasi Acara

            </a>

            <a href="pengguna.php" class="menu-item">

                <img src="../../assets/img/icon-user-admin.png" alt="Pengguna">

                Pengguna

            </a>

            <a href="kategori.php" class="menu-item active">

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

            <div class="category-header">

                <h2>
                    Kelola Kategori
                </h2>

                <button class="btn-tambah-kat">

                    + Tambah Kategori

                </button>

            </div>

            <div class="category-grid">

                <?php foreach($kategori as $kat): ?>

                <div class="cat-card">

                    <div class="cat-icon">

                        <img 
                            src="../../assets/img/<?= $kat['icon']; ?>" 
                            alt="<?= $kat['nama_kategori']; ?>"
                        >

                    </div>

                    <div class="cat-details">

                        <div class="cat-name">

                            <?= $kat['nama_kategori']; ?>

                        </div>

                        <div class="cat-count">

                            <?= $kat['deskripsi']; ?>

                        </div>

                    </div>

                    <button class="btn-edit-kat">

                        Edit

                    </button>

                </div>

                <?php endforeach; ?>

            </div>

        </main>

    </div>

</body>
</html>