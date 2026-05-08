<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket Saya - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.html" class="menu-item"><img src="assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan.html" class="menu-item"><img src="assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.html" class="menu-item active"><img src="assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.html" class="menu-item"><img src="assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="logout.html" class="menu-item"><img src="assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>E-Tiket Saya</h2>
                <p>Tunjukkan tiket ini saat melakukan registrasi di lokasi acara.</p>
            </div>

            <div class="ticket-list">
                <div class="verif-card ticket-card">
                    <div class="verif-icon-box ticket-qr">
                        <img src="assets/img/qr-placeholder.png" alt="QR Code Ticket">
                    </div>
                    <div class="verif-info">
                        <div class="verif-tags">
                            <span class="status-pill disetujui" style="border:none;">Terverifikasi</span>
                            <span class="tag-kategori">Workshop</span>
                        </div>
                        <div class="verif-title">Workshop Public Speaking</div>
                        <div class="verif-org">Oleh Himpunan Mahasiswa TI</div>
                        
                        <div class="verif-details">
                            <span><img src="assets/img/icon-time.png" style="width:12px;"> 25 Juni 2026</span>
                            <span><img src="assets/img/icon-time.png" style="width:12px;"> 09.00 WIB</span>
                            <span><img src="assets/img/icon-loc.png" style="width:12px;"> Ruang Seminar B</span>
                        </div>
                    </div>

                    <div class="verif-actions">
                        <button class="btn btn-primary btn-small">Unduh PDF</button>
                        <button class="btn btn-outline btn-small">Lihat Detail</button>
                    </div>
                </div>
                <div class="verif-card ticket-card">
                    <div class="verif-icon-box ticket-qr">
                        <img src="assets/img/qr-placeholder.png" alt="QR Code Ticket">
                    </div>
                    <div class="verif-info">
                        <div class="verif-tags">
                            <span class="status-pill aktif" style="border:none;">Mendatang</span>
                            <span class="tag-kategori">Seni & Budaya</span>
                        </div>
                        <div class="verif-title">Apresiasi Seni Kampus</div>
                        <div class="verif-org">Oleh BEM FEB</div>
                        <div class="verif-details">
                            <span><img src="assets/img/icon-time.png" style="width:12px;"> 10 Juli 2026</span>
                            <span><img src="assets/img/icon-time.png" style="width:12px;"> 19.00 WIB</span>
                            <span><img src="assets/img/icon-loc.png" style="width:12px;"> Aula Gedung C</span>
                        </div>
                    </div>

                    <div class="verif-actions">
                        <button class="btn btn-primary btn-small">Unduh PDF</button>
                        <button class="btn btn-outline btn-small">Lihat Detail</button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>