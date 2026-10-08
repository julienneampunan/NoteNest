<?php

header("Content-Type: application/json");

require_once "db.php";


try {

    $stmt = $conn->query("

        SELECT

            r.resource_id,
            r.title,
            r.description,
            r.resource_type,
            r.subject_code,
            r.status,
            r.created_at,

            s.name AS subject_name,

            c.name AS category_name,

            st.full_name AS uploader_name

        FROM resource r

        LEFT JOIN subject s
            ON r.subject_code = s.subject_code

        LEFT JOIN category c
            ON r.category_id = c.category_id

        LEFT JOIN student st
            ON r.uploader_id = st.student_id

        WHERE r.status = 'Pending'

        ORDER BY r.created_at DESC

    ");


    echo json_encode(
        $stmt->fetchAll()
    );


} catch (PDOException $e) {

    echo json_encode([]);

}

?>