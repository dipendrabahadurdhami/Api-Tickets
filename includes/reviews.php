<?php
session_start();
include 'config.php'; // Include database connection

// Fetch the latest 5 reviews
$reviews = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 5");

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review = htmlspecialchars($_POST['review']);

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, review) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $rating, $review);
    $stmt->execute();
    header("Location: review.php");
    exit;
}

// Handle like button click (AJAX)
if (isset($_POST['like_review'])) {
    $review_id = $_POST['review_id'];
    $conn->query("UPDATE reviews SET likes = likes + 1 WHERE id = $review_id");
    echo json_encode(["success" => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | ApiTickets</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }
        .review-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .review-item {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .review-item:last-child {
            border-bottom: none;
        }
        .review-item h3 {
            margin: 5px 0;
        }
        .stars {
            color: #FFD700;
        }
        .like-btn {
            cursor: pointer;
            color: blue;
            font-size: 14px;
        }
        .like-btn:hover {
            color: red;
        }
        .see-more {
            display: block;
            margin-top: 10px;
            color: #004099;
            cursor: pointer;
        }
        .see-more:hover {
            text-decoration: underline;
        }
        .review-form {
            margin-top: 20px;
            display: <?php echo isset($_SESSION['user_id']) ? 'block' : 'none'; ?>;
        }
        .login-alert {
            display: <?php echo isset($_SESSION['user_id']) ? 'none' : 'block'; ?>;
            margin-top: 20px;
            color: red;
        }
        @media (max-width: 600px) {
            .review-container {
                width: 95%;
            }
        }
    </style>
</head>
<body>

    <div class="review-container">
        <h2>Customer Reviews</h2>

        <!-- Reviews List -->
        <div id="review-list">
            <?php while ($row = $reviews->fetch_assoc()): ?>
                <div class="review-item">
                    <h3>User #<?php echo $row['user_id']; ?></h3>
                    <p class="stars"><?php echo str_repeat("⭐", $row['rating']); ?></p>
                    <p><?php echo $row['review']; ?></p>
                    <p>
                        <span class="like-btn" data-id="<?php echo $row['id']; ?>">❤️ Like (<?php echo $row['likes']; ?>)</span>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- See More Option -->
        <span class="see-more">See More Reviews...</span>

        <!-- Login Alert -->
        <div class="login-alert">
            <p><a href="login.php">Login</a> to submit your review.</p>
        </div>

        <!-- Review Form -->
        <form method="post" class="review-form">
            <h3>Write a Review</h3>
            <label>Rating:</label>
            <select name="rating" required>
                <option value="5">⭐ ⭐ ⭐ ⭐ ⭐</option>
                <option value="4">⭐ ⭐ ⭐ ⭐</option>
                <option value="3">⭐ ⭐ ⭐</option>
                <option value="2">⭐ ⭐</option>
                <option value="1">⭐</option>
            </select>
            <br>
            <textarea name="review" placeholder="Write your review..." required></textarea><br>
            <button type="submit">Submit Review</button>
        </form>
    </div>

    <script>
        $(document).ready(function(){
            $(".like-btn").click(function(){
                var reviewId = $(this).data("id");
                $.post("review.php", { like_review: true, review_id: reviewId }, function(response){
                    location.reload();
                }, "json");
            });

            $(".see-more").click(function(){
                alert("Load more reviews here.");
            });
        });
    </script>

</body>
</html>
