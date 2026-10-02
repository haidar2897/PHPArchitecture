<?php

require_once "db.php";

$result = $conn->query(
    "SELECT id, full_name, email, phone, gender,
            course, skills, city, percentage
     FROM students
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Students</title>

    <link rel="stylesheet"
          href="style.css">

</head>

<body>

<div class="container">

    <h1>Student List</h1>

    <a href="register.php">
        Add Student
    </a>

    <br><br>

    <table border="1"
           width="100%"
           cellpadding="10">

        <tr>

            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Gender</th>
            <th>Course</th>
            <th>Skills</th>
            <th>City</th>
            <th>Percentage</th>
            <th>Action</th>

        </tr>

        <?php while ($student = $result->fetch_assoc()): ?>

            <tr>

                <td>
                    <?= $student["id"] ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["full_name"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["email"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["phone"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["gender"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["course"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["skills"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["city"]) ?>
                </td>

                <td>
                    <?= htmlspecialchars($student["percentage"]) ?>
                </td>

                <td>

                    <a href="edit.php?id=<?= $student["id"] ?>">
                        Edit
                    </a>

                    |

                    <a href="delete.php?id=<?= $student["id"] ?>"
                       onclick="return confirm('Delete this student?')">
                        Delete
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    </table>

</div>

</body>

</html>