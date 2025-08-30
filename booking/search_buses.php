<?php include('../includes/header.php'); ?>
<?php

include '../config.php';

if (isset($_POST['start_location']) && isset($_POST['end_location']) && isset($_POST['travel_date'])) {
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $travel_date = $_POST['travel_date'];
    $current_datetime = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("SELECT * FROM buses WHERE start_location = ? AND end_location = ? AND available_date = ? AND CONCAT(available_date, ' ', departure_time) >= ?");
    $stmt->bind_param("ssss", $start_location, $end_location, $travel_date, $current_datetime);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<div class="content-container">';
    echo '<h1 class="page-title">Available Buses</h1>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departure_time_am_pm = date("g:i A", strtotime($row['departure_time']));
            echo '<div class="bus-card">';
            echo '<div class="bus-header"><h3 class="bus-name">' . htmlspecialchars($row['bus_name']) . '</h3></div>';
            echo '<div class="bus-details">';
            echo '<div class="detail-item"><span>Cost:</span> Rs. ' . htmlspecialchars($row['cost']) . '</div>';
            echo '<div class="detail-item"><span>Departure Time:</span> ' . htmlspecialchars($departure_time_am_pm) . '</div>';
            echo '<div class="detail-item"><span>Route:</span> ' . htmlspecialchars($row['start_location']) . ' to ' . htmlspecialchars($row['end_location']) . '</div>';
            echo '<div class="detail-item"><span>Date:</span> ' . htmlspecialchars($row['available_date']) . '</div>';
            echo '</div>';
            echo '<form method="POST" action="select_bus.php">';
            echo '<input type="hidden" name="bus_id" value="' . htmlspecialchars($row['id']) . '">';
            echo '<input type="hidden" name="travel_date" value="' . htmlspecialchars($travel_date) . '">';
            echo '<button type="submit" class="select-button">Select</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p class="no-buses">No buses available for the selected route and date.</p>';
    }
    echo '</div>';
    $stmt->close();
} else {
    echo '<p class="error-message">Invalid request.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Buses</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .content-container {
            flex: 1;
           
            padding: 2rem;
            box-sizing: border-box;
        }

        .page-title {
            text-align: center;
            color: #333;
            font-size: 2rem;
           margin-top:61px;
        }

        .bus-card {
            width: 100%;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            border-radius: 10px;
            margin: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            padding: 1rem;
            box-sizing: border-box;
        }

        .bus-header {
            background-color: #0078D7;
            color: #fff;
            padding: 0.75rem;
            text-align: center;
            border-radius: 5px;
        }

        .bus-name {
            font-size: 1.25rem;
            font-weight: bold;
            margin: 0;
        }

        .bus-details {
            padding: 1rem 0;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .detail-item {
            font-size: 1rem;
            color: #555;
            display: flex;
            justify-content: space-between;
        }

        .detail-item span {
            font-weight: bold;
        }

        .select-button {
            padding: 1rem;
            background-color: #0078D7;
            color: #fff;
            border: none;
            text-align: center;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .select-button:hover {
            background-color: #0056b3;
        }

        .no-buses, .error-message {
            text-align: center;
            font-size: 1.125rem;
            color: #d9534f;
            margin-top: 1.25rem;
        }

        footer {
            width: 100%;
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: auto;
        }

        @media (max-width: 600px) {
            .content-container {
                padding: 1rem;
            }

            .bus-card {
                padding: 0.75rem;
            }

            .bus-header {
                font-size: 1rem;
                padding: 0.5rem;
            }

            .bus-name {
                font-size: 1.125rem;
            }

            .detail-item {
                font-size: 0.875rem;
            }

            .select-button {
                font-size: 0.875rem;
                padding: 0.75rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<?php include('../includes/footer.php'); ?>

</body>
</html>
