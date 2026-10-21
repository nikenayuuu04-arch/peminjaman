<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'petugas') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['proses_kembali'])) {
    $id_p = $_POST['id_peminjaman'];
    $tgl_real = $_POST['tgl_kembali_real'];
    
    $conn->query("CALL sp_proses_pengembalian('$id_p', '$tgl_real')");
    addLog($conn, $_SESSION['user']['id_user'], "Petugas memproses pengembalian peminjaman #$id_p");
    header("Location: pengembalian.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Petugas - Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="bg-primary text-white p-3 vh-100 position-fixed" style="width: 240px;">
        <h5 class="fw-bold text-white mb-4"><i class="bi bi-headset me-2"></i> Petugas Area</h5>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link text-white"><i class="bi bi-grid-fill me-2"></i> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="persetujuan.php" class="nav-link text-white"><i class="bi bi-check2-square me-2"></i> Persetujuan Pinjam</a></li>
            <li class="nav-item mb-1"><a href="pengembalian.php" class="nav-link active bg-white text-primary fw-bold"><i class="bi bi-arrow-counterclockwise me-2"></i> Pengembalian</a></li>
            <li class="nav-item mb-1"><a href="laporan.php" class="nav-link text-white"><i class="bi bi-printer me-2"></i> Cetak Laporan</a></li>
        </ul>
        <div class="pt-5 mt-5">
            <a href="../logout.php" class="btn btn-outline-light w-100"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
        </div>
    </div>

    <div class="p-4 w-100" style="margin-left: 240px;">
        <h3 class="fw-bold text-primary mb-4">Pengembalian Alat Bayi</h3>
        <div class="card border-0 shadow-sm p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>ID</th><th>Peminjam</th><th>Alat Bayi</th><th>Tgl Tenggat</th><th>Status</th><th>Denda</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT p.*, u.nama_lengkap, a.nama_alat FROM peminjaman p JOIN users u ON p.id_user=u.id_user JOIN alat a ON p.id_alat=a.id_alat WHERE p.status IN ('Dipinjam', 'Dikembalikan')");
                    while($r = $res->fetch_assoc()):
                    ?>
                    <tr>
                        <td>#<?= $r['id_peminjaman'] ?></td>
                        <td class="fw-bold"><?= $r['nama_lengkap'] ?></td>
                        <td><?= $r['nama_alat'] ?></td>
                        <td><?= $r['tgl_kembali_rencana'] ?></td>
                        <td><span class="badge bg-info text-dark"><?= $r['status'] ?></span></td>
                        <td class="text-danger fw-bold">Rp <?= number_format($r['denda']) ?></td>
                        <td>
                            <?php if($r['status'] == 'Dipinjam'): ?>
                                <form method="POST" class="d-flex gap-1">
                                    <input type="hidden" name="id_peminjaman" value="<?= $r['id_peminjaman'] ?>">
                                    <input type="date" name="tgl_kembali_real" value="<?= date('Y-m-d') ?>" class="form-control form-control-sm" required>
                                    <button type="submit" name="proses_kembali" class="btn btn-warning btn-sm text-nowrap">Proses</button>
                                </form>
                            <?php else: ?>
                                <span class="text-success small"><i class="bi bi-check-all"></i> Selesai</span>
                            <?php endif; ?>
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