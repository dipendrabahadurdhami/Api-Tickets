<?php include('../includes/header.php'); ?>
<?php
// Include the database connection
include('../config.php');





if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apitickets - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            width: 100%;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h2 {
            font-size: 28px;
            color: #4a90e2;
        }

        .profile-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .profile-details .detail {
            flex: 1 1 45%;
            margin-bottom: 15px;
        }

        .profile-details .detail label {
            font-weight: bold;
        }

        .profile-details .detail p {
            margin: 5px 0;
        }

        .btn-edit {
            display: block;
            width: 100%;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-edit:hover {
            background-color: #3578e5;
        }

        @media (max-width: 600px) {
            .profile-details .detail {
                flex: 1 1 100%;
            }
        }

    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <h2>Your Profile</h2>
        <p>Manage your personal details and settings</p>
    </div>

    <div class="profile-details">
        <!-- Display User Details -->
        <div class="detail">
            <label for="name">Full Name</label>
            <p><?php echo htmlspecialchars($user['name']); ?></p>
        </div>
        <div class="detail">
            <label for="email">Email</label>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <div class="detail">
            <label for="phone">Phone Number</label>
            <p><?php echo htmlspecialchars($user['phone']); ?></p>
        </div>
        <div class="detail">
            <label for="role">Role</label>
            <p><?php echo htmlspecialchars($user['role']); ?></p>
        </div>
        <div class="detail">
            <label for="verified">Verification Status</label>
            <p><?php echo $user['is_verified'] == 0 ? 'Verified' : 'Not Verified'; ?></p>
        </div>
    </div>

    <!-- Edit Profile Button -->
    <a href="edit_profile.php" class="btn-edit">Edit Profile</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<?php include('../includes/footer.php'); ?>
</body>
</html>

