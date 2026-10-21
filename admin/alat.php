<?php
require_once '../config/koneksi.php';
cek_role('admin');

if (isset($_POST['tambah_alat'])) {
    $nama_alat   = mysqli_real_escape_string($koneksi, $_POST['nama_alat']);
    $kategori_id = (int)$_POST['kategori_id'];
    $stok        = (int)$_POST['stok'];
    $kondisi     = mysqli_real_escape_string($koneksi, $_POST['kondisi']);

    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_baru = 'default.jpg';

    if (!empty($foto_nama)) {
        $ext = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $foto_baru = time() . '_' . rand(100, 999) . '.' . $ext;
        move_uploaded_file($foto_tmp, "../assets/img/" . $foto_baru);
    }

    mysqli_query($koneksi, "INSERT INTO alat (kategori_id, nama_alat, foto, stok, kondisi) VALUES ('$kategori_id', '$nama_alat', '$foto_baru', '$stok', '$kondisi')");
    catat_log($koneksi, $_SESSION['user_id'], "Menambahkan alat: " . $nama_alat);
    header("Location: alat.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto FROM alat WHERE id='$id'"));
    if ($data['foto'] != 'default.jpg' && file_exists("../assets/img/" . $data['foto'])) {
        unlink("../assets/img/" . $data['foto']);
    }
    mysqli_query($koneksi, "DELETE FROM alat WHERE id='$id'");
    catat_log($koneksi, $_SESSION['user_id'], "Menghapus alat ID: " . $id);
    header("Location: alat.php");
    exit();
}

$alat = mysqli_query($koneksi, "SELECT alat.*, kategori.nama_kategori FROM alat JOIN kategori ON alat.kategori_id = kategori.id ORDER BY alat.id DESC");
$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Alat - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="d-flex">
    <div class="d-flex flex-column p-3 bg-white border-end" style="width: 250px; min-height: 100vh;">
        <a href="index.php" class="d-flex align-items-center mb-4 text-decoration-none text-danger fw-bold fs-5">
            <i class="fa-solid fa-heart-pulse me-2"></i> ToolAdmin
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-1"><a href="index.php" class="nav-link text-dark"><i class="fa-solid fa-house me-2"></i> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="alat.php" class="nav-link active bg-danger text-white"><i class="fa-solid fa-boxes-stacked me-2"></i> Data Alat</a></li>
            <li class="nav-item mb-1"><a href="users.php" class="nav-link text-dark"><i class="fa-solid fa-users me-2"></i> Kelola User</a></li>
            <li class="nav-item mb-1"><a href="peminjaman.php" class="nav-link text-dark"><i class="fa-solid fa-receipt me-2"></i> Peminjaman</a></li>
            <li class="nav-item mb-1"><a href="log.php" class="nav-link text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Log Aktivitas</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Data Produk / Alat</h2>
            <button class="btn btn-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fa-solid fa-plus me-1"></i> Tambah Produk</button>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($alat)): ?>
                        <tr>
                            <td class="px-3 fw-bold"><?= $no++; ?></td>
                            <td>
                                <img src="../assets/img/<?= htmlspecialchars($row['foto']); ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded border">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($row['nama_alat']); ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                            <td><?= $row['stok']; ?></td>
                            <td>
                                <a href="alat.php?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus alat ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah Produk / Alat Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nama Alat</label>
            <input type="text" name="nama_alat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
                <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                    <option value="<?= $k['id']; ?>"><?= htmlspecialchars($k['nama_kategori']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Alat</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kondisi</label>
            <input type="text" name="kondisi" class="form-control" value="Baik" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah_alat" class="btn btn-danger">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>