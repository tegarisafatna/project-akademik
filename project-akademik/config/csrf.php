<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// generate token
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// verifikasi token
function verify_csrf($token) {
    if (!isset($_SESSION['csrf_token']) || !$token) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}
