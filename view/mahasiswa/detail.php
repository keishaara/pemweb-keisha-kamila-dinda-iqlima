<?php
session_start();
require_once __DIR__ . '/../../controllers/MahasiswaController.php';

$controller = new MahasiswaController();
$event = $controller->detailEvent();
$id_event = $_GET['id'];
if(isset($_POST['simpan_event'])){

    require_once __DIR__ . '/../../config/koneksi.php';
    $user_id = $_SESSION['user_id'];
    $cek = mysqli_query(

        $conn,
        "SELECT * FROM saved_events
         WHERE user_id = '$user_id'
         AND event_id = '$id_event'"
    );

    if(mysqli_num_rows($cek) == 0){
        mysqli_query(
            $conn,
            "INSERT INTO saved_events
            (user_id, event_id)
            VALUES
            ('$user_id', '$id_event')"
        );
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Event - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
        <div class="menu-category">Menu</div>
        <a href="dashboard_mhs.php" class="menu-item"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
        <a href="kegiatan_mhs.php" class="menu-item active"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a> 
        <a href="e-tiket.php" class="menu-item"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
        <div class="menu-category">Akun</div>
        <a href="profil.php" class="menu-item"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
        <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
    </aside>

    <main class="content">
        <div class="detail-card">
            <div class="detail-header">
                <a href="kegiatan_mhs.php"
                   class="btn-outline"
                   style="text-decoration: none; display: inline-block;">
                    Kembali
                </a>

                <button class="btn-outline">
                    Bagikan
                </button>

            </div>

            <div class="banner">
                💻
            </div>

            <div class="tags">

                <span>
                    <?= htmlspecialchars($event['kategori_id']) ?>
                </span>

                <span>
                    <?= htmlspecialchars($event['deskripsi']) ?>
                </span>

                <span class="green">
                    Sertifikat
                </span>

            </div>

            <h2>
                <?= htmlspecialchars($event['judul_event']) ?>
            </h2>

            <div class="detail-grid">
                <div class="left">
                    <div class="info-box">

                        <div>📅</div>
                        <div>
                            <p>Tanggal dan Waktu</p>
                            <strong>
                                <?= htmlspecialchars($event['tanggal']) ?><br>
                                <?= htmlspecialchars($event['waktu']) ?>
                            </strong>

                        </div>

                    </div>

                    <div class="info-box">
                        <div>📍</div>
                        <div>
                            <p>Lokasi</p>
                            <strong>
                                <?= htmlspecialchars($event['lokasi']) ?>
                            </strong>
                        </div>

                    </div>

                    <div class="info-box">
                        <div>👥</div>
                        <div>
                            <p>Peserta</p>
                            <strong>
                                Maks 50 Orang
                            </strong>
                        </div>
                    </div>

                    <div class="info-box">
                        <div>🎓</div>
                        <div>
                            <p>Penyelenggara</p>
                            <strong>
                                <?= htmlspecialchars($event['penyelenggara']) ?>
                            </strong>
                        </div>

                    </div>
                    <div class="description">
                        <h4>Tentang Event</h4>
                        <p>
                            <?= nl2br(htmlspecialchars($event['deskripsi'])) ?>
                        </p>

                    </div>
                </div>

                <div class="right">
                    <div class="price-box">
                        <p>HARGA PENDAFTARAN</p>
                        <h2>
                            Rp <?= number_format($event['harga'], 0, ',', '.') ?>
                        </h2>

                        <div class="progress">
                            <div class="bar"></div>
                        </div>

                        <a href="data_diri.php?id=<?= $event['id'] ?>" class="btn-primary">Daftar Sekarang</a>

                       <form method="POST">
                            <button
                                type="submit"
                                name="simpan_event"
                                class="btn-secondary"
                            > Simpan Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>