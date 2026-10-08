<?php

header("Content-Type: application/json");

require_once "db.php";


$student_id =
    intval($_GET["student_id"] ?? 0);


if ($student_id <= 0) {

    echo json_encode([]);

    exit;
}


try {

    $stmt = $conn->prepare("

        SELECT

            r.resource_id,
            r.title,
            r.description,
            r.resource_type,
            r.status,
            r.subject_code,
            r.created_at,

            s.name AS subject_name,

            c.name AS category_name

        FROM resource r

        LEFT JOIN subject s
            ON r.subject_code = s.subject_code

        LEFT JOIN category c
            ON r.category_id = c.category_id

        WHERE r.uploader_id = ?

        ORDER BY r.created_at DESC

    ");


    $stmt->execute([
        $student_id
    ]);


    echo json_encode(
        $stmt->fetchAll()
    );


} catch (PDOException $e) {

    echo json_encode([]);

}

?>