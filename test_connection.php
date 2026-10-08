<?php

require_once "db.php";

try {

    $stmt = $conn->query("SELECT COUNT(*) AS total FROM student");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "NoteNest Database Connected!<br>";
    echo "Student records: " . $row["total"];

} catch (PDOException $e) {

    echo "Database Error: " . $e->getMessage();

}

?>