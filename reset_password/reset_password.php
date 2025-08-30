<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate token
    $query = "SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Update password
        $updateQuery = "UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('ss', $newPassword, $token);
        $updateStmt->execute();

        echo "<p>Password reset successful.</p>";
    } else {
        echo "<p>Invalid or expired token.</p>";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "<p>Invalid request.</p>";
    exit;
}
?>

<?php include('../includes/header.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General styles */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
        }

        /* Centering the content */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Styling the reset password container */
        .reset-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
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

        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="password"]:focus {
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

        @media (max-width: 600px) {
            .reset-container {
                margin-top: 75px;
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
    <div class="reset-container">
        <h2>Reset Password</h2>

        <!-- Displaying messages -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='error-message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>

        <!-- Reset password form -->
        <form action="" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>

        <p>Remembered your password? <a href="../users/login.php">Login here</a></p>
    </div>

    <!-- Footer Section -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
