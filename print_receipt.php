<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $sql = "SELECT orders.id, orders.order_date, orders.quantity, orders.payment_method, livestock.name AS livestock_name, livestock.price, users.username 
            FROM orders 
            JOIN livestock ON orders.livestock_id = livestock.id 
            JOIN users ON orders.user_id = users.id 
            WHERE orders.id = $order_id";
    $result = $conn->query($sql);
    $order = $result->fetch_assoc();
} else {
    echo "No order ID provided.";
    exit();
}

$total_price = $order['price'] * $order['quantity'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; }
        .receipt { max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; background-color: #fff; }
        .receipt h2 { text-align: center; }
        .receipt table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .receipt table, .receipt th, .receipt td { border: 1px solid #ddd; padding: 10px; }
        .receipt th { background-color: #f2f2f2; }
        .receipt .total { text-align: right; font-weight: bold; }
        .back-button { margin-top: 20px; text-align: center; }
    </style>
    <script>
        function printReceipt() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="receipt">
        <h2>Order Receipt</h2>
        <table>
            <tr>
                <th>Order Date</th>
                <td><?php echo $order['order_date']; ?></td>
            </tr>
            <tr>
                <th>Customer</th>
                <td><?php echo $order['username']; ?></td>
            </tr>
            <tr>
                <th>Livestock Name</th>
                <td><?php echo $order['livestock_name']; ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?php echo $order['quantity']; ?></td>
            </tr>
            <tr>
                <th>Price per Unit</th>
                <td>Ksh <?php echo number_format($order['price'], 2); ?></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?php echo $order['payment_method']; ?></td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td>Ksh <?php echo number_format($total_price, 2); ?></td>
            </tr>
        </table>
    </div>
    <div class="back-button">
        <button onclick="goBack()">Back</button>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
        // Immediately print the receipt when the page loads (for printing functionality)
        window.onload = function() {
            printReceipt();
        };
    </script>
</body>
</html>
