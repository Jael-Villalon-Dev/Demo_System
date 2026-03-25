<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /Demo_System/index.php');
    exit;
}

$name = trim($_POST['reg-name'] ?? '');
$email = trim($_POST['reg-email'] ?? '');
$password = trim($_POST['reg-password'] ?? '');
$role = $_POST['reg-role'] ?? 'client';

$allowedRoles = ['client', 'admin', 'superAdmin'];
$role = in_array($role, $allowedRoles, true) ? $role : 'client';

if ($name === '' || $email === '' || $password === '') {
    $_SESSION['flash'] = 'Please complete all fields.';
    header('Location: /Demo_System/index.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash'] = 'Invalid email address.';
    header('Location: /Demo_System/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['flash'] = 'Email already registered.';
    header('Location: /Demo_System/index.php');
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $email, $hashedPassword, $role]);

$_SESSION['user'] = $email;
$_SESSION['role'] = $role;
$_SESSION['flash'] = 'Account created (' . htmlspecialchars($role) . ').';
header('Location: /Demo_System/index.php');
exit;
