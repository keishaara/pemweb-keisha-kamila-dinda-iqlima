<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new AdminController();
$controller->prosesToggleStatusPengguna();

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$role = isset($_GET['role']) ? trim($_GET['role']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$allUsers = $controller->getAllUsers($keyword, $role, $status);
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

            <form method="GET" action="" class="user-controls">

                <div class="search-wrapper">
                    <button type="submit" class="btn btn-primary">
                        Cari
                    </button>
                    <input 
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="Cari pengguna..."
                        value="<?= htmlspecialchars($keyword); ?>"
                    >
                </div>

                <select name="role" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="organisasi" <?= $role === 'organisasi' ? 'selected' : ''; ?>>Organisasi</option>
                    <option value="mahasiswa" <?= $role === 'mahasiswa' ? 'selected' : ''; ?>>Mahasiswa</option>
                </select>

                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Aktif" <?= $status === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Nonaktif" <?= $status === 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                </select>

            </form>

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
                            <?php foreach($allUsers as $user): 
                                $isAktif = ($user['status'] ?? 'Aktif') === 'Aktif';
                                $roleClass = 'role-' . ($user['tipe_akun'] === 'mahasiswa' ? 'mhs' : ($user['tipe_akun'] === 'admin' ? 'admin' : 'org'));
                            ?>
                            <tr>
                                <td>
                                    <b><?= htmlspecialchars($user['nama_lengkap']); ?></b>
                                </td>
                                <td>
                                    <?= htmlspecialchars($user['email']); ?>
                                </td>
                                <td>
                                    <span class="role-pill <?= $roleClass; ?>"><?= ucfirst($user['tipe_akun']); ?></span>
                                </td>
                                
                                <td>
                                    <span class="status-pill <?= $isAktif ? 'aktif' : 'nonaktif'; ?>">
                                        <?= $isAktif ? 'Aktif' : 'Nonaktif'; ?>
                                    </span>
                                </td>
                                
                                <td align="center">
                                    <a href="pengguna.php?action=toggle_status&id=<?= $user['id']; ?>&current=<?= $isAktif ? 'Aktif' : 'Nonaktif'; ?>" 
                                       class="btn-table-action btn-nonaktif <?= !$isAktif ? 'btn-aktifkan' : ''; ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin <?= $isAktif ? 'menonaktifkan' : 'mengaktifkan kembali'; ?> pengguna ini?')">
                                        <?= $isAktif ? 'Nonaktifkan' : 'Aktifkan'; ?>
                                    </a>
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