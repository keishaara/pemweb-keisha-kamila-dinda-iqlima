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
}