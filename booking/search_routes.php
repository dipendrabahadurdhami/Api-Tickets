<?php
include '../config.php';

if (isset($_GET['autocomplete']) && $_GET['autocomplete'] === 'true') {
    $query = "SELECT DISTINCT CONCAT(start_location, ' to ', end_location) AS route FROM routes";
    $result = $conn->query($query);

    $routes = [];
    while ($row = $result->fetch_assoc()) {
        $routes[] = $row['route'];
    }
    echo json_encode($routes);
    exit;
}
?>
