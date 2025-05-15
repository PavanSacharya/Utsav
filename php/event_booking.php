<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $event_type = filter_var($_POST['eventType'], FILTER_SANITIZE_STRING);
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Validate inputs
    if (empty($name) || empty($phone) || empty($location) || empty($event_type) || empty($date) || empty($time)) {
        echo "<script>alert('All fields are required.'); window.location.href='book_event.php?event=" . urlencode($event_type) . "';</script>";
        exit;
    }

    // Combine date and time into a DATETIME format
    $booking_date = "$date $time:00";

    try {
        // Look up the event_id based on eventType
        $stmt = $conn->prepare("SELECT id FROM events WHERE name = ?");
        $stmt->execute([$event_type]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            echo "<script>alert('Invalid event type.'); window.location.href='../index.php#price';</script>";
            exit;
        }

        $event_id = $event['id'];

        // Insert the booking into the bookings table
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id, name, phone, location, booking_date, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$user_id, $event_id, $name, $phone, $location, $booking_date]);

        echo "<script>alert('Booking submitted successfully! We will contact you soon.'); window.location.href='../index.php#price';</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='book_event.php?event=" . urlencode($event_type) . "';</script>";
        exit;
    }
} else {
    header('Location: ../home.php#price');
    exit;
}
?>