<?php
require_once '../config/koneksi.php';
cek_role('peminjam');

$user_id = $_SESSION['user_id'];
$riwayat = mysqli_query($koneksi, "SELECT peminjaman.*, alat.nama_alat 
                                  FROM peminjaman 
                                  JOIN alat ON peminjaman.alat_id = alat.id 
                                  WHERE peminjaman.user_id = '$user_id' 
                                  ORDER BY peminjaman.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Peminjaman</title>
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
            <li class="nav-item mb-1"><a href="alat.php" class="nav-link text-dark"><i class="fa-solid fa-list me-2"></i> Daftar Tabel Alat</a></li>
            <li class="nav-item mb-1"><a href="pinjam.php" class="nav-link text-dark"><i class="fa-solid fa-hand-holding me-2"></i> Ajukan Pinjam</a></li>
            <li class="nav-item mb-1"><a href="riwayat.php" class="nav-link active bg-primary text-white"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Pinjam</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <h2 class="fw-bold mb-4">Riwayat Peminjaman Anda</h2>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Tgl Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th>Tgl Kembali Real</th>
                            <th>Status</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($r = mysqli_fetch_assoc($riwayat)): ?>
                        <tr>
                            <td class="px-3 fw-bold"><?= $no++; ?></td>
                            <td><strong><?= htmlspecialchars($r['nama_alat']); ?></strong></td>
                            <td><?= $r['jumlah']; ?></td>
                            <td><?= $r['tgl_pinjam']; ?></td>
                            <td><?= $r['tgl_kembali_rencana']; ?></td>
                            <td><?= $r['tgl_kembali_real'] ?? '-'; ?></td>
                            <td>
                                <?php 
                                    $st = $r['status'];
                                    if($st=='menunggu') echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                    elseif($st=='disetujui') echo '<span class="badge bg-primary">Disetujui</span>';
                                    elseif($st=='dikembalikan') echo '<span class="badge bg-success">Dikembalikan</span>';
                                    else echo '<span class="badge bg-danger">Ditolak</span>';
                                ?>
                            </td>
                            <td>Rp <?= number_format($r['denda'], 0, ',', '.'); ?></td>
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