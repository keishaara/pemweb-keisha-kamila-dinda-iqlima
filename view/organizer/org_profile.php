<?php

session_start();

require_once __DIR__ . '/../../controllers/OrganizerController.php';

$controller = new OrganizerController();

$organizer = $controller->profile();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi - Evently</title>

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
            <img src="../../assets/img/icon-home2.png" alt="">
            <span>Dashboard</span>
        </a>

        <a href="org_kelola_acara.php" class="org-menu-item">
            <img src="../../assets/img/icon-ticket.png" alt="">
            <span>Kelola Acara</span>
        </a>

        <a href="org_data_peserta.php" class="org-menu-item">
            <img src="../../assets/img/icon-user2.png" alt="">
            <span>Data Peserta</span>
        </a>

        <a href="org_buat_acara.php" class="org-menu-item">
            <img src="../../assets/img/icon-kegiatan.png" alt="">
            <span>Buat Acara</span>
        </a>

        <div class="org-menu-category">Akun</div>

        <a href="org_profile.php" class="org-menu-item active">
            <img src="../../assets/img/icon-profil-organisasi2.png" alt="">
            <span>Profil Organisasi</span>
        </a>

        <a href="../auth/logout.php" class="org-menu-item">
            <img src="../../assets/img/icon-logout.png" alt="">
            <span>Keluar</span>
        </a>

    </aside>

    <main class="org-main">

        <div class="org-container">

            <div class="org-page-header">

                <h1>Profil Organisasi</h1>

                <p>Atur identitas organisasi kamu di sini.</p>

            </div>

            <section class="org-card org-profile-card">
                <div class="org-profile-top">
                    <div class="org-profile-avatar">
                        <img src="../../assets/img/icon-profil-organisasi.png" alt="Profil">
                    </div>
                    <div class="org-profile-meta">
                        <h2>
                            <?= htmlspecialchars($organizer['nama_lengkap'] ?? 'Nama Organisasi') ?>
                        </h2>
                        <p>Organisasi Mahasiswa</p>
                    </div>
                </div>

                <form method="POST">
                    <div class="org-form-grid">
                        <div class="org-form-group">
                            <label>Nama Organisasi</label>
                            <input
                                type="text"
                                name="nama_organisasi"
                                class="org-input"
                                value="<?= htmlspecialchars($organizer['nama_lengkap'] ?? '') ?>"
                            >
                        </div>

                        <div class="org-form-group">
                            <label>Singkatan</label>
                            <input
                                type="text"
                                name="singkatan"
                                class="org-input"
                                value="<?= htmlspecialchars($organizer['singkatan'] ?? '') ?>"
                            >
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
                        <button type="reset" class="org-btn org-btn-outline">Batal</button>
                    </div>
                </form>
            </section>
        </div>

    </main>

</div>

</body>
</html>