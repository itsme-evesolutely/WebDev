
<?php
require "db.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$q = trim($_GET["q"] ?? "");

// Count all student records.
$countResult = $conn->query("SELECT COUNT(*) AS total FROM students");
$totalRecords = (int)$countResult->fetch_assoc()["total"];

// Search by student ID, first name, middle name, last name, or full name.
if ($q !== "") {
    $searchTerm = "%" . $q . "%";

    $sql = "SELECT * FROM students
            WHERE student_number LIKE ?
               OR first_name LIKE ?
               OR middle_name LIKE ?
               OR last_name LIKE ?
               OR CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?
               OR CONCAT_WS(' ', last_name, first_name, middle_name) LIKE ?
            ORDER BY last_name ASC, first_name ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM students
            ORDER BY last_name ASC, first_name ASC";
    $result = $conn->query($sql);
}

$hasResults = $result->num_rows > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Records Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="container">
    <div class="window">

        <!-- macOS-style title bar -->
        <div class="title-bar">
            <div class="window-dots">
                <span class="dot red"></span>
                <span class="dot yellow"></span>
                <span class="dot green"></span>
            </div>

            <span>Student Records Management System</span>
        </div>

        <div class="page-content">

            <!-- Greeting and search -->
            <div class="welcome-row">

                <div class="welcome-text">
                    <div class="eyebrow">
                        STUDENT RECORDS MANAGEMENT SYSTEM
                    </div>

                    <h1>Hello!</h1>

                    <p class="welcome-message">
                        Welcome to Student Records Management System
                        (CRUD Web Application)! So happy that you're here!
                    </p>
                </div>

                <form method="GET" action="index.php" class="top-search">
                    <input
                        type="text"
                        name="q"
                        value="<?= e($q) ?>"
                        placeholder="Search a record"
                        aria-label="Search student records"
                    >

                    <button
                        type="submit"
                        class="button button-primary search-button"
                    >
                        <span class="search-icon" aria-hidden="true"></span>
                        Search
                    </button>

                    <?php if ($q !== ""): ?>
                        <a href="index.php" class="button button-secondary">
                            Clear Search
                        </a>
                    <?php endif; ?>
                </form>

            </div>

            <!-- Student records table -->
            <div class="panel">

                <div class="panel-heading">
                    <div>
                        <h2>Student Records</h2>

                        <p class="panel-description">
                            Manage and view registered student information.
                        </p>
                    </div>

                    <div class="record-count">
                        Total records: <?= $totalRecords ?>
                    </div>
                </div>

                <!-- Success messages -->
                <?php if (isset($_GET["added"])): ?>
                    <div class="success">
                        Student record added successfully!
                    </div>
                <?php elseif (isset($_GET["updated"])): ?>
                    <div class="success">
                        Student record updated successfully!
                    </div>
                <?php elseif (isset($_GET["deleted"])): ?>
                    <div class="success">
                        Student record deleted successfully!
                    </div>
                <?php endif; ?>

                <?php if ($hasResults): ?>

                    <div class="table-container">
                        <table class="records-table">

                            <thead>
                                <tr>
                                    <th>Student ID Number</th>
                                    <th>Fullname</th>
                                    <th>Campus Branch</th>
                                    <th>Course</th>
                                    <th>Year</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>

                                    <?php
                                    $fullName = trim(
                                        $row["first_name"] . " " .
                                        ($row["middle_name"] ?? "") . " " .
                                        $row["last_name"]
                                    );
                                    ?>

                                    <tr>
                                        <td>
                                            <?= e($row["student_number"]) ?>
                                        </td>

                                        <td>
                                            <?= e($fullName) ?>
                                        </td>

                                        <td>
                                            <?= e($row["campus_branch"] ?? "") ?>
                                        </td>

                                        <td>
                                            <?= e($row["course"]) ?>
                                        </td>

                                        <td>
                                            <?= e($row["year_level"]) ?>
                                        </td>

                                        <td>
                                            <?= e($row["email"] ?? "") ?>
                                        </td>

                                        <td>
                                            <div class="action-buttons">

                                                <a
                                                    class="button button-secondary"
                                                    href="edit.php?id=<?= (int)$row["id"] ?>"
                                                >
                                                    Edit
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="delete.php"
                                                    onsubmit="return confirm('Are you sure you want to delete this student record?');"
                                                >
                                                    <input
                                                        type="hidden"
                                                        name="id"
                                                        value="<?= (int)$row["id"] ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="button button-danger"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>
                            </tbody>

                        </table>
                    </div>

                <?php elseif ($q !== "" && $totalRecords > 0): ?>

                    <div class="empty-state">
                        <h3>No matching student found.</h3>

                        <p>
                            Try checking the spelling or searching for
                            a different name or student ID.
                        </p>
                    </div>

                <?php elseif ($q !== "" && $totalRecords === 0): ?>

                    <div class="empty-state">
                        <h3>Your database is empty.</h3>

                        <p>
                            This information cannot be found in the database
                            yet since the database is empty.
                        </p>
                    </div>

                    <script>
                        alert(
                            "This information cannot be found in the database yet since the database is empty."
                        );
                    </script>

                <?php else: ?>

                    <div class="empty-state">
                        <h3>No student records yet.</h3>

                        <p>
                            Add your first student using the section below.
                        </p>
                    </div>

                <?php endif; ?>

            </div>

            <!-- Add student card -->
            <div class="panel add-record-card">

                <h2>Add Student Record</h2>

                <p class="panel-description">
                    Need to register another student?
                    Add their information to your database here.
                </p>

                <a href="add.php" class="button button-primary">
                    + Add Student
                </a>

            </div>

        </div>
    </div>
</div>

</body>
</html>

<?php
if (isset($stmt) && $stmt instanceof mysqli_stmt) {
    $stmt->close();
}
?>
