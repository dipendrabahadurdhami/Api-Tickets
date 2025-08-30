<?php include('../includes/header.php'); ?>
<?php
session_start();
include '../config.php';

// Retrieve the selected bus ID from the session
$bus_id = isset($_SESSION['selected_bus_id']) ? $_SESSION['selected_bus_id'] : null;

// If a bus is selected, fetch seat details for the bus
if ($bus_id) {
    $stmt = $conn->prepare("SELECT * FROM seats WHERE bus_id = ?");
    $stmt->bind_param("i", $bus_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $seats = [];
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
}

// Fetch available buses for the selection dropdown
$bus_query = "SELECT * FROM buses";
$bus_result = $conn->query($bus_query);

// Fetch cost per seat for selected bus
$stmt = $conn->prepare("SELECT cost FROM buses WHERE id = ?");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$cost = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cost = $row['cost'];
} else {
    echo "<p>Error: Could not fetch cost for the selected bus.</p>";
    exit();
}
// Define the cost per seat
$cost_per_seat = $cost;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats</title>
    <style>
         body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    color: #333;
    margin-top: 60px;
    padding: 0;

    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 100vh;
}

h2,
h3 {
    text-align: center;
    color: #2c3e50;
}

.seat-container {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 9px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    width:93%;
    max-width: 622px;
    text-align: center;
}

.row {
    display: flex;
    justify-content: center;
    margin-bottom: 10px;
}

.seat {
    width: 60px;
    height: 40px;
    margin: 5px;
    text-align: center;
    line-height: 40px;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    color: white;
    transition: transform 0.2s ease;
}

.seat:hover {
    transform: scale(1.1);
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
    width: 50px; /* Small gap between two seat groups */
}

.form-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 9px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 93%;
    max-width: 622px;
    margin: -50px auto;
    text-align: left;
    margin-bottom:-1px;
}

.form-container h3 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}

label {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
    display: block;
}

input[type="text"], 
input[type="email"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    margin-bottom: 15px;
}

input[type="text"]:focus, 
input[type="email"]:focus {
    border-color: #4a90e2;
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    background-color: #4a90e2;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #3578e5;
}

p {
    text-align: center;
    font-size: 14px;
    color: #333;
    margin-top: 10px;
}

/* Pop-up Styles */
.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 99;
}

.popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 100;
    width: 90%;
    max-width: 400px;
    text-align: center;
}

.popup h3 {
    margin-bottom: 20px;
    color: #2c3e50;
}

.popup .close-btn {
    background-color: #e74c3c;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
}

.popup button {
    background-color: #28a745;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
}

.popup button:hover {
    background-color: #218838;
}

/* Media Queries */
@media (max-width: 600px) {
    .seat-container,
    .form-container {
        padding: 20px;
    }
    .popup {
        width: 95%;
    }
}


    </style>
    <script>
        const costPerSeat = <?php echo $cost_per_seat; ?>;

        // Toggle the seat selection
        function toggleSeatSelection(seatId) {
            const seat = document.getElementById(seatId);
            if (seat.classList.contains('available')) {
                seat.classList.remove('available');
                seat.classList.add('selected');
            } else if (seat.classList.contains('selected')) {
                seat.classList.remove('selected');
                seat.classList.add('available');
            }

            updateTotalCost();
        }

        // Update the total cost dynamically
        function updateTotalCost() {
            const selectedSeats = document.querySelectorAll('.selected').length;
            const totalCost = selectedSeats * costPerSeat;
            document.getElementById('total_cost_display').innerText = 'RS ' + totalCost;
        }

        // Submit the form and show booking details in the pop-up
        function submitForm() {
            const selectedSeats = [];
            document.querySelectorAll('.selected').forEach(seat => {
                selectedSeats.push(seat.id);
            });

            const name = document.getElementById('name').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const boardingPoint = document.getElementById('boarding_point').value.trim();
            const email = document.getElementById('email').value.trim();

            // Validation checks
            if (!name) {
                alert("Please enter your name.");
                return;
            }
            if (!phone) {
                alert("Please enter your phone number.");
                return;
            }
            if (!boardingPoint) {
                alert("Please enter your boarding point.");
                return;
            }
            if (!email) {
                alert("Please enter your email address.");
                return;
            }
            if (selectedSeats.length === 0) {
                alert("Please select at least one seat.");
                return;
            }

            // Set hidden input value for selected seats
            document.getElementById('selected_seats').value = selectedSeats.join(',');

            // Set booking details in the popup
            document.getElementById('popup_name').innerText = name;
            document.getElementById('popup_phone').innerText = phone;
            document.getElementById('popup_boarding_point').innerText = boardingPoint;
            document.getElementById('popup_email').innerText = email;
            document.getElementById('popup_seats').innerText = selectedSeats.join(', ');
            document.getElementById('popup_cost').innerText = '' + (selectedSeats.length * costPerSeat);

            // Show the popup
            document.getElementById('popup').style.display = 'block';
            document.getElementById('popup-overlay').style.display = 'block';
        }

        // Close the pop-up
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('popup-overlay').style.display = 'none';
        }

        // Confirm the booking and submit the form
        function confirmBooking() {
            document.getElementById('seat_form').submit();
        }
    </script>
