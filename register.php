<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Method not allowed');
}

$username = sanitizeInput($_POST['username'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (empty($username) || empty($email) || empty($password)) {
    sendJsonResponse(false, 'Please fill in all fields');
}

if ($password !== $confirm_password) {
    sendJsonResponse(false, 'Passwords do not match');
}

if (strlen($password) < 6) {
    sendJsonResponse(false, 'Password must be at least 6 characters long');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Load existing users
$users = [];
if (file_exists(USERS_FILE)) {
    $usersData = file_get_contents(USERS_FILE);
    $users = json_decode($usersData, true) ?? [];
}

// Check if user already exists
foreach ($users as $user) {
    if ($user['email'] === $email) {
        sendJsonResponse(false, 'Email already registered');
    }
    if ($user['username'] === $username) {
        sendJsonResponse(false, 'Username already taken');
    }
}

// Create new user
$newUser = [
    'id' => uniqid(),
    'username' => $username,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'created_at' => date('Y-m-d H:i:s'),
    'last_login' => null,
    'purchases' => []
];

$users[] = $newUser;

// Save users
if (file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT))) {
    logActivity('register', 'New user registered: ' . $username, $username);
    sendJsonResponse(true, 'Registration successful');
} else {
    logActivity('register', 'Failed to register user: ' . $username, $username);
    sendJsonResponse(false, 'Registration failed. Please try again.');
}
?>