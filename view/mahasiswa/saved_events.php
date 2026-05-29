<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../auth/index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsave_event'])) {
    $event_id = intval($_POST['event_id']);
    $stmt = mysqli_prepare($conn, "DELETE FROM saved_events WHERE user_id = ? AND event_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $event_id);
    mysqli_stmt_execute($stmt);
    header('Location: saved_events.php');
    exit;
}

$sql = "SELECT e.*, s.id as saved_id
        FROM saved_events s
        JOIN events e ON s.event_id = e.id
        WHERE s.user_id = '$user_id'
        ORDER BY e.tanggal DESC";

$res = mysqli_query($conn, $sql);
$saved = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Disimpan - Evently</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="logo"><img src="../../assets/img/icon.png" alt="Evently"> Evently</div>
            <div class="menu-category">Menu</div>
            <a href="user_dashboard.php" class="menu-item"><img src="../../assets/img/icon-home2.png" alt="Home"> Beranda</a>
            <a href="kegiatan_mhs.php" class="menu-item"><img src="../../assets/img/icon-kegiatan.png" alt="Kegiatan"> Kegiatan</a>
            <a href="e-tiket.php" class="menu-item"><img src="../../assets/img/icon-ticket.png" alt="E-Tiket"> E-Tiket</a>
            <div class="menu-category">Akun</div>
            <a href="profil.php" class="menu-item"><img src="../../assets/img/icon-user2.png" alt="Profil"> Profil Saya</a>
            <a href="../auth/logout.php" class="menu-item"><img src="../../assets/img/icon-logout.png" alt="Keluar"> Keluar</a>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h2>Disimpan</h2>
                <p>Event yang kamu tandai untuk disimpan.</p>
            </div>

            <div class="card-grid">
                <?php if (empty($saved)): ?>
                    <p style="grid-column:1/-1; text-align:center; color:#64748b; padding:40px;">Belum ada event yang disimpan.</p>
                <?php else: ?>
                    <?php foreach($saved as $ev): ?>
                        <div class="event-card">
                            <div class="event-img">
                                <img src="../../assets/img/icon-<?= strtolower($ev['kategori_id'] ?? 'workshop') ?>.png" onerror="this.src='../../assets/img/icon-workshop.png'" alt="Icon">
                            </div>
                            <div class="event-details">
                                <span class="event-tag"><?= htmlspecialchars(strtoupper($ev['nama_kategori'] ?? 'UMUM')) ?></span>
                                <h4 class="event-title"><?= htmlspecialchars($ev['judul_event']) ?></h4>
                                <p class="event-meta"><?= htmlspecialchars($ev['penyelenggara']) ?></p>
                                <div class="event-footer">
                                    <a href="detail.php?id=<?= intval($ev['id']) ?>" class="btn btn-small">Detail</a>
                                    <form method="POST" style="display:inline; margin-left:8px;">
                                        <input type="hidden" name="event_id" value="<?= intval($ev['id']) ?>">
                                        <button type="submit" name="unsave_event" class="btn-outline btn-small" onclick="return confirm('Hapus dari disimpan?')">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
