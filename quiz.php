<?php
session_start();
require 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if a quiz has been selected
if (!isset($_GET['quiz_id'])) {
    echo "No quiz selected. Please select a quiz.";
    exit;
}

$quiz_id = $_GET['quiz_id']; // Get the quiz ID from URL

// Fetch the quiz title
$stmt = $pdo->prepare("SELECT title FROM quizzes WHERE id = :quiz_id");
$stmt->execute(['quiz_id' => $quiz_id]);
$quiz = $stmt->fetch();

if (!$quiz) {
    echo "Invalid quiz.";
    exit;
}

// Fetch 10 random questions for the selected quiz
$stmt = $pdo->prepare("
    SELECT DISTINCT id AS question_id, question_text 
    FROM questions 
    WHERE quiz_id = :quiz_id
    ORDER BY RAND()
    LIMIT 10
");
$stmt->execute(['quiz_id' => $quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch options for each question
foreach ($questions as &$question) {
    $stmt = $pdo->prepare("
        SELECT id AS option_id, option_text 
        FROM options 
        WHERE question_id = :question_id
        ORDER BY RAND()
    ");
    $stmt->execute(['question_id' => $question['question_id']]);
    $question['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz['title']); ?> Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($quiz['title']); ?> Quiz</h1>
        <form action="results.php" method="POST">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
            <?php foreach ($questions as $q): ?>
                <div class="question">
                    <p data-id="<?php echo $q['question_id']; ?>"><strong><?php echo htmlspecialchars($q['question_text']); ?></strong></p>
                    <?php foreach ($q['options'] as $o): ?>
                        <label>
                            <input type="radio" name="answers[<?php echo $q['question_id']; ?>]" value="<?php echo $o['option_id']; ?>" required>
                            <?php echo htmlspecialchars($o['option_text']); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
                <hr>
            <?php endforeach; ?>
            <div class="button-container">
                <a href="quiz_select.php"><button type="button">Back</button></a>
                <button type="submit">Submit Quiz</button>
            </div>
        </form>
    </div>
</body>
</html>
