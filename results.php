<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Validate and fetch quiz data
if (!isset($_POST['quiz_id']) || !isset($_POST['answers'])) {
    header('Location: quiz_select.php');
    exit;
}
$quiz_id = $_POST['quiz_id'];
$answers = $_POST['answers'];

// Fetch quiz title
$stmt = $pdo->prepare("SELECT title FROM quizzes WHERE id = :quiz_id");
$stmt->execute(['quiz_id' => $quiz_id]);
$quiz = $stmt->fetch();
if (!$quiz) {
    header('Location: quiz_select.php');
    exit;
}

// Calculate score
$score = 0;
foreach ($answers as $question_id => $answer_id) {
    $stmt = $pdo->prepare("SELECT is_correct FROM options WHERE id = :answer_id");
    $stmt->execute(['answer_id' => $answer_id]);
    if ($stmt->fetchColumn()) {
        $score++;
    }
}

// Save results
$stmt = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score) VALUES (:user_id, :quiz_id, :score)");
$stmt->execute([
    'user_id' => $_SESSION['user_id'],
    'quiz_id' => $quiz_id,
    'score' => $score
]);

// Calculate the percentage score
$percentage = ($score / count($answers)) * 100;

// Display results
echo "<h1>Quiz Results: " . htmlspecialchars($quiz['title']) . "</h1><hr>";
echo "<h3>Your score: $score out of " . count($answers) . " (" . round($percentage, 2) . "%)</h3>";

$num = 1;
foreach ($answers as $question_id => $answer_id) {
    // Fetch question and user's selected answer
    $stmt = $pdo->prepare("
        SELECT 
            q.question_text, 
            o.option_text AS user_answer 
        FROM questions q 
        LEFT JOIN options o ON o.id = :answer_id 
        WHERE q.id = :question_id
    ");
    $stmt->execute(['question_id' => $question_id, 'answer_id' => $answer_id]);
    $result = $stmt->fetch();

    // Fetch correct answer
    $stmt = $pdo->prepare("
        SELECT option_text 
        FROM options 
        WHERE question_id = :question_id AND is_correct = 1
    ");
    $stmt->execute(['question_id' => $question_id]);
    $correct_answer = $stmt->fetchColumn();

    // Display question, user's answer, and correct answer or "Correct"
    echo "<div>";
    echo "<p><strong>Question $num:</strong> " . htmlspecialchars($result['question_text']) . "<br>";
    
    if (htmlspecialchars($result['user_answer']) == $correct_answer) {
        // If the answer is correct
        echo "<strong>Your Answer:</strong> <span style='color: green;'>Correct</span><br>";
    } else {
        // If the answer is incorrect
        echo "<strong>Your Answer:</strong><span style='color: red;'> " . htmlspecialchars($result['user_answer'] ?? 'No answer selected') . "<br>";
        echo "<span style='color: black;'><strong>Correct Answer:</strong> " . htmlspecialchars($correct_answer ?? 'No correct answer found') . "<br>";
    }

    echo "</div>";
    $num++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz['title']); ?> Quiz Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="button-container">
        <!-- Button to go back to quiz select page -->
        <a href="quiz_select.php"><button type="submit" class="back-button">Back</button></a>

        <!-- Button to retake the quiz -->
        <a href="quiz.php?quiz_id=<?php echo $quiz_id; ?>"><button type="submit" class="retake-button">Retake Quiz</button></a>
    </div>
</body>
</html>
