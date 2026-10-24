
<?php
require "db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$student_number = "";
$first_name = "";
$middle_name = "";
$last_name = "";
$email = "";
$campus_branch = "";
$course = "";
$year_level = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_number = trim($_POST["student_number"] ?? "");
    $first_name = trim($_POST["first_name"] ?? "");
    $middle_name = trim($_POST["middle_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $campus_branch = trim($_POST["campus_branch"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year_level = trim($_POST["year_level"] ?? "");

    if (
        $student_number === "" ||
        $first_name === "" ||
        $last_name === "" ||
        $campus_branch === "" ||
        $course === "" ||
        $year_level === ""
    ) {
        $error = "Please fill in all required fields.";

    } elseif (
        !ctype_digit($year_level) ||
        (int)$year_level < 1 ||
        (int)$year_level > 6
    ) {
        $error = "Please enter a valid year level.";

    } elseif (
        $email !== "" &&
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $error = "Please enter a valid email address.";

    } else {

        $check_sql = "SELECT id
                      FROM students
                      WHERE LOWER(first_name) = LOWER(?)
                      AND LOWER(COALESCE(middle_name, '')) = LOWER(?)
                      AND LOWER(last_name) = LOWER(?)
                      LIMIT 1";

        $check_stmt = $conn->prepare($check_sql);

        $check_stmt->bind_param(
            "sss",
            $first_name,
            $middle_name,
            $last_name
        );

        $check_stmt->execute();

        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {

        
            $error = "This information is already in our system.";

            $student_number = "";
            $first_name = "";
            $middle_name = "";
            $last_name = "";
            $email = "";
            $campus_branch = "";
            $course = "";
            $year_level = "";

        } else {

            $year_level = (int)$year_level;

            $email = $email === "" ? null : $email;
            $middle_name = $middle_name === "" ? null : $middle_name;

            $sql = "INSERT INTO students
                    (student_number, first_name, middle_name, last_name,
                     email, campus_branch, course, year_level)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sssssssi",
                $student_number,
                $first_name,
                $middle_name,
                $last_name,
                $email,
                $campus_branch,
                $course,
                $year_level
            );

            if ($stmt->execute()) {
                $stmt->close();
                $check_stmt->close();

                header("Location: index.php?added=1");
                exit;
            }

            if ($stmt->errno === 1062) {
                $error = "That student ID number already exists.";
            } else {
                $error = "Unable to save the student record. Please try again.";
            }

            $stmt->close();
        }

        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student Record</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="window">
        <div class="title-bar">
            <div class="window-dots">
                <span class="dot red"></span>
                <span class="dot yellow"></span>
                <span class="dot green"></span>
            </div>
            <span>Student Records Management System</span>
        </div>

        <div class="page-content">
            <div class="welcome-text">
                <div class="eyebrow">STUDENT RECORDS MANAGEMENT SYSTEM</div>
                <h1>Add Student Record</h1>
                <p class="welcome-message">
                    Enter the student's information below. Middle name is optional.
                </p>
            </div>

            <div class="panel edit-panel">
                <h2>Student Information</h2>
                <p class="panel-description">
                    Fields marked with * are required.
                </p>

                <?php if ($error !== ""): ?>
                    <div class="error"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="add.php" class="student-form">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="student_number">Student ID Number *</label>
                            <input type="text" id="student_number"
                                   name="student_number" required
                                   value="<?= e($student_number) ?>"
                                   placeholder="e.g. 2024-00001">
                        </div>

                        <div class="form-field">
                            <label for="campus_branch">Campus Branch *</label>
                            <input type="text" id="campus_branch"
                                   name="campus_branch" required
                                   value="<?= e($campus_branch) ?>"
                                   placeholder="Enter campus branch">
                        </div>

                        <div class="form-field">
                            <label for="first_name">First Name *</label>
                            <input type="text" id="first_name"
                                   name="first_name" required
                                   value="<?= e($first_name) ?>"
                                   placeholder="Enter first name">
                        </div>

                        <div class="form-field">
                            <label for="middle_name">Middle Name (Optional)</label>
                            <input type="text" id="middle_name"
                                   name="middle_name"
                                   value="<?= e($middle_name) ?>"
                                   placeholder="Enter middle name">
                        </div>

                        <div class="form-field">
                            <label for="last_name">Last Name *</label>
                            <input type="text" id="last_name"
                                   name="last_name" required
                                   value="<?= e($last_name) ?>"
                                   placeholder="Enter last name">
                        </div>

                        <div class="form-field">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email"
                                   value="<?= e($email) ?>"
                                   placeholder="student@example.com">
                        </div>

                        <div class="form-field">
                            <label for="course">Course *</label>
                            <input type="text" id="course" name="course"
                                   required value="<?= e($course) ?>"
                                   placeholder="e.g. BSIT">
                        </div>

                        <div class="form-field">
                            <label for="year_level">Year Level *</label>
                            <select id="year_level" name="year_level" required>
                                <option value="">Select year level</option>
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?= $i ?>"
                                        <?= (string)$year_level === (string)$i ? "selected" : "" ?>>
                                        Year <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button button-primary">
                            Save Student
                        </button>
                        <a href="index.php" class="button button-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <a href="index.php" class="back-link">← Back to Student Records</a>
        </div>
    </div>
</div>
</body>
</html>
