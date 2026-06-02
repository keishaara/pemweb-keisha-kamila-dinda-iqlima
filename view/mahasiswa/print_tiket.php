<?php
session_start();

require_once __DIR__ . '/../../controllers/MahasiswaController.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$kode = $_GET['kode'] ?? '';

$controller = new MahasiswaController();
$tiket = $controller->getTicketByKode($kode);

if (!$tiket) {
    die('Tiket tidak ditemukan.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>E-Ticket <?= htmlspecialchars($tiket['kode_booking']) ?></title>
<link rel="stylesheet" href="../../assets/css/style.css?v=2">
</head>

<body class="ticket-print-body">

        <div class="ticket-print-card">

            <div class="ticket-print-header">

                <div class="ticket-print-logo">
                    EVENTLY
                </div>

                <div class="ticket-print-code">
                    <?= htmlspecialchars($tiket['kode_booking']) ?>
                </div>

                <div class="ticket-print-status">
                    Terverifikasi
                </div>

            </div>

            <div class="ticket-print-content">

                <div class="ticket-print-row">
                    <span>Nama Event</span>
                    <strong><?= htmlspecialchars($tiket['judul_event']) ?></strong>
                </div>

                <div class="ticket-print-row">
                    <span>Kategori</span>
                    <strong><?= htmlspecialchars($tiket['nama_kategori']) ?></strong>
                </div>

                <div class="ticket-print-row">
                    <span>Penyelenggara</span>
                    <strong><?= htmlspecialchars($tiket['penyelenggara']) ?></strong>
                </div>

                <div class="ticket-print-row">
                    <span>Tanggal</span>
                    <strong><?= date('d M Y', strtotime($tiket['tanggal'])) ?></strong>
                </div>

                <div class="ticket-print-row">
                    <span>Jam</span>
                    <strong><?= date('H.i', strtotime($tiket['waktu'])) ?> WIB</strong>
                </div>

                <div class="ticket-print-row">
                    <span>Lokasi</span>
                    <strong><?= htmlspecialchars($tiket['lokasi']) ?></strong>
                </div>

            </div>

        </div>
         <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 300);
            });
        </script>

    </body>
</html>