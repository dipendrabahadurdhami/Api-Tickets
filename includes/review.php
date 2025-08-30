<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApiTickets Reviews</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .review-container {
            max-width: 1200px;
            width: 90%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .stars {
            font-size: 25px;
            cursor: pointer;
        }
        .star {
            color: white;
            text-shadow: 0 0 2px black;
        }
        .star.selected {
            color: #FFD700;
        }
        textarea {
            width: 90%;
            height: 100px;
            margin-top: 10px;
        }
        button {
            margin-top: 10px;
            padding: 10px;
            background: #004099;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #002766;
        }
        .reviews-list {
            margin-top: 40px;
            padding: 10px;
            background: #e0e0e0;
            border-radius: 5px;
        }
        .review-item {
            margin-bottom: 20px;
            padding: 10px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="review-container">
        <h2>Rate and Review ApiTickets</h2>
        <div class="stars" id="rating-stars">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <input type="hidden" id="rating-value" value="0">
        <textarea id="review-text" placeholder="Write your review..."></textarea><br>
        <button id="submit-review">Submit Review</button>
    </div>

    <div class="reviews-list">
        <h3>Recent Reviews</h3>
        <?php
        // Include database connection
        include('config.php');

        // Query to fetch reviews from database
        $query = "SELECT * FROM reviews ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='review-item'>";
                echo "<strong>User " . htmlspecialchars($row['user_id']) . "</strong> rated <strong>" . $row['rating'] . " stars</strong>";
                echo "<p>" . htmlspecialchars($row['review']) . "</p>";
                echo "<small>Reviewed on: " . $row['created_at'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
        ?>
    </div>

    <script>
    $(document).ready(function(){
        // Rating functionality
        $('.star').click(function(){
            let rating = $(this).data('value');
            $('#rating-value').val(rating);
            $('.star').removeClass('selected');
            $('.star').each(function(){
                if($(this).data('value') <= rating) {
                    $(this).addClass('selected');
                }
            });
        });

        // Submit Review
        $('#submit-review').click(function(){
            let rating = $('#rating-value').val();
            let review = $('#review-text').val();

            if(rating == 0 || review.trim() === "") {
                alert("Please select a rating and enter a review.");
                return;
            }

            // Check if the user is logged in via session variable
            $.ajax({
                url: 'check_login.php', // A small PHP script that checks if the user is logged in
                type: 'GET',
                success: function(response) {
                    if(response === "not_logged_in") {
                        window.location.href = 'login.php'; // Redirect to login if not logged in
                    } else {
                        // Send review data via AJAX if logged in
                        $.ajax({
                            url: 'submit_review.php', 
                            type: 'POST',
                            data: {
                                rating: rating,
                                review: review
                            },
                            success: function(response) {
                                alert("Review submitted successfully!");
                                $('#rating-value').val(0);
                                $('#review-text').val('');
                                $('.star').removeClass('selected');
                                location.reload(); // Reload page to show updated reviews
                            },
                            error: function(xhr, status, error) {
                                alert("There was an error submitting your review. Please try again.");
                            }
                        });
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
