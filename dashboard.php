<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$role = ($username == 'admin') ? 'admin' : 'customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            background-image: url('farm.jpeg'); /* Replace with your background image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scroll on smaller screens */
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            color: #333;
            animation: fadeIn 1s ease; /* Fade-in animation */
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 10px;
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
        .task-bar {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            animation: fadeInUp 1s ease; /* Fade-in and slide-up animation */
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .task-bar a {
            margin: 10px;
            padding: 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            overflow: hidden;
            position: relative;
        }
        .task-bar a::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            z-index: 0;
        }
        .task-bar a:hover::after {
            transform: translate(-50%, -50%) scale(1);
        }
        .task-bar a i {
            position: relative;
            z-index: 1;
        }
        .task-bar a:hover {
            background-color: #45a049;
        }
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            animation: fadeIn 1s ease; /* Fade-in animation */
        }
        .logout a {
            color: #fff; /* White text */
            background-color: #f44336; /* Red background */
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .logout a:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
        @media screen and (max-width: 768px) {
            .task-bar {
                flex-wrap: wrap;
            }
            .task-bar a {
                width: calc(50% - 20px);
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="welcome-message">
            <h2>Welcome, <?php echo $username; ?>!</h2>
            <?php if ($role == 'customer'): ?>
                <p>Explore our selection of quality livestock and place your order today!</p>
            <?php elseif ($role == 'admin'): ?>
                <p>Manage your livestock inventory, view orders, and generate insightful reports.</p>
            <?php endif; ?>
        </div>
        <div class="task-bar">
            <?php if ($role == 'customer'): ?>
                <a href="view_livestock.php"><i class="fas fa-eye"></i> View Livestock</a>
                <a href="order.php"><i class="fas fa-shopping-cart"></i> Order</a>
                <a href="view_orders.php"><i class="fas fa-list-alt"></i> View My Orders</a>
            <?php elseif ($role == 'admin'): ?>
                <a href="add_livestock.php"><i class="fas fa-plus"></i> Add Livestock</a>
                <a href="manage_livestock.php"><i class="fas fa-cog"></i> Manage Livestock</a>
                <a href="view_orders.php"><i class="fas fa-list-alt"></i> View Orders</a>
                <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
