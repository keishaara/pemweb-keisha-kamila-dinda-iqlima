<?php
require_once __DIR__ . '/../config/koneksi.php';

class AdminModel {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllUsers() {
        $query = mysqli_query(
            $this->conn,
            "SELECT * FROM users"
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
            WHERE events.status NOT IN ('approved', 'rejected') 
            ORDER BY events.created_at DESC"
        );

        if (!$query) {
            return [];
        }

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
        $id = intval($id);
        $newStatus = ($currentStatus === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $newStatus = mysqli_real_escape_string($this->conn, $newStatus);
        return mysqli_query($this->conn, "UPDATE users SET status = '$newStatus' WHERE id = '$id'");
    }
} 
?>