<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../index.php");
            exit;
        } else {
            header("Location: login.php?error=Invalid username/email or password");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=Database error: " . $e->getMessage());
        exit;
    }
}
?>