<?php
session_start();
require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

$controller = new MahasiswaController();
$data = $controller->dataDiri();
$user  = $data['user'];
$event = $data['event'];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pembayaran'])) {

    $metode = $_POST['metode_pembayaran'] ?? '';
    $buktiTransfer = '';

    if (
        isset($_FILES['bukti_transfer']) &&
        $_FILES['bukti_transfer']['error'] === 0
    ) {

        $namaFile =
            time() . '_' .
            $_FILES['bukti_transfer']['name'];

        $tmpFile =
            $_FILES['bukti_transfer']['tmp_name'];

        $folderUpload =
            '../../assets/uploads/';

        if (!is_dir($folderUpload)) {
            mkdir($folderUpload, 0777, true);
        }

        move_uploaded_file(
            $tmpFile,
            $folderUpload . $namaFile
        );

        $buktiTransfer = $namaFile;
    }

    $kodeBooking =
        'EVT-' .
        strtoupper(substr(md5(time()), 0, 8));

    $success = $controller->createBooking(
        $event['id'],
        $user['id'],
        $kodeBooking,
        $metode,
        $buktiTransfer
    );

    if ($success) {

        header("Location: e-tiket.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
        
        <div class="menu-category">Menu</div>
        <a href="user_dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
        <a href="kegiatan_mhs.php" class="menu-item active"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
        <a href="e-tiket.php" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
        <div class="menu-category">Akun</div>
        <a href="profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
        <a href="../auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
    </aside>
    
    <main class="content">
        <div class="stepper">
            <div class="step done">
                <div class="step-circle">✓</div>
                <span class="step-label">Pilih Event</span>
            </div>

            <div class="stepper-line done"></div>
            <div class="step done">
                <div class="step-circle">✓</div>
                <span class="step-label">Data Diri</span>
            </div>

            <div class="stepper-line done"></div>
            <div class="step active">
                <div class="step-circle">3</div>
                <span class="step-label">Pembayaran</span>
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="etiket-preview">
                <div style="flex:1; position:relative; z-index:1;">
                    <p class="etiket-label">Preview E-Tiket</p>
                    <h2 class="etiket-title">
                        <?= htmlspecialchars($event['nama_event'] ?? $event['judul_event'] ?? 'Nama Event') ?>
                    </h2>
                    <p class="etiket-subtitle">
                        <?= htmlspecialchars($event['tanggal'] ?? '') ?>
                    </p>
                    <hr class="etiket-divider">
                    <div class="etiket-meta">
                        <div class="etiket-field">
                            <span>Nama</span>
                            <strong><?= htmlspecialchars($user['nama_lengkap'] ?? '') ?></strong>
                        </div>

                        <div class="etiket-field">
                            <span>NPM</span>
                            <strong><?= htmlspecialchars($user['npm'] ?? '') ?></strong>
                        </div>

                        <div class="etiket-code">
                            #EVT-<?= rand(100000,999999) ?>
                        </div>
                    </div>
                </div>
                <span class="etiket-emoji">⭐</span>
            </div>

            <div class="payment-grid">
                <div class="card">
                    <h3>Metode Pembayaran</h3>
                        <label class="method-item selected" onclick="pilihMetode(this, 'tunai')">
                            <div class="method-radio"></div>
                            <div class="method-info">
                                <strong>Tunai</strong>
                                <span>Bayar langsung di tempat (Hari H)</span>
                            </div>
                            <span class="method-icon">💵</span>
                            <input type="radio" name="metode_pembayaran" value="Tunai" checked hidden>
                        </label>
                        <label class="method-item" onclick="pilihMetode(this, 'transfer')">
                            <div class="method-radio"></div>
                            <div class="method-info">
                                <strong>Transfer Bank</strong>
                                <span>Konfirmasi manual dalam 24 jam</span>
                            </div>
                            <span class="method-icon">🏦</span>
                            <input type="radio" name="metode_pembayaran" value="Transfer Bank" hidden>
                        </label>
                        <label class="method-item" onclick="pilihMetode(this, 'qris')">
                            <div class="method-radio"></div>
                            <div class="method-info">
                                <strong>QRISS (All E-Wallet)</strong>
                                <span>Scan QR Code</span>
                            </div>
                            <span class="method-icon">
                                <img src="qriss.png" alt="QRISS" style="width: 40px; height: auto;">
                            </span>
                            <input type="radio" name="metode_pembayaran" value="QRISS" hidden>
                        </label>
                </div>
                <div class="card" id="detail-pembayaran-card" style="display:none;">
                    <h3 id="detail-title">Detail Pembayaran</h3>
                    <div class="rekening-box" id="rekening-box" style="display:none;">
                        <span>Transfer ke rekening</span>
                        <strong>123456789107</strong>
                        <p>Bank BCA a.n. Evently Organisasi</p>
                    </div>

                    <div class="qris-box" id="qris-box" style="display:none; text-align:center;">
                        <img src="../../assets/img/qriss.png" alt="QRIS" style="max-width: 200px; border-radius: 8px;">
                        <p style="margin-top: 10px; color: #64748b;">Scan QRIS ini untuk semua E-Wallet / M-Banking</p>
                    </div>

                    <div id="upload-box">
                        <span class="upload-label" style="display:block; margin-top:15px;">Upload Bukti Transfer</span>
                        <label class="upload-area">
                            <input type="file" name="bukti_transfer" accept="image/*">
                            <p>Klik untuk upload atau drag<br>JPG, PNG, max 5MB</p>
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit_pembayaran" class="btn-submit">Konfirmasi Pembayaran →</button>
        </form>
    </main>
</div>

<script>
    function pilihMetode(el, tipe) {
        document.querySelectorAll('.method-item').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;

        const detailCard = document.getElementById('detail-pembayaran-card');
        const rekBox = document.getElementById('rekening-box');
        const qrisBox = document.getElementById('qris-box');
        const uploadBox = document.getElementById('upload-box');
        const detailTitle = document.getElementById('detail-title');

        if (tipe === 'tunai') {
            detailCard.style.display = 'none';
        } else {
            detailCard.style.display = 'block';
            if (tipe === 'transfer') {
                detailTitle.textContent = 'Detail Transfer';
                rekBox.style.display = 'block';
                qrisBox.style.display = 'none';
            } else if (tipe === 'qris') {
                detailTitle.textContent = 'Detail QRIS';
                rekBox.style.display = 'none';
                qrisBox.style.display = 'block';
            }
        }
    }

    // Initialize display on load based on checked radio
    document.addEventListener('DOMContentLoaded', () => {
        const checkedInput = document.querySelector('input[name="metode_pembayaran"]:checked');
        if(checkedInput) {
            const val = checkedInput.value;
            if (val === 'Tunai') pilihMetode(checkedInput.closest('label'), 'tunai');
            else if (val === 'Transfer Bank') pilihMetode(checkedInput.closest('label'), 'transfer');
            else if (val === 'QRIS') pilihMetode(checkedInput.closest('label'), 'qris');
        }
    });
</script>

</body>
</html>
