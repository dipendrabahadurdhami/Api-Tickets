<?php include('../includes/header.php'); ?>
<?php

require '../config.php';
require 'mail_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $resetToken = bin2hex(random_bytes(32));
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update reset token in database
        $updateQuery = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('sss', $resetToken, $tokenExpiry, $email);
        $updateStmt->execute();

        // Send email
        if (sendResetEmail($email, $resetToken)) {
            echo "<p class='success-message'>A reset link has been sent to your email.</p>";
        } else {
            echo "<p class='error-message'>Failed to send email. Try again later.</p>";
        }
    } else {
        echo "<p class='error-message'>Email not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Ensure html and body cover the entire viewport height */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; /* Stack elements vertically */
        }

        /* Main content area */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            height: 100%;
            min-height: 100vh; /* Ensure the body takes up at least the full height */
        }

        /* The forgot password box container */
        .forgot-password-container {
            margin-top: auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

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
            margin-top: 20px;
        }

        button:hover {
            background-color: #3578e5;
        }

        .error-message, .success-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        .success-message {
            margin-top:79px;
            color: green;
        }

        p {
            text-align: center;
            font-size: 14px;
        }

        a {
            color: #4a90e2;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Mobile Responsive */
        @media (max-width: 600px) {
            .forgot-password-container {
                padding: 20px;
            }
        }

        /* Footer Styling */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>

        <!-- Show messages if they exist -->
        <?php
        if (isset($error_message)) {
            echo "<p class='error-message'>" . $error_message . "</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success-message'>" . $success_message . "</p>";
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <button type="submit">Submit</button>
        </form>

        <p>Remembered your password? <a href="../users/login.php">Login</a></p>
        <p>Need an account? <a href="../users/signupp.php">Sign Up</a></p>
    </div>

    <!-- Footer Section -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
