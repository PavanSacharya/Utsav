<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$eventType = isset($_GET['event']) ? htmlspecialchars($_GET['event']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="booking-container">
        <h2>Book Your Event</h2>
        <form action="event_booking.php" method="POST">
            <div class="input-row">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="input-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" required>
                </div>
            </div>
            <div class="input-row">
                <div class="input-group">
                    <label>Location</label>
                    <input type="text" name="location" required>
                </div>
                <div class="input-group">
                    <label>Event Type</label>
                    <input type="text" name="eventType" value="<?php echo $eventType; ?>" readonly required>
                </div>
            </div>
            <div class="input-row">
                <div class="input-group">
                    <label>Date</label>
                    <input type="date" name="date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                <div class="input-group">
                    <label>Time</label>
                    <input type="time" name="time" required>
                </div>
            </div>
            <button type="submit" class="btn">Submit</button>
            <a href="../index.php#price" class="btn cancel-btn">Cancel</a>
        </form>
    </div>
</body>
</html>