<?php
require_once '../config/koneksi.php';
require_once '../config/auth.php';
require_once '../config/csrf.php';
require_once '../config/flash.php';

require_role('admin');

// state
$errors = [];
$old = [
    'nama' => '',
    'nim' => '',
    'jurusan' => ''
];

// ======================
// PROSES FORM
// ======================
if (isset($_POST['simpan'])) {

    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        add_flash('danger', 'Token tidak valid!');
        header("Location: tambah.php");
        exit();
    }

    // ambil input
    $old['nama'] = trim($_POST['nama']);
    $old['nim'] = trim($_POST['nim']);
    $old['jurusan'] = trim($_POST['jurusan']);

    // ======================
    // VALIDASI
    // ======================

    // nama
    if ($old['nama'] === '') {
        $errors['nama'] = "Nama wajib diisi";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $old['nama'])) {
        $errors['nama'] = "Nama hanya boleh huruf";
    }

    // nim
    if ($old['nim'] === '') {
        $errors['nim'] = "NIM wajib diisi";
    } elseif (!preg_match("/^[0-9]+$/", $old['nim'])) {
        $errors['nim'] = "NIM hanya boleh angka";
    } else {
        // cek unik
        $cek = mysqli_prepare($koneksi, "SELECT id FROM mahasiswa WHERE nim = ?");
        mysqli_stmt_bind_param($cek, "s", $old['nim']);
        mysqli_stmt_execute($cek);
        $res = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($res) > 0) {
            $errors['nim'] = "NIM sudah terdaftar!";
        }
    }

    // jurusan
    if ($old['jurusan'] === '') {
        $errors['jurusan'] = "Jurusan wajib diisi";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $old['jurusan'])) {
        $errors['jurusan'] = "Jurusan hanya boleh huruf";
    }

    // ======================
    // JIKA VALID → INSERT
    // ======================
    if (empty($errors)) {

        $stmt = mysqli_prepare($koneksi,
            "INSERT INTO mahasiswa (nama, nim, jurusan) VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            add_flash('danger', 'Terjadi kesalahan sistem');
            header("Location: tambah.php");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "sss",
            $old['nama'], $old['nim'], $old['jurusan']
        );

        if (!mysqli_stmt_execute($stmt)) {

            // fallback duplicate (race condition)
            if (mysqli_errno($koneksi) == 1062) {
                $errors['nim'] = "NIM sudah terdaftar!";
            } else {
                add_flash('danger', 'Gagal menyimpan data');
                header("Location: tambah.php");
                exit();
            }

        } else {
            add_flash('success', 'Data berhasil ditambahkan!');
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>➕ Tambah Mahasiswa</h3>
        <a href="index.php" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <!-- FLASH -->
    <?php foreach (get_flashes() as $f): ?>
        <div class="alert alert-<?= $f['type']; ?>">
            <?= htmlspecialchars($f['message']); ?>
        </div>
    <?php endforeach; ?>

    <!-- FORM -->
    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" id="formMahasiswa">

                <input type="hidden" name="csrf_token" value="<?= csrf_token(); ?>">

                <!-- NAMA -->
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama"
                        class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($old['nama']); ?>">

                    <div class="invalid-feedback">
                        <?= $errors['nama'] ?? '' ?>
                    </div>
                </div>

                <!-- NIM -->
                <div class="mb-3">
                    <label>NIM</label>
                    <input type="text" id="nim" name="nim"
                        class="form-control <?= isset($errors['nim']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($old['nim']); ?>">

                    <div class="invalid-feedback">
                        <?= $errors['nim'] ?? '' ?>
                    </div>
                </div>

                <!-- JURUSAN -->
                <div class="mb-3">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan"
                        class="form-control <?= isset($errors['jurusan']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($old['jurusan']); ?>">

                    <div class="invalid-feedback">
                        <?= $errors['jurusan'] ?? '' ?>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-primary">
                        Simpan Data
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
