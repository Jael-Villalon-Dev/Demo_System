<?php
declare(strict_types=1);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'demo_system');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP default is empty

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // create DB if missing
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');
    $pdo->exec('USE `' . DB_NAME . '`');

    // create users table if missing
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(120) NOT NULL,
      `email` VARCHAR(150) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL,
      `role` ENUM('client','admin','superAdmin') NOT NULL DEFAULT 'client',
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // create password_resets table for one-time reset tokens
    $pdo->exec("CREATE TABLE IF NOT EXISTS `password_resets` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `email` VARCHAR(150) NOT NULL,
      `token` VARCHAR(64) NOT NULL,
      `expires_at` DATETIME NOT NULL,
      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      UNIQUE KEY (`token`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

