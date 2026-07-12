<?php
require_once '../config/koneksi.php';
require_once '../config/auth.php';

// hanya admin boleh edit
require_role('admin');

// ambil id & validasi
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "ID tidak valid";
    exit();
}
$id = (int) $_GET['id'];

// ambil data lama
$stmt = mysqli_prepare($koneksi, "SELECT * FROM mahasiswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan";
    exit();
}

// proses update
if (isset($_POST['update'])) {

    $nama = trim($_POST['nama']);
    $nim = trim($_POST['nim']);
    $jurusan = trim($_POST['jurusan']);

    // validasi sederhana
    if ($nama === '' || $nim === '' || $jurusan === '') {
        echo "Semua field wajib diisi";
    } else {
        $stmt = mysqli_prepare($koneksi,
            "UPDATE mahasiswa SET nama=?, nim=?, jurusan=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, "sssi", $nama, $nim, $jurusan, $id);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit();
    }
}
?>

<form method="POST">
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>"><br>
    <input type="text" name="nim" value="<?= htmlspecialchars($data['nim']); ?>"><br>
    <input type="text" name="jurusan" value="<?= htmlspecialchars($data['jurusan']); ?>"><br>
    <button name="update">Update</button>
</form>
