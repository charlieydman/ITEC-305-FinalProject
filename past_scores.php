<?php
session_start();
require 'db.php'; // Include database connection

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch the user's past quiz scores along with the quiz titles and the date taken
$stmt = $pdo->prepare("SELECT r.score, q.title, r.date_taken 
                       FROM results r 
                       JOIN quizzes q ON r.quiz_id = q.id 
                       WHERE r.user_id = :user_id
                       ORDER BY r.date_taken DESC");
$stmt->execute(['user_id' => $user_id]);
$scores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Past Quiz Scores</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Your Past Quiz Scores</h1>

    <?php if (empty($scores)): ?>
        <p class="no-scores">You have not taken any quizzes yet.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Quiz Title</th>
                        <th>Score</th>
                        <th>Date Taken</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $score): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($score['title']); ?></td>
                            <td>
                                <?php 
                                    // Display score out of 10
                                    $score_out_of_10 = min($score['score'], 10); // Ensure it doesn't exceed 10
                                    echo $score_out_of_10 . '/10';
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($score['date_taken']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="button-container">
        <a href="quiz_select.php"><button type="button">Back</button></a>
    </div>
</body>
</html>
