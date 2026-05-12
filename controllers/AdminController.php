<?php
require_once __DIR__ . '/../models/AdminModel.php';

class AdminController {

    private $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    public function getTotalUsers() {
        $res = $this->model->countUsers();
        return $res['total'] ?? 0;
    }

    public function getTotalOrganisasi() {
        $res = $this->model->countOrganisasi();
        return $res['total'] ?? 0;
    }

    public function getLatestUsers() {
        return $this->model->getLatestUsers();
    }

    public function getVerifikasiAcara() {
        return $this->model->getVerifikasiAcara();
    }

    public function getAllUsers() {
        return $this->model->getAllUsers();
    }

    public function getKategori() {
    return $this->model->getKategori();
}

    public function dashboard() {
        $totalUsers = $this->getTotalUsers();
        $totalOrganisasi = $this->getTotalOrganisasi();
        $latestUsers = $this->getLatestUsers();
        $verifikasiAcara = $this->getVerifikasiAcara();

        require_once __DIR__ . '/../view/admin/dashboard.php';
    }

    public function pengguna() {
        $allUsers = $this->model->getAllUsers();
        $totalUsers = $this->getTotalUsers();
        
        require_once __DIR__ . '/../view/admin/pengguna.php';
    }

    public function kategori() {
        $kategori = $this->model->getKategori();
        require_once __DIR__ . '/../view/admin/kategori.php';
    }

    public function verifikasi() {
        $verifikasiAcara = $this->getVerifikasiAcara();
        require_once __DIR__ . '/../view/admin/verifikasi.php';
    }
}