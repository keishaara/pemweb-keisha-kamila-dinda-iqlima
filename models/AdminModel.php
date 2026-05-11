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
            $this->conn,
            "SELECT COUNT(*) as total FROM users"
        );

        return mysqli_fetch_assoc($query);
    }

    public function countOrganisasi() {
        $query = mysqli_query(
            $this->conn,
            "SELECT COUNT(*) as total 
             FROM users 
             WHERE tipe_akun='organisasi'"
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
               JOIN categories
                    ON events.kategori_id = categories.id
               ORDER BY events.created_at DESC"
          );

          return mysqli_fetch_all($query, MYSQLI_ASSOC);
     }

     public function getKategori() {
          $query = mysqli_query(
               $this->conn,
               "SELECT * FROM categories"
          );

          return mysqli_fetch_all($query, MYSQLI_ASSOC);
     }
}
?>