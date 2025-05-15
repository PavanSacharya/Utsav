<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $token = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
            $stmt->execute([$token, $expiry, $email]);
            header("Location: forgot_password.php?message=Reset link sent to your email ");
            exit;
        } else {
            header("Location: forgot_password.php?error=Email not found");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: forgot_password.php?error=Database error: " . $e->getMessage());
        exit;
    }
}
?>