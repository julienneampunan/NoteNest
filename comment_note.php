<?php

header("Content-Type: application/json");

require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in first."
    ]);
    exit;
}

$note_id = intval(
    $_POST["note_id"] ?? 0
);

$comment = trim(
    $_POST["comment_text"] ?? ""
);

if ($note_id <= 0 || $comment === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please enter a comment."
    ]);
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    INSERT INTO comments
    (user_id, note_id, comment_text)
    VALUES (?, ?, ?)
");

$stmt->bind_param(
    "iis",
    $user_id,
    $note_id,
    $comment
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Comment added successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Unable to add comment."
    ]);
}

$stmt->close();
$conn->close();

?>