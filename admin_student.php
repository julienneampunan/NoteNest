<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

function response($success, $message, $data = [])
{
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

if (!isset($_SESSION["admin_id"])) {
    response(false, "Admin login required.");
}

$result = $conn->query("
    SELECT
        user_id,
        full_name,
        username,
        email,
        course,
        year_level,
        created_at
    FROM users
    ORDER BY full_name ASC
");

if (!$result) {
    response(false, "Unable to load student accounts.");
}

$students = [];

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$result->free();
$conn->close();

response(
    true,
    "Student accounts loaded successfully.",
    [
        "total" => count($students),
        "students" => $students
    ]
);
?>