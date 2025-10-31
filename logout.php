<?php
require_once 'config.php';

session_start();
session_destroy();

// Log logout activity
logActivity('security', 'User logged out');

header('Location: index.html?logout=success');
exit;
?>