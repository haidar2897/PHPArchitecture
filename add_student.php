<?php

require_once "includes/auth.php";

require_once "config/db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName =
        trim($_POST["full_name"] ?? "");

    $email =
        trim($_POST["email"] ?? "");

    $phone =
        trim($_POST["phone"] ?? "");

    $gender =
        $_POST["gender"] ?? "";

    $dob =
        $_POST["dob"] ?? "";

    $course =
        $_POST["course"] ?? "";

    $skills =
        $_POST["skills"] ?? [];

    $city =
        trim($_POST["city"] ?? "");

    $address =
        trim($_POST["address"] ?? "");

    $website =
        trim($_POST["website"] ?? "");

    $percentage =
        $_POST["percentage"] ?? "";

    $admissionDate =
        $_POST["admission_date"] ?? "";

$profileImage = null;

if (
    isset($_FILES["profile_image"]) &&
    $_FILES["profile_image"]["error"] === UPLOAD_ERR_OK
) {

    $file = $_FILES["profile_image"];

    $allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp"
    ];

    if (!in_array($file["type"], $allowedTypes)) {

        $errors[] = "Only JPG, PNG and WEBP files are allowed.";

    } elseif ($file["size"] > 2 * 1024 * 1024) {

        $errors[] = "Maximum image size is 2 MB.";

    } else {

        $extension =
            pathinfo(
                $file["name"],
                PATHINFO_EXTENSION
            );

        $fileName =
            uniqid("student_", true)
            . "."
            . strtolower($extension);

        $destination =
            "uploads/" . $fileName;

        if (move_uploaded_file(
            $file["tmp_name"],
            $destination
        )) {

            $profileImage = $fileName;

        } else {

            $errors[] =
                "Unable to upload profile image.";
        }
    }
}
    /* Validation */

    if ($fullName === "") {

        $errors[] =
            "Full name is required.";

    }

    if (!filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )) {

        $errors[] =
            "Valid email is required.";

    }

    if ($phone !== "" &&
        !preg_match(
            "/^[0-9]{10}$/",
            $phone
        )) {

        $errors[] =
            "Phone number must contain 10 digits.";

    }

    if (!in_array(
        $gender,
        ["Male", "Female", "Other"]
    )) {

        $errors[] =
            "Select a valid gender.";

    }

    if ($course === "") {

        $errors[] =
            "Please select a course.";

    }

    if (empty($skills)) {

        $errors[] =
            "Select at least one skill.";

    }

    if ($percentage === "" ||
        !is_numeric($percentage) ||
        $percentage < 0 ||
        $percentage > 100) {

        $errors[] =
            "Percentage must be between 0 and 100.";

    }


    if (empty($errors)) {

        $skillsString =
            implode(", ", $skills);


        $sql = "INSERT INTO students
        (
            full_name,
            email,
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
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssssssssds",
            $fullName,
            $email,
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

            header(
                "Location: students.php"
            );

            exit;

        } else {

            $errors[] =
                "Error saving student.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Add Student</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="container">

    <h1>Add Student</h1>

    <?php foreach ($errors as $error): ?>

        <div class="error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endforeach; ?>


    <form method="POST"
      enctype="multipart/form-data">

        <label>Full Name</label>

        <input
            type="text"
            name="full_name"
            maxlength="100"
            required
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
            required
        >


        <label>Phone</label>

        <input
            type="tel"
            name="phone"
            pattern="[0-9]{10}"
        >


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


        <label>Date of Birth</label>

        <input
            type="date"
            name="dob"
            required
        >


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


        <label>City</label>

        <input
            type="text"
            name="city"
        >


        <label>Address</label>

        <textarea
            name="address"
            rows="4"
        ></textarea>

		<label>Profile Image</label>

		<input
			type="file"
			name="profile_image"
			accept=".jpg,.jpeg,.png,.webp"
		>

        <label>Website</label>

        <input
            type="url"
            name="website"
        >


        <label>Percentage</label>

        <input
            type="number"
            name="percentage"
            min="0"
            max="100"
            step="0.01"
            required
        >


        <label>Admission Date</label>

        <input
            type="date"
            name="admission_date"
            required
        >
<?php if (!empty($student["profile_image"])): ?>

    <img
        src="uploads/<?= htmlspecialchars(
            $student["profile_image"]
        ) ?>"
        width="120"
        height="120"
        style="object-fit:cover;border-radius:50%;"
    >

<?php endif; ?>

        <button type="submit">
            Save Student
        </button>

        <a class="button"
           href="students.php">
            Cancel
        </a>

    </form>

</div>

</body>

</html>