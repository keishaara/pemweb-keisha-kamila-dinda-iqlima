<?php
require_once __DIR__ . '/../models/OrganizerModel.php';

class OrganizerController {

    private OrganizerModel $model;

    public function __construct()
    {
        $this->model = new OrganizerModel();
    }

    public function dashboard()
    {
        $organizerId = $_SESSION['user_id'] ?? 0;

        return [
            'organizer' => $this->model->getOrganizerById($organizerId),
            'statistik' => $this->model->getStatistik($organizerId),
            'events'    => $this->model->getEventTerbaru($organizerId),
            'agenda'    => $this->model->getAgendaTerdekat($organizerId)
        ];
    }

    public function getKelolaAcara()
    {
        $organizerId = $_SESSION['user_id'] ?? 0;
        return $this->model->getKelolaAcara($organizerId);
    }

    public function dataPeserta($keyword = '', $eventId = '')
    {
        $organizerId = $_SESSION['user_id'] ?? 0;
        return $this->model->getPesertaByOrganizer($organizerId, $keyword, $eventId);
    }

    public function getEvents()
    {
        $organizerId = $_SESSION['user_id'] ?? 0;
        return $this->model->getEventsByOrganizer($organizerId);
    }

    public function profile()
    {
        $organizerId = $_SESSION['user_id'] ?? 0;
        return $this->model->getOrganizerById($organizerId);
    }

    public function prosesTambahAcara($post, $files)
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $organizer = $this->model->getOrganizerById($userId);
        $penyelenggara = $organizer['nama_lengkap'] ?? 'Organisasi';

        $errors = [];

        $judul_event     = isset($post['judul_event']) ? trim(htmlspecialchars($post['judul_event'])) : '';
        $deskripsi       = isset($post['deskripsi']) ? trim(htmlspecialchars($post['deskripsi'])) : '';
        $tanggal         = isset($post['tanggal']) ? $post['tanggal'] : '';
        $tanggal_selesai = isset($post['tanggal_selesai']) ? $post['tanggal_selesai'] : '';
        $waktu           = isset($post['waktu']) ? $post['waktu'] : '';
        $lokasi          = isset($post['lokasi']) ? trim(htmlspecialchars($post['lokasi'])) : '';
        $kuota           = isset($post['kuota']) ? intval($post['kuota']) : 0;
        $kategori_id     = isset($post['kategori_id']) ? intval($post['kategori_id']) : 0;
        $jenis_acara     = isset($post['jenis_acara']) ? $post['jenis_acara'] : '';
        $harga           = isset($post['harga']) ? intval($post['harga']) : 0;

        if (empty($judul_event))     $errors[] = "Nama Acara tidak boleh kosong.";
        if (empty($deskripsi))       $errors[] = "Deskripsi acara wajib diisi.";
        if (empty($tanggal))         $errors[] = "Tanggal mulai acara tidak boleh kosong.";
        if (empty($waktu))           $errors[] = "Jam pelaksanaan acara wajib ditentukan.";
        if (empty($lokasi))          $errors[] = "Tempat/lokasi acara tidak boleh kosong.";
        if ($kuota <= 0)             $errors[] = "Kuota jumlah peserta wajib berupa angka positif di atas 0.";
        if ($kategori_id <= 0)       $errors[] = "Pilihan kategori tidak valid.";
        if ($jenis_acara !== 'Online' && $jenis_acara !== 'Offline') $errors[] = "Pilih jenis acara antara Online atau Offline.";
        if ($harga < 0) {
            $errors[] = "Harga pendaftaran tidak boleh bernilai negatif.";
        }

        $today = date('Y-m-d');
        if ($tanggal < $today) {
            $errors[] = "Tanggal pelaksanaan acara tidak boleh di masa lampau.";
        }
        if (!empty($tanggal_selesai) && $tanggal_selesai < $tanggal) {
            $errors[] = "Tanggal selesai acara tidak valid karena mendahului tanggal mulai.";
        }

