<?php
session_start();
include '../config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Check if updated seats data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updated_seats'])) {
    // Decode the JSON data for updated seats
    $updatedSeats = json_decode($_POST['updated_seats'], true);
    
    // Check if there are any updated seats
    if (empty($updatedSeats)) {
        echo "No seats to update.";
        exit();
    }

    // Loop through the updated seats and update them in the database
    foreach ($updatedSeats as $seat) {
        $seatId = $seat['seatId'];
        $status = $seat['status'];
        
        // Prepare the update query
        $stmt = $conn->prepare("UPDATE seats SET status = ? WHERE bus_id = ? AND seat_number = ?");
        $stmt->bind_param("sis", $status, $_SESSION['selected_bus_id'], $seatId); // "s" for string, "i" for integer
        
        // Check if the query executed successfully
        if (!$stmt->execute()) {
            // If there's an error with updating the seat, display it
            echo "Error updating seat $seatId: " . $stmt->error;
            exit();
        }
    }
    
    // Set success message in session
    $_SESSION['success_message'] = "Seat status updated successfully!";

    // Redirect back to the seat management page using bus_id from session
    $bus_id = $_SESSION['selected_bus_id'] ?? null;
    if ($bus_id) {
        header("Location: manage_seats.php?bus_id=" . $bus_id);
    } else {
        header("Location: manage_buses.php"); // Fallback if no bus_id found
    }
    exit();
} else {
    echo "No data to update.";
}

$conn->close();
?>
