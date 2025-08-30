<?php
// config.php should include your database connection
include('config.php');

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$is_logged_in) {
        // Redirect to login if not logged in
        echo "<script>window.location.href = 'users/login.php';</script>";
        exit();
    }

    // Handle review submission
    if (isset($_POST['rating'], $_POST['review'])) {
        $rating = $_POST['rating'];
        $review_text = $_POST['review'];

        // Insert the review into the database
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, rating, review_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $rating, $review_text);

        if ($stmt->execute()) {
            echo "<script>window.location.href = window.location.href;</script>";
            exit();
        } else {
            echo "<p>Error: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
}

// Fetch average rating and total reviews
$avg_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews";
$avg_data = mysqli_fetch_assoc(mysqli_query($conn, $avg_rating_sql));
$avg_rating = round($avg_data['avg_rating']);

$total_reviews_sql = "SELECT COUNT(*) AS total_reviews FROM reviews";
$total_reviews_data = mysqli_fetch_assoc(mysqli_query($conn, $total_reviews_sql));
$total_reviews = $total_reviews_data['total_reviews'];

$limit = 5; // Number of reviews per load
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review System</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            color: #333;
        }

        .containers {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            color: #0078d4;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Review Form Styles */
        .review-form {
            margin-bottom: 20px;
        }

        .review-form form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            resize: none;
            text-align: center;
            height: 80px;
        }

        button {
            padding: 12px 20px;
            background-color: #0078d4;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
        }

        button:hover {
            background-color: #005fa3;
        }

        /* Review Rating Styles */
        .review-rating {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .star {
            font-size: 28px;
            color: #d1d1d1;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star.selected {
            color: #ffc107;
        }

        /* Reviews Section Styles */
        .reviews-container {
            margin-top: 20px;
        }

        .review-card {
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f7f7f7;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .review-header {
            font-weight: bold;
            margin-bottom: 8px;
        }

        /* See More Button */
        #load-more {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #0078d4;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
        }

        #load-more:hover {
            background-color: #005fa3;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .review-form form {
                gap: 12px;
            }

            textarea {
                height: 60px;
            }

            .star {
                font-size: 24px;
            }

            button {
                padding: 10px 18px;
            }

            h1, h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="containers">
        <!-- Submit Review Section -->
        <h1>Submit Your Review</h1>
        <div class="review-form">
            <form method="POST" id="review-form">
                <label for="rating">Rating (1-5):</label>
                <div class="review-rating" id="star-rating">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>
                <input type="number" id="rating" name="rating" min="1" max="5" required style="display:none;">
                <label for="review">Review:</label>
                <textarea id="review" name="review" rows="4" required></textarea>
                <button type="submit" id="submit-review">Submit Review</button>
            </form>
        </div>

        <!-- Average Rating -->
        <h2>Total Reviews (<?php echo $total_reviews; ?>)</h2>
        <div class="review-rating">
            <?php for ($i = 0; $i < $avg_rating; $i++)
                echo "<span class='star selected'>&#9733;</span>"; ?>
            <?php for ($i = $avg_rating; $i < 5; $i++)
                echo "<span class='star'>&#9733;</span>"; ?>
        </div>

        <!-- Reviews Section -->
        <h2>All Reviews</h2>
        <div class="reviews-container" id="reviews-container">
            <!-- Reviews loaded dynamically -->
        </div>

        <!-- See More Reviews Button -->
        <button id="load-more" data-page="1">See More Reviews</button>
    </div>

    <script>
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.dataset.value;
                document.getElementById('rating').value = rating;
                document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
                for (let i = 0; i < rating; i++) {
                    document.querySelectorAll('.star')[i].classList.add('selected');
                }
            });
        });

        document.getElementById('review-form').addEventListener('submit', function (e) {
            if (!<?php echo json_encode($is_logged_in); ?>) {
                e.preventDefault();
                window.location.href = 'users/login.php';
            }
        });

        function loadReviews(page = 1) {
            const reviewsContainer = document.getElementById('reviews-container');
            const loadMoreButton = document.getElementById('load-more');

            fetch(`load_reviews.php?page=${page}&limit=5`)
                .then(response => response.text())
                .then(data => {
                    if (data.trim().length) {
                        reviewsContainer.innerHTML += data;
                        loadMoreButton.dataset.page = page + 1;
                    } else {
                        loadMoreButton.style.display = 'none';
                    }
                });
        }

        document.getElementById('load-more').addEventListener('click', () => {
            const page = parseInt(document.getElementById('load-more').dataset.page);
            loadReviews(page);
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadReviews(1);
        });
    </script>
</body>

</html>