<?php
require_once '../config/koneksi.php';
require_once '../config/auth.php';

// hanya admin
require_role('admin');

// validasi id
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo "ID tidak valid";
    exit();
}

$id = (int) $_GET['id'];

// prepared statement (AMAN)
$stmt = mysqli_prepare($koneksi, "DELETE FROM mahasiswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

header("Location: index.php");
exit();
