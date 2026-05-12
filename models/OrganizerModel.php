<?php
require_once __DIR__ . '/../config/koneksi.php';

class OrganizerModel {
    private mysqli $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getOrganizerById($id) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function getStatistik($id)
    {
        $query = mysqli_query($this->conn, "
            SELECT 
                (SELECT COUNT(*) FROM bookings b JOIN events e ON b.event_id = e.id WHERE e.user_id = '$id') as total_peserta,
                (SELECT COUNT(*) FROM events WHERE user_id = '$id' AND status = 'approved' AND tanggal >= CURDATE()) as event_aktif,
                (SELECT COUNT(*) FROM events WHERE user_id = '$id' AND status = 'pending') as menunggu_verifikasi,
                (SELECT COUNT(*) FROM events WHERE user_id = '$id' AND tanggal < CURDATE()) as event_selesai
        ");
        return mysqli_fetch_assoc($query);
    }

    public function getEventTerbaru($id) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM events WHERE user_id = ? ORDER BY id DESC LIMIT 3");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getAgendaTerdekat($id) {
    $query = mysqli_query($this->conn, "SELECT judul_event, tanggal FROM events WHERE user_id = '$id' ORDER BY tanggal ASC LIMIT 3");
    
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    return $data;
}

    public function getKelolaAcara($id) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM events WHERE user_id = ? ORDER BY tanggal DESC");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getPesertaByOrganizer($organizerId) {
        $query = mysqli_query($this->conn, "
            SELECT 
                u.nama_lengkap, 
                u.npm, 
                u.program_studi, 
                u.email, 
                e.judul_event 
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            JOIN users u ON b.user_id = u.id
            WHERE e.user_id = '$organizerId'
            ORDER BY b.id DESC
        ");

        $data = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
        return $data;
    }
}