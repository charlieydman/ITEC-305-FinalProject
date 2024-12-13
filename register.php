<?php
    // Start session
    session_start();
    require 'db.php'; // Include database connection

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = trim($_POST['username']);
        $pass = $_POST['password'];
        $confirmPass = $_POST['confirm_password'];

        // Check if passwords match
        if ($pass !== $confirmPass) {
            $error = "Passwords do not match.";
        } else {
            // Check if the username already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $stmt->execute([$user]);
            $usernameExists = $stmt->fetchColumn();

            if ($usernameExists) {
                $error = "This username is taken.";
            } else {
                // Hash the password
                $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
                try {
                    $stmt->execute([$user, $hashedPass]);
                    // Redirect to login page after successful registration
                    header('Location: login.php');
                    exit;  // Make sure no further code is executed after the redirect
                } catch (PDOException $e) {
                    $error = "Error: " . $e->getMessage();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        /* Style for success message */
        .success {
            display: <?php echo isset($success) ? 'block' : 'none'; ?>;
            background-color: green;
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
    <div>
        <h1>Register</h1>

        <!-- Error popup for password mismatch or username taken -->
        <?php if (isset($error)): ?>
            <div class="popup"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Success message -->
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

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

            <div class="button-container">
                <button type="submit">Register</button>
                <a href="index.php"><button type="button">Back</button></a>
            </div>
        </form>
    </div>
</body>
</html>
