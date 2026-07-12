<?php
include 'config/koneksi.php';

$username = "admin";
$password = password_hash("12345", PASSWORD_DEFAULT); // WAJIB hash
$role = "admin";

mysqli_query($koneksi, "INSERT INTO users VALUES('', '$username', '$password', '$role')");

echo "User berhasil ditambahkan";
?>
