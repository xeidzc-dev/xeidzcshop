<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Method not allowed');
}

$product = sanitizeInput($_POST['product'] ?? '');
$amount = floatval($_POST['amount'] ?? 0);
$email = sanitizeInput($_POST['email'] ?? '');
$payment_method = sanitizeInput($_POST['payment_method'] ?? '');

// Validation
if (empty($product) || $amount <= 0 || empty($email) || empty($payment_method)) {
    sendJsonResponse(false, 'Invalid payment details');
}

// Validate payment method
if (!in_array($payment_method, ['paypal', 'creditcard'])) {
    sendJsonResponse(false, 'Invalid payment method');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Invalid email format');
}

// Process payment (simulated)
$transaction_id = 'TXN_' . uniqid();
$payment_status = 'completed';

// Log payment
$payment_data = [
    'transaction_id' => $transaction_id,
    'product' => $product,
    'amount' => $amount,
    'email' => $email,
    'payment_method' => $payment_method,
    'status' => $payment_status,
    'timestamp' => date('Y-m-d H:i:s'),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
];

logActivity('payment', 
    "Payment processed - Transaction: $transaction_id, Product: $product, Amount: $$amount, Method: $payment_method", 
    $email
);

// Send email notification to admin (simulated)
$subject = "New Purchase - $product";
$message = "New purchase:\nProduct: $product\nAmount: $$amount\nEmail: $email\nTransaction: $transaction_id";

// In production: mail(ADMIN_EMAIL, $subject, $message);

sendJsonResponse(true, 'Payment processed successfully', [
    'transaction_id' => $transaction_id
]);
?>