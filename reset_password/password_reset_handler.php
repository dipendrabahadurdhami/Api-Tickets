<?php
session_start();
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $newPassword, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Password reset successful. You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to reset password. Try again.";
        header("Location: reset_password.php?token=$token");
        exit();
    }
}
?>
