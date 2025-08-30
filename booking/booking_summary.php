<?php
include '../config.php';
include '../users/mail_functions.php'; 
session_start(); 

// Check required session variables
if (!isset($_SESSION['selected_bus_id']) || !isset($_SESSION['user_id'])) {
    echo "Error: Required session data is missing. Please start the booking process again.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['selected_seats']) && !empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['boarding_point'])) {
        $selectedSeats = explode(',', $_POST['selected_seats']);
        $name = htmlspecialchars($_POST['name']);
        $phone = htmlspecialchars($_POST['phone']);
        $email = !empty($_POST['email']) ? htmlspecialchars($_POST['email']) : 'N/A';
        $boardingPoint = htmlspecialchars($_POST['boarding_point']); // New field for boarding point

        if (count($selectedSeats) > 0) {
            $bus_id = $_SESSION['selected_bus_id'];
            $user_id = $_SESSION['user_id'];

            // Fetch bus cost
            $stmt = $conn->prepare("SELECT cost FROM buses WHERE id = ?");
            $stmt->bind_param("i", $bus_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $cost = $row['cost'];
                $stmt->close();
            } else {
                echo "Error: Could not fetch cost for the selected bus.";
                exit();
            }

            $totalCost = $cost * count($selectedSeats);
            $bookingDate = date('Y-m-d H:i:s');
            $selectedSeatsString = implode(',', $selectedSeats);

            // Insert booking details into the database
            $stmt = $conn->prepare("INSERT INTO bookings (bus_id, seat_no, name, phone, email, boarding_point, booking_date, total_cost) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssssi", $bus_id, $selectedSeatsString, $name, $phone, $email, $boardingPoint, $bookingDate, $totalCost);
            if ($stmt->execute()) {
                $booking_id = $stmt->insert_id;
                $stmt->close();

                // Update seat statuses
                foreach ($selectedSeats as $seat) {
                    $updateSeatStmt = $conn->prepare("UPDATE seats SET status = 'booked' WHERE bus_id = ? AND seat_number = ?");
                    $updateSeatStmt->bind_param("is", $bus_id, $seat);
                    $updateSeatStmt->execute();
                    $updateSeatStmt->close();
                }
                $message = "Your booking (Seats: $selectedSeatsString) has been successfully completed for Bus ID: $bus_id.";
                $notificationStmt = $conn->prepare("INSERT INTO notifications (user_id, booking_id, message, is_read, created_at) VALUES (?, ?, ?, 0, ?)");
                $createdAt = date('Y-m-d H:i:s');
                $notificationStmt->bind_param("iiss", $user_id, $booking_id, $message, $createdAt);
                $notificationStmt->execute();
                $notificationStmt->close();

                // Fetch bus details
                $stmt = $conn->prepare("SELECT id, start_location, end_location, bus_name, departure_time, cost, available_date FROM buses WHERE id = ?");
                $stmt->bind_param("i", $bus_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $busDetails = $result->fetch_assoc();

                    $emailBody = "
Booking Confirmation

Dear {$name},

Your booking has been successfully completed. Below are your booking details:

Bus Information:
- Bus Name: {$busDetails['bus_name']}
- Route: {$busDetails['start_location']} to {$busDetails['end_location']}
- Departure Time: {$busDetails['departure_time']}
- Available Date: {$busDetails['available_date']}
- Seats: {$selectedSeatsString}
- Boarding Point: {$boardingPoint}
- Total Cost: {$totalCost}

Thank you for booking with us!!!
for inquiry: +977-9809461534

Best Regards,
ApiTickets
";

                    // Send confirmation email
                    $subject = "Booking Confirmation - Bus ID: {$busDetails['id']}";
                    if (sendBookingEmail($email, $subject, $emailBody)) {
                    } else {
                        echo "Error: Email could not be sent.";
                    }
                } else {
                    echo "Error: Bus details could not be retrieved.";
                }

                // Unset session and redirect
                unset($_SESSION['selected_bus_id']);
                header('Location: ../index.php');
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error: No seats selected. Please try again.";
        }
    } else {
        echo "Error: Missing required form fields.";
    }
} else {
    echo "Error: Invalid request method.";
}

?>


<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  
function sendBookingEmail($email, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.apitickets.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'info@apitickets.com';
        $mail->Password = 'apitickets@123'; // 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465;

        // Email content
        $mail->setFrom('info@apitickets.com', 'API Tickets'); 
        $mail->addAddress($email); 
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send email
        if ($mail->send()) {
            return true;
        } else {
            throw new Exception('Email could not be sent.');
        }
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
?>