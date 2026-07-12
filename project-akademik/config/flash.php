<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Tambah pesan (bisa banyak dalam satu request)
 * $type: success | danger | warning | info
 */
function add_flash($type, $message) {
    if (!isset($_SESSION['flashes'])) {
        $_SESSION['flashes'] = [];
    }
    $_SESSION['flashes'][] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Ambil & hapus semua pesan (one-time)
 */
function get_flashes() {
    $flashes = $_SESSION['flashes'] ?? [];
    unset($_SESSION['flashes']);
    return $flashes;
}
