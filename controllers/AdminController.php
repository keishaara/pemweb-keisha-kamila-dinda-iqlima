<?php
require_once __DIR__ . '/../models/AdminModel.php';

class AdminController {

    private $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?module=admin&action=login");
            exit;
        }
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

    public function action_login() {
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
            header("Location: index.php?module=admin&action=dashboard");
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier']);
            $password   = $_POST['password'];
            $role       = 'admin';

            if (!empty($identifier) && !empty($password)) {
                $res = $this->model->login($identifier, $role);
                
                if ($user = mysqli_fetch_assoc($res)) {
                    if (($user['status'] ?? 'Aktif') === 'Nonaktif') {
                        $error = "Akun Anda telah dinonaktifkan.";
                    } elseif (password_verify($password, $user['password'])) {
                        $_SESSION['user_id']      = $user['id'];
                        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                        $_SESSION['email']        = $user['email'];
                        $_SESSION['role']         = 'admin';
                        $_SESSION['last_activity'] = time();

                        header("Location: index.php?module=admin&action=dashboard");
                        exit;
                    } else {
                        $error = "Akun tidak ditemukan atau role tidak sesuai.";
                    }
                } else {
                    $error = "Akun tidak ditemukan atau role tidak sesuai.";
                }
            } else {
                $error = "Semua field wajib diisi.";
            }    
        }
        
        require_once __DIR__ . '/../view/admin/login_admin.php';
    }

    public function action_dashboard() {
        $this->checkAuth();
        $totalUsersData = $this->getTotalUsers(); 
        $totalOrgData = $this->getTotalOrganisasi(); 
        $latestUsers = $this->getLatestUsers();
        $verifikasiAcara = $this->getVerifikasiAcara();
        $semuaAcara = $this->getAllEvents();
        $totalMahasiswa = $this->getTotalMahasiswa();

        require_once __DIR__ . '/../view/admin/dashboard.php';
    }

    public function action_semua_acara() {
        $this->checkAuth();
        $this->prosesLockEvent();
        $semuaAcara = $this->getAllEvents();
        require_once __DIR__ . '/../view/admin/semua_acara.php';
    }

    public function action_pengguna() {
        $this->checkAuth();
        $this->prosesToggleStatusPengguna();
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        $allUsers = $this->model->getAllUsers($keyword, $role, $status);
        $totalUsersCount = $this->getTotalUsers();
        $hasStatusColumn = $this->usersHaveStatusColumn();
        
        require_once __DIR__ . '/../view/admin/pengguna.php';
    }

    public function action_kategori() {
        $this->checkAuth();
        $this->prosesHapusKategori();
        $this->prosesTambahKategori();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
            $id_edit = intval($_POST['id_kategori']);
            $this->prosesEditKategori($id_edit);
        }
        $kategori = $this->model->getKategori();
        require_once __DIR__ . '/../view/admin/kategori.php';
    }

    public function action_verifikasi() {
        $this->checkAuth();
        $this->prosesVerifikasiAcara();
        $verifikasiAcara = $this->getVerifikasiAcara();
        require_once __DIR__ . '/../view/admin/verifikasi.php';
    }

    public function prosesTambahKategori() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_tambah'])) {
            $nama      = trim(htmlspecialchars($_POST['nama_kategori']));
            $deskripsi = trim(htmlspecialchars($_POST['deskripsi']));
            
            if (empty($nama) || empty($deskripsi)) {
                $_SESSION['kat_error'] = "Semua kolom kategori wajib diisi.";
                header("Location: index.php?module=admin&action=kategori");
                exit;
            }
            $conn = $GLOBALS['conn']; 
            $nama_clean = mysqli_real_escape_string($conn, $nama);
            $check = mysqli_query($conn, "SELECT id FROM categories WHERE nama_kategori = '$nama_clean'");
            
            if (mysqli_num_rows($check) > 0) {
                $_SESSION['kat_error'] = "Kategori dengan nama '$nama' sudah ada di platform Evently.";
                header("Location: index.php?module=admin&action=kategori");
                exit;
            }
            $this->model->insertKategori($nama, $deskripsi);
            header("Location: index.php?module=admin&action=kategori&status=success");
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
                header("Location: index.php?module=admin&action=kategori");
                exit;
            }

            $conn = $GLOBALS['conn'];
            $id = intval($id);
            $nama_clean = mysqli_real_escape_string($conn, $nama);

            $check = mysqli_query($conn, "SELECT id FROM categories WHERE nama_kategori = '$nama_clean' AND id != $id");            
            if (mysqli_num_rows($check) > 0) {
                $_SESSION['kat_error'] = "Gagal memperbarui! Nama kategori '$nama' sudah digunakan oleh kategori lain.";
                header("Location: index.php?module=admin&action=kategori");
                exit;
            }

            $this->model->updateKategori($id, $nama, $deskripsi);
            header("Location: index.php?module=admin&action=kategori&status=updated");
            exit;
        }
    }

    public function prosesHapusKategori() {
        if (isset($_GET['act']) && $_GET['act'] === 'hapus' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $this->model->deleteKategori($id);
            header("Location: index.php?module=admin&action=kategori&status=deleted");
            exit;
        }
    }

   public function prosesToggleStatusPengguna() {
        if (isset($_GET['act']) && $_GET['act'] === 'toggle_status' && isset($_GET['id']) && isset($_GET['current'])) {
            $id = intval($_GET['id']);
            $currentStatus = $_GET['current'];

            $eksekusi = $this->model->toggleUserStatus($id, $currentStatus);
            if (!$eksekusi) {
                die("Gagal memperbarui status di database! Kemungkinan nama kolom salah atau koneksi terputus.");
            }
            header("Location: index.php?module=admin&action=pengguna");
            exit;
        }
    }

    public function prosesVerifikasiAcara() {
        if (isset($_GET['act']) && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $action = $_GET['act'];

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
                header("Location: index.php?module=admin&action=verifikasi");
                exit;
            }
            
            unset($_SESSION['db_error']);
            header("Location: index.php?module=admin&action=verifikasi&status=success");
            exit;
        }
    }

    public function prosesLockEvent() {
        if (isset($_GET['act']) && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $action = $_GET['act'];
            
            if ($action === 'lock') {
                $eksekusi = $this->model->updateStatusEvent($id, 'locked');
                if (!$eksekusi) {
                    $_SESSION['action_error'] = "Gagal mengunci acara!";
                } else {
                    $_SESSION['action_success'] = "Acara berhasil dikunci dan ditangguhkan.";
                }
                header("Location: index.php?module=admin&action=semua_acara");
                exit;
            } elseif ($action === 'unlock_approve') {
                $eksekusi = $this->model->updateStatusEvent($id, 'approved');
                if (!$eksekusi) {
                    $_SESSION['action_error'] = "Gagal menyetujui acara!";
                } else {
                    $_SESSION['action_success'] = "Acara berhasil disetujui kembali.";
                }
                header("Location: index.php?module=admin&action=semua_acara");
                exit;
            } elseif ($action === 'unlock_reject') {
                $eksekusi = $this->model->updateStatusEvent($id, 'rejected');
                if (!$eksekusi) {
                    $_SESSION['action_error'] = "Gagal menolak acara!";
                } else {
                    $_SESSION['action_success'] = "Acara berhasil ditolak secara permanen.";
                }
                header("Location: index.php?module=admin&action=semua_acara");
                exit;
            }
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