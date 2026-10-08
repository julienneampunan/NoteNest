<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please enter your email and password."
    ]);
    exit;
}

$stmt = $conn->prepare("
    SELECT
        user_id,
        full_name,
        username,
        email,
        password,
        course,
        year_level
    FROM users
    WHERE email = ?
    LIMIT 1
");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Database query error: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $email);

if (!$stmt->execute()) {
    echo json_encode([
        "success" => false,
        "message" => "Unable to execute login query: " . $stmt->error
    ]);
    $stmt->close();
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "Account not found."
    ]);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user["password"])) {
    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "Incorrect password."
    ]);
    exit;
}

/* SAVE USER SESSION */

$_SESSION["user_id"] = $user["user_id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["email"] = $user["email"];
$_SESSION["course"] = $user["course"];
$_SESSION["year_level"] = $user["year_level"];

/* REMOVE PASSWORD BEFORE SENDING TO JAVASCRIPT */

unset($user["password"]);

$stmt->close();

echo json_encode([
    "success" => true,
    "message" => "Login successful.",
    "user" => $user
]);

exit;
?>