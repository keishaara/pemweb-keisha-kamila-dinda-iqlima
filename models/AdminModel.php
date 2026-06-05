<?php
require_once __DIR__ . '/../config/koneksi.php';

class AdminModel {

    private $conn;

    public function __construct() {
        global $conn;
        if (!isset($conn)) {
            require_once __DIR__ . '/../config/koneksi.php';
        }
        
        $this->conn = $conn;
    }

    public function hasUserStatusColumn() {
        $result = mysqli_query($this->conn, "SHOW COLUMNS FROM users LIKE 'status'");
        return $result && mysqli_num_rows($result) > 0;
    }

    public function getAllUsers($keyword = '', $role = '', $status = '') {
        $conditions = [];
        
        if (!empty($keyword)) {
            $keyword_safe = mysqli_real_escape_string($this->conn, $keyword);
            $conditions[] = "(nama_lengkap LIKE '%$keyword_safe%' OR email LIKE '%$keyword_safe%')";
        }
        
        if (!empty($role)) {
            $role_safe = mysqli_real_escape_string($this->conn, $role);
            $conditions[] = "tipe_akun = '$role_safe'";
        }
        
        if ($this->hasUserStatusColumn() && !empty($status)) {
            $status_safe = mysqli_real_escape_string($this->conn, $status);
            if ($status_safe === 'Aktif') {
                $conditions[] = "(status = 'Aktif' OR status IS NULL OR status = '')";
            } else {
                $conditions[] = "status = '$status_safe'";
            }
        }
        
        $whereClause = "";
        if (count($conditions) > 0) {
            $whereClause = "WHERE " . implode(" AND ", $conditions);
        }
        
        $query = mysqli_query(
            $this->conn,
            "SELECT * FROM users $whereClause ORDER BY id DESC"
        );
        
        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

    public function countUsers() {
        $query = mysqli_query(
            $this->conn, "SELECT COUNT(*) as total FROM users"
        );
        return mysqli_fetch_assoc($query);
    }

    public function countOrganisasi() {
        $query = mysqli_query(
            $this->conn, 
            "SELECT COUNT(*) as total FROM users WHERE tipe_akun='organisasi'"
        );
        return mysqli_fetch_assoc($query);
    }

    public function countMahasiswa() {
        $query = mysqli_query(
            $this->conn, 
            "SELECT COUNT(*) as total FROM users WHERE tipe_akun='mahasiswa'"
        );
        return mysqli_fetch_assoc($query);
    }

    public function getLatestUsers() {
        $query = mysqli_query(
            $this->conn,
            "SELECT * FROM users
             ORDER BY id DESC
             LIMIT 4"
        );
        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    
    public function getVerifikasiAcara() {
        $query = mysqli_query(
            $this->conn,
            "SELECT 
                events.*,
                categories.nama_kategori
            FROM events
            LEFT JOIN categories ON events.kategori_id = categories.id
            WHERE events.status = 'pending'
            ORDER BY events.created_at DESC"
        );

        if (!$query) {
            return [];
        }

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    
    
    public function getLatestEvents() {
        $query = mysqli_query(
            $this->conn,
            "SELECT events.*, categories.nama_kategori 
            FROM events 
            LEFT JOIN categories ON events.kategori_id = categories.id 
            ORDER BY events.created_at DESC 
            LIMIT 5"
        );
        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    
    public function getKategori() {
        $query = mysqli_query($this->conn, "SELECT * FROM categories ORDER BY nama_kategori ASC");
        
        if (!$query) {
            return [];
        }

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

    public function insertKategori($nama, $deskripsi) {
        $nama = mysqli_real_escape_string($this->conn, $nama);
        $deskripsi = mysqli_real_escape_string($this->conn, $deskripsi);
        return mysqli_query($this->conn, "INSERT INTO categories (nama_kategori, deskripsi) VALUES ('$nama', '$deskripsi')");
    }

    public function getCategoryById($id) {
        $id = intval($id);
        $query = mysqli_query($this->conn, "SELECT * FROM categories WHERE id = '$id'");
        return mysqli_fetch_assoc($query);
    }

    public function updateKategori($id, $nama, $deskripsi) {
        $id = intval($id);
        $nama = mysqli_real_escape_string($this->conn, $nama);
        $deskripsi = mysqli_real_escape_string($this->conn, $deskripsi);
        return mysqli_query($this->conn, "UPDATE categories SET nama_kategori = '$nama', deskripsi = '$deskripsi' WHERE id = '$id'");
    }

    public function deleteKategori($id) {
        $id = intval($id);
        mysqli_query($this->conn, "UPDATE events SET kategori_id = NULL WHERE kategori_id = '$id'");
        return mysqli_query($this->conn, "DELETE FROM categories WHERE id = '$id'");
    }

    public function toggleUserStatus($id, $currentStatus) {
        if (!$this->hasUserStatusColumn()) {
            return false; 
        }

        $id = intval($id);
        $statusClean = trim($currentStatus);
        $newStatus = ($statusClean === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $newStatus = mysqli_real_escape_string($this->conn, $newStatus);

        return mysqli_query($this->conn, "UPDATE users SET status = '$newStatus' WHERE id = '$id'");
    }

    
    public function getAllEvents() {
        $query = mysqli_query(
            $this->conn,
            "SELECT events.*, categories.nama_kategori 
            FROM events 
            LEFT JOIN categories ON events.kategori_id = categories.id 
            ORDER BY events.created_at DESC"
        );
        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
    public function login($identifier, $role) {
        $identifier = mysqli_real_escape_string($this->conn, $identifier);
        $role = mysqli_real_escape_string($this->conn, $role);

        $query = "SELECT id, nama_lengkap, email, npm, password, tipe_akun, status 
                  FROM users 
                  WHERE (email = '$identifier' OR npm = '$identifier') 
                  AND tipe_akun = '$role'";
                  
        return mysqli_query($this->conn, $query);
    }
    public function updateStatusEvent($id, $status) {
        $id = intval($id);
        $status = mysqli_real_escape_string($this->conn, $status);
        return mysqli_query($this->conn, "UPDATE events SET status = '$status' WHERE id = '$id'");
    }
} 
?>