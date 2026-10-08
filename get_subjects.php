<?php

header("Content-Type: application/json");

require_once "db.php";


try {

    $stmt = $conn->query("

        SELECT

            student_id,
            full_name,
            email,
            course,
            year_level,
            created_at

        FROM student

        ORDER BY created_at DESC

    ");


    echo json_encode(
        $stmt->fetchAll()
    );


} catch (PDOException $e) {

    echo json_encode([]);

}

?>