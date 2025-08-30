<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_seats = json_decode($_POST['selected_seats'], true);
    $bus_id = $_SESSION['selected_bus_id'];
    $travel_date = $_SESSION['travel_date'];
    

    foreach ($selected_seats as $seat) {
        $stmt = $conn->prepare("UPDATE seats SET status = 'booked' WHERE bus_id = ? AND seat_number = ?");
        $stmt->bind_param("is", $bus_id, $seat);
        $stmt->execute();
    }

    // Redirect to booking summary
    header("Location: booking_summary.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
