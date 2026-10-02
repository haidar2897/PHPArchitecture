<?php

require_once "includes/auth.php";

require_once "config/db.php";

$totalStudents = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM students"
);

if ($result) {

    $row = $result->fetch_assoc();

    $totalStudents = $row["total"];
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="navbar">

    <h2>Student Management</h2>

    <div>

        Welcome,
        <?= htmlspecialchars($_SESSION["username"]) ?>

        |

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>

<div class="dashboard">

    <h1>Dashboard</h1>

    <div class="cards">

        <div class="card">

            <h3>Total Students</h3>

            <p>
                <?= $totalStudents ?>
            </p>

        </div>

        <div class="card">

            <h3>Actions</h3>

            <a href="add_student.php">
                Add Student
            </a>

        </div>

        <div class="card">

            <h3>Students</h3>

            <a href="students.php">
                View Students
            </a>

        </div>

    </div>

</div>

</body>

</html>
</html>
</html>