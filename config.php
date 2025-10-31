<?php
// Configuration file for the website

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security configuration
define('SECRET_KEY', 'xeidzc-shop-secret-key-2024');
define('ENCRYPTION_KEY', 'xeidzc-encryption-key-2024');

// File paths - use absolute paths
define('BASE_PATH', __DIR__);
define('USERS_FILE', BASE_PATH . '/users/users.json');
define('LOGIN_LOGS', BASE_PATH . '/logs/login_logs.txt');
define('REGISTER_LOGS', BASE_PATH . '/logs/register_logs.txt');
define('PAYMENT_LOGS', BASE_PATH . '/logs/payment_logs.txt');
define('SECURITY_LOGS', BASE_PATH . '/logs/security_logs.txt');

// Email configuration
define('ADMIN_EMAIL', 'xeidzc@gmail.com');

// Create necessary directories if they don't exist
function createDirectories() {
    $directories = [
        dirname(USERS_FILE),
        dirname(LOGIN_LOGS)
    ];
    
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    // Create empty users.json if it doesn't exist
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, '[]');
    }
    
    return true;
}

// Initialize directories
createDirectories();

// Function to log activities
function logActivity($type, $message, $user = 'Unknown') {
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $logEntry = "[$timestamp] [$type] [$ip] [$user] $message\n";
    
    switch($type) {
        case 'login':
            $logFile = LOGIN_LOGS;
            break;
        case 'register':
            $logFile = REGISTER_LOGS;
            break;
        case 'payment':
            $logFile = PAYMENT_LOGS;
            break;
        case 'security':
            $logFile = SECURITY_LOGS;
            break;
        default:
            $logFile = SECURITY_LOGS;
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Input sanitization
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// JSON response helper
function sendJsonResponse($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $data));
    exit;
}

// Security headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

// Start session
session_start();
?>