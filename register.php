<?php
    // Start session
    session_start();
    require 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = trim($_POST['username']);
        $pass = $_POST['password'];
        $confirmPass = $_POST['confirm_password'];

        if ($pass !== $confirmPass) {
            echo "Passwords do not match.";
            exit;
        }

        // Hash the password
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        try {
            $stmt->execute([$user, $hashedPass]);
            echo "Registration successful! <a href='login.php'>Login here</a>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div>
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <br><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
