<?php
require_once '../config/koneksi.php';
cek_role('admin');

$total_pinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman"))['total'];
$total_alat   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM alat"))['total'];
$total_user   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex">
    <!-- Sidebar Langsung di File -->
    <div class="d-flex flex-column p-3 bg-white border-end" style="width: 250px; min-height: 100vh;">
        <a href="index.php" class="d-flex align-items-center mb-4 text-decoration-none text-danger fw-bold fs-5">
            <i class="fa-solid fa-heart-pulse me-2"></i> ToolAdmin
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link active bg-danger text-white"><i class="fa-solid fa-house me-2"></i> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="alat.php" class="nav-link text-dark"><i class="fa-solid fa-boxes-stacked me-2"></i> Data Alat</a></li>
            <li class="nav-item mb-1"><a href="users.php" class="nav-link text-dark"><i class="fa-solid fa-users me-2"></i> Kelola User</a></li>
            <li class="nav-item mb-1"><a href="peminjaman.php" class="nav-link text-dark"><i class="fa-solid fa-receipt me-2"></i> Peminjaman</a></li>
            <li class="nav-item mb-1"><a href="log.php" class="nav-link text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Log Aktivitas</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="p-4 flex-grow-1">
        <h2 class="fw-bold mb-4">Dashboard Admin</h2>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-danger text-white border-0 shadow-sm p-3 rounded-3">
                    <p class="mb-1 fw-semibold">Total Transaksi</p>
                    <h1 class="fw-bold mb-0"><?= $total_pinjam; ?></h1>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark border-0 shadow-sm p-3 rounded-3">
                    <p class="mb-1 fw-semibold">Total Produk / Alat</p>
                    <h1 class="fw-bold mb-0"><?= $total_alat; ?></h1>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white border-0 shadow-sm p-3 rounded-3">
                    <p class="mb-1 fw-semibold">Total Pengguna</p>
                    <h1 class="fw-bold mb-0"><?= $total_user; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>