<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

/* =========================
   JSON RESPONSE
========================= */

function sendResponse($success, $message, $data = [])
{
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

/* =========================
   CHECK REQUEST
========================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request.");
}

/* =========================
   CHECK STUDENT LOGIN
========================= */

if (!isset($_SESSION["user_id"])) {
    sendResponse(false, "Please log in first.");
}

$user_id = (int) $_SESSION["user_id"];

/* =========================
   GET FORM DATA
========================= */

$title = trim($_POST["title"] ?? "");
$description = trim($_POST["description"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$course = trim($_POST["course"] ?? "");
$category_id = (int) ($_POST["category_id"] ?? 0);

/* =========================
   VALIDATION
========================= */

if ($title === "") {
    sendResponse(false, "Please enter a resource title.");
}

if ($subject === "") {
    sendResponse(false, "Please enter the subject.");
}

if ($category_id <= 0) {
    sendResponse(false, "Please select a category.");
}

if (!isset($_FILES["file"])) {
    sendResponse(false, "Please select a file.");
}

/* =========================
   FILE CHECK
========================= */

$file = $_FILES["file"];

if ($file["error"] !== UPLOAD_ERR_OK) {

    $errorMessage = "There was a problem uploading the file.";

    switch ($file["error"]) {

        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $errorMessage = "The uploaded file is too large.";
            break;

        case UPLOAD_ERR_PARTIAL:
            $errorMessage = "The file was only partially uploaded.";
            break;

        case UPLOAD_ERR_NO_FILE:
            $errorMessage = "Please select a file.";
            break;
    }

    sendResponse(false, $errorMessage);
}

/* =========================
   ALLOWED FILE TYPES
========================= */

$allowed = [
    "pdf",
    "doc",
    "docx",
    "ppt",
    "pptx",
    "xls",
    "xlsx"
];

$extension = strtolower(
    pathinfo($file["name"], PATHINFO_EXTENSION)
);

if (!in_array($extension, $allowed, true)) {
    sendResponse(false, "This file type is not allowed.");
}

/* =========================
   MAX SIZE = 20MB
========================= */

$maxSize = 20 * 1024 * 1024;

if ($file["size"] > $maxSize) {
    sendResponse(
        false,
        "File is too large. Maximum size is 20MB."
    );
}

/* =========================
   UPLOAD FOLDER
========================= */

$uploadFolder = dirname(__DIR__) . "/frontend/uploads/";

if (!is_dir($uploadFolder)) {

    if (!mkdir($uploadFolder, 0777, true)) {
        sendResponse(
            false,
            "Unable to create uploads folder."
        );
    }
}

/* =========================
   CREATE FILE NAME
========================= */

$newFileName =
    "notenest_" .
    uniqid() .
    "." .
    $extension;

$filePath = $uploadFolder . $newFileName;

/* =========================
   MOVE FILE
========================= */

if (!move_uploaded_file(
    $file["tmp_name"],
    $filePath
)) {
    sendResponse(
        false,
        "Unable to save uploaded file."
    );
}

$relativePath = "uploads/" . $newFileName;

/* =========================
   DATABASE
========================= */

try {

    /* Check category */

    $categoryCheck = $conn->prepare(
        "SELECT category_id
         FROM categories
         WHERE category_id = ?
         LIMIT 1"
    );

    if (!$categoryCheck) {
        throw new Exception(
            "Unable to check category."
        );
    }

    $categoryCheck->bind_param(
        "i",
        $category_id
    );

    if (!$categoryCheck->execute()) {
        throw new Exception(
            "Unable to check category."
        );
    }

    $categoryResult = $categoryCheck->get_result();

    if ($categoryResult->num_rows === 0) {

        $categoryCheck->close();

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        sendResponse(
            false,
            "Selected category does not exist."
        );
    }

    $categoryCheck->close();

    /* Start transaction */

    $conn->begin_transaction();

    /* =========================
       INSERT NOTE
       STATUS = PENDING
    ========================= */

    $stmt = $conn->prepare(
        "INSERT INTO notes
        (
            user_id,
            title,
            description,
            subject,
            course,
            file_name,
            file_path,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')"
    );

    if (!$stmt) {
        throw new Exception(
            "Unable to prepare resource query."
        );
    }

    $originalFileName = $file["name"];

    $stmt->bind_param(
        "issssss",
        $user_id,
        $title,
        $description,
        $subject,
        $course,
        $originalFileName,
        $relativePath
    );

    if (!$stmt->execute()) {
        throw new Exception(
            "Unable to save resource."
        );
    }

    $note_id = $stmt->insert_id;

    $stmt->close();

    /* =========================
       CATEGORY RELATIONSHIP
    ========================= */

    $categoryStmt = $conn->prepare(
        "INSERT INTO note_categories
        (
            note_id,
            category_id
        )
        VALUES (?, ?)"
    );

    if (!$categoryStmt) {
        throw new Exception(
            "Unable to prepare category query."
        );
    }

    $categoryStmt->bind_param(
        "ii",
        $note_id,
        $category_id
    );

    if (!$categoryStmt->execute()) {
        throw new Exception(
            "Unable to save resource category."
        );
    }

    $categoryStmt->close();

    /* =========================
       SUCCESS
    ========================= */

    $conn->commit();

    sendResponse(
        true,
        "Resource uploaded successfully and is waiting for admin approval.",
        [
            "note_id" => $note_id,
            "file" => $relativePath,
            "status" => "pending"
        ]
    );

} catch (Exception $e) {

    $conn->rollback();

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    sendResponse(
        false,
        $e->getMessage()
    );
}

$conn->close();

?>
