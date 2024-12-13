<?php
session_start();
require 'db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to quiz select page
        header("Location: quiz_select.php");
        exit;
    } else {
        // Set error message
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Style for the error popup */
        .popup {
            display: <?php echo isset($error) ? 'block' : 'none'; ?>;
            background-color: red;
            color: white;
            width: 50%;
            padding: 15px;
            position: relative;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Login</h1>

    <!-- Error popup for incorrect username or password -->
    <?php if (isset($error)): ?>
        <div class="popup"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br><br>
        </div>
        <div class="button-container">
            <button type="submit">Login</button>
            <a href="index.php"><button type="button">Back</button></a>
        </div>
    </form>
</body>
</html>
