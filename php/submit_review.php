<?php
session_start();
require_once 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Validate inputs
    if (empty($name) || empty($subject) || empty($description)) {
        echo "<script>alert('All fields are required.'); window.location.href='../index.php#review';</script>";
        exit;
    }

    try {
        // Insert the review into the database
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, name, subject, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $subject, $description]);

        // Redirect back to the review section with a success message
        echo "<script>alert('Review submitted successfully!'); window.location.href='../index.php#review';</script>";
        exit;
    } catch (PDOException $e) {
        // Redirect back with an error message
        echo "<script>alert('Error submitting review: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='../index.php#review';</script>";
        exit;
    }
} else {
    // If accessed directly without POST, redirect back
    header('Location: ../index.php#review');
    exit;
}
?>