<?php
require_once __DIR__ . '/../models/AdminModel.php';

class AdminController {

    private $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    public function dashboard() {
        $totalUsers = $this->model->countUsers();
        $totalOrganisasi = $this->model->countOrganisasi();
        $latestUsers = $this->model->getLatestUsers();
        $verifikasiAcara = $this->model->getVerifikasiAcara();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function pengguna() {
        $allUsers = $this->model->getAllUsers();
        $totalUsers = $this->model->countUsers();
        require_once __DIR__ . '/../views/admin/pengguna.php';
    }

    public function kategori() {
        $kategori = $this->model->getKategori();
        require_once __DIR__ . '/../views/admin/kategori.php';
    }

    public function verifikasi() {
        $verifikasiAcara = $this->model->getVerifikasiAcara();
        require_once __DIR__ . '/../views/admin/verifikasi.php';
    }
}