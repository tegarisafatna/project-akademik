<?php
session_start();
header('Content-Type: application/json');

require_once '../config/koneksi.php';
require_once '../config/auth.php';
require_once '../config/csrf.php';

require_role('admin');

$res = [
    'success' => false,
    'message' => '',
    'errors'  => []
];

// method check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $res['message'] = 'Method tidak diizinkan';
    echo json_encode($res);
    exit();
}

// CSRF
if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    http_response_code(419);
    $res['message'] = 'Token tidak valid';
    echo json_encode($res);
    exit();
}

// ambil input
$nama    = trim($_POST['nama'] ?? '');
$nim     = trim($_POST['nim'] ?? '');
$jurusan = trim($_POST['jurusan'] ?? '');

// validasi
if ($nama === '') {
    $res['errors']['nama'] = 'Nama wajib diisi';
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $nama)) {
    $res['errors']['nama'] = 'Nama hanya boleh huruf';
}

if ($nim === '') {
    $res['errors']['nim'] = 'NIM wajib diisi';
} elseif (!preg_match('/^[0-9]+$/', $nim)) {
    $res['errors']['nim'] = 'NIM hanya boleh angka';
} else {
    // cek unik (UX)
    $cek = mysqli_prepare($koneksi, "SELECT id FROM mahasiswa WHERE nim = ?");
    mysqli_stmt_bind_param($cek, "s", $nim);
    mysqli_stmt_execute($cek);
    $r = mysqli_stmt_get_result($cek);
    if (mysqli_num_rows($r) > 0) {
        $res['errors']['nim'] = 'NIM sudah terdaftar!';
    }
}

if ($jurusan === '') {
    $res['errors']['jurusan'] = 'Jurusan wajib diisi';
} elseif (!preg_match('/^[a-zA-Z\s]+$/', $jurusan)) {
    $res['errors']['jurusan'] = 'Jurusan hanya boleh huruf';
}

// kalau ada error → kirim balik
if (!empty($res['errors'])) {
    $res['message'] = 'Periksa kembali input Anda';
    echo json_encode($res);
    exit();
}

// insert (prepared)
$stmt = mysqli_prepare($koneksi,
    "INSERT INTO mahasiswa (nama, nim, jurusan) VALUES (?, ?, ?)"
);

if (!$stmt) {
    http_response_code(500);
    $res['message'] = 'Terjadi kesalahan sistem';
    echo json_encode($res);
    exit();
}

mysqli_stmt_bind_param($stmt, "sss", $nama, $nim, $jurusan);

if (!mysqli_stmt_execute($stmt)) {
    // fallback duplicate (race condition)
    if (mysqli_errno($koneksi) == 1062) {
        $res['errors']['nim'] = 'NIM sudah terdaftar!';
        $res['message'] = 'NIM sudah digunakan';
        echo json_encode($res);
        exit();
    }

    http_response_code(500);
    $res['message'] = 'Gagal menyimpan data';
    echo json_encode($res);
    exit();
}

// sukses
$res['success'] = true;
$res['message'] = 'Data berhasil ditambahkan!';
echo json_encode($res);
