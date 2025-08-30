<?php
include '../config.php';

if (isset($_POST['query']) && isset($_POST['type'])) {
    $query = $_POST['query'];
    $type = $_POST['type'];

    // Query based on type (start or end location)
    if ($type === 'start') {
        $stmt = $conn->prepare("SELECT DISTINCT start_location FROM routes WHERE start_location LIKE ? LIMIT 10");
    } else if ($type === 'end') {
        $stmt = $conn->prepare("SELECT DISTINCT end_location FROM routes WHERE end_location LIKE ? LIMIT 10");
    }

    $search = "%" . $query . "%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $locations = [];
    while ($row = $result->fetch_assoc()) {
        $locations[] = $type === 'start' ? $row['start_location'] : $row['end_location'];
    }

    echo json_encode($locations);
}
?>
