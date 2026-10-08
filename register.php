<?php

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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    response(false, "Invalid request.");
}

$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$course = trim($_POST["course"] ?? "");
$year_level = trim($_POST["year_level"] ?? "");


$username = strtolower(
    preg_replace(
        "/[^a-zA-Z0-9]/",
        "",
        explode("@", $email)[0] ?? ""
    )
);

if ($username === "") {
    $username = "student";
}

/* Required fields */

if (
    $full_name === "" ||
    $email === "" ||
    $password === ""
) {
    response(
        false,
        "Please complete all required fields."
    );
}

/* Email */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    response(
        false,
        "Please enter a valid email address."
    );
}

/* Password */

if (strlen($password) < 6) {
    response(
        false,
        "Password must be at least 6 characters."
    );
}

/* Check email */

$checkEmail = $conn->prepare(
    "SELECT user_id
     FROM users
     WHERE email = ?
     LIMIT 1"
);

if (!$checkEmail) {
    response(
        false,
        "Database error while checking email."
    );
}

$checkEmail->bind_param(
    "s",
    $email
);

$checkEmail->execute();

$result = $checkEmail->get_result();

if ($result->num_rows > 0) {

    $checkEmail->close();

    response(
        false,
        "An account with this email already exists."
    );
}

$checkEmail->close();

/* Make username unique */

$baseUsername = $username;
$count = 1;

while (true) {

    $checkUsername = $conn->prepare(
        "SELECT user_id
         FROM users
         WHERE username = ?
         LIMIT 1"
    );

    $checkUsername->bind_param(
        "s",
        $username
    );

    $checkUsername->execute();

    $usernameResult =
        $checkUsername->get_result();

    $checkUsername->close();

    if ($usernameResult->num_rows === 0) {
        break;
    }

    $username =
        $baseUsername . $count;

    $count++;
}

/* Hash password */

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

/* Insert */

$stmt = $conn->prepare(
    "INSERT INTO users
    (
        full_name,
        username,
        email,
        password,
        course,
        year_level
    )
    VALUES (?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    response(
        false,
        "Unable to prepare registration."
    );
}

$stmt->bind_param(
    "ssssss",
    $full_name,
    $username,
    $email,
    $hashedPassword,
    $course,
    $year_level
);

if (!$stmt->execute()) {

    $error = $stmt->error;

    $stmt->close();

    response(
        false,
        "Registration failed: " . $error
    );
}

$user_id = $stmt->insert_id;

$stmt->close();
$conn->close();

response(
    true,
    "Account created successfully!",
    [
        "user_id" => $user_id,
        "username" => $username
    ]
);

?>