<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'cookie_secure' => false,
    ]);
}

define('APP_URL', 'http://localhost/Demo_System');

function isUserLogged(): bool {
    return !empty($_SESSION['user']) && !empty($_SESSION['role']);
}

function requireRole(string $role): void {
    if (!isUserLogged() || ($_SESSION['role'] ?? '') !== $role) {
        header('Location: /Demo_System/index.php');
        exit;
    }
}
