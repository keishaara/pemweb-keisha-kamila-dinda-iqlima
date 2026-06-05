<?php
require_once __DIR__ . '/config/koneksi.php';

$query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE tipe_akun='mahasiswa'");

if (!$query) {
    echo "Error: " . mysqli_error($conn);
} else {
    $res = mysqli_fetch_assoc($query);
    echo "Total Mahasiswa: " . $res['total'];
}
?>
