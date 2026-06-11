<?php
require_once __DIR__ . '/../config/koneksi.php';

class PublicController {
    
    public function action_index() {
        require_once __DIR__ . '/../view/public/index.php';
    }

    public function action_fitur() {
        require_once __DIR__ . '/MahasiswaController.php';
        $mhsController = new MahasiswaController();
        $events = $mhsController->indexFeatures();
        require_once __DIR__ . '/../view/public/fitur.php';
    }

    public function action_tentang() {
        require_once __DIR__ . '/../view/public/tentang.php';
    }

    public function action_kegiatan() {
        global $conn;
        
        $search  = isset($_GET['q']) ? trim($_GET['q']) : '';
        $cat_id  = isset($_GET['cat_id']) ? trim($_GET['cat_id']) : '';
        $is_free = isset($_GET['free']) ? true : false;
        
        $sql = "SELECT e.*, c.nama_kategori 
                FROM events e 
                LEFT JOIN categories c ON e.kategori_id = c.id 
                WHERE e.status = 'approved'";
        
        if ($search) {
            $search_safe = mysqli_real_escape_string($conn, $search);
            $sql .= " AND (e.judul_event LIKE '%$search_safe%' OR e.penyelenggara LIKE '%$search_safe%')";
        }
        
        if ($cat_id) {
            $cat_id_safe = mysqli_real_escape_string($conn, $cat_id);
            $sql .= " AND e.kategori_id = '$cat_id_safe'";
        }
        
        if ($is_free) {
            $sql .= " AND e.harga = 0";
        }
        
        $sql .= " ORDER BY e.tanggal DESC";
        
        $res    = mysqli_query($conn, $sql);
        $events = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        require_once __DIR__ . '/../view/public/kegiatan.php';
    }
}
