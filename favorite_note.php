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
 
$note_id = intval($_POST["note_id"] ?? 0);
 
if ($note_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid note."
    ]);
    exit;
}
 
$user_id = $_SESSION["user_id"];
 

 
$stmt = $conn->prepare("
    INSERT INTO favorites (user_id, note_id)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE note_id = note_id
");
 
$stmt->bind_param("ii", $user_id, $note_id);
 
if ($stmt->execute()) {
 
    echo json_encode([
        "success" => true,
        "message" => "Added to favorites."
    ]);
 
} else {
 
    echo json_encode([
        "success" => false,
        "message" => "Unable to favorite note."
    ]);
}
 
$stmt->close();
$conn->close();
 
?>
 