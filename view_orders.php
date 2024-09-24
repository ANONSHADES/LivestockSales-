<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$role = ($username == 'admin') ? 'admin' : 'customer';

if ($role == 'customer') {
    $user_id = (int)$conn->query("SELECT id FROM users WHERE username = '$username'")->fetch_assoc()['id'];
    $sql = "SELECT orders.id, livestock.name, orders.quantity, orders.payment_method, orders.status, orders.order_date, 
                   (orders.quantity * livestock.price) AS total_price_in_ksh
            FROM orders 
            JOIN livestock ON orders.livestock_id = livestock.id 
            WHERE orders.user_id = $user_id";
} else {
    $sql = "SELECT orders.id, livestock.name, orders.quantity, orders.payment_method, orders.status, orders.order_date, users.username,
                   (orders.quantity * livestock.price) AS total_price_in_ksh
            FROM orders 
            JOIN livestock ON orders.livestock_id = livestock.id 
            JOIN users ON orders.user_id = users.id";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $role == 'customer' ? 'My Orders' : 'All Orders'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
        .container {
            max-width: 1200px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: slideInUp 1s ease; /* Slide-in animation */
        }
        @keyframes slideInUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            text-align: center;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .actions a.confirm {
            background-color: #4CAF50;
        }
        .actions a.confirm:hover {
            background-color: #45a049;
        }
        .actions a.delete {
            background-color: #f44336;
        }
        .actions a.delete:hover {
            background-color: #e32f2f;
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        .back-button button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            cursor: pointer;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .back-button button:hover {
            background-color: #45a049;
        }
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $role == 'customer' ? 'My Orders' : 'All Orders'; ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Livestock Name</th>
                    <th>Quantity</th>
                    <th>Total Price (Ksh)</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <?php if ($role == 'admin'): ?>
                        <th>Customer</th>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo 'Ksh ' . number_format($row['total_price_in_ksh'], 2); ?></td>
                    <td><?php echo $row['payment_method']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                    <?php if ($role == 'admin'): ?>
                        <td><?php echo $row['username']; ?></td>
                        <td class="actions">
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="confirm_payment.php?order_id=<?php echo $row['id']; ?>" class="confirm">Confirm Payment</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="back-button">
            <button onclick="goBack()">Back</button>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>

