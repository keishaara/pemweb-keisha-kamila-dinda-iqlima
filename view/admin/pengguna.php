<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController();
$allUsers = $controller->getAllUsers();
$totalUsersCount = $controller->getTotalUsers();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna - Evently</title>
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

            <a href="pengguna.php" class="menu-item active">
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

                <h2 style="font-size: 28px; color: #335485; font-family: serif;">
                    Manajemen Pengguna
                </h2>

                <p class="verif-subtitle">
                    <?= $totalUsersCount; ?> pengguna terdaftar
                </p>

            </div>

            <div class="user-controls">

                <div class="search-wrapper">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>
                    <input 
                        type="text"
                        class="search-input"
                        placeholder="Cari pengguna..."
                    >
                </div>

                <select class="filter-select">
                    <option>Semua Role</option>
                </select>

                <select class="filter-select">
                    <option>Semua Status</option>
                </select>

            </div>

            <div class="user-table-card">

                <table class="table-container-simple">

                    <thead>
                        <tr>
                            <th>NAMA</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                            <th>STATUS</th>
                            <th style="text-align:center;">AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($allUsers)): ?>
                            <?php foreach($allUsers as $user): ?>
                            <tr>
                                <td>
                                    <b><?= htmlspecialchars($user['nama_lengkap']); ?></b>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user['email']); ?>
                                </td>
                                <td>
                                    <?php if($user['tipe_akun'] == 'mahasiswa'): ?>
                                        <span class="role-pill role-mhs">Mahasiswa</span>
                                    <?php else: ?>
                                        <span class="role-pill role-org">Organisasi</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pill aktif">Aktif</span>
                                </td>
                                <td align="center">
                                    <button class="btn-table-action btn-nonaktif">
                                        Nonaktifkan
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" align="center">Tidak ada data pengguna.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>

        </main>

    </div>

</body>
</html>