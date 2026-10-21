<?php
require_once 'config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'admin') {
    header("Location: admin/index.php");
} elseif ($_SESSION['role'] == 'petugas') {
    header("Location: petugas/index.php");
} else {
    header("Location: peminjam/index.php");
}
exit();
?>