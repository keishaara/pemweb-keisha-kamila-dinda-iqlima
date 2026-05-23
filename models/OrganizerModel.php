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
    $sql = "SELECT e.*, 
                   (SELECT COUNT(*) FROM bookings b WHERE b.event_id = e.id) as jumlah_peserta 
            FROM events e 
            WHERE e.user_id = ? 
            ORDER BY e.tanggal DESC";

    $stmt = mysqli_prepare($this->conn, $sql);
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

    public function insertEvent($data)
    {
        $sql = "INSERT INTO events (judul_event, penyelenggara, deskripsi, poster, tanggal, tanggal_selesai, waktu, lokasi, kuota, harga, kategori_id, jenis_acara, status, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            die("Gagal mempersiapkan query: " . $this->conn->error);
        }
        $types = "ssssssssiiissi";
        $stmt->bind_param(
            $types,
            $data['judul_event'],
            $data['penyelenggara'],
            $data['deskripsi'],
            $data['poster'],
            $data['tanggal'],
            $data['tanggal_selesai'],
            $data['waktu'],
            $data['lokasi'],
            $data['kuota'],
            $data['harga'],
            $data['kategori_id'],
            $data['jenis_acara'],
            $data['status'],
            $data['user_id']
        );
        return $stmt->execute();
    }

    public function deleteEvent($eventId, $userId) {
    $sql_bookings = "DELETE FROM bookings WHERE event_id = ? 
                     AND event_id IN (SELECT id FROM events WHERE user_id = ?)";
    $stmt_bookings = mysqli_prepare($this->conn, $sql_bookings);
                     mysqli_stmt_bind_param($stmt_bookings, "ii", $eventId, $userId);
                     mysqli_stmt_execute($stmt_bookings);
    $sql_event = "DELETE FROM events WHERE id = ? AND user_id = ?";
    $stmt_event = mysqli_prepare($this->conn, $sql_event);
                  mysqli_stmt_bind_param($stmt_event, "ii", $eventId, $userId);
    
    return mysqli_stmt_execute($stmt_event);
}
// Ambil 1 data event untuk ditampilkan kembali di form
public function getEventById($eventId, $userId) {
    $stmt = mysqli_prepare($this->conn, "SELECT * FROM events WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $eventId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

    public function updateEvent($eventId, $userId, $data) {
        $sql = "UPDATE events SET 
                    judul_event = ?, 
                    deskripsi = ?, 
                    tanggal = ?, 
                    kuota = ?
                WHERE id = ? AND user_id = ?";
                
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param(
            $stmt, 
            "sssiii", 
            $data['judul_event'], 
            $data['deskripsi'], 
            $data['tanggal'], 
            $data['kuota'], 
            $eventId, 
            $userId
        );
        return mysqli_stmt_execute($stmt);
    }

}