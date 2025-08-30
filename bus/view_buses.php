<?php 
include('../includes/header.php'); 
session_start();
include '../config.php';

// Fetch available buses based on the user role
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_date = $_POST['selected_date'];

    // Check user role
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        // If admin, fetch all buses for the selected date
        $query = "SELECT * FROM buses WHERE available_date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $selected_date);
    } elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'staff') {
        // If staff, fetch only buses assigned to the staff's user_id
        $user_id = $_SESSION['user_id']; // Get the logged-in staff's user ID
        $query = "SELECT * FROM buses WHERE available_date = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $selected_date, $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Buses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            width:90%;
            margin: 72px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            
        }

        h2 {
            color: #333333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555555;
        }

        input[type="date"],
        button {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #cccccc;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .bus-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background-color: #fefefe;
        }

        .bus-info h3 {
            color: #444444;
            margin: 0;
            font-size: 18px;
        }

        .bus-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #666666;
        }

        .btn-view {
            align-self: flex-start;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .btn-view:hover {
            background-color: #218838;
        }

        @media (min-width: 768px) {
            .bus-card {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .bus-info {
                max-width: 70%;
            }

            .btn-view {
                margin-left: auto;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }

            .bus-info p {
                font-size: 12px;
            }

            .btn-view {
                font-size: 12px;
                padding: 8px 12px;
            }
        }

        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto; /* Ensures footer stays at the bottom */
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>View Available Buses</h2>

        <form method="POST" action="view_buses.php">
            <label for="selected_date">Select Date:</label>
            <input type="date" id="selected_date" name="selected_date" required>
            <button type="submit">View Buses</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && $result->num_rows > 0): ?>
            <?php while ($bus = $result->fetch_assoc()): ?>
                <div class="bus-card">
                    <div class="bus-info">
                        <h3><?php echo htmlspecialchars($bus['bus_name']); ?></h3>
                        <p><strong>Start Location:</strong> <?php echo htmlspecialchars($bus['start_location']); ?></p>
                        <p><strong>End Location:</strong> <?php echo htmlspecialchars($bus['end_location']); ?></p>
                        <p><strong>Departure Time:</strong> <?php echo htmlspecialchars($bus['departure_time']); ?></p>
                        <p><strong>Cost:</strong> Rs. <?php echo htmlspecialchars($bus['cost']); ?></p>
                    </div>
                    <form method="POST" action="view_bus_details.php">
                        <input type="hidden" name="bus_id" value="<?php echo $bus['id']; ?>">
                        <button type="submit" class="btn-view">View Details</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>No buses available for the selected date.</p>
        <?php endif; ?>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
