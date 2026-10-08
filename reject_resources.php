<?php

header("Content-Type: application/json");

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$note_id = intval($_POST["note_id"] ?? 0);

if ($note_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid resource ID."
    ]);
    exit;
}

try {

    $stmt = $conn->prepare("
        UPDATE notes
        SET status = 'rejected'
        WHERE note_id = ?
        AND status = 'pending'
    ");

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param("i", $note_id);

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Resource was not found or is no longer pending."
        ]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "message" => "Resource rejected successfully."
    ]);

    $stmt->close();

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>