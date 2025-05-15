<?php
session_start();


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Include the database connection file with the correct path
try {
    require_once 'db_connect.php';
} catch (Exception $e) {
    error_log("Failed to include db_connect.php: " . $e->getMessage());
    echo "<script>alert('Error: Unable to connect to the database. Please try again later.'); window.location.href=''../index.php#review'';</script>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form inputs
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING);
    $subject = filter_var($_POST['subject'] ?? '', FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.location.href=''../index.php#review'';</script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href=''../index.php#review'';</script>";
        exit;
    }

    try {
        // Insert the contact message into the database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $subject, $message]);

        // Redirect back to the contact section with a success message
        echo "<script>alert('Message sent successfully!'); window.location.href='../index.php#contact';</script>";
        exit;
    } catch (PDOException $e) {
        // Log the error and show a user-friendly message
        error_log("Database error: " . $e->getMessage());
        echo "<script>alert('Error sending message: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='../index.php#review';</script>";
        exit;
    }
} else {
    // If accessed directly without POST, redirect back
     header('Location: ../index.php#review');
    exit;
}
?>