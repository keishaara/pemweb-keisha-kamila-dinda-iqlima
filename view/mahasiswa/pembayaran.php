<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <a href="user_dashboard.html" class="logo">
            <img src="assets/img/icon-calendar-check.png" alt="Evently"> Evently
        </a>

        <span class="menu-category">Menu</span>
        <a href="user_dashboard.html" class="menu-item">
            <img src="assets/img/icon-home.png" alt=""> Beranda
        </a>
        <a href="kegiatan.html" class="menu-item active">
            <img src="assets/img/icon-calendar.png" alt=""> Kegiatan
        </a>
        <a href="e-tiket.html" class="menu-item">
            <img src="assets/img/icon-ticket.png" alt=""> E-Tiket
        </a>

        <span class="menu-category">Akun</span>
        <a href="profil.html" class="menu-item">
            <img src="assets/img/icon-user.png" alt=""> Profil Saya
        </a>
        <a href="logout.html" class="menu-item">
            <img src="assets/img/icon-logout.png" alt=""> Keluar
        </a>
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

        <div class="etiket-preview">
            <div style="flex:1; position:relative; z-index:1;">
                <p class="etiket-label">Preview E-Tiket</p>
                <h2 class="etiket-title">Workshop UI/UX Design for Beginners</h2>
                <p class="etiket-subtitle">Batch 3 | 15 Mei 2025</p>
                <hr class="etiket-divider">
                <div class="etiket-meta">
                    <div class="etiket-field">
                        <span>Nama</span>
                        <strong>Daniel Sesar</strong>
                    </div>
                    <div class="etiket-field">
                        <span>NPM</span>
                        <strong>260510022</strong>
                    </div>
                    <div class="etiket-code">#EVT-2025-080342</div>
                </div>
            </div>
            <span class="etiket-emoji">⭐</span>
        </div>

        <div class="payment-grid">

            <div class="card">
                <h3>Metode Pembayaran</h3>
                <div class="method-list">
                    <div class="method-item selected" onclick="pilihMetode(this)">
                        <div class="method-radio"></div>
                        <div class="method-info">
                            <strong>Transfer Bank BCA</strong>
                            <span>Konfirmasi manual dalam 24 jam</span>
                        </div>
                        <span class="method-icon">🏦</span>
                    </div>
                    <div class="method-item" onclick="pilihMetode(this)">
                        <div class="method-radio"></div>
                        <div class="method-info">
                            <strong>GoPay / OVO</strong>
                            <span>Scan QR Code</span>
                        </div>
                        <span class="method-icon">📱</span>
                    </div>
                    <div class="method-item" onclick="pilihMetode(this)">
                        <div class="method-radio"></div>
                        <div class="method-info">
                            <strong>Bayar di Tempat</strong>
                            <span>Konfirmasi saat hari H</span>
                        </div>
                        <span class="method-icon">💵</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Detail Transfer</h3>
                <div class="rekening-box">
                    <span>Transfer ke rekening</span>
                    <strong>123456789107</strong>
                    <p>Bank BCA a.n. UKM Desain Unila</p>
                </div>
                <span class="upload-label">Upload Bukti Transfer</span>
                <label class="upload-area">
                    <input type="file" accept="image/*">
                    <p>Klik untuk upload atau drag<br>JPG, PNG, max 5MB</p>
                </label>
            </div>

        </div>

        <button class="btn-submit" onclick="window.location='e-tiket.html'">
            Konfirmasi Pembayaran →
        </button>

    </main>
</div>

<script>
    function pilihMetode(el) {
        document.querySelectorAll('.method-item').forEach(i => i.classList.remove('selected'));
        el.classList.add('selected');
    }
</script>

</body>
</html>