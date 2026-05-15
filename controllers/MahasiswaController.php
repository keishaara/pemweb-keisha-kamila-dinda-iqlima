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
        $eventId = $_GET['event_id'] ?? 1;

        return [
            'user'  => $this->model->getUserById($userId),
            'event' => $this->model->getEventById($eventId)
        ];
    }

    public function indexFeatures()
    {
        return $this->eventModel->getLatestEvents(4);
    }
}