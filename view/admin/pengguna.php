<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$controller = new AdminController();
$controller->prosesToggleStatusPengguna();

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$role = isset($_GET['role']) ? trim($_GET['role']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$allUsers = $controller->getAllUsers($keyword, $role, $status);
$hasStatusColumn = $controller->usersHaveStatusColumn();
$totalUsersCount = $controller->getTotalUsers();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna - Evently</title>
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

            <div class="menu-category">
                Manajemen
            </div>

            <a href="dashboard.php" class="menu-item">
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

            <a href="pengguna.php" class="menu-item active">
                <i class="fa-solid fa-users"></i>
                Pengguna
            </a>

            <a href="kategori.php" class="menu-item">
                <i class="fa-solid fa-layer-group"></i>
                Kategori
            </a>

            <div class="menu-category">
                Sistem
            </div>

            <a href="../auth/logout.php" class="menu-item">
                <i class="fa-solid fa-right-from-bracket"></i>
                Keluar
            </a>

        </aside>

        <main class="main-content">

            <div class="page-header">
                <h2>
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

                <?php if ($hasStatusColumn): ?>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Aktif" <?= $status === 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Nonaktif" <?= $status === 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                <?php endif; ?>

            </form>

            <div class="user-table-card">

                <table class="table-container-simple">

                    <thead>
                        <tr>
                            <th>NAMA</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                            <?php if ($hasStatusColumn): ?>
                                <th>STATUS</th>
                            <?php endif; ?>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(!empty($allUsers)): ?>
                            <?php foreach($allUsers as $user): 
                                $isAktif = $hasStatusColumn ? (($user['status'] ?? 'Aktif') === 'Aktif') : true;
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
                                <?php if ($hasStatusColumn): ?>
                                    <td>
                                        <span class="status-pill <?= $isAktif ? 'aktif' : 'nonaktif'; ?>">
                                            <?= $isAktif ? 'Aktif' : 'Nonaktif'; ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                                <td align="center">
                                    <?php if ($hasStatusColumn): ?>
                                        <a href="pengguna.php?action=toggle_status&id=<?= $user['id']; ?>&current=<?= $isAktif ? 'Aktif' : 'Nonaktif'; ?>"
                                           class="btn-table-action btn-nonaktif <?= !$isAktif ? 'btn-aktifkan' : ''; ?>" 
                                           onclick="return confirm('Apakah Anda yakin ingin <?= $isAktif ? 'menonaktifkan' : 'mengaktifkan kembali'; ?> pengguna ini?')">
                                            <?= $isAktif ? 'Nonaktifkan' : 'Aktifkan'; ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= $hasStatusColumn ? 5 : 4 ?>" align="center">Tidak ada data pengguna.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>