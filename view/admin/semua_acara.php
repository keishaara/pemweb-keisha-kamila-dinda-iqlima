<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new AdminController();
$semuaAcara = $controller->getAllEvents();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Semua Acara - Admin Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
          <div class="logo">
               <img src="../../assets/img/icon.png" alt="Evently">
               Evently
          </div>
    
          <div class="menu-category">Manajemen</div>
    
          <a href="dashboard.php" class="menu-item">
               <img src="../../assets/img/icon-home2.png" alt="Dashboard">
               Dashboard
          </a>

          <a href="verifikasi.php" class="menu-item">
               <img src="../../assets/img/icon-ticket.png" alt="Verifikasi">
               Verifikasi Acara
          </a>

          <a href="semua_acara.php" class="menu-item active">
               <img src="../../assets/img/icon-allevents.png" alt="Semua Acara">
               Semua Acara
          </a>

          <a href="pengguna.php" class="menu-item">
               <img src="../../assets/img/icon-user-admin.png" alt="Pengguna">
               Pengguna
          </a>

          <a href="kategori.php" class="menu-item">
               <img src="../../assets/img/icon-kegiatan.png" alt="Kategori">
               Kategori
          </a>

          <div class="menu-category">Sistem</div>
    
          <a href="../auth/logout.php" class="menu-item">
               <img src="../../assets/img/icon-logout.png" alt="Logout">
               Keluar
          </a>
     </aside>

        <main class="main-content">
          <div class="page-header">
               <h2>Daftar Semua Acara</h2>
               <p class="subtitle">
                    Berikut adalah semua acara yang telah didaftarkan di platform Evently.
               </p>
          </div>
             
            <div class="events-table-container">
                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>Judul Acara</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($semuaAcara)): ?>
                            <?php foreach($semuaAcara as $acara): ?>
                            <tr>
                                <td><?= htmlspecialchars($acara['judul_event']); ?></td>
                                <td><?= htmlspecialchars($acara['nama_kategori'] ?? 'Tanpa Kategori'); ?></td>
                                <td><?= htmlspecialchars($acara['tanggal']); ?></td>
                                <td>
                                    <?php 
                                    $status = strtolower($acara['status']); 
                                    ?>
                                    <span class="status-pill status-<?= $status; ?>">
                                        <?= ucfirst($status); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">Belum ada acara yang didaftarkan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>