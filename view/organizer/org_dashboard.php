<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Organisasi - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="org-layout">
        <aside class="org-sidebar">
            <a href="index.html" class="org-logo">
                <img src="assets/img/icon.png" alt="Evently">
                <span>Evently</span>
            </a>

            <div class="org-menu-category">Menu Organisasi</div>

            <a href="org_dashboard.html" class="org-menu-item active">
                <img src="assets/img/icon-home3.png" alt="Dashboard">
                <span>Dashboard</span>
            </a>
            <a href="org_kelola_acara.html" class="org-menu-item">
                <img src="assets/img/icon-ticket.png" alt="Kelola Acara">
                <span>Kelola Acara</span>
            </a>
            <a href="org_data_peserta.html" class="org-menu-item">
                <img src="assets/img/icon-user2.png" alt="Data Peserta">
                <span>Data Peserta</span>
            </a>
            <a href="org_buat_acara.html" class="org-menu-item">
                <img src="assets/img/icon-kegiatan.png" alt="Buat Acara">
                <span>Buat Acara</span>
            </a>

            <div class="org-menu-category">Akun</div>

            <a href="org_profile.html" class="org-menu-item">
                <img src="assets/img/icon-profil-organisasi.png" alt="Profil">
                <span>Profil Organisasi</span>
            </a>
            <a href="logout.html" class="org-menu-item">
                <img src="assets/img/icon-logout.png" alt="Keluar">
                <span>Keluar</span>
            </a>
        </aside>

        <main class="org-main">
            <div class="org-container">
                <div class="org-banner">
                    <div class="org-banner-badge">ORGANISASI PANEL</div>
                    <h1>Halo, HMTI Unila!</h1>
                    <p>Ada 2 event yang perlu kamu pantau hari ini.</p>
                </div>

                <div class="org-stats">
                    <div class="org-stat-card">
                        <div class="org-stat-icon">
                            <img src="assets/img/icon-peserta.png" alt="Peserta">
                        </div>
                        <div class="org-stat-info">
                            <h3>1.240</h3>
                            <p>Total Peserta</p>
                        </div>
                    </div>

                    <div class="org-stat-card">
                        <div class="org-stat-icon">
                            <img src="assets/img/icon-time.png" alt="Event">
                        </div>
                        <div class="org-stat-info">
                            <h3>3</h3>
                            <p>Event Aktif</p>
                        </div>
                    </div>

                    <div class="org-stat-card">
                        <div class="org-stat-icon">
                            <img src="assets/img/icon-clock.png" alt="Pending">
                        </div>
                        <div class="org-stat-info">
                            <h3>1</h3>
                            <p>Menunggu Verif</p>
                        </div>
                    </div>

                    <div class="org-stat-card">
                        <div class="org-stat-icon">
                            <img src="assets/img/icon-check.png" alt="Selesai">
                        </div>
                        <div class="org-stat-info">
                            <h3>12</h3>
                            <p>Event Selesai</p>
                        </div>
                    </div>
                </div>

                <div class="org-dashboard-grid">
                    <section class="org-card org-card-table">
                        <div class="org-card-header">
                            <h2>Performa Event Terbaru</h2>
                        </div>

                        <table class="org-table">
                            <thead>
                                <tr>
                                    <th>Nama Event</th>
                                    <th>Peserta</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Workshop UI/UX</strong></td>
                                    <td>45/50</td>
                                    <td><span class="org-pill org-pill-success">Berjalan</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Webinar AI</strong></td>
                                    <td>120/200</td>
                                    <td><span class="org-pill org-pill-warning">Menunggu Verif</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Seminar Career</strong></td>
                                    <td>90/100</td>
                                    <td><span class="org-pill org-pill-success">Berjalan</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </section>

                    <aside class="org-card org-agenda-card">
                        <h2>Jadwal Terdekat</h2>

                        <div class="org-agenda-item">
                            <div class="org-agenda-date">12 MEI 2026</div>
                            <div class="org-agenda-title">Workshop UI/UX Figma</div>
                        </div>

                        <div class="org-agenda-item">
                            <div class="org-agenda-date">15 MEI 2026</div>
                            <div class="org-agenda-title">Rapat Koordinasi HMTI</div>
                        </div>

                        <div class="org-agenda-item org-agenda-item-last">
                            <div class="org-agenda-date">20 MEI 2026</div>
                            <div class="org-agenda-title">Webinar Future AI</div>
                        </div>

                        <a href="kelola_acara.html" class="org-btn org-btn-outline org-btn-full">Lihat Semua</a>
                    </aside>
                </div>
            </div>
        </main>
    </div>
</body>
</html>