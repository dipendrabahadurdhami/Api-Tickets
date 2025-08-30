<?php include('../includes/header.php'); ?>
<?php
include '../config.php';

$user_id = $_SESSION['user_id'];

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
        notifications.user_id = ? 
    ORDER BY 
        notifications.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['delete_all'])) {
    $deleteQuery = "DELETE FROM notifications WHERE user_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $user_id);
    $deleteStmt->execute();
    echo "<script>window.location.href = 'your_booking.php';</script>"; 
    exit;
}

if (isset($_GET['delete_notification_id'])) {
    $notification_id = $_GET['delete_notification_id'];
    $deleteQuery = "DELETE FROM notifications WHERE id = ? AND user_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $notification_id, $user_id);
    $deleteStmt->execute();
    echo "<script>window.location.href = 'your_booking.php';</script>";
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
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .notification-container {
            flex: 1;
            max-width: 1200px;
            width:90%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
        }

        .notification {
            padding: 15px;
            border: 1px solid #ddd;
            margin: 10px 0;
            background-color: #fff;
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

        .notification-header .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        .notification-header .delete-btn:hover {
            background-color: #e60000;
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
            width: 100%;
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

        @media (max-width: 768px) {
            .notification-header {
                flex-wrap: wrap;
            }

            .notification-header .delete-btn, .view-details-btn, .clear-all-btn {
                width: 100%;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="notification-container">
       

        <form method="POST">
            <button type="submit" name="delete_all" class="clear-all-btn">Delete All Bookings</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($notification = $result->fetch_assoc()): ?>
                <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                    <div class="notification-header">
                        <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($notification['booking_id']); ?></p>
                        <a href="your_booking.php?delete_notification_id=<?php echo $notification['notification_id']; ?>" class="delete-btn">Delete</a>
                    </div>
                    <a href="your_booking_details.php?notification_id=<?php echo $notification['notification_id']; ?>" class="view-details-btn">View Details</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No Bookings found.</p>
        <?php endif; ?>
    </div>
   
   <?php include('../includes/footer.php'); ?>
</body>
</html>
