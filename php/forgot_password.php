<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Evento</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600;700&display=swap">
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="login-container">
        <h2>Forgot Password</h2>
        <form action="forgot_password_process.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($message): ?>
                <p style="color: green;"><?php echo $message; ?></p>
            <?php endif; ?>
            <button type="submit">Reset Password</button>
            <p>Back to <a href="login.php">Login</a></p>
            <p><a href="../index.php">Home</a></p>
        </form>
    </div>
</body>
</html>