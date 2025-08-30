<?php
include('config.php');
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 5;
$start = ($page - 1) * $limit;

$sql = "SELECT r.*, u.name AS user_name FROM reviews r JOIN users u ON r.user_id = u.user_id ORDER BY r.created_at DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

while ($review = $result->fetch_assoc()) {
    echo "<div class='review-card'>";
    echo "<div class='review-header'>";
    echo "<span>" . htmlspecialchars($review['user_name'], ENT_QUOTES, 'UTF-8') . "</span>";
    echo "</div>";
    echo "<div class='review-rating'>";
    for ($i = 0; $i < $review['rating']; $i++) echo "<span class='star selected'>&#9733;</span>";
    for ($i = $review['rating']; $i < 5; $i++) echo "<span class='star'>&#9733;</span>";
    echo "</div>";
    echo "<p class='review-text'>" . htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8') . "</p>";
    echo "</div>";
}
$stmt->close();
$conn->close();
?>
