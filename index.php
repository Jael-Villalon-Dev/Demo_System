<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

$message = $_SESSION['flash'] ?? '';
if (!empty($_SESSION['flash'])) {
    unset($_SESSION['flash']);
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Demo System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <?php if (!empty($_SESSION['user'])): ?>
    <section class="logged-in-card animate-slide-down">
      <p class="logged-in-text">Welcome <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong> — role: <em><?php echo htmlspecialchars($_SESSION['role']); ?></em></p>
      <?php if ($_SESSION['role'] === 'superAdmin'): ?>
        <p class="role-note">Super Admin panel is enabled. You have full access.</p>
      <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <p class="role-note">Admin panel unlocked. Manage users and settings.</p>
      <?php else: ?>
        <p class="role-note">Client dashboard active. View portfolio and trades.</p>
      <?php endif; ?>
      <div class="logged-in-actions">
        <a class="btn logout" href="/Demo_System/auth/logout.php">Log Out</a>
      </div>
    </section>
  <?php endif; ?>

  <article class="card">
    <section class="panel left">
      <div class="brand">
        <div class="brand-logo" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="CoinBase logo">
            <circle cx="14" cy="14" r="14" fill="#2e6ffe" />
            <text x="50%" y="55%" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-size="14" font-weight="800" fill="#fff" dy="0.1em">CB</text>
          </svg>
        </div>
        <strong>CoinBase</strong>
      </div>
      <h1 id="form-title">Log in to your account</h1>
      <p class="subtitle" id="form-description">Enter your email address and password to login.</p>

      <?php if ($message): ?>
        <div style="color: green; margin-bottom: 10px; background: #e8f5e9; padding: 8px; border-radius: 6px; border-left: 4px solid #4caf50;"><?php echo $message; ?></div>
      <?php endif; ?>

      <?php if (empty($_SESSION['user'])): ?>
        <div class="tabs">
          <button class="tab active" data-target="login">Login</button>
          <button class="tab" data-target="register">Sign Up</button>
        </div>

        <form id="login-form" method="post" action="/Demo_System/auth/login.php">
          <label for="login-email">Email Address</label>
          <input id="login-email" name="login-email" type="email" placeholder="you@example.com" required />
          <label for="login-password">Password</label>
          <input id="login-password" name="login-password" type="password" placeholder="••••••••" required />

          <div class="actions">
            <label class="checkbox-label"><input type="checkbox" id="remember-me"/> Remember me</label>
            <a href="#" class="link" id="toggle-forgot">Forgot password?</a>
          </div>

          <button class="btn" type="submit">Login</button>

          <div class="divider"><span>or</span></div>

          <div class="social-btns">
            <button type="button" class="social-btn" onclick="alert('Google login');"><span>G</span> Google</button>
            <button type="button" class="social-btn" onclick="alert('Facebook login');"><span>f</span> Facebook</button>
          </div>

          <p class="small-note">Don’t have an account? <a href="#" class="link" id="toggle-signup">Sign Up</a></p>
        </form>

        <form id="forgot-form" class="hidden" method="post" action="/Demo_System/auth/forgot_request.php">
          <label for="forgot-email">Email Address</label>
          <input id="forgot-email" name="forgot-email" type="email" placeholder="you@example.com" required />
          <button class="btn" type="submit">Send reset link</button>
          <p class="small-note">Back to <a href="#" class="link" id="toggle-login-2">Login</a></p>
        </form>

        <form id="register-form" class="hidden" method="post" action="/Demo_System/auth/register.php">
          <label for="reg-name">Full Name</label>
          <input id="reg-name" name="reg-name" type="text" placeholder="John Doe" required />
          <label for="reg-email">Email Address</label>
          <input id="reg-email" name="reg-email" type="email" placeholder="you@example.com" required />
          <label for="reg-password">Password</label>
          <input id="reg-password" name="reg-password" type="password" placeholder="Min. 8 characters" required />
          <label for="reg-role">Role</label>
          <select id="reg-role" name="reg-role" required>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
            <option value="superAdmin">Super Admin</option>
          </select>

          <button class="btn" type="submit">Create Account</button>
          <p class="small-note">Already have an account? <a href="#" class="link" id="toggle-login">Login</a></p>
        </form>
      <?php else: ?>
        <div class="logged-in-subtext">You are already logged in. Use the top logout button to switch account.</div>
      <?php endif; ?>
    </section>

    <section class="panel right">
      <h2>Easy way to manage your portfolio.</h2>
      <p>Join the Coinito community now and get real-time insights, quick trades, and powerful analytics for crypto + DeFi.</p>

      <?php if (!empty($_SESSION['role'])): ?>
        <div class="dashboard-panel">
          <?php if ($_SESSION['role'] === 'superAdmin'): ?>
            <p class="dashboard-note">SuperAdmin: You can view system settings, users, and global analytics.</p>
            <a class="action-btn" href="#">Open SuperAdmin Console</a>
          <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <p class="dashboard-note">Admin: manage users, approve requests, and monitor activity.</p>
            <a class="action-btn" href="#">Go to Admin Dashboard</a>
          <?php else: ?>
            <p class="dashboard-note">Client: review your portfolio, place orders, and track performance.</p>
            <a class="action-btn" href="#">Open Client Dashboard</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="illustration"></div>
    </section>
  </article>

  <script src="Script.js"></script>
</body>
</html>