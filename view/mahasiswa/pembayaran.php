<?php
/**
 * @var array $event
 * @var array $user
 */
// Logic has been moved to MahasiswaController
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="logo"><i class="fa-solid fa-calendar-check"></i> Evently</div>
        
        <div class="menu-category">Menu</div>
        <a href="index.php?module=mahasiswa&action=dashboard" class="menu-item"><i class="fa-solid fa-house"></i> Beranda</a>
        <a href="index.php?module=mahasiswa&action=kegiatan" class="menu-item active"><i class="fa-solid fa-layer-group"></i> Kegiatan</a>
        <a href="index.php?module=mahasiswa&action=etiket" class="menu-item"><i class="fa-solid fa-ticket"></i> E-Tiket</a>
        <div class="menu-category">Akun</div>
        <a href="index.php?module=mahasiswa&action=profil" class="menu-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
        <a href="view/auth/logout.php" class="menu-item"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
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

        <?php if (!empty($error)): ?>
            <div class="profile-message profile-error" style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?module=mahasiswa&action=pembayaran&id=<?= $event['id'] ?>" enctype="multipart/form-data">
            <div class="etiket-preview">
                <div class="etiket-preview-info">
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
                            <span class="method-icon qris-method-icon">
                                <i class="fa-solid fa-qrcode"></i>
                            </span>
                            <input type="radio" name="metode_pembayaran" value="QRISS" hidden>
                        </label>
                </div>
                <div class="card" id="detail-pembayaran-card">
                    <h3 id="detail-title">Detail Pembayaran</h3>
                    <div class="rekening-box" id="rekening-box">
                        <span>Transfer ke rekening</span>
                        <strong>123456789107</strong>
                        <p>Bank BCA a.n. Evently Organisasi</p>
                    </div>

                    <div class="qris-box" id="qris-box">
                        <p class="qris-hint"><i class="fa-solid fa-magnifying-glass-plus"></i> Klik gambar untuk perbesar & unduh</p>
                        <img
                            src="assets/img/qriss.jpg"
                            alt="QRIS"
                            class="qris-img-clickable"
                            onclick="bukaLightboxQris()"
                            title="Klik untuk perbesar">
                        <p>Scan QRIS ini untuk semua E-Wallet / M-Banking</p>
                    </div>

                    <!-- Lightbox QRIS -->
                    <div class="qris-lightbox" id="qrisLightbox" onclick="tutupLightboxQris(event)">
                        <div class="qris-lightbox-inner">
                            <button class="qris-lightbox-close" onclick="tutupLightboxQris()" title="Tutup">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <img src="assets/img/qriss.jpg" alt="QRIS Besar" class="qris-lightbox-img">
                            <a
                                href="assets/img/qriss.jpg"
                                download="QRIS-Evently.jpg"
                                class="qris-download-btn"
                                onclick="event.stopPropagation()">
                                <i class="fa-solid fa-download"></i> Unduh QRIS
                            </a>
                        </div>
                    </div>

                    <div id="upload-box">
                        <span class="upload-label">Upload Bukti Transfer</span>
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

<script src="assets/js/mahasiswa_pembayaran.js"></script>

</body>
</html>
