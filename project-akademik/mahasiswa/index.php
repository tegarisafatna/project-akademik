<?php
require_once '../config/koneksi.php';
require_once '../config/auth.php';

$role = $_SESSION['role'];

// ambil data
$result = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc;
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📚 Data Mahasiswa</h3>

        <div>
            <a href="../dashboard/index.php" class="btn btn-secondary btn-sm">
                ← Dashboard
            </a>

            <?php if ($role === 'admin'): ?>
            <a href="tambah.php" class="btn btn-primary btn-sm">
                + Tambah
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <?php if ($role === 'admin'): ?>
                        <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                <?php $no = 1; ?>
                <?php while ($d = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($d['nama']); ?></td>
                        <td><?= htmlspecialchars($d['nim']); ?></td>
                        <td><?= htmlspecialchars($d['jurusan']); ?></td>

                        <?php if ($role === 'admin'): ?>
                        <td>
                            <a href="edit.php?id=<?= $d['id']; ?>" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <a href="hapus.php?id=<?= $d['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin hapus data?')">
                                Hapus
                            </a>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>
