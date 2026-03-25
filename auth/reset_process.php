<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

// auto-cleanup expired tokens
$pdo->prepare('DELETE FROM password_resets WHERE expires_at < NOW()')->execute();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$token = $_POST['token'] ?? '';
$newPassword = trim($_POST['new-password'] ?? '');
$confirmPassword = trim($_POST['confirm-password'] ?? '');

if ($token === '' || $newPassword === '' || $confirmPassword === '') {
    $_SESSION['flash'] = 'All fields are required.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

if (strlen($token) !== 64) {
    $_SESSION['flash'] = 'Invalid token format.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

if ($newPassword !== $confirmPassword) {
    $_SESSION['flash'] = 'Passwords do not match.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

if (strlen($newPassword) < 8) {
    $_SESSION['flash'] = 'Password must be at least 8 characters.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT email, expires_at FROM password_resets WHERE token = ? LIMIT 1');
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    $_SESSION['flash'] = 'Invalid or expired token.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$expiresAt = new DateTime($reset['expires_at']);
if ($expiresAt < new DateTime()) {
    $_SESSION['flash'] = 'Reset link has expired. Request a new one.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$email = $reset['email'];
$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$update->execute([$hash, $email]);
$pdo->prepare('DELETE FROM password_resets WHERE token = ?')->execute([$token]);

$_SESSION['flash'] = 'Password reset successful! Please login with your new password.';
header('Location: ' . APP_URL . '/index.php');
exit;
