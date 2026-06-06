<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new AdminController();
$controller->prosesHapusKategori();
$controller->prosesTambahKategori();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
    $id_edit = intval($_POST['id_kategori']);
    $controller->prosesEditKategori($id_edit);
}

$kategori = $controller->getKategori();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori - Evently</title>
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
            <a href="semua_acara.php" class="menu-item">
                <i class="fa-solid fa-calendar-days"></i>
                Semua Acara
            </a>

            <a href="pengguna.php" class="menu-item">
                <i class="fa-solid fa-users"></i>
                Pengguna
            </a>

            <a href="kategori.php" class="menu-item active">
                <i class="fa-solid fa-layer-group"></i>
                Kategori
            </a>

            <div class="menu-category">Sistem</div>

            <a href="../auth/logout.php" class="menu-item">
                <i class="fa-solid fa-right-from-bracket"></i>
                Keluar
            </a>
        </aside>

        <main class="main-content">

            <div class="category-header">
                <div class="page-header">
                    <h2>Kelola Kategori</h2>
                    <p class="subtitle">Tambahkan, edit, atau hapus kategori acara sesuai kebutuhan.</p>
                </div>
                <button class="btn-tambah-kat" onclick="bukaModal()">+ Tambah Kategori</button>
            </div>

            <?php if (isset($_SESSION['kat_error'])): ?>
                <div class="kat-alert-danger">
                    <?= $_SESSION['kat_error']; ?>
                </div>
                <?php unset($_SESSION['kat_error']); ?>
            <?php endif; ?>

            <div class="category-grid">
                <?php if (!empty($kategori)): ?>
                    <?php foreach($kategori as $kat): ?>
                    <div class="cat-card">
                        <div class="cat-icon">
                            <?php 
                                $nama_kat = strtolower($kat['nama_kategori']);
                                $fa_icon = 'fa-solid fa-layer-group';
                                if (strpos($nama_kat, 'music') !== false || strpos($nama_kat, 'musik') !== false) {
                                    $fa_icon = 'fa-solid fa-music';
                                } elseif (strpos($nama_kat, 'olahraga') !== false || strpos($nama_kat, 'sport') !== false) {
                                    $fa_icon = 'fa-solid fa-medal';
                                } elseif (strpos($nama_kat, 'teknologi') !== false || strpos($nama_kat, 'tech') !== false || strpos($nama_kat, 'it') !== false) {
                                    $fa_icon = 'fa-solid fa-laptop-code';
                                } elseif (strpos($nama_kat, 'seni') !== false || strpos($nama_kat, 'art') !== false) {
                                    $fa_icon = 'fa-solid fa-palette';
                                } elseif (strpos($nama_kat, 'pendidikan') !== false || strpos($nama_kat, 'seminar') !== false || strpos($nama_kat, 'education') !== false) {
                                    $fa_icon = 'fa-solid fa-graduation-cap';
                                } elseif (strpos($nama_kat, 'bisnis') !== false || strpos($nama_kat, 'business') !== false) {
                                    $fa_icon = 'fa-solid fa-briefcase';
                                } elseif (strpos($nama_kat, 'kesehatan') !== false || strpos($nama_kat, 'health') !== false) {
                                    $fa_icon = 'fa-solid fa-heart-pulse';
                                } elseif (strpos($nama_kat, 'budaya') !== false || strpos($nama_kat, 'culture') !== false) {
                                    $fa_icon = 'fa-solid fa-masks-theater';
                                }
                            ?>
                            <i class="<?= $fa_icon; ?>"></i>
                        </div>

                        <div class="cat-details">
                            <div class="cat-name">
                                <?= htmlspecialchars($kat['nama_kategori']); ?>
                            </div>
                            <div class="cat-count">
                                <?= htmlspecialchars($kat['deskripsi'] ?? 'Tidak ada deskripsi'); ?>
                            </div>
                        </div>

                        <div class="action-flex">
                            <button type="button" class="btn-edit-kat-btn" 
                                    onclick="bukaModalEdit(<?= $kat['id']; ?>, <?= htmlspecialchars(json_encode($kat['nama_kategori'])); ?>, <?= htmlspecialchars(json_encode($kat['deskripsi'] ?? '')); ?>)">
                                Edit
                            </button>
                            <a href="kategori.php?action=hapus&id=<?= $kat['id']; ?>" class="btn-delete-kat" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada kategori yang ditambahkan.</p>
                <?php endif; ?>
            </div>

        </main>

    </div>

    <div id="modalTambah" class="modal">
        <div class="modal-content">
            <h3>Tambah Kategori Baru</h3>
            <form method="POST" action="">
                <div class="kat-form-group">
                    <label class="kat-form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="field-input" required>
                </div>
                <div class="kat-form-group">
                    <label class="kat-form-label">Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" class="field-textarea" required></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit_tambah" class="btn-submit kat-btn-submit">Simpan</button>
                    <button type="button" onclick="tutupModal()" class="kat-btn-cancel">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <h3>Edit Kategori</h3>
            <form method="POST" action="">
                <input type="hidden" name="id_kategori" id="edit_id_kategori">
                
                <div class="kat-form-group">
                    <label class="kat-form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit_nama_kategori" class="field-input" required>
                </div>
                <div class="kat-form-group">
                    <label class="kat-form-label">Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="field-textarea" required></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit_edit" class="btn-submit kat-btn-submit">Simpan Perubahan</button>
                    <button type="button" onclick="tutupModalEdit()" class="kat-btn-cancel">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModal() { document.getElementById('modalTambah').style.display = 'flex'; }
        function tutupModal() { document.getElementById('modalTambah').style.display = 'none'; }
        function bukaModalEdit(id, nama, deskripsi) {
            document.getElementById('edit_id_kategori').value = id;
            document.getElementById('edit_nama_kategori').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('modalEdit').style.display = 'flex';
        }
        function tutupModalEdit() { document.getElementById('modalEdit').style.display = 'none'; }
    </script>

</body>
</html>