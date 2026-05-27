<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisasi') {
    header("Location: ../auth/index.php");
    exit;
}

require_once __DIR__ . '/../../controllers/OrganizerController.php';
$controller = new OrganizerController();
$event_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$is_edit = ($event_id !== null);

if ($is_edit) {
    $event = $controller->detailAcara($event_id);

    if (!$event) {
        header("Location: org_kelola_acara.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($is_edit) {
        $controller->prosesEditAcara($event_id);
    } else {
        if ($controller->prosesTambahAcara($_POST, $_FILES)) {
            header("Location: org_kelola_acara.php?status=success"); 
            exit();
        } else {
            $error_msg = "Gagal menambahkan acara. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit Acara' : 'Buat Acara' ?> - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="org-layout">
        <aside class="org-sidebar">
            <a href="index.php" class="org-logo">
                <img src="../../assets/img/icon.png" alt="Evently">
                <span>Evently</span>
            </a>

            <div class="org-menu-category">Menu Organisasi</div>

            <a href="org_dashboard.php" class="org-menu-item">
                <img src="../../assets/img/icon-home2.png" alt="Dashboard">
                <span>Dashboard</span>
            </a>
            <a href="org_kelola_acara.php" class="org-menu-item <?= $is_edit ? 'active' : '' ?>">
                <img src="../../assets/img/icon-ticket.png" alt="Kelola Acara">
                <span>Kelola Acara</span>
            </a>
            <a href="org_data_peserta.php" class="org-menu-item">
                <img src="../../assets/img/icon-user2.png" alt="Data Peserta">
                <span>Data Peserta</span>
            </a>
            <a href="org_buat_acara.php" class="org-menu-item <?= !$is_edit ? 'active' : '' ?>">
                <img src="../../assets/img/icon-kegiatan2.png" alt="Buat Acara">
                <span>Buat Acara</span>
            </a>

            <div class="org-menu-category">Akun</div>

            <a href="org_profile.php" class="org-menu-item">
                <img src="../../assets/img/icon-profil-organisasi.png" alt="Profil">
                <span>Profil Organisasi</span>
            </a>
            <a href="logout.php" class="org-menu-item">
                <img src="../../assets/img/icon-logout.png" alt="Keluar">
                <span>Keluar</span>
            </a>
        </aside>

        <main class="org-main">
            <div class="org-container">
                <div class="org-page-header">
                    <h1><?= $is_edit ? 'Edit Acara' : 'Buat Acara Baru' ?></h1>
                    <p><?= $is_edit ? 'Perbarui informasi data acara Anda di bawah ini.' : 'Lengkapi data acara sebelum dikirim untuk verifikasi.' ?></p>
                </div>

                <?php if (isset($error_msg)): ?>
                    <div style="color: red; margin-bottom: 15px;"><?= $error_msg ?></div>
                <?php endif; ?>

                <section class="org-card">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="org-form-grid">
                            <div class="org-form-group org-full">
                                <label>Nama Acara</label>
                                <input type="text" name="judul_event" class="org-input" placeholder="Contoh: Workshop UI/UX" value="<?= $is_edit ? htmlspecialchars($event['judul_event'] ?? '') : '' ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Kategori</label>
                                <select name="kategori_id" class="org-select" required>
                                    <option value="1" <?= $is_edit && ($event['kategori_id'] ?? '') == '1' ? 'selected' : '' ?>>Seminar</option>
                                    <option value="2" <?= $is_edit && ($event['kategori_id'] ?? '') == '2' ? 'selected' : '' ?>>Workshop</option>
                                    <option value="3" <?= $is_edit && ($event['kategori_id'] ?? '') == '3' ? 'selected' : '' ?>>Pelatihan</option>
                                    <option value="4" <?= $is_edit && ($event['kategori_id'] ?? '') == '4' ? 'selected' : '' ?>>Diskusi</option>
                                </select>
                            </div>

                            <div class="org-form-group">
                                <label>Jenis Acara</label>
                                <select name="jenis_acara" class="org-select" required>
                                    <option value="Online" <?= $is_edit && ($event['jenis_acara'] ?? '') == 'Online' ? 'selected' : '' ?>>Online</option>
                                    <option value="Offline" <?= $is_edit && ($event['jenis_acara'] ?? '') == 'Offline' ? 'selected' : '' ?>>Offline</option>
                                </select>
                            </div>

                            <div class="org-form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal" class="org-input" value="<?= $is_edit ? htmlspecialchars($event['tanggal'] ?? '') : '' ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="org-input" value="<?= $is_edit ? htmlspecialchars($event['tanggal_selesai'] ?? '') : '' ?>">
                            </div>

                            <div class="org-form-group">
                                <label>Jam</label>
                                <input type="time" name="waktu" class="org-input" value="<?= $is_edit ? htmlspecialchars($event['waktu'] ?? '') : '' ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" class="org-input" placeholder="Ruang Seminar A / Zoom" value="<?= $is_edit ? htmlspecialchars($event['lokasi'] ?? '') : '' ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Kuota Peserta</label>
                                <input type="number" name="kuota" class="org-input" placeholder="50" value="<?= $is_edit ? htmlspecialchars($event['kuota'] ?? '') : '' ?>">
                            </div>
                        </div>

                        <div class="org-form-group org-full">
                            <label>Deskripsi Acara</label>
                            <textarea name="deskripsi" class="org-textarea" rows="6" placeholder="Tuliskan deskripsi acara secara lengkap..." required><?= $is_edit ? htmlspecialchars($event['deskripsi'] ?? '') : '' ?></textarea>
                        </div>

                        <div class="org-form-group org-full">
                            <label>Poster Acara</label>
                            <div class="org-upload-box" style="position: relative; cursor: pointer;">
                                <input type="file" name="poster" accept="image/*" style="position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor: pointer;">
                                <p><?= $is_edit && !empty($event['poster']) ? 'Pilih file baru jika ingin mengganti poster' : 'Unggah poster acara di sini' ?></p>
                                <span>PNG, JPG maksimal 2MB</span>
                            </div>
                            <?php if ($is_edit && !empty($event['poster'])): ?>
                                <p style="font-size: 13px; color: #64748b; margin-top: 8px;">Poster saat ini: <strong><?= htmlspecialchars($event['poster']) ?></strong></p>
                            <?php endif; ?>
                        </div>

                        <div class="org-form-actions">
                            <button type="submit" class="org-btn org-btn-primary"><?= $is_edit ? 'Simpan Perubahan' : 'Kirim untuk Verifikasi' ?></button>
                            <button type="button" class="org-btn org-btn-outline">Simpan Draft</button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
</body>
</html>