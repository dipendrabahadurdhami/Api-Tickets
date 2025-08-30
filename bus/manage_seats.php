<?php include('../includes/header.php'); ?>
<?php

include '../config.php';

// Ensure the user is logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "User not logged in.";
    exit();
}

// Check if bus_id is passed in the URL and store it in session
if (isset($_GET['bus_id'])) {
    $_SESSION['selected_bus_id'] = $_GET['bus_id']; // Store bus_id in session
}

// Use bus_id from session (if it's not set in the URL)
$bus_id = $_SESSION['selected_bus_id'] ?? null;
if (!$bus_id) {
    echo "Bus ID not specified.";
    exit();
}

// Fetch bus details
$stmt = $conn->prepare("SELECT * FROM buses WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $bus_id, $user_id);
$stmt->execute();
$bus_result = $stmt->get_result();
$bus = $bus_result->fetch_assoc();

// If the bus does not belong to the logged-in user, deny access
if (!$bus) {
    echo "Bus not found or does not belong to you.";
    exit();
}

// Fetch seats for the selected bus
$stmt = $conn->prepare("SELECT * FROM seats WHERE bus_id = ?");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$seats_result = $stmt->get_result();

$seats = [];
while ($seat = $seats_result->fetch_assoc()) {
    $seats[] = $seat;
}

// Check if a success message is set
$success_message = $_SESSION['success_message'] ?? null;
if ($success_message) {
    echo "<script>window.onload = function() { showSuccessMessage('$success_message'); }</script>";
    unset($_SESSION['success_message']); // Clear the message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bus Seats</title>
    <style>
        * {
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin-top: auto;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h2 {
            margin-top: auto;
            color: #2c3e50;
            font-size: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .seat-container {
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
        }

        .row {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            text-align: center;
            line-height: 40px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            color: white;
        }

        .available {
            background-color: #28a745;
        }

        .booked {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .selected {
            background-color: #ffc107;
        }

        .aisle {
            width: 50px;
        }

        .gap {
            width: 50px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 80%;
            max-width: 400px;
            z-index: 1000;
                height: 248px;
        }

        .modal.show {
            display: block;
        }

        .modal-header {
            font-size: 20px;
            color: green;
            margin-bottom: 15px;
        }

        .modal-content {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .modal button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal button:hover {
            background-color: #0056b3;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 10px;
            display: block;
            width: 100%;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .go-back {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 10px;
            display: block;
            width: 100%;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
            
        }

        .go-back:hover {
            background-color: #5a6268;
        }

        @media screen and (max-width: 768px) {
            h2 {
                font-size: 20px;
            }

            .seat {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            button {
                font-size: 14px;
                padding: 12px 18px;
            }
        }

        @media screen and (max-width: 480px) {
            body {
                padding: 10px;
            }

            h2 {
                font-size: 18px;
            }

            .seat {
                width: 35px;
                height: 35px;
                font-size: 12px;
            }

            button {
                font-size: 12px;
                padding: 10px 16px;
            }
        }
    </style>
    <script>
        function showSuccessMessage(message) {
            const modal = document.getElementById('successModal');
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = message;
            modal.classList.add('show');
        }

        function closeModal() {
            const modal = document.getElementById('successModal');
            modal.classList.remove('show');
        }

        function toggleSeatStatus(seatId) {
            const seat = document.getElementById(seatId);
            if (seat.classList.contains('available')) {
                seat.classList.remove('available');
                seat.classList.add('booked');
            } else if (seat.classList.contains('booked')) {
                seat.classList.remove('booked');
                seat.classList.add('available');
            }
        }

        function submitForm() {
            const updatedSeats = [];
            document.querySelectorAll('.seat').forEach(seat => {
                if (seat.classList.contains('booked') || seat.classList.contains('available')) {
                    updatedSeats.push({ seatId: seat.id, status: seat.classList.contains('available') ? 'available' : 'booked' });
                }
            });

            document.getElementById('updated_seats').value = JSON.stringify(updatedSeats);
            document.getElementById('seat_form').submit();
        }
    </script>
</head>
<body>


<!-- Seat Management -->
<div class="seat-container">
    <?php if (!empty($seats)): ?>
        <?php
        $upper_rows = array_slice($seats, 0, count($seats) - 5);
        $last_row = array_slice($seats, -5);
        $seats_per_row = 4;
        $counter = 0;

        foreach ($upper_rows as $seat):
            if ($counter % $seats_per_row == 0): ?>
                <div class="row">
            <?php endif; ?>

            <div class="seat <?php echo $seat['status'] === 'available' ? 'available' : 'booked'; ?>" 
                 id="<?php echo $seat['seat_number']; ?>" 
                 <?php echo $seat['status'] === 'available' ? "onclick=\"toggleSeatStatus('$seat[seat_number]')\"" : "style='cursor: not-allowed;'"; ?> >
                <?php echo $seat['seat_number']; ?>
            </div>

            <?php if (($counter + 1) % 2 == 0 && ($counter + 1) % $seats_per_row != 0): ?>
                <div class="gap"></div>
            <?php endif; ?>

            <?php $counter++;
            if ($counter % $seats_per_row == 0 || $counter == count($upper_rows)): ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="row">
        <?php foreach ($last_row as $seat): ?>
            <div class="seat <?php echo $seat['status'] === 'available' ? 'available' : 'booked'; ?>" 
                 id="<?php echo $seat['seat_number']; ?>" 
                 <?php echo $seat['status'] === 'available' ? "onclick=\"toggleSeatStatus('$seat[seat_number]')\"" : "style='cursor: not-allowed;'"; ?> >
                <?php echo $seat['seat_number']; ?>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No seats found for this bus.</p>
    <?php endif; ?>
</div>

<!-- Form to submit the seat updates -->
<form id="seat_form" method="POST" action="update_seat_status.php">
    <input type="hidden" id="updated_seats" name="updated_seats">
    <button type="button" onclick="submitForm()">Update Seat Status</button>
</form>

<!-- Go Back Button -->
<form method="GET" action="../bus/view_buses.php">
    <button type="submit">Go Back</button>
</form>

<!-- Success Message Modal -->
<div id="successModal" class="modal">
    <div class="modal-header">Success</div>
    <div id="modalContent" class="modal-content"></div>
    <button onclick="closeModal()">Close</button>
</div>

</body>
</html>
<?php include('../includes/footer.php'); ?>
