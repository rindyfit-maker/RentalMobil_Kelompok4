<?php
// auth.php — helper session

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

function isUser(): bool {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'user';
}

function requireLogin(string $redirect = 'login.php'): void {
    if (!isLoggedIn()) {
        header("Location: $redirect");
        exit;
    }
}

function requireAdmin(string $redirect = 'admindasboard.php'): void {
    requireLogin();
    if (!isAdmin()) {
        header("Location: $redirect?pesan=akses_ditolak");
        exit;
    }
}

function requireUser(string $redirect = 'admindasboard.php'): void {
    requireLogin();
    // Both admin and user can access user pages
}

function getSessionUser(): array {
    return [
        'id'    => $_SESSION['user_id'] ?? '',
        'nama'  => $_SESSION['nama']    ?? '',
        'email' => $_SESSION['email']   ?? '',
        'role'  => $_SESSION['role']    ?? '',
    ];
}

function logout(): void {
    session_destroy();
    header('Location: login.php');
    exit;
}