</head>

<body>
    <?php if ($bus_id): ?>
        <div class="seat-container">
            <?php
            $upper_rows = array_slice($seats, 0, count($seats) - 5); // Upper rows (excluding the last row)
            $last_row = array_slice($seats, -5); // Last row (5 seats)
            $seats_per_row = 4; // Seats per row for upper rows
            $counter = 0;

            // Display upper rows with gaps after every 2 seats
            foreach ($upper_rows as $seat):
                if ($counter % $seats_per_row == 0): ?>
                    <div class="row">
                <?php endif; ?>

                <div class="seat <?php echo $seat['status'] === 'available' ? 'available' : 'booked'; ?>"
                    id="<?php echo $seat['seat_number']; ?>" <?php echo $seat['status'] === 'available' ? "onclick=\"toggleSeatSelection('$seat[seat_number]')\"" : "style='cursor: not-allowed;'"; ?>>
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

            <!-- Display last row without gaps -->
            <div class="row">
            <?php foreach ($last_row as $seat): ?>
                <div class="seat <?php echo $seat['status'] === 'available' ? 'available' : 'booked'; ?>"
                    id="<?php echo $seat['seat_number']; ?>" <?php echo $seat['status'] === 'available' ? "onclick=\"toggleSeatSelection('$seat[seat_number]')\"" : "style='cursor: not-allowed;'"; ?>>
                    <?php echo $seat['seat_number']; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        </div>

        <div class="form-container">
            <h2>Enter Passenger Details</h2>
            <form id="seat_form" method="POST" action="booking_summary.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="boarding_point">Boarding Point:</label>
                    <input type="text" id="boarding_point" name="boarding_point" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>

                <input type="hidden" id="selected_seats" name="selected_seats">

                <button type="button" onclick="submitForm()">Proceed</button>
            </form>
        </div>

        <div class="popup-overlay" id="popup-overlay"></div>
        <div class="popup" id="popup">
            <h3>Booking Summary</h3>
            <p><strong>Name:</strong> <span id="popup_name"></span></p>
            <p><strong>Phone:</strong> <span id="popup_phone"></span></p>
            <p><strong>Boarding Point:</strong> <span id="popup_boarding_point"></span></p>
            <p><strong>Email:</strong> <span id="popup_email"></span></p>
            <p><strong>Seats:</strong> <span id="popup_seats"></span></p>
            <p><strong>Total Cost:</strong> RS <span id="popup_cost"></span></p>
            <button onclick="confirmBooking()">Confirm Booking</button>
            <button class="close-btn" onclick="closePopup()">Close</button>
        </div>
    <?php else: ?>
        <p>No bus selected. Please go back and choose a bus.</p>
    <?php endif; ?>
</body>

</html>
<?php include('../includes/footer.php'); ?>
