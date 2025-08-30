
<?php
session_start(); // Ensure session is started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You Must Login First - Sajilo Ticket</title>
    <link rel="stylesheet" href="path_to_your_css_file.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .message-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .message-container h2 {
            color: #333;
        }
        .message-container p {
            color: #555;
        }
        .btn-login {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-login:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h2>You must log in first</h2>
        <p>In order to book a ticket, you need to be logged in. Please log in to continue.</p>
        <a href="../users/login.php" class="btn-login">Login</a>
    </div>
</body>
</html>
