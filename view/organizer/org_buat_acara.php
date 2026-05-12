<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Acara - Evently</title>
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
            <a href="org_kelola_acara.php" class="org-menu-item">
                <img src="../../assets/img/icon-ticket.png" alt="Kelola Acara">
                <span>Kelola Acara</span>
            </a>
            <a href="org_data_peserta.php" class="org-menu-item">
                <img src="../../assets/img/icon-user2.png" alt="Data Peserta">
                <span>Data Peserta</span>
            </a>
            <a href="org_buat_acara.php" class="org-menu-item active">
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
                    <h1>Buat Acara Baru</h1>
                    <p>Lengkapi data acara sebelum dikirim untuk verifikasi.</p>
                </div>

                <section class="org-card">
                    <div class="org-form-grid">
                        <div class="org-form-group org-full">
                            <label>Nama Acara</label>
                            <input type="text" class="org-input" placeholder="Contoh: Workshop UI/UX">
                        </div>

                        <div class="org-form-group">
                            <label>Kategori</label>
                            <select class="org-select">
                                <option>Seminar</option>
                                <option>Workshop</option>
                                <option>Pelatihan</option>
                                <option>Diskusi</option>
                            </select>
                        </div>

                        <div class="org-form-group">
                            <label>Jenis Acara</label>
                            <select class="org-select">
                                <option>Online</option>
                                <option>Offline</option>
                            </select>
                        </div>

                        <div class="org-form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="org-input">
                        </div>

                        <div class="org-form-group">
                            <label>Tanggal Selesai</label>
                            <input type="date" class="org-input">
                        </div>

                        <div class="org-form-group">
                            <label>Jam</label>
                            <input type="time" class="org-input">
                        </div>

                        <div class="org-form-group">
                            <label>Lokasi</label>
                            <input type="text" class="org-input" placeholder="Ruang Seminar A / Zoom">
                        </div>

                        <div class="org-form-group">
                            <label>Kuota Peserta</label>
                            <input type="number" class="org-input" placeholder="50">
                        </div>
                    </div>

                    <div class="org-form-group org-full">
                        <label>Deskripsi Acara</label>
                        <textarea class="org-textarea" rows="6" placeholder="Tuliskan deskripsi acara secara lengkap..."></textarea>
                    </div>

                    <div class="org-form-group org-full">
                        <label>Poster Acara</label>
                        <div class="org-upload-box">
                            <p>Unggah poster acara di sini</p>
                            <span>PNG, JPG maksimal 2MB</span>
                        </div>
                    </div>

                    <div class="org-form-actions">
                        <button class="org-btn org-btn-primary">Kirim untuk Verifikasi</button>
                        <button class="org-btn org-btn-outline">Simpan Draft</button>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>