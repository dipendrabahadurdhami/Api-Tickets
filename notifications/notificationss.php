<?php

include '../config.php'; // Database configuration
include('../includes/header.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch notifications with booking details, but only unread ones
$query = "
    SELECT 
        notifications.id AS notification_id, 
        notifications.message, 
        notifications.is_read, 
        notifications.created_at, 
        bookings.id AS booking_id 
    FROM 
        notifications 
    LEFT JOIN 
        bookings 
    ON 
        notifications.booking_id = bookings.id 
    WHERE 
        notifications.user_id = ? AND notifications.is_read = 0
    ORDER BY 
        notifications.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle marking all notifications as read
if (isset($_POST['clear_all'])) {
    $clearQuery = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
    $clearStmt = $conn->prepare($clearQuery);
    $clearStmt->bind_param("i", $user_id);
    $clearStmt->execute();
    echo "<script>window.location.href = 'notificationss.php';</script>"; // Redirect to refresh the page
    exit;
}

// Handle marking an individual notification as read
if (isset($_GET['clear_notification_id'])) {
    $notification_id = $_GET['clear_notification_id'];
    $clearQuery = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $clearStmt = $conn->prepare($clearQuery);
    $clearStmt->bind_param("ii", $notification_id, $user_id);
    $clearStmt->execute();
    echo "<script>window.location.href = 'notificationss.php';</script>";  // Redirect to refresh the page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .notification-container {
          
            max-width: 1200px;
            width: 90%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 72px auto;
        }

        .notification {
            padding: 15px;
            border: 1px solid #ddd;
            margin: 10px 0;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-radius: 8px;
        }

        .notification.unread {
            background-color: #f0f8ff;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header p {
            margin: 0;
        }

        .notification-header .clear-btn {
            background-color: #ffae42;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }

        .notification-header .clear-btn:hover {
            background-color: #d98c26;
        }

        .view-details-btn {
            display: block;
            text-align: center;
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .view-details-btn:hover {
            background-color: #2980b9;
        }

        .clear-all-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .clear-all-btn:hover {
            background-color: #2980b9;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }

        footer p {
            margin: 0;
        }

        footer a {
            color: white;
            text-decoration: none;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            body {
                font-size: 14px;
            }
            .clear-btn, .view-details-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <h2>Your Notifications</h2>

        <form method="POST">
            <button type="submit" name="clear_all" class="clear-all-btn">Clear All Notifications</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($notification = $result->fetch_assoc()): ?>
                <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                    <div class="notification-header">
                        <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($notification['booking_id']); ?></p>
                        <a href="notificationss.php?clear_notification_id=<?php echo $notification['notification_id']; ?>" class="clear-btn">Clear Notification</a>
                    </div>
                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                    <a href="view_booking.php?notification_id=<?php echo $notification['notification_id']; ?>" class="view-details-btn">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No notifications found.</p>
        <?php endif; ?>
    </div>
    <?php include('../includes/footer.php'); ?>
</body>
</html>
