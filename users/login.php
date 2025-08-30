<?php include('../includes/header.php'); ?>
<?php
session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApiTickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
      
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; 
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
            min-height: 100vh; 
        }

     
        .login-container {
            margin-top:100px;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            width:90%;
            margin-top: 20px;
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

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="email"]:focus, input[type="password"]:focus {
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

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
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
        .login-container {
                margin-top:90px;
                padding: 20px;
            }

        @media (max-width: 600px) {
            .login-container {
                margin-top:75px;
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
            margin-top: auto; /* Ensures footer is pushed to the bottom */
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login to Your Account</h2>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form method="POST" action="login_handler.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="signup_user.php">Sign Up</a></p>
        <p>Forget Password ? <a href="../reset_password/forgot_password.php">click here to Reset password</a></p>
    </div>
    


    <!-- Footer Section -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
