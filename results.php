<?php
    session_start();
    $quiz_id = $_POST['quiz_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll();

    $score = 0;
    $total = count($questions);
    foreach ($questions as $index => $question) {
        $user_answer = $_POST["q$index"] ?? null;
        if ($user_answer == $question['correct_answer']) {
            $score++;
        } else {
            echo "<p>Question: " . htmlspecialchars($question['question']) . "<br>";
            echo "Correct Answer: " . htmlspecialchars($question["answer_" . $question['correct_answer']]) . "</p>";
        }
    }

    // Save score
    $stmt = $pdo->prepare("INSERT INTO scores (user_id, quiz_id, score, total) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $quiz_id, $score, $total]);

    echo "Your score: $score / $total";
