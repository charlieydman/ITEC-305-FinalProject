<?php
    session_start();

    // Redirect to login page if not logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    require 'db.php'; // Database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Selection</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h3>Select a quiz below:</h3> 

    <div class="quiz-container">
        <?php
        // Fetch quizzes from the database
        $stmt = $pdo->query("SELECT * FROM quizzes");
        $n=0;
        while ($quiz = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "
            <div class='quiz-box'>
                <img src='pic$n.jpg'>
                <div class='quiz-title'>" . htmlspecialchars($quiz['title']) . "</div>
                <a href='quiz.php?quiz_id=" . $quiz['id'] . "'><button>Take Quiz</button></a>
            </div>";
            $n++;
        }
        ?>  
    </div>

    <div class="button-container">
        <a href="past_scores.php"><button>Past Quizes</button></a>
        <a href="logout.php"><button>Logout</button></a>
    </div>
</body>
</html>
