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
            "SELECT *
             FROM events
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
}