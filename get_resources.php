<?php

header("Content-Type: application/json");

require_once "db.php";

$sql = "
    SELECT
        note_id,
        user_id,
        title,
        description,
        subject,
        course,
        file_name,
        file_path,
        created_at
    FROM notes
    ORDER BY created_at DESC
";

$result = $conn->query($sql);

if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Unable to load resources: " . $conn->error
    ]);

    exit;
}

$resources = [];

while ($row = $result->fetch_assoc()) {

    $resources[] = $row;

}

echo json_encode([
    "success" => true,
    "resources" => $resources
]);

$conn->close();

?>