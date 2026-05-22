<?php

session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/koneksi.php';

$controller = new MahasiswaController();

$data = $controller->dataDiri();

$user  = $data['user'];

$event = $data['event'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $metode = $_POST['metode_pembayaran'] ?? '';

    $buktiTransfer = '';

    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === 0) {

        $namaFile = time() . '_' . $_FILES['bukti_transfer']['name'];

        $tmpFile  = $_FILES['bukti_transfer']['tmp_name'];

        $folderUpload = '../../assets/uploads/';

        if (!is_dir($folderUpload)) {

            mkdir($folderUpload, 0777, true);
        }

        move_uploaded_file(
            $tmpFile,
            $folderUpload . $namaFile
        );

        $buktiTransfer = $namaFile;
    }

   $kodeBooking = 'EVT-' . strtoupper(substr(md5(time()), 0, 8));

    $query = mysqli_query(

        $conn,

        "INSERT INTO bookings
        (
            event_id,
            user_id,
            kode_booking,
            status,
            created_at
        )

        VALUES
        (
            '{$event['id']}',
            '{$user['id']}',
            '$kodeBooking',
            'active'
            ,NOW()
        )"
    );

    if($query){

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
</head>
<body>

<div class="layout">

    <aside class="sidebar">

        <a href="user_dashboard.php" class="logo">
            <img src="../../assets/img/icon-calendar-check.png" alt="Evently"> Evently
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

        <a href="../auth/logout.php" class="menu-item">
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

                    <p class="etiket-label">
                        Preview E-Tiket
                    </p>

                    <h2 class="etiket-title">
                        <?= htmlspecialchars($event['nama_event']) ?>
                    </h2>

                    <p class="etiket-subtitle">
                        <?= htmlspecialchars($event['tanggal']) ?>
                    </p>

                    <hr class="etiket-divider">

                    <div class="etiket-meta">

                        <div class="etiket-field">

                            <span>Nama</span>

                            <strong>
                                <?= htmlspecialchars($user['nama_lengkap']) ?>
                            </strong>

                        </div>

                        <div class="etiket-field">

                            <span>NPM</span>

                            <strong>
                                <?= htmlspecialchars($user['npm']) ?>
                            </strong>

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

                    <div class="method-list">

                        <label class="method-item selected" onclick="pilihMetode(this)">

                            <div class="method-radio"></div>

                            <div class="method-info">

                                <strong>Transfer Bank BCA</strong>

                                <span>Konfirmasi manual dalam 24 jam</span>

                            </div>

                            <span class="method-icon">🏦</span>

                            <input
                                type="radio"
                                name="metode_pembayaran"
                                value="Transfer Bank BCA"
                                checked
                                hidden
                            >

                        </label>

                        <label class="method-item" onclick="pilihMetode(this)">

                            <div class="method-radio"></div>

                            <div class="method-info">

                                <strong>GoPay / OVO</strong>

                                <span>Scan QR Code</span>

                            </div>

                            <span class="method-icon">📱</span>

                            <input
                                type="radio"
                                name="metode_pembayaran"
                                value="GoPay / OVO"
                                hidden
                            >

                        </label>

                        <label class="method-item" onclick="pilihMetode(this)">

                            <div class="method-radio"></div>

                            <div class="method-info">

                                <strong>Bayar di Tempat</strong>

                                <span>Konfirmasi saat hari H</span>

                            </div>

                            <span class="method-icon">💵</span>

                            <input
                                type="radio"
                                name="metode_pembayaran"
                                value="Bayar di Tempat"
                                hidden
                            >

                        </label>

                    </div>

                </div>

                <div class="card">

                    <h3>Detail Transfer</h3>

                    <div class="rekening-box">

                        <span>Transfer ke rekening</span>

                        <strong>123456789107</strong>

                        <p>Bank BCA a.n. UKM Desain Unila</p>

                    </div>

                    <span class="upload-label">
                        Upload Bukti Transfer
                    </span>

                    <label class="upload-area">

                        <input
                            type="file"
                            name="bukti_transfer"
                            accept="image/*"
                        >

                        <p>
                            Klik untuk upload atau drag<br>
                            JPG, PNG, max 5MB
                        </p>

                    </label>

                </div>

            </div>

            <button type="submit" class="btn-submit">
                Konfirmasi Pembayaran →
            </button>

        </form>

    </main>

</div>

<script>

    function pilihMetode(el) {

        document
            .querySelectorAll('.method-item')
            .forEach(i => i.classList.remove('selected'));

        el.classList.add('selected');

        el.querySelector('input').checked = true;
    }

</script>

</body>
</html>