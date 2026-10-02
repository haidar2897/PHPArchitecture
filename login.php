<?php

session_start();

require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $error = "Username and password are required.";

    } else {

        $sql = "SELECT id, username, password
                FROM users
                WHERE username = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify(
                $password,
                $user["password"]
            )) {

                $_SESSION["user_id"] =
                    $user["id"];

                $_SESSION["username"] =
                    $user["username"];

                header(
                    "Location: dashboard.php"
                );

                exit;

            } else {

                $error = "Invalid username or password.";

            }

        } else {

            $error = "Invalid username or password.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Login</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="login-container">

    <h2>Student Management</h2>

    <h3>Login</h3>

    <?php if ($error): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Username</label>

        <input
            type="text"
            name="username"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>

</html>