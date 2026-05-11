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
}