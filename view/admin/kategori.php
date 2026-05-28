<?php
session_start();
require_once __DIR__ . '/../../controllers/AdminController.php';

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
    <style>
    </style>
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
            <a href="semua_acara.php" class="menu-item">
                <img src="../../assets/img/icon-allevent.png" alt="Semua Acara">
                Semua Acara
            </a>

            <a href="pengguna.php" class="menu-item">
                <img src="../../assets/img/icon-user-admin.png" alt="Pengguna">
                Pengguna
            </a>

            <a href="kategori.php" class="menu-item active">
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

            <div class="category-header">
                <div class="page-header">
                    <h2>Kelola Kategori</h2>
                    <p class="subtitle">Tambahkan, edit, atau hapus kategori acara sesuai kebutuhan.</p>
                </div>
                <button class="btn-tambah-kat" onclick="bukaModal()">+ Tambah Kategori</button>
            </div>

            <div class="category-grid">
                <?php if (!empty($kategori)): ?>
                    <?php foreach($kategori as $kat): ?>
                    <div class="cat-card">
                        <div class="cat-icon">
                            <?php 
                                $iconName = !empty($kat['icon']) ? $kat['icon'] : 'icon-kegiatan.png';
                            ?>
                            <img 
                                src="../../assets/img/<?= htmlspecialchars($iconName); ?>" 
                                alt="<?= htmlspecialchars($kat['nama_kategori']); ?>"
                                onerror="this.src='../../assets/img/icon.png';"
                            >
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
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-size:13px; margin-bottom:4px;">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="field-input" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-size:13px; margin-bottom:4px;">Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" class="field-textarea" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; height:80px;" required></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit_tambah" class="btn-submit" style="background:#3d5a80; color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer;">Simpan</button>
                    <button type="button" onclick="tutupModal()" style="background:#e0e0e0; padding:8px 16px; border:none; border-radius:6px; cursor:pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <h3>Edit Kategori</h3>
            <form method="POST" action="">
                <input type="hidden" name="id_kategori" id="edit_id_kategori">
                
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-size:13px; margin-bottom:4px;">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit_nama_kategori" class="field-input" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;" required>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-size:13px; margin-bottom:4px;">Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="field-textarea" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px; height:80px;" required></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" name="submit_edit" class="btn-submit" style="background:#3d5a80; color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer;">Simpan Perubahan</button>
                    <button type="button" onclick="tutupModalEdit()" style="background:#e0e0e0; padding:8px 16px; border:none; border-radius:6px; cursor:pointer;">Batal</button>
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