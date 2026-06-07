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

    public function getTotalMahasiswa() {
        $res = $this->model->countMahasiswa();
        return $res['total'] ?? 0;
    }

    public function getLatestUsers() {
        return $this->model->getLatestUsers();
    }

    public function getVerifikasiAcara() {
        return $this->model->getVerifikasiAcara();
    }
   
    public function getLatestEvents() {
        return $this->model->getLatestEvents();
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
        $totalMahasiswa = $this->getTotalMahasiswa();
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
            $nama      = trim(htmlspecialchars($_POST['nama_kategori']));
            $deskripsi = trim(htmlspecialchars($_POST['deskripsi']));
            
            if (empty($nama) || empty($deskripsi)) {
                $_SESSION['kat_error'] = "Semua kolom kategori wajib diisi.";
                header("Location: kategori.php");
                exit;
            }
            $conn = $GLOBALS['conn']; 
            $nama_clean = mysqli_real_escape_string($conn, $nama);
            $check = mysqli_query($conn, "SELECT id FROM categories WHERE nama_kategori = '$nama_clean'");
            
            if (mysqli_num_rows($check) > 0) {
                $_SESSION['kat_error'] = "Kategori dengan nama '$nama' sudah ada di platform Evently.";
                header("Location: kategori.php");
                exit;
            }
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
            $nama      = trim(htmlspecialchars($_POST['nama_kategori']));
            $deskripsi = trim(htmlspecialchars($_POST['deskripsi']));
            
            if (empty($nama) || empty($deskripsi)) {
                $_SESSION['kat_error'] = "Semua kolom perubahan kategori wajib diisi.";
                header("Location: kategori.php");
                exit;
            }

            $conn = $GLOBALS['conn'];
            $id = intval($id);
            $nama_clean = mysqli_real_escape_string($conn, $nama);

            $check = mysqli_query($conn, "SELECT id FROM categories WHERE nama_kategori = '$nama_clean' AND id != $id");            
            if (mysqli_num_rows($check) > 0) {
                $_SESSION['kat_error'] = "Gagal memperbarui! Nama kategori '$nama' sudah digunakan oleh kategori lain.";
                header("Location: kategori.php");
                exit;
            }

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

            $eksekusi = $this->model->toggleUserStatus($id, $currentStatus);
            if (!$eksekusi) {
                die("Gagal memperbarui status di database! Kemungkinan nama kolom salah atau koneksi terputus.");
            }
            header("Location: pengguna.php");
            exit;
        }
    }

    public function prosesVerifikasiAcara() {
        if (isset($_GET['action']) && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $action = $_GET['action'];

            if ($action === 'setuju') {
                $eksekusi = $this->model->updateStatusEvent($id, 'approved');
            } elseif ($action === 'tolak') {
                $alasan = isset($_GET['alasan']) ? trim(urldecode($_GET['alasan'])) : 'Ditolak oleh admin tanpa alasan spesifik.';
                $eksekusi = $this->model->rejectEventWithReason($id, $alasan);
            } else {
                return; 
            }
            
            if (!$eksekusi) {
                $dbError = isset($GLOBALS['conn']) ? mysqli_error($GLOBALS['conn']) : 'Query gagal dieksekusi';
                $_SESSION['db_error'] = "Gagal Update! Pesan Error: " . $dbError;
                header("Location: verifikasi.php");
                exit;
            }
            
            unset($_SESSION['db_error']);
            header("Location: verifikasi.php?status=success");
            exit;
        }
    }

    private function updateEventStatus($id, $status) {
        $methods = [
            'updateStatusEvent',
            'updateEventStatus',
            'setStatusEvent',
            'setEventStatus',
        ];

        foreach ($methods as $method) {
            if (method_exists($this->model, $method)) {
                return $this->model->{$method}($id, $status);
            }
        }
        return false;
    }

    public function getAllEvents() {
    return $this->model->getAllEvents();
    }
}
?>