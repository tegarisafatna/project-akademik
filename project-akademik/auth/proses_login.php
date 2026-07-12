<?php
session_start();
require_once '../config/koneksi.php';
require_once '../config/flash.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === '' || $password === '') {
        add_flash('warning', 'Username dan password wajib diisi!');
        header("Location: login.php");
        exit();
    }

    // prepared statement
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['login'] = true;
        $_SESSION['role'] = $user['role'];

        add_flash('success', 'Login berhasil!');
        header("Location: ../dashboard/index.php");
        exit();

    } else {
        add_flash('danger', 'Username atau password salah!');
        header("Location: login.php");
        exit();
    }

} else {
    add_flash('danger', 'Akses tidak valid!');
    header("Location: login.php");
    exit();
}
?>
