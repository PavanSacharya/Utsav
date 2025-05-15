<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = filter_var($_POST['identifier'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    if (empty($identifier) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Normalize the identifier by converting to lowercase for consistent comparison
        $identifier = strtolower($identifier);

        try {
            $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ? OR email = ?");
            $stmt->execute([$identifier, $identifier]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header('Location: admin.php');
                exit;
            } else {
                $error = "Invalid username/email or password.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Event Organizer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin_login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="admin_login.php" method="POST">
                <input type="text" name="identifier" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>