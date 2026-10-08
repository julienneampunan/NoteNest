<?php

header("Content-Type: application/json");

require_once "db.php";

try {

    $stmt = $conn->query("
        SELECT
            category_id,
            name,
            description
        FROM category
        ORDER BY name
    ");


    echo json_encode(
        $stmt->fetchAll()
    );


} catch (PDOException $e) {

    echo json_encode([]);

}

?>