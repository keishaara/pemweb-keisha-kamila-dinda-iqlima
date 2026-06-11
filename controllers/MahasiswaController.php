<?php
require_once __DIR__ . '/../models/MahasiswaModel.php';
require_once __DIR__ . '/../models/EventModel.php'; 

class MahasiswaController {

    private MahasiswaModel $model;
    private EventModel $eventModel; 

    public function __construct()
    {
        $this->model = new MahasiswaModel();
        $this->eventModel = new EventModel();
    }

    public function detailEvent()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php");
            exit;
        }
        $eventId = $_GET['id'] ?? 1;
        return $this->model->getEventById($eventId);
    }

    public function dataDiri()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php");
            exit;
        }
        $userId  = $_SESSION['user_id'];

        if (isset($_REQUEST['id'])) {
            $_SESSION['current_event_id'] = $_REQUEST['id'];
        } elseif (isset($_REQUEST['event_id'])) {
            $_SESSION['current_event_id'] = $_REQUEST['event_id'];
        }

        $eventId = $_SESSION['current_event_id'] ?? 1;

        return [
            'user'  => $this->model->getUserById($userId),
            'event' => $this->model->getEventById($eventId)
        ];
    }

    public function getDashboardStats($userId)
    {
        return $this->model->getDashboardStats($userId);
    }

    public function getSavedCount($userId)
    {
        return $this->model->getSavedCount($userId);
    }

    public function getUpcomingEventsDashboard($userId)
    {
        return $this->model->getUpcomingEventsDashboard($userId);
    }

    public function indexFeatures()
    {
        return $this->eventModel->getLatestEvents(4);
    }

    public function cekEventDisimpan($userId, $eventId)
    {
        return $this->model->isEventSaved($userId, $eventId);
    }

    public function simpanEvent($userId, $eventId)
    {
        return $this->model->saveEvent($userId, $eventId);
    }

    public function getTicketsByUser($userId, $status = '')
    {
        return $this->model->getTicketsByUser($userId, $status);
    }

    public function getTicketByKode($kodeBooking)
    {
        return $this->model->getTicketByKode($kodeBooking);
    }

    public function getEvents($search = '', $catId = '', $isFree = false)
    {
        return $this->model->getEvents($search, $catId, $isFree);
    }

    public function getProfile($userId)
    {
        return $this->model->getUserById($userId);
    }

    public function updateProfile(
    $userId,
    $nama,
    $email,
    $programStudi,
    $wa,
    $semester,
    $fileFoto = null, 
    $oldFoto = null
)
{
    $fotoName = $oldFoto; 
    if ($fileFoto && $fileFoto['error'] === UPLOAD_ERR_OK) {
        $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($fileFoto['name']));
        
        $uploadDir = __DIR__ . '/../assets/profiles/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileFoto['tmp_name'], $uploadDir . $fileName)) {
            $fotoName = $fileName; 

            if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
                unlink($uploadDir . $oldFoto);
            }
        }
    }

    return $this->model->updateProfile(
        $userId, $nama, $email, $programStudi, $wa, $semester, $fotoName
    );
}

    public function changePassword(
        $userId,
        $newPassword
    )
    {
        $hash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        return $this->model->updatePassword(
            $userId,
            $hash
        );
    }

    public function getSavedEvents($userId)
    {
        return $this->model->getSavedEvents($userId);
    }

    public function removeSavedEvent(
        $userId,
        $eventId
    )
    {
        return $this->model->removeSavedEvent(
            $userId,
            $eventId
        );
    }

    public function createBooking(
        $eventId,
        $userId,
        $kodeBooking,
        $metode,
        $buktiTransfer
    )
    {
        return $this->model->createBooking(
            $eventId,
            $userId,
            $kodeBooking,
            $metode,
            $buktiTransfer
        );
    }

    public function isAlreadyRegistered($userId, $eventId)
    {
        return $this->model->isAlreadyRegistered(
            $userId,
            $eventId
        );
    }

    // --- MVC ACTIONS ---
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
            header("Location: view/auth/login.php");
            exit;
        }
    }

    public function action_dashboard() {
        $this->checkAuth();
        $user_id = $_SESSION['user_id'];
        $nama_user = $_SESSION['nama_lengkap'] ?? 'User';

        $stats = $this->getDashboardStats($user_id);
        $saved = $this->getSavedCount($user_id);
        $res_event = $this->getUpcomingEventsDashboard($user_id);

        require_once __DIR__ . '/../view/mahasiswa/user_dashboard.php';
    }

    public function action_profil() {
        $this->checkAuth();
        $uid = $_SESSION['user_id'];
        
        $msg = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getProfile($uid);
            if (isset($_POST['update_profil'])) {
                $success = $this->updateProfile(
                    $uid,
                    trim($_POST['nama'] ?? ''),
                    trim($_POST['email'] ?? ''),
                    trim($_POST['program_studi'] ?? ''),
                    trim($_POST['wa'] ?? ''),
                    trim($_POST['semester'] ?? ''),
                    $_FILES['foto_profil'] ?? null,  
                    $user['foto_profil'] ?? null     
                );
                $msg = $success ? "Profil berhasil diperbarui." : "Gagal update profil.";
                $msgType = $success ? "success" : "error";
            } elseif (isset($_POST['ganti_sandi'])) {
                $old  = $_POST['pass_lama'];
                $new  = $_POST['pass_baru'];
                $conf = $_POST['konfirmasi'];

                if ($new !== $conf) {
                    $msg = "Konfirmasi sandi tidak cocok.";
                    $msgType = "error";
                } elseif (!password_verify($old, $user['password'])) {
                    $msg = "Sandi lama salah.";
                    $msgType = "error";
                } else {
                    $success = $this->changePassword($uid, $new);
                    $msg = $success ? "Kata sandi berhasil diubah." : "Gagal mengubah sandi.";
                    $msgType = $success ? "success" : "error";
                }
            }
        }
        
        $user = $this->getProfile($uid);
        require_once __DIR__ . '/../view/mahasiswa/profil.php';
    }

    public function action_kegiatan() {
        $this->checkAuth();
        $search  = $_GET['q'] ?? '';
        $cat_id  = $_GET['cat_id'] ?? '';
        $is_free = isset($_GET['free']);

        $events = $this->getEvents($search, $cat_id, $is_free);
        require_once __DIR__ . '/../view/mahasiswa/kegiatan_mhs.php';
    }

    public function action_etiket() {
        $this->checkAuth();
        $statusFilter = $_GET['status'] ?? '';
        $uid = $_SESSION['user_id'];
        $tiketList = $this->getTicketsByUser($uid, $statusFilter);
        $judul = "E-Tiket Saya";
        require_once __DIR__ . '/../view/mahasiswa/e-tiket.php';
    }

    public function action_detail() {
        $this->checkAuth();
        $eventId = $_GET['id'] ?? 1;
        $event = $this->model->getEventById($eventId);
        if (!$event) {
            echo "Event tidak ditemukan.";
            exit;
        }
        $userId = $_SESSION['user_id'];
        $isSaved = $this->cekEventDisimpan($userId, $eventId);
        $isRegistered = $this->isAlreadyRegistered($userId, $eventId);
        require_once __DIR__ . '/../view/mahasiswa/detail.php';
    }

    public function action_pembayaran() {
        $this->checkAuth();
        
        $userId  = $_SESSION['user_id'];
        if (isset($_REQUEST['id'])) {
            $_SESSION['current_event_id'] = $_REQUEST['id'];
        } elseif (isset($_REQUEST['event_id'])) {
            $_SESSION['current_event_id'] = $_REQUEST['event_id'];
        }
        $eventId = $_SESSION['current_event_id'] ?? 1;
        $event = $this->model->getEventById($eventId);
        if (!$event) {
            echo "Event tidak ditemukan.";
            exit;
        }
        $user = $this->model->getUserById($userId);

        if ($this->isAlreadyRegistered($userId, $eventId)) {
            header("Location: index.php?module=mahasiswa&action=etiket");
            exit;
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $metode = $_POST['metode_pembayaran'] ?? '';
            $bukti  = $_FILES['bukti_transfer'] ?? null;

            if (empty($metode) && $event['harga'] > 0) {
                $error = "Metode pembayaran wajib dipilih.";
            } else {
                $kodeBooking = 'EVT-' . strtoupper(substr(uniqid(), -6));
                $namaFile = null;

                if ($event['harga'] > 0 && $bukti && $bukti['error'] === 0) {
                    $ext = pathinfo($bukti['name'], PATHINFO_EXTENSION);
                    $namaFile = time() . '_' . $kodeBooking . '.' . $ext;
                    $target   = __DIR__ . '/../assets/bukti_transfer/' . $namaFile;
                    if (!is_dir(__DIR__ . '/../assets/bukti_transfer/')) {
                        mkdir(__DIR__ . '/../assets/bukti_transfer/', 0777, true);
                    }
                    if (move_uploaded_file($bukti['tmp_name'], $target)) {
                        // success upload
                    } else {
                        $error = "Gagal mengunggah bukti pembayaran.";
                    }
                }

                if (!$error) {
                    $success = $this->createBooking(
                        $eventId,
                        $userId,
                        $kodeBooking,
                        $event['harga'] == 0 ? 'Gratis' : $metode,
                        $namaFile
                    );
                    if ($success) {
                        header("Location: index.php?module=mahasiswa&action=etiket");
                        exit;
                    } else {
                        $error = "Gagal mendaftar event. Silakan coba lagi.";
                    }
                }
            }
        }

        require_once __DIR__ . '/../view/mahasiswa/pembayaran.php';
    }

    public function action_savedEvents() {
        $this->checkAuth();
        $uid = $_SESSION['user_id'];
        $saved = $this->getSavedEvents($uid);
        require_once __DIR__ . '/../view/mahasiswa/saved_events.php';
    }

    public function action_hapusSavedEvent() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'] ?? null;
            $userId  = $_SESSION['user_id'];
            if ($eventId) {
                $this->removeSavedEvent($userId, $eventId);
            }
        }
        header("Location: index.php?module=mahasiswa&action=savedEvents");
        exit;
    }

    public function action_simpanEventAction() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'] ?? null;
            $userId  = $_SESSION['user_id'];
            if ($eventId) {
                $isSaved = $this->cekEventDisimpan($userId, $eventId);
                if ($isSaved) {
                    $this->removeSavedEvent($userId, $eventId);
                } else {
                    $this->simpanEvent($userId, $eventId);
                }
            }
        }
        $from = $_POST['from'] ?? 'dashboard';
        $redirectUrl = 'index.php?module=mahasiswa&action=dashboard';
        if ($from === 'detail') {
            $redirectUrl = "index.php?module=mahasiswa&action=detail&id=" . ($eventId ?? 1);
        } elseif ($from === 'saved') {
            $redirectUrl = 'index.php?module=mahasiswa&action=savedEvents';
        }
        header("Location: " . $redirectUrl);
        exit;
    }

    public function action_printTiket() {
        $this->checkAuth();
        $kode = $_GET['kode'] ?? '';
        if (empty($kode)) {
            echo "Kode booking tidak valid.";
            exit;
        }
        $tiket = $this->getTicketByKode($kode);
        if (!$tiket || $tiket['user_id'] != $_SESSION['user_id']) {
            echo "Tiket tidak ditemukan atau akses ditolak.";
            exit;
        }
        require_once __DIR__ . '/../view/mahasiswa/print_tiket.php';
    }

    public function action_dataDiri() {
        $this->checkAuth();
        $userId  = $_SESSION['user_id'];

        if (isset($_REQUEST['id'])) {
            $_SESSION['current_event_id'] = $_REQUEST['id'];
        } elseif (isset($_REQUEST['event_id'])) {
            $_SESSION['current_event_id'] = $_REQUEST['event_id'];
        }

        $eventId = $_SESSION['current_event_id'] ?? 1;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Location: index.php?module=mahasiswa&action=pembayaran");
            exit;
        }

        $user = $this->model->getUserById($userId);
        $event = $this->model->getEventById($eventId);
        if (!$event) {
            echo "Event tidak ditemukan.";
            exit;
        }
        
        $labelAlasan = ($event['kategori_id'] == 1 || $event['kategori_id'] == 3) ? 'Alasan Mengikuti' : 'Harapan Mengikuti';
        $labelPengalaman = 'Apakah Anda memiliki pengalaman sebelumnya?';

        require_once __DIR__ . '/../view/mahasiswa/data_diri.php';
    }
}