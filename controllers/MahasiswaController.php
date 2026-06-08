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
}