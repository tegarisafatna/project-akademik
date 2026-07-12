<?php require_once '../config/flash.php'; ?>
<?php $flashes = get_flashes(); ?>

<?php foreach ($flashes as $f): ?>
<div class="alert alert-<?= $f['type']; ?>">
    <?= $f['message']; ?>
</div>
<?php endforeach; ?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

<div class="card shadow-lg p-4" style="width: 350px; border-radius: 15px;">

    <h3 class="text-center mb-3">Login</h3>
    <p class="text-center text-muted mb-4">Masuk ke sistem akademik</p>

    <form method="POST" action="proses_login.php">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Login
        </button>

    </form>

</div>

</body>
</html>