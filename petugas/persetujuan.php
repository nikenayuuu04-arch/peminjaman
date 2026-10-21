<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'petugas') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['acc'])) {
    $id = $_GET['acc'];
    $status = $_GET['status'];
    $conn->query("UPDATE peminjaman SET status='$status' WHERE id_peminjaman='$id'");
    addLog($conn, $_SESSION['user']['id_user'], "Petugas mengubah status peminjaman #$id menjadi $status");
    header("Location: persetujuan.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Petugas - Persetujuan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="bg-primary text-white p-3 vh-100 position-fixed" style="width: 240px;">
        <h5 class="fw-bold text-white mb-4"><i class="bi bi-headset me-2"></i> Petugas Area</h5>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link text-white"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="persetujuan.php" class="nav-link active bg-white text-primary fw-bold"><i class="bi bi-check2-square me-2"></i> Persetujuan Pinjam</a></li>
            <li class="nav-item mb-1"><a href="pengembalian.php" class="nav-link text-white"><i class="bi bi-arrow-counterclockwise me-2"></i> Pengembalian</a></li>
            <li class="nav-item mb-1"><a href="laporan.php" class="nav-link text-white"><i class="bi bi-printer me-2"></i> Cetak Laporan</a></li>
        </ul>
        <div class="pt-5 mt-5">
            <a href="../logout.php" class="btn btn-outline-light w-100"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
        </div>
    </div>

    <div class="p-4 w-100" style="margin-left: 240px;">
        <h3 class="fw-bold text-primary mb-4">Persetujuan Peminjaman</h3>
        <div class="card border-0 shadow-sm p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Peminjam</th>
                        <th>Alat Bayi</th>
                        <th>Durasi</th>
                        <th>Estimasi Sewa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT p.*, u.nama_lengkap, a.nama_alat, a.harga_sewa FROM peminjaman p JOIN users u ON p.id_user=u.id_user JOIN alat a ON p.id_alat=a.id_alat ORDER BY p.id_peminjaman DESC");
                    while($r = $res->fetch_assoc()):
                        $tgl1 = new DateTime($r['tgl_pinjam']);
                        $tgl2 = new DateTime($r['tgl_kembali_rencana']);
                        $durasi = $tgl1->diff($tgl2)->days ?: 1;
                        $total_sewa = $durasi * $r['harga_sewa'];
                    ?>
                    <tr>
                        <td>#<?= $r['id_peminjaman'] ?></td>
                        <td class="fw-bold"><?= $r['nama_lengkap'] ?></td>
                        <td><?= $r['nama_alat'] ?></td>
                        <td><?= $durasi ?> Hari</td>
                        <td class="fw-bold text-primary">Rp <?= number_format($total_sewa) ?></td>
                        <td><span class="badge bg-<?= $r['status']=='Menunggu'?'warning':($r['status']=='Dipinjam'?'primary':'danger') ?>"><?= $r['status'] ?></span></td>
                        <td>
                            <?php if($r['status'] == 'Menunggu'): ?>
                                <a href="persetujuan.php?acc=<?= $r['id_peminjaman'] ?>&status=Dipinjam" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Setujui</a>
                                <a href="persetujuan.php?acc=<?= $r['id_peminjaman'] ?>&status=Ditolak" class="btn btn-danger btn-sm"><i class="bi bi-x-lg"></i> Tolak</a>
                            <?php else: ?> - <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>