        $nama_poster = 'default.png'; 
        if (isset($files['poster']) && $files['poster']['error'] === 0) {
            $ext = strtolower(pathinfo($files['poster']['name'], PATHINFO_EXTENSION));
            $fileSize = $files['poster']['size'];

            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $errors[] = "Format file gambar poster ditolak. Hanya diperbolehkan ekstensi PNG, JPG, dan JPEG.";
            }
            if ($fileSize > 2097152) { 
                $errors[] = "Ukuran file gambar poster melampaui batas maksimal. Batas ukuran poster adalah 2MB.";
            }

            if (empty($errors)) {
                $nama_poster = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($files['poster']['tmp_name'], __DIR__ . '/../../assets/img/' . $nama_poster);
            }
        } else {
            $errors[] = "File poster gambar acara wajib diunggah.";
        }

        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            return false;
        }

        $dataDB = [
            'judul_event'     => $judul_event,
            'penyelenggara'   => $penyelenggara,
            'deskripsi'       => $deskripsi,
            'poster'          => $nama_poster, 
            'tanggal'         => $tanggal,
            'tanggal_selesai' => $tanggal_selesai, 
            'waktu'           => $waktu,
            'lokasi'          => $lokasi,
            'kuota'           => $kuota, 
            'harga'           => $harga, 
            'kategori_id'     => $kategori_id,
            'jenis_acara'     => $jenis_acara, 
            'status'          => 'pending', 
            'user_id'         => $userId
        ];
        return $this->model->insertEvent($dataDB);
    }

   public function hapusAcara() {
        if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
            $event_id = intval($_GET['id']);
            $user_id = $_SESSION['user_id'] ?? 0;

            if ($this->model->deleteEvent($event_id, $user_id)) {
                header("Location: org_kelola_acara.php?status=deleted");
                exit();
            } else {
                header("Location: org_kelola_acara.php?status=failed");
                exit();
            }
        }
    }

    public function detailAcara($eventId) {
        $userId = $_SESSION['user_id'] ?? 0;
        return $this->model->getEventById($eventId, $userId);
    }

    public function prosesEditAcara($eventId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'] ?? 0;
            $errors = [];

            $event = $this->model->getEventById($eventId, $userId);
            $status = strtolower($event['status'] ?? 'pending');
            if ($status === 'approved' || $status === 'disetujui') {
                $_SESSION['form_errors'] = ["Acara ini sudah disetujui oleh admin dan tidak boleh diubah lagi."];
                header("Location: org_kelola_acara.php?status=action_blocked");
                exit();
            }

            $judul_event = isset($_POST['judul_event']) ? trim(htmlspecialchars($_POST['judul_event'])) : '';
            $deskripsi   = isset($_POST['deskripsi']) ? trim(htmlspecialchars($_POST['deskripsi'])) : '';
            $tanggal     = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
            $kuota       = isset($_POST['kuota']) ? intval($_POST['kuota']) : 0;
            $harga       = isset($_POST['harga']) ? intval($_POST['harga']) : 0;

            if (empty($judul_event)) $errors[] = "Nama Acara tidak boleh dikosongkan.";
            if (empty($deskripsi))   $errors[] = "Deskripsi teks acara wajib diisi.";
            if (empty($tanggal))     $errors[] = "Tanggal pelaksanaan tidak boleh dikosongkan.";
            if ($kuota <= 0)         $errors[] = "Kuota batasan peserta harus berupa angka di atas 0.";
            if ($harga < 0)          $errors[] = "Harga pendaftaran tidak boleh bernilai negatif.";

            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                header("Location: org_buat_acara.php?id=" . $eventId);
                exit();
            }
            
            $dataInput = [
                'judul_event' => $judul_event,
                'deskripsi'   => $deskripsi,
                'tanggal'     => $tanggal,
                'kuota'       => $kuota,
                'harga'       => $harga
            ];

            if ($this->model->updateEvent($eventId, $userId, $dataInput)) {
                header("Location: org_kelola_acara.php?status=updated");
                exit();
            } else {
                header("Location: org_kelola_acara.php?status=update_failed");
                exit();
            }
        }
    }
}
?>