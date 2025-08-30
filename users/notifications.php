<?php include('../includes/header.php'); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config.php';


if (!isset($_SESSION['user_id'])) {
    
    echo "<script>window.location.href = '../users/login.php';</script>";
    exit();
}


$userId = $_SESSION['user_id'];


$notificationQuery = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread' ORDER BY created_at DESC";
$notificationStmt = $conn->prepare($notificationQuery);
$notificationStmt->bind_param("i", $userId);
$notificationStmt->execute();
$notificationsResult = $notificationStmt->get_result();


if (isset($_GET['notification_id'])) {
    $notificationId = intval($_GET['notification_id']);
    $updateQuery = "UPDATE notifications SET status = 'read' WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $notificationId);
    $updateStmt->execute();

  
    
    echo "<script>window.location.href = 'notifications.php';</script>";
    exit();
}


$userQuery = "SELECT name FROM users WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userRow = $userResult->fetch_assoc();
$username = $userRow['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
  
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        color: #333;
        margin: 20px;
    }

    .container {
      
        width: 80%;
        max-width: 1200px;
        margin: 0 auto;
    }

    h1.header {
        margin-top: 75px;
        text-align: center;
        color: #333;
        margin-bottom: 20px;
        font-size: 28px;
    }

    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notification-card {
        background-color: #fff;
        padding: 15px;
        margin: 10px 0;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .notification-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .notification-card.unread {
        border-left: 5px solid #0078d4;
    }

    .message {
        font-size: 16px;
        font-weight: 500;
        color: #555;
    }

    .timestamp {
        font-size: 12px;
        color: #888;
    }

    .mark-read {
        background-color: #0078d4;
        color: #fff;
        padding: 8px 15px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
        transition: background-color 0.3s ease;
    }

    .mark-read:hover {
        background-color: #005b99;
    }

    .empty-message {
        text-align: center;
        font-size: 18px;
        color: #888;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .container {
            width: 90%;
        }

        h1.header {
            font-size: 24px;
        }

        .notification-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 12px;
        }

        .notification-card .message {
            font-size: 14px;
        }

        .mark-read {
            margin-top: 10px;
            align-self: flex-start;
        }
    }

    @media screen and (max-width: 480px) {
        h1.header {
            font-size: 18px; /* Decreased font size */
        }

        .notification-card {
            font-size: 14px;
            padding: 10px;
        }

        .message {
            font-size: 12px; 
        }

        .timestamp {
            font-size: 10px; 
        }

        .mark-read {
            padding: 6px 12px;
            font-size: 12px; 
        }

        .empty-message {
            font-size: 16px; 
        }
    }

</style>

</head>
<body>
    <div class="container">
        <h1 class="header">Hello, <?php echo htmlspecialchars($username); ?>!</h1>

        <?php if ($notificationsResult->num_rows > 0): ?>
            <ul class="notification-list">
                <?php while ($notification = $notificationsResult->fetch_assoc()): ?>
                    <li class="notification-card <?php echo $notification['status'] === 'unread' ? 'unread' : ''; ?>">
                        <div>
                            <p class="message"><?php echo htmlspecialchars($notification['message']); ?></p>
                            <span class="timestamp"><?php echo $notification['created_at']; ?></span>
                        </div>
                        <a href="notifications.php?notification_id=<?php echo $notification['id']; ?>" class="mark-read">
                            Mark as Read
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="empty-message">You have no unread notifications.</p>
        <?php endif; ?>
    </div>
</body>
</html>
