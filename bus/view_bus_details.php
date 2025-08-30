<?php 
include('../includes/header.php'); 
session_start();
include '../config.php';

// Check if bus_id is sent via POST
if (isset($_POST['bus_id'])) {
    $bus_id = $_POST['bus_id'];

    // Store bus_id in the session for persistence
    $_SESSION['bus_id'] = $bus_id;

    // Fetch the bus details
    $query = "SELECT * FROM buses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bus = $result->fetch_assoc();
} elseif (isset($_SESSION['bus_id'])) {
    // If bus_id is already in the session, use it
    $bus_id = $_SESSION['bus_id'];

    // Fetch the bus details again in case of a refresh or session-based access
    $query = "SELECT * FROM buses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bus = $result->fetch_assoc();
} else {
    $bus = null; // No bus_id provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 1200px;
            width:90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .bus-details {
            margin-top: 20px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
        }

        .bus-details h3 {
            margin-top: 0;
        }

        .btn {
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-container {
            display: flex;
            justify-content: start;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bus Details</h2>

        <?php if ($bus): ?>
            <div class="bus-details">
                <h3><?php echo htmlspecialchars($bus['bus_name']); ?></h3>
                <p><strong>Start Location:</strong> <?php echo htmlspecialchars($bus['start_location']); ?></p>
                <p><strong>End Location:</strong> <?php echo htmlspecialchars($bus['end_location']); ?></p>
                <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($bus['departure_time']); ?></p>
                <p><strong>Cost:</strong> Rs. <?php echo htmlspecialchars($bus['cost']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($bus['phone_number']); ?></p>
                <p><strong>AC Option:</strong> <?php echo htmlspecialchars($bus['ac_option']); ?></p>
                <p><strong>Charger Option:</strong> <?php echo htmlspecialchars($bus['charger_option']); ?></p>
                <img src="<?php echo htmlspecialchars($bus['bus_photo']); ?>" alt="Bus Photo" style="max-width: 100%; height: auto;">
            </div>
        <?php else: ?>
            <p>Bus details not found.</p>
        <?php endif; ?>

        <div class="btn-container">
            <form method="POST" action="view_buses.php" style="margin-right: 10px;">
                <button type="submit" class="btn">Back to Buses</button>
            </form>

            <?php if ($bus): ?>
                <form method="GET" action="staff_boking.php">
                    <input type="hidden" name="bus_id" value="<?php echo $bus['id']; ?>">
                    <button type="submit" class="btn">View Booking</button>
                </form>

                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'staff'): ?>
                    <form method="GET" action="manage_seats.php">
                        <input type="hidden" name="bus_id" value="<?php echo $bus['id']; ?>">
                        <button type="submit" class="btn">Manage Seats</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
