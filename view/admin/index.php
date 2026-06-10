<?php
session_start();

require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController();

// Basic routing
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// If not logged in, force action to login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    if ($page !== 'login') {
        $page = 'login';
    }
} else {
    // If logged in and trying to access login page, redirect to dashboard
    if ($page === 'login') {
        header("Location: index.php?page=dashboard");
        exit;
    }
}

switch ($page) {
    case 'login':
        $controller->login();
        break;
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'kategori':
        $controller->kategori();
        break;
    case 'pengguna':
        $controller->pengguna();
        break;
    case 'semua_acara':
        $controller->semua_acara();
        break;
    case 'verifikasi':
        $controller->verifikasi();
        break;
    case 'logout':
        header("Location: ../auth/logout.php");
        exit;
    default:
        $controller->dashboard();
        break;
}
