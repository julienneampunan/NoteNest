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

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("
    SELECT
        user_id,
        full_name,
        username,
        email,
        course,
        year_level,
        profile_picture,
        created_at
    FROM users
    WHERE user_id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "User not found."
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "user" => $user
]);

$stmt->close();
$conn->close();

?>