<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kegiatan - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.html" class="menu-item"><img src="assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan.html" class="menu-item active"><img src="assets/img/icon-kegiatan.png" alt="Kegiatan">
                Kegiatan</a>
            <a href="e-tiket.html" class="menu-item"><img src="assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.html" class="menu-item"><img src="assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="logout.html" class="menu-item"><img src="assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Jelajahi Event</h2>
                <p>Temukan kegiatan sesuai minatmu</p>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Cari event . .">
                <button>Semua Tanggal ⌄</button>
            </div>

            <div class="filter-tabs">
                <button class="active">Semua</button>
                <button>Workshop</button>
                <button>Musik</button>
                <button>Volunteer</button>
                <button>Gratis</button>
            </div>

            <div class="card-grid">
                <div class="event-card">
                    <span class="badge">Populer</span>
                    <div class="icon"><img src="assets/img/icon-music.png" alt="Music"></div>

                    <div class="event-body">
                        <p class="category">MUSIK</p>
                        <h4>Malam Apresiasi Seni</h4>
                        <p class="organizer">BEM FEB</p>

                        <div class="event-footer">
                            <a href="detail.html" class="btn">Detail</a>
                            <span class="price">Rp 50.000</span>
                        </div>
                    </div>
                </div>

                <div class="event-card green">
                    <span class="badge">Gratis</span>
                    <div class="icon"><img src="assets/img/icon-binaan.png" alt="Music"></div>

                    <div class="event-body">
                        <p class="category">VOLUNTEER</p>
                        <h4>Program Mengajar Binaan</h4>
                        <p class="organizer">BEM Unila</p>

                        <div class="event-footer">
                            <a href="detail.html" class="btn">Detail</a>
                        </div>
                    </div>
                </div>

                <div class="event-card yellow">
                    <span class="badge">Deadline</span>
                    <div class="icon"><img src="assets/img/icon-workshop.png" alt="Music"></div>

                    <div class="event-body">
                        <p class="category">WORKSHOP</p>
                        <h4>Figma UI/UX Bootcamp</h4>
                        <p class="organizer">UKM Desain</p>

                        <div class="event-footer">
                            <a href="detail.html" class="btn">Detail</a>
                            <span class="price">Rp 150.000</span>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</body>
</html>
