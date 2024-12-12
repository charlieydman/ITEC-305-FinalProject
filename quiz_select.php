<?php
    session_start();

    // Redirect to login page if not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    require 'db.php'; // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Selection</title>
</head>
<body>
    <h1>Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Select a quiz below:</p>
    <ul>
        <?php
        // Fetch quizzes from the database
        $stmt = $pdo->query("SELECT * FROM quizzes");
        while ($quiz = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li><a href='quiz.php?quiz_id=" . $quiz['id'] . "'>" . htmlspecialchars($quiz['title']) . "</a></li>";
        }
        ?>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>
