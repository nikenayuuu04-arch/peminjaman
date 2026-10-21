<?php
require_once '../config/koneksi.php';
cek_role('peminjam');

$alat_list = mysqli_query($koneksi, "SELECT alat.*, kategori.nama_kategori FROM alat JOIN kategori ON alat.kategori_id = kategori.id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Katalog Alat - Peminjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="d-flex flex-column p-3 bg-white border-end" style="width: 250px; min-height: 100vh;">
        <a href="index.php" class="d-flex align-items-center mb-4 text-decoration-none text-primary fw-bold fs-5">
            <i class="fa-solid fa-toolbox me-2"></i> PinjamAlat
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link active bg-primary text-white"><i class="fa-solid fa-border-all me-2"></i> Katalog Alat</a></li>
            <li class="nav-item mb-1"><a href="alat.php" class="nav-link text-dark"><i class="fa-solid fa-list me-2"></i> Daftar Tabel Alat</a></li>
            <li class="nav-item mb-1"><a href="pinjam.php" class="nav-link text-dark"><i class="fa-solid fa-hand-holding me-2"></i> Ajukan Pinjam</a></li>
            <li class="nav-item mb-1"><a href="riwayat.php" class="nav-link text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Pinjam</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <div class="p-4 bg-primary text-white rounded-3 mb-4 shadow-sm">
            <h2 class="fw-bold">Sewa & Pinjam Alat Praktis</h2>
            <p class="mb-0">Pilih alat laboratorium/praktik yang Anda butuhkan secara langsung.</p>
        </div>

        <h4 class="fw-bold mb-3">Daftar Produk / Alat Tersedia</h4>
        <div class="row">
            <?php while ($alat = mysqli_fetch_assoc($alat_list)): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <img src="../assets/img/<?= htmlspecialchars($alat['foto']); ?>" class="card-img-top p-3" style="height: 180px; object-fit: contain;">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-info text-dark w-auto align-self-start mb-2"><?= htmlspecialchars($alat['nama_kategori']); ?></span>
                        <h6 class="fw-bold card-title"><?= htmlspecialchars($alat['nama_alat']); ?></h6>
                        <small class="text-muted mb-2">Kondisi: <?= htmlspecialchars($alat['kondisi']); ?></small>
                        <p class="card-text mt-auto fw-bold">Stok: <?= $alat['stok']; ?></p>
                        <a href="pinjam.php?alat_id=<?= $alat['id']; ?>" class="btn btn-primary btn-sm w-100 fw-bold">Pinjam Alat</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>