<?php
$koneksi = mysqli_connect("localhost", "root", "", "akademik");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
