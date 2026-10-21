<?php
require_once '../config/koneksi.php';
cek_role('petugas');

$laporan = mysqli_query($koneksi, "SELECT peminjaman.*, alat.nama_alat, users.nama_lengkap 
                                  FROM peminjaman 
                                  JOIN alat ON peminjaman.alat_id = alat.id 
                                  JOIN users ON peminjaman.user_id = users.id 
                                  ORDER BY peminjaman.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Laporan - Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <a href="index.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fa-solid fa-print me-1"></i> Cetak Laporan</button>
    </div>

    <div class="text-center mb-4">
        <h3 class="fw-bold">LAPORAN PEMINJAMAN ALAT</h3>
        <p class="text-muted">Sistem Informasi Peminjaman Alat UKK RPL</p>
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali Real</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($laporan)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($r['nama_lengkap']); ?></td>
                <td><?= htmlspecialchars($r['nama_alat']); ?></td>
                <td><?= $r['jumlah']; ?></td>
                <td><?= $r['tgl_pinjam']; ?></td>
                <td><?= $r['tgl_kembali_real'] ?? '-'; ?></td>
                <td><?= ucfirst($r['status']); ?></td>
                <td>Rp <?= number_format($r['denda'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>