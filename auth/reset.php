<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

// auto-cleanup expired tokens
$pdo->prepare('DELETE FROM password_resets WHERE expires_at < NOW()')->execute();

$token = $_GET['token'] ?? '';
if (!$token || strlen($token) !== 64) {
    $_SESSION['flash'] = 'Invalid reset link.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT email, expires_at FROM password_resets WHERE token = ? LIMIT 1');
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    $_SESSION['flash'] = 'Invalid or expired reset token.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$expiresAt = new DateTime($reset['expires_at']);
if ($expiresAt < new DateTime()) {
    $pdo->prepare('DELETE FROM password_resets WHERE token = ?')->execute([$token]);
    $_SESSION['flash'] = 'Reset link has expired. Request a new one.';
    header('Location: ' . APP_URL . '/index.php');
    exit;
}

$email = $reset['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password</title>
  <link rel="stylesheet" href="/Demo_System/style.css" />
</head>
<body>
  <article class="card">
    <section class="panel left">
      <h1>Reset your password</h1>
      <p class="subtitle">Change password for: <?php echo htmlspecialchars($email); ?></p>
      
      <form method="post" action="/Demo_System/auth/reset_process.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
        <label for="new-password">New Password</label>
        <input id="new-password" name="new-password" type="password" placeholder="Min. 8 characters" required />
        <label for="confirm-password">Confirm Password</label>
        <input id="confirm-password" name="confirm-password" type="password" placeholder="Confirm password" required />
        <button class="btn" type="submit">Update Password</button>
      </form>

      <p class="small-note"><a href="<?php echo APP_URL; ?>/index.php" class="link">Back to Login</a></p>
    </section>
  </article>
</body>
</html>

