<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

try {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {

        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);

        exit;
    }



    $sql = "
        SELECT
            n.note_id,
            n.title,
            n.description,
            n.subject,
            n.file_name,
            n.file_path,
            n.status,
            u.full_name,
            u.email,
            u.course,
            u.year_level
        FROM notes n
        LEFT JOIN users u
            ON n.user_id = u.user_id
        WHERE LOWER(TRIM(n.status)) = 'approved'
        ORDER BY n.note_id DESC
    ";


    $result = $conn->query($sql);


    if (!$result) {

        echo json_encode([
            "success" => false,
            "message" => "Database query failed: " . $conn->error
        ]);

        exit;
    }


    $resources = [];


    while ($row = $result->fetch_assoc()) {

        $resources[] = [

            "note_id" => $row["note_id"] ?? null,

            "title" => $row["title"] ?? "",

            "description" => $row["description"] ?? "",

            "subject" => $row["subject"] ?? "",

            "file_name" => $row["file_name"] ?? "",

            "file_path" => $row["file_path"] ?? "",

            "status" => $row["status"] ?? "",

            "full_name" => $row["full_name"] ?? "",

            "email" => $row["email"] ?? "",

            "course" => $row["course"] ?? "",

            "year_level" => $row["year_level"] ?? ""

        ];
    }


    $result->free();


    echo json_encode([

        "success" => true,

        "message" => "Approved resources loaded successfully.",

        "data" => [

            "resources" => $resources

        ]

    ]);

    $conn->close();

    exit;


} catch (Throwable $e) {

    echo json_encode([

        "success" => false,

        "message" => "PHP error: " . $e->getMessage()

    ]);

    exit;
}

?>
