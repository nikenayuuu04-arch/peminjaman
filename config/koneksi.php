<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_peminjaman_alat";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

function cek_role($role_dilewati) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
    if ($_SESSION['role'] != $role_dilewati) {
        header("Location: ../index.php");
        exit();
    }
}

function catat_log($koneksi, $user_id, $aktivitas) {
    $stmt = mysqli_prepare($koneksi, "INSERT INTO log_aktivitas (user_id, aktivitas) VALUES (?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $aktivitas);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}
?>