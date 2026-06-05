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
// $totalMahasiswa = $controller->getTotalMahasiswa();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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
            <a href="dashboard.php" class="menu-item active">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
            <a href="verifikasi.php" class="menu-item">
                <i class="fa-solid fa-ticket"></i>
                Verifikasi Acara
            </a>
            <a href="semua_acara.php" class="menu-item">
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
            <a href="../auth/logout.php" class="menu-item" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="fa-solid fa-right-from-bracket"></i>
                Keluar
            </a>
        </aside>

        <main class="main-content">
            <div class="admin-banner">
                <h1>Panel Admin</h1>
                <p>Semua platform dalam kendalimu</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card" onclick="window.location.href='pengguna.php'" style="cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    <h3><?= $totalUsersData; ?></h3>
                    <p>Total Pengguna</p>
                    <div class="stat-trend">Data pengguna terdaftar</div>
                </div>

                <div class="stat-card" onclick="window.location.href='pengguna.php'" style="cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
                    <h3><?= $totalMahasiswa; ?></h3>
                    <p>Mahasiswa Aktif</p>
                    <div class="stat-trend">Total akun mahasiswa</div>
                </div>

                <div class="stat-card" onclick="window.location.href='pengguna.php'" style="cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
                    <h3><?= $totalOrgData; ?></h3>
                    <p>Organisasi Aktif</p>
                    <div class="stat-trend">Total akun organisasi</div>
                </div>

                <div class="stat-card" onclick="window.location.href='verifikasi.php'" style="cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
                    <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                    <h3><?= count(array_filter($verifikasiAcara, fn($e) => strtolower($e['status']) == 'pending')); ?></h3>
                    <p>Menunggu Verifikasi</p>
                    <div class="stat-trend negative">Perlu tindakan admin</div>
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
