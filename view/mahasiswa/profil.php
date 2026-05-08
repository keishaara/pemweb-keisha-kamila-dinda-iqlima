<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.html" class="menu-item"><img src="assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan.html" class="menu-item"><img src="assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.html" class="menu-item"><img src="assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.html" class="menu-item active"><img src="assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="logout.html" class="menu-item"><img src="assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header"><h2>Profil Saya</h2></div>

            <div class="profile-header">
                <div class="profile-avatar">AB</div>
                <div class="profile-info">
                    <h2>Ahmad Bagas</h2>
                    <p>NPM: 2316001 | Teknik Informatika | Semester 5</p>
                </div>
            </div>

            <div class="card mb-3">
                <h3 class="section-title">Informasi Saya</h3>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="Ahmad Bagas">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPM</label>
                            <input type="text" class="form-control" value="2316001" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Kampus</label>
                            <input type="email" name="email" class="form-control" value="ahmad.bagas@unila.ac.id">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Whatsapp</label>
                            <input type="text" name="wa" class="form-control" value="+62812345678">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="prodi" class="form-control" value="Teknik Informatika">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Semester</label>
                            <input type="text" name="semester" class="form-control" value="5">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 class="section-title">Ganti Kata Sandi</h3>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sandi Lama</label>
                            <input type="password" name="pass_lama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="pass_baru" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Sandi Baru</label>
                        <input type="password" name="konfirmasi" class="form-control" required>
                    </div>
                    <div class="form-actions" style="justify-content: flex-start;">
                        <button type="submit" name="ganti_password" class="btn btn-outline">Ubah Sandi</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
