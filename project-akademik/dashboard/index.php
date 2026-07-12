<?php include '../templates/header.php'; ?>

<!-- SIDEBAR -->
<div class="col-md-3 col-lg-2 bg-light vh-100 p-3">
    <h5>Menu</h5>
    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="../dashboard/index.php" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <!-- ADMIN MENU -->
        <?php if ($role == 'admin'): ?>
        <li class="nav-item">
            <a href="../mahasiswa/index.php" class="nav-link">
                <i class="bi bi-people"></i> Kelola Mahasiswa
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-person-gear"></i> Kelola User
            </a>
        </li>
        <?php endif; ?>

        <!-- USER MENU -->
        <?php if ($role == 'user'): ?>
        <li class="nav-item">
            <a href="../mahasiswa/index.php" class="nav-link">
                <i class="bi bi-eye"></i> Lihat Mahasiswa
            </a>
        </li>
        <?php endif; ?>

    </ul>
</div>

<!-- CONTENT -->
<div class="col-md-9 col-lg-10 p-4">

    <h3>Dashboard</h3>
    <p>Selamat datang, kamu login sebagai <b><?= $role; ?></b></p>

    <!-- CARD -->
    <div class="row">

        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5>Total Mahasiswa</h5>
                    <p>Data tersedia</p>
                </div>
            </div>
        </div>

        <?php if ($role == 'admin'): ?>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5>Manajemen User</h5>
                    <p>Khusus Admin</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php include '../templates/footer.php'; ?>
