<?php
include '../config.php'; // Database connection

// Check if the search query is provided
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Fetch matching locations from the routes table
    $stmt = $conn->prepare("SELECT DISTINCT start_location FROM routes WHERE start_location LIKE ? 
                            UNION 
                            SELECT DISTINCT end_location FROM routes WHERE end_location LIKE ?");
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();

    $result = $stmt->get_result();
    $locations = [];

    while ($row = $result->fetch_assoc()) {
        $locations[] = $row['start_location'];
    }

    // Return JSON response
    echo json_encode($locations);

    $stmt->close();
    $conn->close();
}
?>
