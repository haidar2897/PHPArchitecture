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


/* GET EXISTING STUDENT */

$sql =
    "SELECT *
     FROM students
     WHERE id = ?";

$stmt =
    $conn->prepare($sql);

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


/* UPDATE */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName =
        trim($_POST["full_name"]);

    $email =
        trim($_POST["email"]);

    $phone =
        trim($_POST["phone"]);

    $course =
        $_POST["course"];

    $city =
        trim($_POST["city"]);

    $percentage =
        $_POST["percentage"];


    if ($fullName === "" ||
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )) {

        die("Invalid input.");

    }


    $sql =
        "UPDATE students
         SET
            full_name = ?,
            email = ?,
            phone = ?,
            course = ?,
            city = ?,
            percentage = ?
         WHERE id = ?";


    $stmt =
        $conn->prepare($sql);


    $stmt->bind_param(
        "sssssdi",
        $fullName,
        $email,
        $phone,
        $course,
        $city,
        $percentage,
        $id
    );


    $stmt->execute();

    header(
        "Location: students.php"
    );

    exit;
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Edit Student</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="container">

    <h1>Edit Student</h1>

    <form method="POST">

        <label>Full Name</label>

        <input
            type="text"
            name="full_name"
            value="<?= htmlspecialchars(
                $student["full_name"]
            ) ?>"
            required
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars(
                $student["email"]
            ) ?>"
            required
        >


        <label>Phone</label>

        <input
            type="tel"
            name="phone"
            value="<?= htmlspecialchars(
                $student["phone"]
            ) ?>"
        >


        <label>Course</label>

        <select name="course">

            <?php

            $courses = [
                "Java",
                "PHP",
                "Python",
                "Angular",
                "Data Science"
            ];

            foreach ($courses as $course):

            ?>

                <option
                    value="<?= $course ?>"
                    <?= $student["course"] === $course
                        ? "selected"
                        : "" ?>
                >
                    <?= $course ?>
                </option>

            <?php endforeach; ?>

        </select>


        <label>City</label>

        <input
            type="text"
            name="city"
            value="<?= htmlspecialchars(
                $student["city"]
            ) ?>"
        >


        <label>Percentage</label>

        <input
            type="number"
            name="percentage"
            min="0"
            max="100"
            step="0.01"
            value="<?= $student["percentage"] ?>"
            required
        >


        <button type="submit">
            Update Student
        </button>


        <a
            href="students.php"
            class="button"
        >
            Cancel
        </a>

    </form>

</div>

</body>

</html>