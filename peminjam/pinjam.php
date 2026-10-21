<?php
require_once '../config/koneksi.php';
cek_role('peminjam');

$user_id = $_SESSION['user_id'];
$selected_alat = isset($_GET['alat_id']) ? (int)$_GET['alat_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alat_id     = (int)$_POST['alat_id'];
    $jumlah      = (int)$_POST['jumlah'];
    $tgl_pinjam  = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali_rencana'];

    mysqli_query($koneksi, "INSERT INTO peminjaman (user_id, alat_id, jumlah, tgl_pinjam, tgl_kembali_rencana, status) VALUES ('$user_id', '$alat_id', '$jumlah', '$tgl_pinjam', '$tgl_kembali', 'menunggu')");

    catat_log($koneksi, $user_id, "Mengajukan peminjaman alat ID: " . $alat_id);
    header("Location: riwayat.php");
    exit();
}

$alat_result = mysqli_query($koneksi, "SELECT * FROM alat WHERE stok > 0");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Form Pengajuan Pinjam</title>
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
            <li class="nav-item mb-1"><a href="pinjam.php" class="nav-link active bg-primary text-white"><i class="fa-solid fa-hand-holding me-2"></i> Ajukan Pinjam</a></li>
            <li class="nav-item mb-1"><a href="riwayat.php" class="nav-link text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Pinjam</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <div class="card border-0 shadow-sm p-4 mx-auto rounded-3" style="max-width: 600px;">
            <h4 class="fw-bold mb-4 text-primary">Form Pengajuan Peminjaman</h4>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Pilih Alat</label>
                    <select name="alat_id" class="form-select" required>
                        <?php while ($alt = mysqli_fetch_assoc($alat_result)): ?>
                            <option value="<?= $alt['id']; ?>" <?= ($alt['id'] == $selected_alat) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($alt['nama_alat']); ?> (Stok: <?= $alt['stok']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Pinjam</label>
                    <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Rencana Kembali</label>
                    <input type="date" name="tgl_kembali_rencana" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Kirim Pengajuan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>