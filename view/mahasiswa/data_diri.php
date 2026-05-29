<?php
session_start();
require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$controller = new MahasiswaController();
$data = $controller->dataDiri();
$user  = $data['user'];
$event = $data['event'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_data_diri'])) {
    $_SESSION['alasan'] = $_POST['alasan'];
    $_SESSION['pengalaman'] = $_POST['pengalaman'];
    header("Location: pembayaran.php?id=" . $event['id']);
    exit;
}

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
        <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
        <div class="menu-category">Menu</div>
        <a href="user_dashboard.php" class="menu-item"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
        <a href="kegiatan_mhs.php" class="menu-item active"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
        <a href="e-tiket.php" class="menu-item"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
        <div class="menu-category">Akun</div>
        <a href="profil.php" class="menu-item"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
        <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
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

        <form method="POST" action="pembayaran.php?id=<?= $event['id'] ?>">
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
                        <label for="tidak">tidak ada</label>
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
            <button type="submit" name="submit_data_diri" class="btn-submit">Lanjut ke Pembayaran →</button>
        </form>
    </main>
</div>

</body>
</html>
