<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

// auto-cleanup expired tokens
$pdo->prepare('DELETE FROM password_resets WHERE expires_at < NOW()')->execute();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$email = trim($_POST['forgot-email'] ?? '');
if ($email === '') {
    $_SESSION['flash'] = 'Enter your email.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = 'Invalid email format.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    // keep response generic for security
    $_SESSION['flash'] = 'If this email exists, a reset link has been sent.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$token = bin2hex(random_bytes(32));
$expiresAt = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

$pdo->prepare('DELETE FROM password_resets WHERE email = ?')->execute([$email]);
$insert = $pdo->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)');
$insert->execute([$email, $token, $expiresAt]);

$resetUrl = APP_URL . '/auth/reset.php?token=' . urlencode($token);

require_once __DIR__ . '/../inc/mail.php';

// try to get user's name for personalized email
$nameStmt = $pdo->prepare('SELECT name FROM users WHERE email = ? LIMIT 1');
$nameStmt->execute([$email]);
$nameRow = $nameStmt->fetch();
$userName = $nameRow['name'] ?? $email;

if (sendPasswordResetEmail($email, $userName, $resetUrl)) {
    $_SESSION['flash'] = 'Password reset link sent to ' . htmlspecialchars($email) . '. Check your inbox.';
} else {
    // Fallback: show link in UI for local testing
    $_SESSION['flash'] = 'Reset link (test mode - copy/paste): ' . htmlspecialchars($resetUrl);
}

header('Location: ' . APP_URL . '/index.php');
exit;
