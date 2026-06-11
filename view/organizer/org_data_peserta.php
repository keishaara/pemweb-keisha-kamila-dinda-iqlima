<?php 
/**
 * @var array $pesertaList
 * @var array $eventsList
 * @var string $keyword
 * @var string $eventId
 */
if (!isset($pesertaList)) { header('Location: index.php?module=organizer&action=data_peserta'); exit; } ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta - Evently</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="org-layout">
        <aside class="org-sidebar">
            <div class="org-logo">
            <i class="fa-solid fa-calendar-check"></i> Evently
        </div>

            <div class="org-menu-category">Menu Organisasi</div>

            <a href="index.php?module=organizer&action=dashboard" class="org-menu-item">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>
            <a href="index.php?module=organizer&action=kelola_acara" class="org-menu-item">
            <i class="fa-solid fa-ticket"></i>
            <span>Kelola Acara</span>
        </a>
            <a href="index.php?module=organizer&action=data_peserta" class="org-menu-item active">
            <i class="fa-solid fa-users"></i>
            <span>Data Peserta</span>
        </a>
            <a href="index.php?module=organizer&action=buat_acara" class="org-menu-item">
            <i class="fa-solid fa-layer-group"></i>
            <span>Buat Acara</span>
        </a>

            <div class="org-menu-category">Akun</div>

            <a href="index.php?module=organizer&action=profile" class="org-menu-item">
            <i class="fa-solid fa-user-tie"></i>
            <span>Profil Organisasi</span>
        </a>
            <a href="index.php?module=auth&action=logout" class="org-menu-item">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Keluar</span>
        </a>
        </aside>

        <main class="org-main">
            <div class="org-container">
                <div class="org-page-header">
                    <h1>Data Peserta</h1>
                    <p>Daftar peserta untuk event yang sudah terdaftar.</p>
                </div>

                <section class="org-card">
                    <form action="index.php" method="GET" class="org-table-top">
                        <input type="hidden" name="module" value="organizer">
                        <input type="hidden" name="action" value="data_peserta">
                        <div class="org-search-box">
                            <input type="text" name="search" placeholder="Cari..." value="<?= htmlspecialchars($keyword); ?>">
                        </div>

                        <select name="event_id" class="org-select" onchange="this.form.submit()">
                            <option value="">Semua Event</option>
                            <?php foreach ($eventsList as $event): ?>
                                <option value="<?= $event['id']; ?>" <?= $eventId == $event['id'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($event['judul_event']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="d-none"></button>
                    </form>

                    <table class="org-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NPM</th>
                                <th>Program Studi</th>
                                <th>Email</th>
                                <th>Event</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pesertaList)): ?>
                                <tr>
                                    <td colspan="5" class="org-no-data-cell">
                                        Belum ada peserta yang mendaftar pada acara Anda.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pesertaList as $peserta): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($peserta['nama_lengkap'] ?? '-') ?></strong></td>
                                        <td><?= htmlspecialchars($peserta['npm'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($peserta['program_studi'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($peserta['email'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($peserta['judul_event'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>
</body>
</html>