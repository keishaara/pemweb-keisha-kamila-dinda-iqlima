<?php
session_start();
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {

    private $model;

    public function __construct() {
        $this->model = new MahasiswaModel();
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] == 'admin') {
                header("Location:index.php?page=dashboard");
            } elseif ($_SESSION['role'] == 'mahasiswa') {
                header("Location:index.php?page=profil");
            } else {
                header("Location:index.php?page=org_dashboard");
            }
            exit;
        }
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $identifier = trim($_POST['identifier']);
            $password = $_POST['password'];
            $role = $_POST['role'];
            
            if (
                !empty($identifier) &&
                !empty($password) &&
                !empty($role)
            ) {

                $result = $this->model->login(
                    $identifier,
                    $role
                );
                
                if ($user = mysqli_fetch_assoc($result)) {
                    if (
                        password_verify(
                            $password,
                            $user['password']
                        )
                    ) {
                        if (($user['status'] ?? 'Aktif') === 'Nonaktif') {
                            $error = "Akun Anda telah dinonaktifkan oleh admin.";
                        } else {
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                            $_SESSION['role'] = $user['tipe_akun'];

                            if (
                                $user['tipe_akun']
                                == 'admin'
                            ) {
                                header(
                                    "Location:index.php?page=dashboard"
                                );
                            } elseif (
                                $user['tipe_akun']
                                == 'mahasiswa'
                            ) {
                                header(
                                    "Location:index.php?page=profil"
                                );
                            } else {
                                header(
                                    "Location:index.php?page=org_dashboard"
                                );
                            }
                            exit;
                        }

                    } else {
                        $error = "Kata sandi salah.";
                    }

                } else {
                    $error = "Akun tidak ditemukan atau role tidak sesuai.";
                }

            } else {
                $error = "Semua field wajib diisi.";
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location:index.php?page=login");
        exit;
    }   
} 
?>