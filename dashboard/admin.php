<?php
require_once __DIR__ . '/../config/app.php';
requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="/Demo_System/style.css" />
</head>
<body>
  <main class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <p>You're logged in as <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></p>
    <p>Role: <em><?php echo htmlspecialchars($_SESSION['role']); ?></em></p>
    <p><a class="btn" href="/Demo_System/auth/logout.php">Logout</a></p>
  </main>
</body>
</html>
