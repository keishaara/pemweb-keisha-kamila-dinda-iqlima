<?php if (!isset($totalUsersData)) { header('Location: index.php?module=admin&action=dashboard'); exit; } ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
            <a href="index.php?module=admin&action=dashboard" class="menu-item active">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>
            <a href="index.php?module=admin&action=verifikasi" class="menu-item">
                <i class="fa-solid fa-ticket"></i>
                Verifikasi Acara
            </a>
            <a href="index.php?module=admin&action=semua_acara" class="menu-item">
                <i class="fa-solid fa-calendar-days"></i>
                Semua Acara
            </a>
            <a href="index.php?module=admin&action=pengguna" class="menu-item">
                <i class="fa-solid fa-users"></i>
                Pengguna
            </a>
            <a href="index.php?module=admin&action=kategori" class="menu-item">
                <i class="fa-solid fa-layer-group"></i>
                Kategori
            </a>
            <div class="menu-category">Sistem</div>
            <a href="index.php?module=auth&action=logout" class="menu-item" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="fa-solid fa-right-from-bracket"></i>
                Keluar
            </a>
        </aside>

        <main class="main-content">
            <div class="ccc">
                <h1>Panel Admin</h1>
                <p>Semua platform dalam kendalimu</p>
            </div>

            <div class="org-stats">
                <div class="org-stat-card clickable-card" onclick="window.location.href='index.php?module=admin&action=pengguna'">
                    <div class="org-stat-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="org-stat-info">
                        <h3><?= $totalUsersData; ?></h3>
                        <p>Total Pengguna</p>
                    </div>
                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='index.php?module=admin&action=pengguna'">
                    <div class="org-stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
                    <div class="org-stat-info">
                        <h3><?= htmlspecialchars($totalMahasiswa ?? 0); ?></h3>
                        <p>Mahasiswa Aktif</p>
                    </div>
                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='index.php?module=admin&action=pengguna'">
                    <div class="org-stat-icon"><i class="fa-solid fa-building"></i></div>
                    <div class="org-stat-info">
                        <h3><?= htmlspecialchars($totalOrgData ?? 0); ?></h3>
                        <p>Organisasi Aktif</p>
                    </div>
                </div>

                <div class="org-stat-card clickable-card" onclick="window.location.href='index.php?module=admin&action=verifikasi'">
                    <div class="org-stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                    <div class="org-stat-info">
                        <h3><?= htmlspecialchars(count(array_filter($verifikasiAcara ?? [], function($e) { return strtolower($e['status'] ?? '') === 'pending'; }))); ?></h3>
                        <p>Menunggu Verifikasi</p>
                    </div>
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
                            <?php foreach(array_slice($semuaAcara ?? [], 0, 3) as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['judul_event'] ?? ''); ?></td>
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
                            <?php foreach($latestUsers ?? [] as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['nama_lengkap'] ?? ''); ?></td>
                                <td><?= ucfirst($user['tipe_akun'] ?? ''); ?></td>
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
                window.location.href = 'index.php?module=auth&action=logout'; 
            } else {
                history.pushState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>
