<?php
session_start();
include '../config.php'; 
$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare and execute query to check user credentials
    $stmt = $conn->prepare("SELECT user_id, name, password, role, verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
       
        if ($user['verified'] == 1) {
           
            if (password_verify($password, $user['password'])) { 
               
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                
                if ($user['role'] === 'admin') {
                    $_SESSION['message'] = "Welcome Admin!";
                    header("Location: ../index.php");
                } else {
                    $_SESSION['message'] = "Login successful! Welcome, " . htmlspecialchars($user['name']) . ".";
                    header("Location: ../index.php");
                }
                exit();
            } else {
               
                $_SESSION['error_message'] = "Invalid email or password.";
            }
        } else {
            
            $_SESSION['error_message'] = "Please verify your email to log in.";
        }
    } else {
        // User not found
        $_SESSION['error_message'] = "Invalid email or password.";
    }

   
    header("Location: ../users/login.php");
    exit();
} else {
  
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: ../users/login.php");
    exit();
}
?>
