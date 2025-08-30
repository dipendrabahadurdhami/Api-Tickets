<?php
session_start(); // Start session to store bus_id
include '../config.php';

// Check if bus_id and travel_date are passed through the form
if (isset($_POST['bus_id']) && isset($_POST['travel_date'])) {
    // Store the selected bus_id and travel_date in the session
    $_SESSION['selected_bus_id'] = $_POST['bus_id'];
    $_SESSION['travel_date'] = $_POST['travel_date'];

  
    
    // Redirect to the seat selection page after selecting a bus
   if (!isset($_SESSION['user_id'])) {
    // Redirect to login.php if not logged in
    header("Location: ../users/login.php");
    exit;
}

// If logged in, proceed to seat_selection.php
    header("Location: seat_select.php");
    exit;
    exit();
} else {
    echo "No bus selected.";
}
?>
