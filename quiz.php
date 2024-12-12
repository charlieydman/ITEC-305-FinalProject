<?php
    $quiz_id = $_GET['quiz_id'];
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY RAND() LIMIT 10");
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll();

    echo "<form action='results.php' method='POST'>";
    foreach ($questions as $index => $question) {
        echo "<p>" . htmlspecialchars($question['question']) . "</p>";
        for ($i = 1; $i <= 4; $i++) {
            echo "<input type='radio' name='q$index' value='$i'>" . htmlspecialchars($question["answer_$i"]) . "<br>";
        }
    }
    echo "<input type='hidden' name='quiz_id' value='$quiz_id'>";
    echo "<button type='submit'>Submit</button>";
    echo "</form>";
