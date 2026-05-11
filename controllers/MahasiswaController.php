<?php
session_start();
require_once __DIR__ . '/../models/MahasiswaModel.php';

class MahasiswaController {

    private MahasiswaModel $model;

    public function __construct()
    {
        $this->model = new MahasiswaModel();
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
}