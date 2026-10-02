<?php

require_once "includes/auth.php";

require_once "config/db.php";

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {

    die("Invalid student ID.");

}


$sql =
    "DELETE FROM students
     WHERE id = ?";

$stmt =
    $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$stmt->close();


header(
    "Location: students.php"
);

exit;

?>