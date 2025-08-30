<?php include('../includes/header.php'); ?>
<?php
session_start();
include '../config.php';

// Fetch user_id from session
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Fetch all buses associated with the user
    $stmt = $conn->prepare("SELECT * FROM buses WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $buses = [];
    while ($row = $result->fetch_assoc()) {
        $buses[] = $row;
    }
} else {
    echo "User not logged in.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Your Buses</title>
    <style>
        /* Global styles */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Bus list centering */
        .bus-lists {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px; /* Space between the bus items */
            margin-top: 20px;
        }

        /* List styling */
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .bus-item {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            width: 280px; /* Set width for better control */
        }

        .bus-item a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        .bus-item a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Hover effects */
        .bus-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* No buses available message */
        p {
            font-size: 16px;
            color: #7f8c8d;
            text-align: center;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            body {
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }

            .bus-item {
                padding: 15px;
                width: 90%; /* Ensure it's responsive on tablets */
            }

            .bus-item a {
                font-size: 16px;
            }
        }

        @media screen and (max-width: 480px) {
            h2 {
                font-size: 18px;
            }

            .bus-item a {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <h2>Your Buses</h2>

    <div class="bus-lists">
        <?php if (!empty($buses)): ?>
            <ul>
                <?php foreach ($buses as $bus): ?>
                    <li class="bus-item">
                        <a href="manage_seats.php?bus_id=<?php echo $bus['id']; ?>">Manage Seats for Bus: <?php echo $bus['bus_name']; ?> (Bus ID: <?php echo $bus['id']; ?>)</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No buses available buses.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include('../includes/footer.php'); ?>
