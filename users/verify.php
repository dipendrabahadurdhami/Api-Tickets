<?php
session_start();
include('../config.php'); 

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM pending_users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        $insertStmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role, verified) VALUES (?, ?, ?, ?, ?, 1)");
        $insertStmt->bind_param("sssss", $user['name'], $user['email'], $user['phone'], $user['password'], $user['role']);
        if ($insertStmt->execute()) {
            
            $deleteStmt = $conn->prepare("DELETE FROM pending_users WHERE token = ?");
            $deleteStmt->bind_param("s", $token);
            $deleteStmt->execute();

            
            $_SESSION['success_message'] = "Your account has been verified successfully!";
            echo "<script>window.location.href = 'login.php';</script>";
            exit();
        } else {
            $_SESSION['error_message'] = "Verification failed. Please try again later.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid or expired verification link.";
    }
} else {
    $_SESSION['error_message'] = "No token provided.";
}


echo "<script>window.location.href = 'signup.php';</script>";
exit();
?>
