<?php
require_once __DIR__ . '/../config/koneksi.php';

class MahasiswaModel {

    private mysqli $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function login(
        string $identifier,
        string $role
    )
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT 
                id,
                nama_lengkap,
                email,
                npm,
                password,
                tipe_akun,
                jurusan,
                semester,
                no_hp,
                status
             FROM users
             WHERE (email = ? OR npm = ?)
             AND tipe_akun = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $identifier,
            $identifier,
            $role
        );
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function getDashboardStats($userId)
    {
        $sql = "
            SELECT
                COUNT(*) as total_terdaftar,
                SUM(CASE WHEN e.tanggal < CURDATE() THEN 1 ELSE 0 END) as total_selesai,
                SUM(CASE WHEN e.tanggal >= CURDATE() THEN 1 ELSE 0 END) as total_mendatang
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            WHERE b.user_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getSavedCount($userId)
    {
        $sql = "
            SELECT COUNT(*) as total_saved
            FROM saved_events
            WHERE user_id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getUpcomingEventsDashboard($userId)
    {
        $sql = "
            SELECT e.*, c.nama_kategori
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            LEFT JOIN categories c ON e.kategori_id = c.id
            WHERE b.user_id = ?
            AND e.tanggal >= CURDATE()
            ORDER BY e.tanggal ASC
            LIMIT 2
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getUserById(int $id)
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT *
             FROM users
             WHERE id = ?"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id
        );
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function getEventById(int $id)
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT e.*, c.nama_kategori
             FROM events e
             LEFT JOIN categories c ON e.kategori_id = c.id
             WHERE e.id = ?"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id
        );
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function isEventSaved($userId, $eventId)
    {
        $stmt = $this->conn->prepare("
            SELECT 1
            FROM saved_events
            WHERE user_id = ? AND event_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function saveEvent($userId, $eventId)
    {
        if ($this->isEventSaved($userId, $eventId)) {
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO saved_events (user_id, event_id)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $userId, $eventId);

        return $stmt->execute();
    }

    public function getTicketsByUser($userId, $status = '')
    {
        $whereStatus = '';

        if ($status == 'selesai') {
            $whereStatus = " AND e.tanggal < CURDATE() ";
        } elseif ($status == 'mendatang') {
            $whereStatus = " AND e.tanggal >= CURDATE() ";
        }

        $sql = "
            SELECT
                b.kode_booking,
                b.status AS status_booking,
                b.event_id,
                e.judul_event,
                e.tanggal,
                e.waktu,
                e.lokasi,
                e.penyelenggara,
                e.poster,
                c.nama_kategori
            FROM bookings b
            JOIN events e ON b.event_id = e.id
            LEFT JOIN categories c ON e.kategori_id = c.id
            WHERE b.user_id = ?
            $whereStatus
            ORDER BY e.tanggal ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTicketByKode($kodeBooking)
    {
        $sql = "
            SELECT
                b.kode_booking,
                b.status AS status_booking,
                b.event_id,
                e.judul_event,
                e.deskripsi,
                e.poster,
                e.tanggal,
                e.tanggal_selesai,
                e.waktu,
                e.lokasi,
                e.penyelenggara,
                e.harga,
                c.id AS kategori_id,
                c.nama_kategori
            FROM bookings b
            INNER JOIN events e ON b.event_id = e.id
            LEFT JOIN categories c ON e.kategori_id = c.id
            WHERE b.kode_booking = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $kodeBooking);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getEvents($search = '', $catId = '', $isFree = false)
    {
        $sql = "
            SELECT e.*, c.nama_kategori
            FROM events e
            LEFT JOIN categories c ON e.kategori_id = c.id
            WHERE e.status = 'approved'
        ";

        $params = [];
        $types = "";

        if (!empty($search)) {
            $sql .= " AND (e.judul_event LIKE ? OR e.penyelenggara LIKE ?)";
            $keyword = "%" . $search . "%";
            $params[] = $keyword;
            $params[] = $keyword;
            $types .= "ss";
        }

        if (!empty($catId)) {
            $sql .= " AND e.kategori_id = ? ";
            $params[] = (int)$catId;
            $types .= "i";
        }

        if ($isFree) {
            $sql .= " AND e.harga = 0 ";
        }

        $sql .= " ORDER BY e.tanggal DESC";

        $stmt = $this->conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateProfile(
        $userId,
        $nama,
        $email,
        $programStudi,
        $wa,
        $semester
    )
    {
        $stmt = $this->conn->prepare(
            "UPDATE users
             SET nama_lengkap=?,
                 email=?,
                 program_studi=?,
                 no_whatsapp=?,
                 semester=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "sssssi",
            $nama,
            $email,
            $programStudi,
            $wa,
            $semester,
            $userId
        );

        return $stmt->execute();
    }

    public function updatePassword(
        $userId,
        $hashedPassword
    )
    {
        $stmt = $this->conn->prepare(
            "UPDATE users
             SET password=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "si",
            $hashedPassword,
            $userId
        );

        return $stmt->execute();
    }

    public function getSavedEvents($userId)
    {
        $stmt = $this->conn->prepare(
            "SELECT e.*, s.id as saved_id
             FROM saved_events s
             JOIN events e ON s.event_id = e.id
             WHERE s.user_id = ?
             ORDER BY e.tanggal DESC"
        );

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function removeSavedEvent($userId, $eventId)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM saved_events
             WHERE user_id = ?
             AND event_id = ?"
        );

        $stmt->bind_param(
            "ii",
            $userId,
            $eventId
        );

        return $stmt->execute();
    }

    public function createBooking(
        $eventId,
        $userId,
        $kodeBooking,
        $metode,
        $buktiTransfer
    )
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO bookings
            (
                event_id,
                user_id,
                kode_booking,
                metode_pembayaran,
                bukti_transfer,
                status,
                created_at
            )
            VALUES
            (
                ?, ?, ?, ?, ?, 'active', NOW()
            )"
        );

        $stmt->bind_param(
            "iisss",
            $eventId,
            $userId,
            $kodeBooking,
            $metode,
            $buktiTransfer
        );

        return $stmt->execute();
    }

    public function isAlreadyRegistered($userId, $eventId)
    {
        $stmt = $this->conn->prepare(
            "SELECT id
             FROM bookings
             WHERE user_id = ?
             AND event_id = ?"
        );

        $stmt->bind_param(
            "ii",
            $userId,
            $eventId
        );

        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
}