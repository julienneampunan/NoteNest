<?php

header("Content-Type: application/json");

require_once "db.php";


$resource_id =
    intval($_GET["id"] ?? 0);


if ($resource_id <= 0) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Invalid resource."

    ]);

    exit;
}


try {

    $stmt =
        $conn->prepare("

            SELECT
                file_url,
                file_name

            FROM `file`

            WHERE resource_id = ?

            LIMIT 1

        ");


    $stmt->execute([
        $resource_id
    ]);


    $file =
        $stmt->fetch();


    if (!$file) {

        echo json_encode([

            "success" => false,

            "message" =>
                "File not found."

        ]);

        exit;
    }


    echo json_encode([

        "success" => true,

        "file_url" =>
            $file["file_url"],

        "file_name" =>
            $file["file_name"]

    ]);


} catch (PDOException $e) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Download error."

    ]);

}

?>