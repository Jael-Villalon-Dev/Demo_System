<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Demo_System/index.php');
    exit;
}

$email = trim($_POST['forgot-email'] ?? '');
$newPassword = trim($_POST['forgot-password'] ?? '');

if ($email === '' || $newPassword === '') {
    $_SESSION['flash'] = 'Email and new password are required.';
    header('Location: /Demo_System/index.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = 'Please enter a valid email.';
    header('Location: /Demo_System/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['flash'] = 'No account found with that email.';
    header('Location: /Demo_System/index.php');
    exit;
}

$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare('UPDATE users SET password = ? WHERE email = ?');
$update->execute([$hash, $email]);

$_SESSION['flash'] = 'Password updated successfully. Please login with your new password.';
header('Location: /Demo_System/index.php');
exit;
