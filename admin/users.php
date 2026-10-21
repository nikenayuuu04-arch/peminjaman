<?php
require_once '../config/koneksi.php';
cek_role('admin');

if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $role     = $_POST['role'];

    mysqli_query($koneksi, "INSERT INTO users (username, password, nama_lengkap, role) VALUES ('$username', '$password', '$nama', '$role')");
    catat_log($koneksi, $_SESSION['user_id'], "Menambahkan user: " . $username);
    header("Location: users.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");
    catat_log($koneksi, $_SESSION['user_id'], "Menghapus user ID: " . $id);
    header("Location: users.php");
    exit();
}

$users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola User - Admin</title>
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
            <li class="nav-item mb-1"><a href="alat.php" class="nav-link text-dark"><i class="fa-solid fa-boxes-stacked me-2"></i> Data Alat</a></li>
            <li class="nav-item mb-1"><a href="users.php" class="nav-link active bg-danger text-white"><i class="fa-solid fa-users me-2"></i> Kelola User</a></li>
            <li class="nav-item mb-1"><a href="peminjaman.php" class="nav-link text-dark"><i class="fa-solid fa-receipt me-2"></i> Peminjaman</a></li>
            <li class="nav-item mb-1"><a href="log.php" class="nav-link text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i> Log Aktivitas</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Kelola User</h2>
            <button class="btn btn-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalUser"><i class="fa-solid fa-plus me-1"></i> Tambah User</button>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($u = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td class="px-3 fw-bold"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($u['username']); ?></td>
                            <td><?= htmlspecialchars($u['nama_lengkap']); ?></td>
                            <td><span class="badge bg-info text-dark"><?= ucfirst($u['role']); ?></span></td>
                            <td>
                                <a href="users.php?hapus=<?= $u['id']; ?>" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus user ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUser" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah User Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="peminjam">Peminjam</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah_user" class="btn btn-danger">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>