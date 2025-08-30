<?php

include('../includes/header.php');
?>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        color: #333;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .signup-container {
        margin-top:200px;
        background-color: #fff;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        margin-bottom: 20px;
    }

    h2 {
        text-align: center;
        color: #444;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    input[type="text"],
    form input[type="tel"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="text"]:focus,
    input[type="tel"]:focus,
    input[type="email"]:focus,
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

    .error-message {
        color: red;
        text-align: center;
        margin-bottom: 10px;
    }

    .success-message {
        color: green;
        text-align: center;
        margin-bottom: 10px;
    }

    @media (max-width: 600px) {
        .signup-container {
            margin-top:450px;
            padding: 20px;
        }
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        border-radius: 10px;
        width: 90%;
        max-width: 400px;
        text-align: center;
    }

    .modal-content h2 {
        color: #4a90e2;
        margin-bottom: 15px;
    }

    .modal-content p {
        color: #333;
        margin-bottom: 20px;
    }

    .modal-content button {
        background-color: #4a90e2;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-content button:hover {
        background-color: #3578e5;
    }

    footer {
        margin-top: auto; /* Push footer to the bottom */
    }
</style>

<div class="signup-container">
    <h2>Create Your Account</h2>

    <!-- Display error or success messages -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("successModal").style.display = "block";
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form action="signup_handler.php" method="POST">
        <div class="form-group">
            <input type="text" name="name" placeholder="Full Name" required>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="form-group">
            <input type="tel" name="phone" placeholder="Phone Number" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password (min 8 characters)" required>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<!-- Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <h2>Check Your Email</h2>
        <p>We have sent a verification link to your email. Please verify your email to proceed.</p>
        <button onclick="redirectToLogin()">Go to Login</button>
    </div>
</div>

<script>
   
    function redirectToLogin() {
        window.location.href = 'login.php';
    }

    // Close the modal (optional)
    window.onclick = function (event) {
        let modal = document.getElementById("successModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
</script>

<?php include('../includes/footer.php'); ?>
