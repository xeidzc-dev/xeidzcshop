<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Method not allowed');
}

$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    sendJsonResponse(false, 'Please fill in all fields');
}

// Load users from file
$users = [];
if (file_exists(USERS_FILE)) {
    $usersData = file_get_contents(USERS_FILE);
    $users = json_decode($usersData, true) ?? [];
}

// Find user
$user = null;
foreach ($users as $storedUser) {
    if ($storedUser['email'] === $email) {
        $user = $storedUser;
        break;
    }
}

if (!$user || !password_verify($password, $user['password'])) {
    logActivity('login', 'Failed login attempt for email: ' . $email, $email);
    sendJsonResponse(false, 'Invalid email or password');
}

// Successful login
logActivity('login', 'User logged in successfully', $user['username']);

// Update last login
$user['last_login'] = date('Y-m-d H:i:s');
foreach ($users as &$u) {
    if ($u['email'] === $email) {
        $u = $user;
        break;
    }
}

file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));

// Return user data (without password)
unset($user['password']);
sendJsonResponse(true, 'Login successful', ['user' => $user]);
?>