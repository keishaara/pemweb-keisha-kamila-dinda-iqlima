<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new AdminController();
$controller->prosesLockEvent(); // Tambahkan pemanggilan method di sini
$semuaAcara = $controller->getAllEvents();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Semua Acara - Admin Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
           <div class="logo">
               <i class="fa-solid fa-calendar-check"></i>
               Evently
           </div>
    
          <div class="menu-category">Manajemen</div>
    
           <a href="dashboard.php" class="menu-item">
               <i class="fa-solid fa-chart-line"></i>
               Dashboard
           </a>

           <a href="verifikasi.php" class="menu-item">
               <i class="fa-solid fa-ticket"></i>
               Verifikasi Acara
           </a>

           <a href="semua_acara.php" class="menu-item active">
               <i class="fa-solid fa-calendar-days"></i>
               Semua Acara
           </a>

           <a href="pengguna.php" class="menu-item">
               <i class="fa-solid fa-users"></i>
               Pengguna
           </a>

           <a href="kategori.php" class="menu-item">
               <i class="fa-solid fa-layer-group"></i>
               Kategori
           </a>

          <div class="menu-category">Sistem</div>
    
           <a href="../auth/logout.php" class="menu-item" onclick="return confirm('Apakah Anda yakin ingin logout?')">
               <i class="fa-solid fa-right-from-bracket"></i>
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
                            <th>Aksi</th>
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
                                    if ($status === 'locked') {
                                        $statusClass = 'status-locked';
                                        $statusText = 'Locked';
                                    } else {
                                        $statusClass = 'status-' . $status;
                                        $statusText = ucfirst($status);
                                    }
                                    ?>
                                    <span class="status-pill <?= $statusClass; ?> <?= $status === 'locked' ? 'locked-pill' : '' ?>">
                                        <?= $statusText; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($status === 'approved'): ?>
                                        <a href="semua_acara.php?action=lock&id=<?= $acara['id']; ?>" class="btn-table-action btn-lock" onclick="return confirm('Apakah Anda yakin ingin MENGUNCI (suspend) acara ini? Ini akan menyembunyikan acara dari mahasiswa.');">
                                            <i class="fas fa-lock"></i> Kunci
                                        </a>
                                    <?php elseif ($status === 'locked'): ?>
                                        <div class="flex gap-5">
                                            <a href="semua_acara.php?action=unlock_approve&id=<?= $acara['id']; ?>" class="btn-table-action btn-approve" onclick="return confirm('Setujui kembali acara ini?');">
                                                <i class="fas fa-check"></i> Setujui
                                            </a>
                                            <a href="semua_acara.php?action=unlock_reject&id=<?= $acara['id']; ?>" class="btn-table-action btn-reject" onclick="return confirm('Tolak acara ini secara permanen?');">
                                                <i class="fas fa-times"></i> Tolak
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-empty">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="org-no-data-cell">Belum ada acara yang didaftarkan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>