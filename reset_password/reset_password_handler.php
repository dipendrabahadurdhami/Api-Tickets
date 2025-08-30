<?php
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header("Location: reset_password.php?token=$token");
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $token);
        $updateStmt->execute();

        $_SESSION['success_message'] = "Password successfully reset.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid or expired reset token.";
        header("Location: login.php");
        exit();
    }
}
?>
