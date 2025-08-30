<?php
include('../includes/header.php');
session_start();

if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'staff' || $_SESSION['user_role'] === 'admin')) {
    include '../config.php'; // Database connection

    $user_id = $_SESSION['user_id'];
    $selected_bus_id = isset($_SESSION['selected_bus_id']) ? $_SESSION['selected_bus_id'] : $_SESSION['bus_id'];

    // If `bus_id` is not set, attempt to retrieve or notify
    if ($selected_bus_id == 0) {
        echo '<div class="alert alert-danger text-center">Bus ID is not set. Please select a valid bus.</div>';
        exit();
    }

    // SQL Query to fetch bookings including boarding point
    $stmt = $conn->prepare("SELECT 
        b.id AS booking_id, 
        b.seat_no, 
        b.name, 
        b.phone, 
        b.email, 
        b.booking_date, 
        b.total_cost, 
        b.boarding_point, 
        buses.id AS bus_id, 
        buses.bus_name, 
        buses.start_location, 
        buses.end_location 
    FROM bookings b 
    JOIN buses ON b.bus_id = buses.id 
    WHERE b.bus_id = ? AND buses.user_id = ? 
    ORDER BY b.booking_date DESC");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $selected_bus_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $bus_name = "Unknown Bus";
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $bus_name = $row['bus_name'];
        $result->data_seek(0); // Reset result pointer for further use
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Bookings</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f8f9fa;
                color: #333;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .container-fluid {
                flex: 1;
            }
            h2 {
                color: #007bff;
                text-align: center;
                margin-top: 84px;
            }
            .custom-table {
                background-color: #ffffff;
                border-collapse: collapse;
                width: 100%;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            .custom-table th {
                background-color: #117eaf;
                color: white;
                text-align: center;
                padding: 10px;
            }
            .custom-table td {
                text-align: center;
                padding: 12px;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .footer {
                background: #333;
                color: white;
                text-align: center;
                padding: 0px;
                margin-top: auto;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <h2>Bookings for: <?php echo htmlspecialchars($bus_name); ?></h2>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered custom-table'>";
        echo "<thead><tr>
                <th>Booking ID</th>
                <th>Passenger Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Seat No</th>
                <th>Boarding Point</th>
                <th>Route</th>
                <th>Booking Date</th>
                <th>Total Cost</th>
              </tr></thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['booking_id']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['phone']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['seat_no']) . "</td>
                    <td>" . htmlspecialchars($row['boarding_point']) . "</td>
                    <td>" . htmlspecialchars($row['start_location']) . " to " . htmlspecialchars($row['end_location']) . "</td>
                    <td>" . htmlspecialchars($row['booking_date']) . "</td>
                    <td>NPR " . number_format($row['total_cost'], 2) . "</td>
                  </tr>";
        }

        echo "</tbody></table></div>";
    } else {
        echo '<div class="alert alert-warning text-center">No bookings found for this bus.</div>';
    }
    ?>
        </div>
        <footer class="footer">
            <?php include('../includes/footer.php'); ?>
        </footer>
    </body>
    </html>
    
    <?php
} else {
    echo '<div class="alert alert-danger text-center">Access Denied. Only admins or staff can view this page.</div>';
    exit();
}
?>
