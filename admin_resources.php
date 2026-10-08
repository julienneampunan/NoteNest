<?php

header("Content-Type: application/json");

require_once "db.php";

try {

    $sql = "
        SELECT
            n.note_id,
            n.title,
            n.description,
            n.subject,
            n.course,
            n.file_name,
            n.file_path,
            n.status,
            u.full_name,
            u.email
        FROM notes n
        LEFT JOIN users u
            ON n.user_id = u.user_id
        WHERE n.status = 'pending'
        ORDER BY n.note_id DESC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception($conn->error);
    }

    $resources = [];

    while ($row = $result->fetch_assoc()) {
        $resources[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => [
            "resources" => $resources
        ]
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>