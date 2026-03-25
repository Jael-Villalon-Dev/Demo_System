<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Demo_System/index.php');
    exit;
}

$email = trim($_POST['login-email'] ?? '');
$password = trim($_POST['login-password'] ?? '');

if ($email === '' || $password === '') {
    $_SESSION['flash'] = 'Email and password required.';
    header('Location: /Demo_System/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, password, role FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['flash'] = 'Invalid credentials.';
    header('Location: /Demo_System/index.php');
    exit;
}

$_SESSION['user'] = $email;
$_SESSION['role'] = $user['role'];
$_SESSION['flash'] = 'Login successful as ' . htmlspecialchars($user['role']);
header('Location: /Demo_System/index.php');
exit;
