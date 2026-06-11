<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi - Evently</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="org-layout">

    <aside class="org-sidebar">

        <i class="fa-solid fa-calendar-check"></i>
        <span>Evently</span>

        <div class="org-menu-category">Menu Organisasi</div>

        <a href="index.php?module=organizer&action=dashboard" class="org-menu-item">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="index.php?module=organizer&action=kelola_acara" class="org-menu-item">
            <i class="fa-solid fa-ticket"></i>
            <span>Kelola Acara</span>
        </a>

        <a href="index.php?module=organizer&action=data_peserta" class="org-menu-item">
            <i class="fa-solid fa-users"></i>
            <span>Data Peserta</span>
        </a>

        <a href="index.php?module=organizer&action=buat_acara" class="org-menu-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Buat Acara</span>
        </a>

        <div class="org-menu-category">Akun</div>

        <a href="index.php?module=organizer&action=profile" class="org-menu-item active">
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
                <h1>Profil Organisasi</h1>
                <p>Atur identitas organisasi kamu di sini.</p>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
                <div class="org-alert org-alert-success">
                    <strong>Sukses!</strong> Profil berhasil diperbarui.
                </div>
            <?php endif; ?>

            <section class="org-card org-profile-card">
                <div class="org-profile-top">
                    <div class="org-profile-avatar org-avatar-container">
                        <?php if (!empty($organizer['foto_profil'])): ?>
                            <img id="previewImg" src="assets/profiles/<?= htmlspecialchars($organizer['foto_profil']); ?>" alt="Logo Organisasi" class="org-avatar-img-contain">
                            <i id="defaultIcon" class="fa-solid fa-building-columns d-none"></i>
                        <?php else: ?>
                            <img id="previewImg" src="" alt="Logo Organisasi" class="org-avatar-img-contain d-none">
                            <i id="defaultIcon" class="fa-solid fa-building-columns org-default-icon"></i>
                        <?php endif; ?>
                    </div>
                    <div class="org-profile-meta">
                        <h2>
                            <?= htmlspecialchars($organizer['nama_lengkap'] ?? 'Nama Organisasi') ?>
                        </h2>
                        <p>Organisasi Mahasiswa (<?= htmlspecialchars($organizer['singkatan'] ?? '-') ?>)</p>
                    </div>
                </div>

                <form action="index.php?module=organizer&action=profile" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="old_foto" value="<?= htmlspecialchars($organizer['foto_profil'] ?? '') ?>">
                    
                    <div class="org-form-group org-full mb-20">
                        <label>Logo / Foto Profil Organisasi</label>
                        <input type="file" name="foto_profil" id="inputFoto" class="org-input" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted-666">Format yang didukung: JPG, JPEG, PNG.</small>
                    </div>

                    <div class="org-form-grid">
                        <div class="org-form-group">
                            <label>Nama Organisasi</label>
                            <input type="text" name="nama_lengkap" class="org-input" value="<?= htmlspecialchars($organizer['nama_lengkap'] ?? '') ?>">
                        </div>

                        <div class="org-form-group">
                            <label>Singkatan Nama</label>
                            <input type="text" name="singkatan" class="org-input" value="<?= htmlspecialchars($organizer['singkatan'] ?? '') ?>">
                        </div>

                        <div class="org-form-group">
                            <label>Email</label>
                            <input
                                type="email"
                                name="email"
                                class="org-input"
                                value="<?= htmlspecialchars($organizer['email'] ?? '') ?>"
                            >
                        </div>

                        <div class="org-form-group">
                            <label>WhatsApp</label>
                            <input
                                type="text"
                                name="whatsapp"
                                class="org-input"
                                value="<?= htmlspecialchars($organizer['no_whatsapp'] ?? '') ?>"
                            >
                        </div>
                    </div>

                    <div class="org-form-group org-full">
                        <label>Deskripsi</label>
                        <textarea
                            name="deskripsi"
                            class="org-textarea"
                            rows="6"
                        ><?= htmlspecialchars($organizer['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="org-form-actions">
                        <button type="submit" class="org-btn org-btn-primary">Simpan Perubahan</button>
                        <button type="reset" class="org-btn org-btn-outline" onclick="window.location.reload();">Batal</button>
                    </div>
                </form>
            </section>
        </div>

    </main>

</div>

<script src="assets/js/org_profile.js"></script>

</body>
</html>