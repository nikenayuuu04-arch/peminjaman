<?php
require_once '../config/koneksi.php';
cek_role('peminjam');

$alat_list = mysqli_query($koneksi, "SELECT alat.*, kategori.nama_kategori FROM alat JOIN kategori ON alat.kategori_id = kategori.id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Tabel Alat</title>
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
            <li class="nav-item mb-1"><a href="index.php" class="nav-link text-dark"><i class="fa-solid fa-border-all me-2"></i> Katalog Alat</a></li>
            <li class="nav-item mb-1"><a href="alat.php" class="nav-link active bg-primary text-white"><i class="fa-solid fa-list me-2"></i> Daftar Tabel Alat</a></li>
            <li class="nav-item mb-1"><a href="pinjam.php" class="nav-link text-dark"><i class="fa-solid fa-hand-holding me-2"></i> Ajukan Pinjam</a></li>
            <li class="nav-item mb-1"><a href="riwayat.php" class="nav-link text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Pinjam</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <h2 class="fw-bold mb-4">Tabel Daftar Alat</h2>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Foto</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($a = mysqli_fetch_assoc($alat_list)): ?>
                        <tr>
                            <td class="px-3 fw-bold"><?= $no++; ?></td>
                            <td><img src="../assets/img/<?= htmlspecialchars($a['foto']); ?>" style="width: 45px; height: 45px; object-fit: cover;" class="rounded border"></td>
                            <td class="fw-bold"><?= htmlspecialchars($a['nama_alat']); ?></td>
                            <td><?= htmlspecialchars($a['nama_kategori']); ?></td>
                            <td><?= $a['stok']; ?></td>
                            <td><span class="badge bg-success"><?= htmlspecialchars($a['kondisi']); ?></span></td>
                            <td>
                                <a href="pinjam.php?alat_id=<?= $a['id']; ?>" class="btn btn-primary btn-sm">Pinjam</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>