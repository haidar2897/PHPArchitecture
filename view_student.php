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


$sql = "SELECT *
        FROM students
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$result =
    $stmt->get_result();

$student =
    $result->fetch_assoc();


if (!$student) {

    die("Student not found.");

}

?>

<!DOCTYPE html>

<html>

<head>

    <title>View Student</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="container">

    <h1>Student Details</h1>

    <p>
        <strong>Name:</strong>
        <?= htmlspecialchars(
            $student["full_name"]
        ) ?>
    </p>

    <p>
        <strong>Email:</strong>
        <?= htmlspecialchars(
            $student["email"]
        ) ?>
    </p>

    <p>
        <strong>Phone:</strong>
        <?= htmlspecialchars(
            $student["phone"]
        ) ?>
    </p>

    <p>
        <strong>Gender:</strong>
        <?= htmlspecialchars(
            $student["gender"]
        ) ?>
    </p>

    <p>
        <strong>Date of Birth:</strong>
        <?= htmlspecialchars(
            $student["dob"]
        ) ?>
    </p>

    <p>
        <strong>Course:</strong>
        <?= htmlspecialchars(
            $student["course"]
        ) ?>
    </p>

    <p>
        <strong>Skills:</strong>
        <?= htmlspecialchars(
            $student["skills"]
        ) ?>
    </p>

    <p>
        <strong>City:</strong>
        <?= htmlspecialchars(
            $student["city"]
        ) ?>
    </p>

    <p>
        <strong>Address:</strong>
        <?= htmlspecialchars(
            $student["address"]
        ) ?>
    </p>

    <p>
        <strong>Percentage:</strong>
        <?= $student["percentage"] ?>%
    </p>


    <br>

    <a
        href="edit_student.php?id=
        <?= $student["id"] ?>"
        class="button"
    >
        Edit
    </a>

    <a
        href="students.php"
        class="button"
    >
        Back
    </a>

</div>

</body>

</html>