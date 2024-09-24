<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        $registration_message = "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        .register-container {
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

        .login-message {
            color: #333;
            margin-top: 10px;
        }

        .login-message a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if (isset($registration_message)): ?>
            <div class="login-message"><?php echo $registration_message; ?></div>
        <?php else: ?>
            <form method="POST" action="">
                Username: <input type="text" name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <button type="submit">Register</button>
            </form>
            <div class="login-message">
                Already registered? <a href="login.php">Login here</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
