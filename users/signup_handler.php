<?php
session_start();
include('../config.php');
include 'mail_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';


    if (empty($name) || empty($email) || empty($password)|| empty($phone)) {
        $_SESSION['error_message'] = "Please fill out all fields.";
        echo "<script>window.location.href = 'signup.php';</script>";
        exit();
    }

   
    $stmt = $conn->prepare("SELECT id FROM pending_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
       
        $user = $result->fetch_assoc();
        $verificationToken = bin2hex(random_bytes(16));

       
        $updateStmt = $conn->prepare("UPDATE pending_users SET token = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $verificationToken, $email);
        $updateStmt->execute();
    } else {
        // If the email doesn't exist, insert the new user into the pending_users table
        $verificationToken = bin2hex(random_bytes(16)); 
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user'; 
      
        $query = $conn->prepare("INSERT INTO pending_users (name, email, phone, password, role, token) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssss", $name, $email, $phone, $hashedPassword, $role, $verificationToken);
        $query->execute();
    }

  
    if (sendVerificationEmail($email, $verificationToken)) {
        // Success: email sent and user inserted/updated in pending table
        $_SESSION['success_message'] = "Signup successful! Please verify your email.Note: must check on spam also ";
        echo "<script>window.location.href = 'login.php';</script>";
        exit();
    } else {
       
        $_SESSION['error_message'] = "Verification email failed to send. Please try again.";
        echo "<script>window.location.href = 'signup.php';</script>";
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid request method.";
    echo "<script>window.location.href = 'signup.php';</script>";
    exit();
}
