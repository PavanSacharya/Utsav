<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Evento</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600;700&display=swap">
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login_process.php" method="POST">
            <input type="text" name="email" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <button type="submit">Login</button>
            <p>New user? <a href="signup.php">Sign up here</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
            <p><a href="../index.php">Home</a></p>
        </form>
    </div>
</body>
</html>