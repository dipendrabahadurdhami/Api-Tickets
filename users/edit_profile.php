<?php include('../includes/header.php'); ?>
<?php
// Include the database connection
include('../config.php');

// Start the session
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}


$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $update_query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('sssi', $name, $email, $phone, $user_id);
    $update_stmt->execute();
    header('Location: profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Apitickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        .edit-profile-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            margin-bottom: 15px;
        }

        .btn-save {
            width: 100%;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-save:hover {
            background-color: #3578e5;
        }
    </style>
</head>
<body>

<div class="edit-profile-container">
    <h2>Edit Profile</h2>
    <form action="edit_profile.php" method="POST">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <button type="submit" class="btn-save">Save Changes</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php include('../includes/footer.php'); ?>
</body>
</html>
