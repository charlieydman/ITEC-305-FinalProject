<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Quiz Website</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome to the Quiz Website!</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="quiz_select.php"><button>Go to Quizes</button></a>
        <a href="logout.php"><button>Logout</button></a>
    <?php else: ?>
        <p>Please log in or register to continue:</p>
        <div class="button-container">
            <a href="login.php"><button>Login</button></a>
            <a href="register.php"><button>Register</button></a>
        </div>
    <?php endif; ?>
</body>
</html>
