<?php

header("Content-Type: application/json");

require_once "db.php";

try {

    $search = trim($_GET["search"] ?? "");
    $subject = trim($_GET["subject"] ?? "");
    $category = trim($_GET["category"] ?? "");

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
            u.full_name
        FROM notes n
        LEFT JOIN users u
            ON n.user_id = u.user_id
        WHERE n.status = 'approved'
    ";

    $params = [];
    $types = "";

    if ($search !== "") {

        $sql .= "
            AND (
                n.title LIKE ?
                OR n.description LIKE ?
                OR n.subject LIKE ?
                OR n.course LIKE ?
            )
        ";

        $searchValue = "%" . $search . "%";

        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;
        $params[] = $searchValue;

        $types .= "ssss";
    }

    if ($subject !== "") {

        $sql .= " AND n.subject LIKE ? ";

        $params[] = "%" . $subject . "%";

        $types .= "s";
    }

    if ($category !== "") {

        $sql .= " AND n.course LIKE ? ";

        $params[] = "%" . $category . "%";

        $types .= "s";
    }

    $sql .= " ORDER BY n.note_id DESC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();

    $result = $stmt->get_result();

    $notes = [];

    while ($row = $result->fetch_assoc()) {

        $notes[] = [
            "note_id" => $row["note_id"],
            "title" => $row["title"],
            "description" => $row["description"],
            "subject" => $row["subject"],
            "course" => $row["course"],
            "file_name" => $row["file_name"],
            "file_path" => $row["file_path"],
            "status" => $row["status"],
            "full_name" => $row["full_name"]
        ];
    }

    echo json_encode([
        "success" => true,
        "notes" => $notes
    ]);

    $stmt->close();

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "notes" => []
    ]);
}

$conn->close();
?>