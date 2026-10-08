<?php

header("Content-Type: application/json");

require_once "db.php";


$data = json_decode(
    file_get_contents("php://input"),
    true
);


$student_id =
    intval($data["student_id"] ?? 0);

$resource_id =
    intval($data["resource_id"] ?? 0);

$rating_value =
    intval($data["rating_value"] ?? 0);


if (
    $student_id <= 0 ||
    $resource_id <= 0 ||
    $rating_value < 1 ||
    $rating_value > 5
) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Invalid rating."

    ]);

    exit;
}


try {

    $check =
        $conn->prepare("

            SELECT rating_id

            FROM rating

            WHERE student_id = ?

            AND resource_id = ?

        ");


    $check->execute([

        $student_id,
        $resource_id

    ]);


    if ($check->fetch()) {

        $stmt =
            $conn->prepare("

                UPDATE rating

                SET rating_value = ?,
                    rated_at = CURRENT_TIMESTAMP

                WHERE student_id = ?

                AND resource_id = ?

            ");


        $stmt->execute([

            $rating_value,
            $student_id,
            $resource_id

        ]);


    } else {

        $stmt =
            $conn->prepare("

                INSERT INTO rating

                (
                    student_id,
                    resource_id,
                    rating_value
                )

                VALUES (?, ?, ?)

            ");


        $stmt->execute([

            $student_id,
            $resource_id,
            $rating_value

        ]);

    }


    echo json_encode([

        "success" => true,

        "message" =>
            "Rating submitted successfully!"

    ]);


} catch (PDOException $e) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Unable to submit rating."

    ]);

}

?>