<?php
require_once 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    catat_log($koneksi, $_SESSION['user_id'], "User " . $_SESSION['username'] . " logout.");
}

session_unset();
session_destroy();

header("Location: login.php");
exit();
?>