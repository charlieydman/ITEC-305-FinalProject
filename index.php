<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Quiz Website</title>
</head>
<body>
    <h1>Welcome to the Quiz Website!</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in.</p>
        <a href="quiz_select.php">Go to Quiz Selection</a><br>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <p>Please log in or register to continue:</p>
        <a href="login.php">Login</a><br>
        <a href="register.php">Register</a>
    <?php endif; ?>
</body>
</html>
