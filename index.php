<?php
session_start();

$module = $_GET['module'] ?? 'public';
$actionParam = $_GET['action'] ?? '';

if ($module === 'mahasiswa') {
    require_once __DIR__ . '/controllers/MahasiswaController.php';
    $controller = new MahasiswaController();
    $actionParam = $actionParam ?: 'dashboard';
    
    $allowed_actions = [
        'dashboard', 'kegiatan', 'etiket', 'profil', 
        'detail', 'pembayaran', 'savedEvents', 'printTiket', 'dataDiri', 'hapusSavedEvent', 'simpanEventAction'
    ];

    if (in_array($actionParam, $allowed_actions)) {
        $methodName = 'action_' . $actionParam;
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            $controller->action_dashboard();
        }
    } else {
        $controller->action_dashboard();
    }

} elseif ($module === 'admin') {
    require_once __DIR__ . '/controllers/AdminController.php';
    $controller = new AdminController();
    $actionParam = $actionParam ?: 'dashboard';

    $allowed_actions = [
        'login', 'dashboard', 'kategori', 'pengguna', 'semua_acara', 'verifikasi'
    ];

    if (in_array($actionParam, $allowed_actions)) {
        $methodName = 'action_' . $actionParam;
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            $controller->action_dashboard();
        }
    } else {
        $controller->action_dashboard();
    }

} elseif ($module === 'organizer') {
    require_once __DIR__ . '/controllers/OrganizerController.php';
    $controller = new OrganizerController();
    $actionParam = $actionParam ?: 'dashboard';

    $allowed_actions = [
        'dashboard', 'kelola_acara', 'data_peserta', 'buat_acara', 'profile', 'hapus_acara'
    ];

    if (in_array($actionParam, $allowed_actions)) {
        $methodName = 'action_' . $actionParam;
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            $controller->action_dashboard();
        }
    } else {
        $controller->action_dashboard();
    }

} elseif ($module === 'auth') {
    require_once __DIR__ . '/controllers/AuthController.php';
    $controller = new AuthController();
    $actionParam = $actionParam ?: 'login';

    $allowed_actions = [
        'login', 'register', 'logout', 'forgotPassword', 'resetPassword'
    ];

    if (in_array($actionParam, $allowed_actions)) {
        $methodName = 'action_' . $actionParam;
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            $controller->action_login();
        }
    } else {
        $controller->action_login();
    }

} else {
    // Default to Public
    require_once __DIR__ . '/controllers/PublicController.php';
    $controller = new PublicController();
    $actionParam = $actionParam ?: 'index';

    $allowed_actions = [
        'index', 'fitur', 'kegiatan', 'tentang'
    ];

    if (in_array($actionParam, $allowed_actions)) {
        $methodName = 'action_' . $actionParam;
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            $controller->action_index();
        }
    } else {
        $controller->action_index();
    }
}
