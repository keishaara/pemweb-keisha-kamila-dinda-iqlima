<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evently - Platform Kegiatan Kampus #1</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="landing-page">
        <nav class="navbar">
            <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="nav-links">
                <a href="#">Fitur</a>
                <a href="kegiatan.php">Kegiatan</a>
                <a href="#">Tentang</a>
                <?php if (!isset($_SESSION['user_id'])): ?>        
                <?php else: ?>
                    <a href="<?= ($_SESSION['role'] === 'admin') ? '../admin/dashboard.php' : '../mahasiswa/user_dashboard.php'; ?>" class="btn btn-outline btn-small active">Dashboard</a>
                <?php endif; ?>
            </div>
        </nav>

        <section class="hero">
            <div class="hero-badge">Platform Kegiatan Kampus #1</div>
            <h1>Semua Kegiatan<br>Kampus, Satu <span>Tempat</span></h1>
            <p>Evently menyatukan informasi seminar, workshop, dan event organisasi kampus dalam satu platform terpusat yang mudah diakses.</p>
            <div class="hero-buttons">
                <a href="../auth/register.php" class="btn btn-primary">Mulai Sekarang</a>
                <a href="kegiatan.php" class="btn btn-outline">Lihat Kegiatan</a>
            </div>

            <div class="stats">
                <div class="stat-item"><h3>2.4K+</h3><p>Mahasiswa</p></div>
                <div class="stat-item"><h3>150</h3><p>Organisasi</p></div>
                <div class="stat-item"><h3>30</h3><p>Kegiatan</p></div>
                <div class="stat-item"><h3>8</h3><p>Kategori</p></div>
            </div>
        </section>
    </div>
</body>
</html>
