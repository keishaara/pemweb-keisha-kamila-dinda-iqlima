<?php

require_once __DIR__ . '/../config/koneksi.php';

class AdminModel {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Ambil semua user
    public function getAllUsers() {

        $query = mysqli_query(
            $this->conn,
            "SELECT * FROM users"
        );

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

    // Hitung total user
    public function countUsers() {

        $query = mysqli_query(
            $this->conn,
            "SELECT COUNT(*) as total FROM users"
        );

        return mysqli_fetch_assoc($query);
    }

    // Hitung total organisasi
    public function countOrganisasi() {

        $query = mysqli_query(
            $this->conn,
            "SELECT COUNT(*) as total 
             FROM users 
             WHERE tipe_akun='organisasi'"
        );

        return mysqli_fetch_assoc($query);
    }

    // Ambil user terbaru
    public function getLatestUsers() {

        $query = mysqli_query(
            $this->conn,
            "SELECT * FROM users
             ORDER BY id DESC
             LIMIT 4"
        );

        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

     // Ambil data verifikasi acara
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

     // Ambil semua kategori
     public function getKategori() {

          $query = mysqli_query(
               $this->conn,
               "SELECT * FROM categories"
          );

          return mysqli_fetch_all($query, MYSQLI_ASSOC);
     }
}
?>