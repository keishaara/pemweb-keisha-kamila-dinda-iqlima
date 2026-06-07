<?php
if (isset($_SESSION['last_activity'])) {
    $waktu_idle = time() - $_SESSION['last_activity'];
    
    if ($waktu_idle >= 1800) {
        session_unset();
        session_destroy();
        header("Location: ../auth/login.php?status=timeout");
        exit;
    }
}
$_SESSION['last_activity'] = time();