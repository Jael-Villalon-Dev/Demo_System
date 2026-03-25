<?php
require_once __DIR__ . '/../config/app.php';

session_unset();
session_destroy();

header('Location: /Demo_System/index.php');
exit;
