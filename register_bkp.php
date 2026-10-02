<?php

require_once "config/db.php";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Text input
    $fullName = trim($_POST["full_name"] ?? "");

    // Email input
    $email = trim($_POST["email"] ?? "");

    // Password input
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    // Telephone input
    $phone = trim($_POST["phone"] ?? "");

    // Radio button
    $gender = $_POST["gender"] ?? "";

    // Date input
    $dob = $_POST["dob"] ?? "";

    // Select
    $course = $_POST["course"] ?? "";

    // Checkboxes
    $skills = $_POST["skills"] ?? [];
    $skillsString = implode(", ", $skills);

    // Text input
    $city = $_POST["city"] ?? "";

    // Textarea
    $address = trim($_POST["address"] ?? "");

    // URL
    $website = trim($_POST["website"] ?? "");

    // Number
    $percentage = $_POST["percentage"] ?? "";

    // Date
    $admissionDate = $_POST["admission_date"] ?? "";


    /* ---------------- VALIDATION ---------------- */

    if ($fullName === "") {
        $errors[] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]{2,100}$/", $fullName)) {
        $errors[] = "Full name can contain only letters and spaces.";
    }


    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }


    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }


    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }


    if ($phone !== "" && !preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must contain 10 digits.";
    }


    if (!in_array($gender, ["Male", "Female", "Other"])) {
        $errors[] = "Please select a valid gender.";
    }


    if ($dob === "") {
        $errors[] = "Date of birth is required.";
    }


    $validCourses = [
        "Java",
        "PHP",
        "Python",
        "Angular",
        "Data Science"
    ];

    if (!in_array($course, $validCourses)) {
        $errors[] = "Please select a valid course.";
    }


    if (empty($skills)) {
        $errors[] = "Select at least one skill.";
    }


    if ($city === "") {
        $errors[] = "City is required.";
    }


    if ($address === "") {
        $errors[] = "Address is required.";
    }


    if ($website !== "" &&
        !filter_var($website, FILTER_VALIDATE_URL)) {

        $errors[] = "Invalid website URL.";
    }


    if ($percentage === "" ||
        !is_numeric($percentage) ||
        $percentage < 0 ||
        $percentage > 100) {

        $errors[] = "Percentage must be between 0 and 100.";
    }


    if ($admissionDate === "") {
        $errors[] = "Admission date is required.";
    }


    /* ---------------- DATABASE INSERT ---------------- */

    if (empty($errors)) {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO students
                (
                    full_name,
                    email,
                    password,
                    phone,
                    gender,
                    dob,
                    course,
                    skills,
                    city,
                    address,
                    website,
                    percentage,
                    admission_date
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssssssssds",
            $fullName,
            $email,
            $hashedPassword,
            $phone,
            $gender,
            $dob,
            $course,
            $skillsString,
            $city,
            $address,
            $website,
            $percentage,
            $admissionDate
        );

        if ($stmt->execute()) {
            $success = "Student registered successfully!";
        } else {
            $errors[] = "Unable to register student.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Registration</title>

    <link rel="stylesheet"
          href="style.css">
</head>

<body>

<div class="container">

    <h1>Student Registration</h1>

    <?php if (!empty($errors)): ?>

        <div class="error">

            <?php foreach ($errors as $error): ?>

                <p><?= htmlspecialchars($error) ?></p>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <?php if ($success): ?>

        <div class="success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>


    <form method="POST"
          action=""
          enctype="multipart/form-data">

        <!-- TEXT -->

        <label>Full Name</label>

        <input
            type="text"
            name="full_name"
            placeholder="Enter your name"
            maxlength="100"
            required
        >


        <!-- EMAIL -->

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="example@gmail.com"
            required
        >


        <!-- PASSWORD -->

        <label>Password</label>

        <input
            type="password"
            name="password"
            minlength="8"
            required
        >


        <!-- PASSWORD -->

        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            minlength="8"
            required
        >


        <!-- TEL -->

        <label>Phone Number</label>

        <input
            type="tel"
            name="phone"
            pattern="[0-9]{10}"
            placeholder="10 digit number"
        >


        <!-- RADIO -->

        <label>Gender</label>

        <div class="radio-group">

            <label>
                <input
                    type="radio"
                    name="gender"
                    value="Male"
                    required
                >
                Male
            </label>

            <label>
                <input
                    type="radio"
                    name="gender"
                    value="Female"
                >
                Female
            </label>

            <label>
                <input
                    type="radio"
                    name="gender"
                    value="Other"
                >
                Other
            </label>

        </div>


        <!-- DATE -->

        <label>Date of Birth</label>

        <input
            type="date"
            name="dob"
            required
        >


        <!-- SELECT -->

        <label>Course</label>

        <select name="course" required>

            <option value="">
                Select Course
            </option>

            <option value="Java">
                Java
            </option>

            <option value="PHP">
                PHP
            </option>

            <option value="Python">
                Python
            </option>

            <option value="Angular">
                Angular
            </option>

            <option value="Data Science">
                Data Science
            </option>

        </select>


        <!-- CHECKBOX -->

        <label>Skills</label>

        <div class="checkbox-group">

            <label>
                <input
                    type="checkbox"
                    name="skills[]"
                    value="Java"
                >
                Java
            </label>

            <label>
                <input
                    type="checkbox"
                    name="skills[]"
                    value="Spring Boot"
                >
                Spring Boot
            </label>

            <label>
                <input
                    type="checkbox"
                    name="skills[]"
                    value="PHP"
                >
                PHP
            </label>

            <label>
                <input
                    type="checkbox"
                    name="skills[]"
                    value="MySQL"
                >
                MySQL
            </label>

        </div>


        <!-- TEXT -->

        <label>City</label>

        <input
            type="text"
            name="city"
            required
        >


        <!-- TEXTAREA -->

        <label>Address</label>

        <textarea
            name="address"
            rows="4"
            maxlength="500"
            required
        ></textarea>


        <!-- URL -->

        <label>Personal Website</label>

        <input
            type="url"
            name="website"
            placeholder="https://example.com"
        >


        <!-- NUMBER -->

        <label>Percentage</label>

        <input
            type="number"
            name="percentage"
            min="0"
            max="100"
            step="0.01"
            required
        >


        <!-- DATE -->

        <label>Admission Date</label>

        <input
            type="date"
            name="admission_date"
            required
        >


        <!-- FILE -->

        <label>Profile Image</label>

        <input
            type="file"
            name="profile_image"
            accept="image/png,image/jpeg"
        >


        <!-- RANGE -->

        <label>Experience Level</label>

        <input
            type="range"
            name="experience"
            min="0"
            max="10"
            value="0"
        >


        <!-- COLOR -->

        <label>Favorite Color</label>

        <input
            type="color"
            name="favorite_color"
            value="#000000"
        >


        <!-- HIDDEN -->

        <input
            type="hidden"
            name="form_type"
            value="student_registration"
        >


        <button type="submit">
            Register
        </button>

        <button type="reset"
                class="reset-button">
            Reset
        </button>

    </form>

</div>

</body>

</html>