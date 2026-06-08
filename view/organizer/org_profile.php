<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST); 
    var_dump($_FILES); 
    die('Berhenti di sini untuk cek data');
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organisasi') {
    header("Location: ../auth/index.php");
    exit;
}

require_once __DIR__ . '/../../controllers/OrganizerController.php';
require_once __DIR__ . '/../../config/session.php';

$controller = new OrganizerController();
$organizer = $controller->profile();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->prosesEditProfil($_POST, $_FILES['foto_profil'] ?? null, $organizer['foto_profil'] ?? null)) {
        echo "<script>
                alert('Profil berhasil diperbarui!'); 
                window.location.href='org_profile.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui profil.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi - Evently</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="org-layout">

    <aside class="org-sidebar">

        <i class="fa-solid fa-calendar-check"></i>
        <span>Evently</span>

        <div class="org-menu-category">Menu Organisasi</div>

        <a href="org_dashboard.php" class="org-menu-item">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="org_kelola_acara.php" class="org-menu-item">
            <i class="fa-solid fa-ticket"></i>
            <span>Kelola Acara</span>
        </a>

        <a href="org_data_peserta.php" class="org-menu-item">
            <i class="fa-solid fa-users"></i>
            <span>Data Peserta</span>
        </a>

        <a href="org_buat_acara.php" class="org-menu-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Buat Acara</span>
        </a>

        <div class="org-menu-category">Akun</div>

        <a href="org_profile.php" class="org-menu-item active">
            <i class="fa-solid fa-user-tie"></i>
            <span>Profil Organisasi</span>
        </a>

        <a href="../auth/logout.php" class="org-menu-item">
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

            <section class="org-card org-profile-card">
                <div class="org-profile-top">
                    <div class="org-profile-avatar" style="overflow: hidden; padding: 0; display: flex; justify-content: center; align-items: center;">
                        <?php if (!empty($organizer['foto_profil'])): ?>
                            <img id="previewImg" src="../../assets/profiles/<?= htmlspecialchars($organizer['foto_profil']); ?>" alt="Logo Organisasi" style="width: 100%; height: 100%; object-fit: cover;">
                            <i id="defaultIcon" class="fa-solid fa-building-columns" style="display: none;"></i>
                        <?php else: ?>
                            <img id="previewImg" src="" alt="Logo Organisasi" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            <i id="defaultIcon" class="fa-solid fa-building-columns" style="font-size: 2rem; color: #555;"></i>
                        <?php endif; ?>
                    </div>
                    <div class="org-profile-meta">
                        <h2>
                            <?= htmlspecialchars($organizer['nama_lengkap'] ?? 'Nama Organisasi') ?>
                        </h2>
                        <p>Organisasi Mahasiswa (<?= htmlspecialchars($organizer['singkatan'] ?? '-') ?>)</p>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    
                    <div class="org-form-group org-full" style="margin-bottom: 20px;">
                        <label>Logo / Foto Profil Organisasi</label>
                        <input type="file" name="foto_profil" id="inputFoto" class="org-input" accept="image/png, image/jpeg, image/jpg">
                        <small style="color: #666;">Format yang didukung: JPG, JPEG, PNG.</small>
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

<script>
document.getElementById('inputFoto').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('previewImg');
            const defaultIcon = document.getElementById('defaultIcon');
            
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            if (defaultIcon) {
                defaultIcon.style.display = 'none';
            }
        }
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>