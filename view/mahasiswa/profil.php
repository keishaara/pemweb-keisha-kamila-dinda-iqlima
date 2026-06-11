<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-layout-mhs">
        <aside class="sidebar-mhs">
            <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="index.php?module=mahasiswa&action=dashboard" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="index.php?module=mahasiswa&action=kegiatan" class="menu-item"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
            <a href="index.php?module=mahasiswa&action=etiket" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="index.php?module=mahasiswa&action=profil" class="menu-item active"><i class="fa-solid fa-user"></i> Profil Saya</a>
            <a href="index.php?module=auth&action=logout" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </aside>

        <main class="main-content-mhs">
            <div class="page-header"><h2>Profil Saya</h2></div>

            <?php if (!empty($msg)): ?>
                <div class="profile-message profile-<?= htmlspecialchars($msgType ?? '', ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-avatar profile-avatar-container">
                    <?php if (!empty($user['foto_profil'])): ?>
                        <?php $foto = (string)($user['foto_profil'] ?? ''); ?>
                        <img id="previewImg" src="assets/profiles/<?= htmlspecialchars($foto, ENT_QUOTES, 'UTF-8'); ?>" alt="Foto" class="profile-avatar-img-cover">
                        <div id="defaultAvatar" class="d-none"><?= htmlspecialchars(strtoupper(substr((string)($user['nama_lengkap'] ?? ''), 0, 2)), ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php else: ?>
                        <img id="previewImg" src="" alt="Foto" class="profile-avatar-img-fill d-none">
                        <div id="defaultAvatar"><?= htmlspecialchars(strtoupper(substr((string)($user['nama_lengkap'] ?? ''), 0, 2)), ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h2><?= htmlspecialchars((string)($user['nama_lengkap'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>NPM: <?= htmlspecialchars((string)($user['npm'] ?? ''), ENT_QUOTES, 'UTF-8'); ?> | <?= htmlspecialchars((string)($user['program_studi'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?> | Semester <?= htmlspecialchars((string)($user['semester'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>

            <div class="card mb-3">
                <h3 class="section-title">Informasi Saya</h3>
                <form method="POST" action="index.php?module=mahasiswa&action=profil" enctype="multipart/form-data">
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Ubah Foto Profil</label>
                        <input type="file" name="foto_profil" id="inputFoto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <small class="text-muted">Format: JPG, JPEG, PNG.</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars((string)($user['nama_lengkap'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">NPM</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars((string)($user['npm'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Kampus</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars((string)($user['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Whatsapp</label>
                            <input type="text" name="wa" class="form-control" value="<?= htmlspecialchars($user['no_whatsapp'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="program_studi" class="form-control" value="<?= htmlspecialchars($user['program_studi'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Semester</label>
                            <input type="number" name="semester" class="form-control" value="<?= htmlspecialchars($user['semester'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_profil" class="btn-simpan">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 class="section-title">Ganti Kata Sandi</h3>
                <form method="POST" action="index.php?module=mahasiswa&action=profil">
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
                    <div class="form-actions justify-start">
                        <button type="submit" name="ganti_sandi" class="btn btn-outline">Ubah Sandi</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    document.getElementById('inputFoto').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('previewImg');
                const defaultAvatar = document.getElementById('defaultAvatar');
                
                previewImg.src = e.target.result;
                previewImg.style.display = 'block'; 
                if (defaultAvatar) {
                    defaultAvatar.style.display = 'none'; 
                }
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</body>
</html>