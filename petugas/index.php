<?php
require_once '../config/koneksi.php';
cek_role('petugas');

if (isset($_GET['aksi']) && $_GET['aksi'] == 'setujui') {
    $id_pinjam = (int)$_GET['id'];
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id='$id_pinjam'"));
    $alat_id = $data['alat_id'];
    $jumlah  = $data['jumlah'];

    mysqli_query($koneksi, "UPDATE peminjaman SET status='disetujui' WHERE id='$id_pinjam'");
    mysqli_query($koneksi, "UPDATE alat SET stok = stok - $jumlah WHERE id='$alat_id'");
    
    catat_log($koneksi, $_SESSION['user_id'], "Petugas menyetujui peminjaman ID: " . $id_pinjam);
    header("Location: index.php");
    exit();
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'kembali') {
    $id_pinjam = (int)$_GET['id'];
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id='$id_pinjam'"));
    $alat_id     = $data['alat_id'];
    $jumlah      = $data['jumlah'];
    $tgl_real    = date('Y-m-d');
    $tgl_rencana = $data['tgl_kembali_rencana'];
    
    $denda = 0;
    if (strtotime($tgl_real) > strtotime($tgl_rencana)) {
        $selisih_hari = (strtotime($tgl_real) - strtotime($tgl_rencana)) / (60 * 60 * 24);
        $denda = $selisih_hari * 5000;
    }

    mysqli_query($koneksi, "UPDATE peminjaman SET status='dikembalikan', tgl_kembali_real='$tgl_real', denda='$denda' WHERE id='$id_pinjam'");
    mysqli_query($koneksi, "UPDATE alat SET stok = stok + $jumlah WHERE id='$alat_id'");
    
    catat_log($koneksi, $_SESSION['user_id'], "Petugas memproses pengembalian ID: " . $id_pinjam);
    header("Location: index.php");
    exit();
}

$peminjaman = mysqli_query($koneksi, "SELECT peminjaman.*, alat.nama_alat, users.nama_lengkap 
                                     FROM peminjaman 
                                     JOIN alat ON peminjaman.alat_id = alat.id 
                                     JOIN users ON peminjaman.user_id = users.id 
                                     ORDER BY peminjaman.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="d-flex flex-column p-3 bg-white border-end" style="width: 250px; min-height: 100vh;">
        <a href="index.php" class="d-flex align-items-center mb-4 text-decoration-none text-primary fw-bold fs-5">
            <i class="fa-solid fa-clipboard-check me-2"></i> Petugas Panel
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link active bg-primary text-white"><i class="fa-solid fa-house me-2"></i> Persetujuan</a></li>
            <li class="nav-item mb-1"><a href="laporan.php" class="nav-link text-dark"><i class="fa-solid fa-file-invoice me-2"></i> Laporan</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <h2 class="fw-bold mb-4">Persetujuan & Monitoring</h2>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Rencana Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($peminjaman)): ?>
                        <tr>
                            <td class="px-3 fw-bold"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td><?= htmlspecialchars($row['nama_alat']); ?></td>
                            <td><?= $row['tgl_kembali_rencana']; ?></td>
                            <td><span class="badge bg-info text-dark"><?= ucfirst($row['status']); ?></span></td>
                            <td>Rp <?= number_format($row['denda'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($row['status'] == 'menunggu'): ?>
                                    <a href="index.php?aksi=setujui&id=<?= $row['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman?')">Setujui</a>
                                <?php elseif ($row['status'] == 'disetujui'): ?>
                                    <a href="index.php?aksi=kembali&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm text-dark" onclick="return confirm('Proses pengembalian alat?')">Terima Pengembalian</a>
                                <?php else: ?>
                                    <span class="text-muted">Selesai</span>
                                <?php endif; ?>
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