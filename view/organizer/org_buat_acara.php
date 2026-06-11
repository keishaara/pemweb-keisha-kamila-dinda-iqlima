<?php if (!isset($is_edit)) { header('Location: index.php?module=organizer&action=buat_acara'); exit; } ?>

<!DOCTYPE html>
<html lang="id">
<?php
/**
 * @var bool $is_edit
 * @var array $categories
 * @var array $event
 */
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit Acara' : 'Buat Acara' ?> - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="org-layout">
        <aside class="org-sidebar">
            <div class="org-logo">
            <i class="fa-solid fa-calendar-check"></i> Evently
        </div>

            <div class="org-menu-category">Menu Organisasi</div>

            <a href="index.php?module=organizer&action=dashboard" class="org-menu-item">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="index.php?module=organizer&action=kelola_acara" class="org-menu-item <?= $is_edit ? 'active' : '' ?>">
                <i class="fa-solid fa-ticket"></i>
                <span>Kelola Acara</span>
            </a>
            <a href="index.php?module=organizer&action=data_peserta" class="org-menu-item">
                <i class="fa-solid fa-users"></i>
                <span>Data Peserta</span>
            </a>
            <a href="index.php?module=organizer&action=buat_acara" class="org-menu-item <?= !$is_edit ? 'active' : '' ?>">
                <i class="fa-solid fa-layer-group"></i>
                <span>Buat Acara</span>
            </a>

            <div class="org-menu-category">Akun</div>

            <a href="index.php?module=organizer&action=profile" class="org-menu-item">
                <i class="fa-solid fa-user-tie"></i>
                <span>Profil Organisasi</span>
            </a>
            <a href="index.php?module=auth&action=logout" class="org-menu-item">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </a>
        </aside>

        <main class="org-main">
            <div class="org-container">
                <div class="org-page-header">
                    <h1><?= $is_edit ? 'Edit Acara' : 'Buat Acara Baru' ?></h1>
                    <p><?= $is_edit ? 'Perbarui informasi data acara Anda di bawah ini.' : 'Lengkapi data acara sebelum dikirim untuk verifikasi.' ?></p>
                </div>

                <?php if (isset($_SESSION['form_errors'])): ?>
                    <div class="org-alert org-alert-danger">
                        <b>Gagal Memproses Data Form:</b>
                        <ul class="org-alert-list">
                            <?php foreach ($_SESSION['form_errors'] as $err): ?>
                                <li><?= htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['form_errors']); ?>
                <?php endif; ?>

                <section class="org-card">
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="org-form-grid">
                            <div class="org-form-group org-full">
                                <label>Nama Acara</label>
                                <input type="text" name="judul_event" class="org-input" placeholder="Contoh: Workshop UI/UX" value="<?= htmlspecialchars($_POST['judul_event'] ?? ($is_edit ? ($event['judul_event'] ?? '') : '')) ?>" required>
                            </div>
                            <div class="org-form-group">
                                <label>Kategori</label>
                              <?php
                                $selectedKategori = $_POST['kategori_id']
                                    ?? ($is_edit ? ($event['kategori_id'] ?? '') : '');
                                ?>

                                <select name="kategori_id" class="org-select" required>
                                    <option value="" disabled <?= empty($selectedKategori) ? 'selected' : '' ?>>-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $selectedKategori == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nama_kategori']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="org-form-group">
                                <label>Jenis Acara</label>
                                <select name="jenis_acara" class="org-select" required>
                                    <option value="Online" <?= ($_POST['jenis_acara'] ?? ($is_edit ? $event['jenis_acara'] : '')) == 'Online' ? 'selected' : '' ?>>Online</option>
                                    <option value="Offline" <?= ($_POST['jenis_acara'] ?? ($is_edit ? $event['jenis_acara'] : '')) == 'Offline' ? 'selected' : '' ?>>Offline</option>
                                </select>
                            </div>

                            <div class="org-form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="tanggal" class="org-input" value="<?= htmlspecialchars($_POST['tanggal'] ?? ($is_edit ? ($event['tanggal'] ?? '') : '')) ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="org-input" value="<?= htmlspecialchars($_POST['tanggal_selesai'] ?? ($is_edit ? ($event['tanggal_selesai'] ?? '') : '')) ?>">
                            </div>

                            <div class="org-form-group">
                                <label>Jam</label>
                                <input type="time" name="waktu" class="org-input" value="<?= htmlspecialchars($_POST['waktu'] ?? ($is_edit ? ($event['waktu'] ?? '') : '')) ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi" class="org-input" placeholder="Ruang Seminar A / Zoom" value="<?= htmlspecialchars($_POST['lokasi'] ?? ($is_edit ? ($event['lokasi'] ?? '') : '')) ?>" required>
                            </div>

                            <div class="org-form-group">
                                <label>Kuota Peserta</label>
                                <input type="number" name="kuota" class="org-input" placeholder="50" min = "1" value="<?= htmlspecialchars($_POST['kuota'] ?? ($is_edit ? ($event['kuota'] ?? '') : '')) ?>">
                            </div>

                            <div class="org-form-group">
                                <label>Harga Pendaftaran (Rp)</label>
                                <input type="number" name="harga" class="org-input" placeholder="Contoh: 15000 (Isi 0 jika gratis)" value="<?= htmlspecialchars($_POST['harga'] ?? ($is_edit ? ($event['harga'] ?? '0') : '0')) ?>" min="0" required>
                            </div>
                        </div>

                        <div class="org-form-group org-full">
                            <label>Deskripsi Acara</label>
                            <textarea name="deskripsi" class="org-textarea" rows="6" placeholder="Tuliskan deskripsi acara secara lengkap..." required><?= htmlspecialchars($_POST['deskripsi'] ?? ($is_edit ? ($event['deskripsi'] ?? '') : '')) ?></textarea>
                        </div>

                        <div class="org-form-group org-full">
                            <label>Poster Acara</label>
                            <div class="org-upload-box">
                                <input type="file" name="poster" id="posterInput" accept="image/*">
                                <div id="uploadPlaceholder">
                                    <p><?= $is_edit && !empty($event['poster']) ? 'Pilih file baru jika ingin mengganti poster' : 'Unggah poster acara di sini' ?></p>
                                    <span>PNG, JPG maksimal 2MB</span>
                                </div>
                                <img id="posterPreview" src="#" alt="Preview Poster" class="poster-preview-org">
                            </div>
                            <?php if ($is_edit && !empty($event['poster'])): ?>
                                <p>Poster saat ini: <strong><?= htmlspecialchars($event['poster']) ?></strong></p>
                            <?php endif; ?>
                        </div>

                        <div class="org-form-actions">
                            <button type="submit" class="org-btn org-btn-primary"><?= $is_edit ? 'Simpan Perubahan' : 'Kirim untuk Verifikasi' ?></button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
    <script src="assets/js/org_buat_acara.js"></script>
</body>
</html>