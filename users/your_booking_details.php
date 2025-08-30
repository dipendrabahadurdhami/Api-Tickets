<?php include('../includes/header.php'); ?>
<?php

include_once '../config.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['notification_id']) || !is_numeric($_GET['notification_id'])) {
    die("Error: Invalid or missing notification ID.");
}

$notification_id = intval($_GET['notification_id']);


$query = "
    SELECT 
        notifications.id AS notification_id, 
        notifications.message, 
        notifications.created_at, 
        bookings.id AS booking_id, 
        bookings.bus_id, 
        bookings.seat_no, 
        bookings.total_cost, 
        bookings.booking_date, 
        buses.bus_name AS bus_name,
        buses.start_location AS start_location, 
        buses.end_location AS end_location,
        buses.available_date AS available_date,
        buses.departure_time AS departure_time
    FROM 
        notifications 
    LEFT JOIN 
        bookings 
    ON 
        notifications.booking_id = bookings.id 
    LEFT JOIN 
        buses 
    ON 
        bookings.bus_id = buses.id 
    WHERE 
        notifications.id = ? AND notifications.user_id = ? 
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("ii", $notification_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Notification not found or unauthorized access.");
}

$notification = $result->fetch_assoc();

// Mark notification as read
$updateQuery = "UPDATE notifications SET is_read = 1 WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("i", $notification_id);
$updateStmt->execute();
$updateStmt->close();

$stmt->close();


$booking_date = new DateTime($notification['booking_date'], new DateTimeZone('UTC'));
$booking_date->setTimezone(new DateTimeZone('Asia/Kathmandu')); // NPT - Nepal Time
$formatted_booking_date = $booking_date->format('Y-m-d h:i A'); // Format to Y-m-d h:i AM/PM

$departure_time = new DateTime($notification['departure_time'], new DateTimeZone('UTC'));
$departure_time->setTimezone(new DateTimeZone('Asia/Kathmandu')); // NPT - Nepal Time
$formatted_departure_time = $departure_time->format('h:i A'); // Format to h:i AM/PM
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>booking details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .containers {
            max-width: 1200px;
            width:90%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 8px 0;
        }

        strong {
            color: #34495e;
        }

        .notification-details, .booking-details {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .booking-details p {
            margin-bottom: 10px;
        }

        .error-message {
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
        }

        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="containers">
     

        <?php if ($notification['booking_id']): ?>
            <div class="booking-details">
                <h3>Booking Details:</h3>
                <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($notification['booking_id']); ?></p>
                <p><strong>Bus Name:</strong> <?php echo htmlspecialchars($notification['bus_name']); ?></p>
                <p><strong>Route:</strong> <?php echo htmlspecialchars($notification['start_location']) . ' to ' . htmlspecialchars($notification['end_location']); ?></p>
                <p><strong>Seats:</strong> <?php echo htmlspecialchars($notification['seat_no']); ?></p>
                <p><strong>Total Cost:</strong> Rs. <?php echo htmlspecialchars($notification['total_cost']); ?></p>
                <p><strong>Travel Date:</strong> <?php echo $formatted_booking_date; ?></p> <!-- Booking Date -->
               
            </div>
        <?php else: ?>
            <div class="error-message">
                <p>detail not found</p>
            </div>
        <?php endif; ?>

        <a href="your_booking.php" class="button">Go Back</a>
    </div>
</body>
<?php include('../includes/footer.php'); ?>
</html>
