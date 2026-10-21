<?php
require_once '../config/koneksi.php';
cek_role('admin');

$logs = mysqli_query($koneksi, "SELECT log_aktivitas.*, users.nama_lengkap 
                               FROM log_aktivitas 
                               LEFT JOIN users ON log_aktivitas.user_id = users.id 
                               ORDER BY log_aktivitas.id DESC LIMIT 100");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Log Aktivitas - Admin</title>
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
            <li class="nav-item mb-1"><a href="users.php" class="nav-link text-dark"><i class="fa-solid fa-users me-2"></i> Kelola User</a></li>
            <li class="nav-item mb-1"><a href="peminjaman.php" class="nav-link text-dark"><i class="fa-solid fa-receipt me-2"></i> Peminjaman</a></li>
            <li class="nav-item mb-1"><a href="log.php" class="nav-link active bg-danger text-white"><i class="fa-solid fa-clock-rotate-left me-2"></i> Log Aktivitas</a></li>
        </ul>
        <hr>
        <a href="../logout.php" class="btn btn-outline-danger w-100"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
    </div>

    <div class="p-4 flex-grow-1">
        <h2 class="fw-bold mb-4">Log Aktivitas</h2>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Waktu</th>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($l = mysqli_fetch_assoc($logs)): ?>
                        <tr>
                            <td class="px-3"><small class="text-muted"><?= $l['waktu']; ?></small></td>
                            <td><strong><?= htmlspecialchars($l['nama_lengkap'] ?? 'System'); ?></strong></td>
                            <td><?= htmlspecialchars($l['aktivitas']); ?></td>
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