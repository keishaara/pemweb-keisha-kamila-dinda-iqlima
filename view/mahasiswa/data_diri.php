<?php

session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';

$controller = new MahasiswaController();

$data = $controller->dataDiri();

$user  = $data['user'];
$event = $data['event'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <a href="user_dashboard.php" class="logo">
            <img src="../../assets/img/icon.png" alt="Evently"> Evently
        </a>

        <span class="menu-category">Menu</span>

        <a href="user_dashboard.php" class="menu-item">
            <img src="../../assets/img/icon-home.png" alt=""> Beranda
        </a>

        <a href="kegiatan_mhs.php" class="menu-item active">
            <img src="../../assets/img/icon-calendar.png" alt=""> Kegiatan
        </a>

        <a href="e-tiket.php" class="menu-item">
            <img src="../../assets/img/icon-ticket.png" alt=""> E-Tiket
        </a>

        <span class="menu-category">Akun</span>

        <a href="profil.php" class="menu-item">
            <img src="../../assets/img/icon-user.png" alt=""> Profil Saya
        </a>

        <a href="../../logout.php" class="menu-item">
            <img src="../../assets/img/icon-logout.png" alt=""> Keluar
        </a>
    </aside>

    <main class="content">

        <div class="stepper">

            <div class="step done">
                <div class="step-circle">✓</div>
                <span class="step-label">Pilih Event</span>
            </div>

            <div class="stepper-line done"></div>

            <div class="step active">
                <div class="step-circle">2</div>
                <span class="step-label">Data Diri</span>
            </div>

            <div class="stepper-line"></div>

            <div class="step">
                <div class="step-circle">3</div>
                <span class="step-label">Pembayaran</span>
            </div>

        </div>

        <form method="POST" action="pembayaran.php?event_id=<?= $event['id'] ?>">

            <div class="form-grid">

                <div class="card">

                    <h3>Data Peserta</h3>

                    <div class="field-row">

                        <div class="field-group">
                            <label class="field-label">Nama Lengkap</label>

                            <input
                                type="text"
                                class="field-input"
                                value="<?= htmlspecialchars($user['nama_lengkap']) ?>"
                                readonly>
                        </div>

                        <div class="field-group">
                            <label class="field-label">NPM</label>

                            <input
                                type="text"
                                class="field-input"
                                value="<?= htmlspecialchars($user['npm']) ?>"
                                readonly>
                        </div>

                    </div>

                    <div class="field-row">

                        <div class="field-group">
                            <label class="field-label">Jurusan</label>

                            <input
                                type="text"
                                class="field-input"
                                value="<?= htmlspecialchars($user['program_studi']) ?>"
                                readonly>
                        </div>

                        <div class="field-group">
                            <label class="field-label">Semester</label>

                            <input
                                type="text"
                                class="field-input"
                                value="<?= htmlspecialchars($user['semester'] ?? '') ?>"
                                readonly>
                        </div>

                    </div>

                    <div class="field-group">

                        <label class="field-label">Email Kampus</label>

                        <input
                            type="email"
                            class="field-input"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            readonly>

                    </div>

                    <div class="field-group">

                        <label class="field-label">No. Whatsapp</label>

                        <input
                            type="tel"
                            class="field-input"
                            value="<?= htmlspecialchars($user['no_whatsapp']) ?>"
                            readonly>

                    </div>

                </div>

                <div class="card">

                    <h3>Informasi Tambahan</h3>

                    <div class="field-group">

                        <label class="field-label">
                            Alasan mengikuti workshop
                        </label>

                        <textarea
                            class="field-textarea"
                            name="alasan"
                            placeholder="Tulis alasan kamu..."
                            required></textarea>

                    </div>

                    <span class="radio-label">
                        Pengalaman desain sebelumnya
                    </span>

                    <div class="radio-group">

                        <input
                            type="radio"
                            name="pengalaman"
                            id="ada"
                            value="Ada">

                        <label for="ada">Ada</label>

                        <input
                            type="radio"
                            name="pengalaman"
                            id="tidak"
                            value="Tidak Ada"
                            checked>

                        <label for="tidak">Tidak Ada</label>

                    </div>

                </div>

                <div class="card">

                    <h3>Ringkasan Pembayaran</h3>

                    <div class="summary-banner">
                        💻
                    </div>

                    <div class="summary-row">
                        <span class="summary-key">Tanggal</span>

                        <span class="summary-val">
                            <?= htmlspecialchars($event['tanggal']) ?>
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-key">Waktu</span>

                        <span class="summary-val">
                            <?= htmlspecialchars($event['waktu']) ?>
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-key">Lokasi</span>

                        <span class="summary-val">
                            <?= htmlspecialchars($event['lokasi']) ?>
                        </span>
                    </div>

                    <div class="summary-total">

                        <span class="key-total">Total</span>

                        <span class="val-total">
                            Rp <?= number_format($event['harga'], 0, ',', '.') ?>
                        </span>

                    </div>

                </div>

            </div>

            <button type="submit" class="btn-submit">
                Lanjut ke Pembayaran →
            </button>

        </form>

    </main>
</div>

</body>
</html>