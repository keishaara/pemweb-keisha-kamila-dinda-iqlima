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

    public function getAllUsers($keyword = '', $role = '', $status = '') {
        return $this->model->getAllUsers($keyword, $role, $status);
    }

    public function usersHaveStatusColumn() {
        return $this->model->hasUserStatusColumn();
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
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        $allUsers = $this->model->getAllUsers($keyword, $role, $status);
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

    public function prosesTambahKategori() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_tambah'])) {
            $nama = $_POST['nama_kategori'];
            $deskripsi = $_POST['deskripsi'];
            $this->model->insertKategori($nama, $deskripsi);
            header("Location: kategori.php?status=success");
            exit;
        }
    }

    public function getCategoryById($id) {
        return $this->model->getCategoryById($id);
    }

    public function prosesEditKategori($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
            $nama = $_POST['nama_kategori'];
            $deskripsi = $_POST['deskripsi'];
            $this->model->updateKategori($id, $nama, $deskripsi);
            header("Location: kategori.php?status=updated");
            exit;
        }
    }

    public function prosesHapusKategori() {
        if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $this->model->deleteKategori($id);
            header("Location: kategori.php?status=deleted");
            exit;
        }
    }

   public function prosesToggleStatusPengguna() {
        if (isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id']) && isset($_GET['current'])) {
            $id = intval($_GET['id']);
            $currentStatus = $_GET['current'];
            $this->model->toggleUserStatus($id, $currentStatus);
            header("Location: pengguna.php");
            exit;
        }
    }

    public function prosesVerifikasiAcara() {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $action = $_GET['action'];
            
            if ($action === 'setuju') {
                $status = 'approved';
            } elseif ($action === 'tolak') {
                $status = 'rejected';
            } else {
                return;
            }
            
            $this->model->updateStatusEvent($id, $status);
            header("Location: verifikasi.php");
            exit;
        }
    }
}
