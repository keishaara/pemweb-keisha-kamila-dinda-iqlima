<?php
require_once __DIR__ . '/../config/koneksi.php';

class EventModel {

    private mysqli $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getEventById(int $id)
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT * FROM events WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function getLatestEvents(int $limit = 4)
    {
        $query = "SELECT events.*, users.nama_lengkap as nama_organisasi, categories.nama_kategori 
                  FROM events 
                  JOIN users ON events.user_id = users.id 
                  LEFT JOIN categories ON events.kategori_id = categories.id
                  WHERE events.status = 'approved' 
                  ORDER BY events.tanggal ASC 
                  LIMIT ?";
                  
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
}