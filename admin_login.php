
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

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    response(false, "Please enter your admin email and password.");
}

$stmt = $conn->prepare("
    SELECT
        admin_id,
        full_name,
        email,
        password
    FROM admins
    WHERE email = ?
    LIMIT 1
");

if (!$stmt) {
    response(false, "Database error.");
}

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    response(false, "Admin account not found.");
}

$admin = $result->fetch_assoc();

if (!password_verify($password, $admin["password"])) {
    $stmt->close();
    response(false, "Incorrect admin password.");
}

/* IMPORTANT: SAVE ADMIN SESSION */
$_SESSION["admin_id"] = $admin["admin_id"];
$_SESSION["admin_name"] = $admin["full_name"];
$_SESSION["admin_email"] = $admin["email"];

unset($admin["password"]);

$stmt->close();
$conn->close();

response(
    true,
    "Admin login successful.",
    [
        "admin" => $admin
    ]
);
?>
