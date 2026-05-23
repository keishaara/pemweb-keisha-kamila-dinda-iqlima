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

    public function dataPeserta()
    {
        $organizerId = $_SESSION['user_id'] ?? 0;
        return $this->model->getPesertaByOrganizer($organizerId);
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

        $nama_poster = 'default.png'; 
        if (isset($files['poster']) && $files['poster']['error'] === 0) {
            $ext = strtolower(pathinfo($files['poster']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $nama_poster = time() . '_' . uniqid() . '.' . $ext;
               move_uploaded_file($files['poster']['tmp_name'], __DIR__ . '/../assets/img/' . $nama_poster);
            }
        }

        $dataDB = [
            'judul_event'     => htmlspecialchars($post['judul_event']),
            'penyelenggara'   => $penyelenggara,
            'deskripsi'       => htmlspecialchars($post['deskripsi']),
            'poster'          => $nama_poster, 
            'tanggal'         => htmlspecialchars($post['tanggal']),
            'tanggal_selesai' => htmlspecialchars($post['tanggal_selesai']), 
            'waktu'           => htmlspecialchars($post['waktu']),
            'lokasi'          => htmlspecialchars($post['lokasi']),
            'kuota'           => !empty($post['kuota']) ? intval($post['kuota']) : 0, 
            'harga'           => 0.00, 
            'kategori_id'     => intval($post['kategori_id']),
            'jenis_acara'     => htmlspecialchars($post['jenis_acara']), 
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
            
            $dataInput = [
                'judul_event' => $_POST['judul_event'],
                'deskripsi'   => $_POST['deskripsi'],
                'tanggal'     => $_POST['tanggal'],
                'kuota'       => intval($_POST['kuota'])
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