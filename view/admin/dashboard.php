<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new AdminController();
$totalUsersData = $controller->getTotalUsers(); 
$latestEvents = $controller->getLatestEvents();
$totalOrgData = $controller->getTotalOrganisasi(); 
$latestUsers = $controller->getLatestUsers();
$verifikasiAcara = $controller->getVerifikasiAcara();
$semuaAcara = $controller->getAllEvents();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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
            <a href="dashboard.php" class="menu-item active">
                <img src="../../assets/img/icon-dash-admin-active.png" alt="Dashboard">
                Dashboard
            </a>
            <a href="verifikasi.php" class="menu-item">
                <img src="../../assets/img/icon-ticket.png" alt="Verifikasi">
                Verifikasi Acara
            </a>
            <a href="semua_acara.php" class="menu-item">
                <img src="../../assets/img/icon-allevent.png" alt="Semua Acara">
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
            <a href="../auth/logout.php" class="menu-item" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <img src="../../assets/img/icon-logout.png" alt="Logout">
                Keluar
            </a>
        </aside>

        <main class="main-content">
            <div class="admin-banner">
                <h1>Panel Admin</h1>
                <p>Semua platform dalam kendalimu</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><img src="../../assets/img/dsa1.png" alt="Users"></div>
                    <h3><?= $totalUsersData; ?></h3>
                    <p>Total Pengguna</p>
                    <div class="stat-trend">Data pengguna terdaftar</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><img src="../../assets/img/dsa2.png" alt="Acara"></div>
                    <h3><?= count($verifikasiAcara); ?></h3>
                    <p>Acara Menunggu</p>
                    <div class="stat-trend">Jumlah acara yang perlu diverifikasi</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><img src="../../assets/img/dsa3.png" alt="Verifikasi"></div>
                    <h3><?= count(array_filter($verifikasiAcara, fn($e) => $e['status'] == 'pending')); ?></h3>
                    <p>Menunggu Verifikasi</p>
                    <div class="stat-trend negative">Perlu tindakan admin</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><img src="../../assets/img/dsa4.png" alt="Organisasi"></div>
                    <h3><?= $totalOrgData; ?></h3>
                    <p>Organisasi Aktif</p>
                    <div class="stat-trend">Total akun organisasi</div>
                </div>
            </div>

            <div class="sections-wrapper">
                <div class="table-container">
                    <h3 class="table-header-title">Acara Terbaru</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Acara</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($semuaAcara, 0, 3) as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['judul_event']); ?></td>
                                <td>
                                    <?php
                                        $eventStatus = strtolower($event['status'] ?? 'pending');
                                        if ($eventStatus === 'approved') {
                                            $statusClass = 'disetujui';
                                            $statusLabel = 'Disetujui';
                                        } elseif ($eventStatus === 'rejected') {
                                            $statusClass = 'ditolak';
                                            $statusLabel = 'Ditolak';
                                        } else {
                                            $statusClass = 'menunggu';
                                            $statusLabel = 'Menunggu';
                                        }
                                    ?>
                                    <span class="status-pill <?= $statusClass ?>">
                                        <?= htmlspecialchars($statusLabel); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-container">
                    <h3 class="table-header-title">Pengguna Terbaru</h3>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($latestUsers as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                                <td><?= ucfirst($user['tipe_akun']); ?></td>
                                <td><span class="status-pill aktif">Aktif</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        history.pushState(null, null, window.location.href);

        window.addEventListener('popstate', function (event) {
            const yakinLogout = confirm("Apakah Anda ingin logout?");
            
            if (yakinLogout) {
                window.location.href = '../auth/logout.php'; 
            } else {
                history.pushState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>
