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
        $tanggal_selesai = isset($post['tanggal_selesai']) ? $post['tanggal_selesai'] : null;
        $waktu           = isset($post['waktu']) ? $post['waktu'] : '';
        $lokasi          = isset($post['lokasi']) ? trim(htmlspecialchars($post['lokasi'])) : '';
        $kuota           = isset($post['kuota']) ? intval($post['kuota']) : 0;
        $kategori_id     = isset($post['kategori_id']) ? intval($post['kategori_id']) : null;
        $jenis_acara     = isset($post['jenis_acara']) ? $post['jenis_acara'] : '';
        $harga           = isset($post['harga']) ? intval($post['harga']) : 0;

        if (empty($judul_event))     $errors[] = "Nama Acara tidak boleh kosong.";
        if (empty($deskripsi))       $errors[] = "Deskripsi acara wajib diisi.";
        if (empty($tanggal))         $errors[] = "Tanggal mulai acara tidak boleh kosong.";
        if (empty($waktu))           $errors[] = "Jam pelaksanaan acara wajib ditentukan.";
        if (empty($lokasi))          $errors[] = "Tempat/lokasi acara tidak boleh kosong.";
        if ($kuota <= 0)             $errors[] = "Kuota jumlah peserta wajib berupa angka positif di atas 0.";
        if (empty($jenis_acara))     $errors[] = "Pilih jenis acara antara Online atau Offline.";

        $nama_poster = 'default.png'; 
        if (isset($files['poster']) && $files['poster']['error'] === 0) {
            $ext = strtolower(pathinfo($files['poster']['name'], PATHINFO_EXTENSION));
            $fileSize = $files['poster']['size'];

            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $errors[] = "Format file gambar poster ditolak. Hanya diperbolehkan ekstensi PNG, JPG, dan JPEG.";
            }
            if ($fileSize > 2097152) { 
                $errors[] = "Ukuran file gambar poster melampaui batas maksimal 2MB.";
            }

            if (empty($errors)) {
                $nama_poster = time() . '_' . uniqid() . '.' . $ext;
                $target_dir = __DIR__ . '/../assets/poster/';
                
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }
                
                move_uploaded_file($files['poster']['tmp_name'], $target_dir . $nama_poster);
            }
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
            $tanggal_selesai = isset($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : '';
            $waktu       = isset($_POST['waktu']) ? $_POST['waktu'] : '';
            $lokasi      = isset($_POST['lokasi']) ? trim(htmlspecialchars($_POST['lokasi'])) : '';
            $kategori_id = isset($_POST['kategori_id']) ? intval($_POST['kategori_id']) : 0;
            $jenis_acara = isset($_POST['jenis_acara']) ?trim($_POST['jenis_acara'] ?? '') : '';
            $kuota       = isset($_POST['kuota']) ? intval($_POST['kuota']) : 0;
            $harga       = isset($_POST['harga']) ? intval($_POST['harga']) : 0;

            if (empty($judul_event)) $errors[] = "Nama Acara tidak boleh dikosongkan.";
            if (empty($deskripsi))   $errors[] = "Deskripsi teks acara wajib diisi.";
            if (empty($tanggal))     $errors[] = "Tanggal pelaksanaan tidak boleh dikosongkan.";
            if ($kuota <= 0)         $errors[] = "Kuota batasan peserta harus berupa angka di atas 0.";
            if ($harga < 0)          $errors[] = "Harga pendaftaran tidak boleh bernilai negatif.";
            if ($kategori_id <= 0)   $errors[] = "Pilihan kategori tidak valid.";

            if (!empty($errors)) {
                $_SESSION['form_errors'] = $errors;
                header("Location: org_buat_acara.php?id=" . $eventId);
                exit();
            }
            
            $dataInput = [
                'judul_event' => $judul_event,
                'deskripsi'   => $deskripsi,
                'tanggal'     => $tanggal,
                'tanggal_selesai' => $tanggal_selesai,
                'waktu'       => $waktu,
                'lokasi'      => $lokasi,
                'kategori_id' => $kategori_id,
                'jenis_acara' => $jenis_acara,
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

        public function prosesEditProfil($postData, $fileFoto = null, $oldFoto = null) {
            $userId = $_SESSION['user_id'] ?? 0;
            
            $fotoName = $oldFoto; 

            if ($fileFoto && $fileFoto['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($fileFoto['name'], PATHINFO_EXTENSION));
                
                if (in_array($ext, ['png', 'jpg', 'jpeg']) && $fileFoto['size'] <= 2097152) {
                    $fileName = 'org_' . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", basename($fileFoto['name']));
                    
                    $uploadDir = __DIR__ . '/../assets/profiles/'; 
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (move_uploaded_file($fileFoto['tmp_name'], $uploadDir . $fileName)) {
                        $fotoName = $fileName;
                        
                        if (!empty($oldFoto) && file_exists($uploadDir . $oldFoto)) {
                            unlink($uploadDir . $oldFoto);
                        }
                    }
                }
            }
            
            $dataInput = [
            'nama_lengkap' => $postData['nama_lengkap'] ?? '',
            'singkatan'    => $postData['singkatan'] ?? '',
            'email'        => $postData['email'] ?? '',
            'no_whatsapp'  => $postData['whatsapp'] ?? '',
            'deskripsi'    => $postData['deskripsi'] ?? '',
            'foto_profil'  => $fotoName 
        ];
        return $this->model->updateOrganizerProfile($userId, $dataInput);
    }
}