<?php

require_once __DIR__ . '/../config/koneksi.php';

class OrganizerModel {

    private mysqli $conn;

    public function __construct()
    {
        global $conn;

        $this->conn = $conn;
    }

    public function getOrganizerById($id)
    {
        $query = mysqli_query(

            $this->conn,

            "SELECT *
             FROM users
             WHERE id = '$id'"
        );

        return mysqli_fetch_assoc($query);
    }

    public function getStatistik($id)
    {
        return [

            'total_peserta' => 1240,

            'event_aktif' => 3,

            'menunggu_verifikasi' => 1,

            'event_selesai' => 12
        ];
    }

    public function getEventTerbaru($id)
    {
        $query = mysqli_query(

            $this->conn,

            "SELECT *
             FROM events
             ORDER BY id DESC
             LIMIT 3"
        );

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {

            $data[] = $row;
        }

        return $data;
    }

    public function getAgendaTerdekat($id)
    {
        $query = mysqli_query(

            $this->conn,

            "SELECT nama_event, tanggal
             FROM events
             ORDER BY tanggal ASC
             LIMIT 3"
        );

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {

            $data[] = $row;
        }

        return $data;
    }

    public function getKelolaAcara($id)
    {
        $query = mysqli_query(

            $this->conn,

            "SELECT *
             FROM events
             ORDER BY tanggal DESC"
        );

        $data = [];

        while ($row = mysqli_fetch_assoc($query)) {

            $data[] = $row;
        }

        return $data;
    }
}