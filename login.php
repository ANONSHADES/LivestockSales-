<?php
include 'db.php';

session_start();

// Check if the user just registered
if (isset($_SESSION['registered'])) {
    $registered_message = "Registration successful! You can now login.";
    unset($_SESSION['registered']); // Clear the session variable after displaying the message
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease; /* Fade-in animation */
            background-image: url('farm.jpeg'); /* Replace with your background image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .login-container {
            max-width: 300px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #fff;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: slideInDown 1s ease; /* Slide-in animation */
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            text-align: left;
        }

        form input[type="text"],
        form input[type="password"],
        form button {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: calc(100% - 20px);
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="password"]:focus {
            border-color: #4CAF50;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 0;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #f44336;
            margin-top: 10px;
        }

        .register-message {
            color: #333;
            margin-top: 10px;
        }

        .register-message a {
            color: #4CAF50;
            text-decoration: none;
        }

        .register-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($registered_message)): ?>
            <div class="register-message"><?php echo $registered_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div class="register-message">
            New user? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>
