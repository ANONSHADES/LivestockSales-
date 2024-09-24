<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$user_id = (int)$conn->query("SELECT id FROM users WHERE username = '$username'")->fetch_assoc()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming form submission includes livestock_id, quantity, and payment_method
    $livestock_id = $_POST['livestock_id'];
    $quantity = $_POST['quantity'];
    $payment_method = $_POST['payment_method'];

    // Validate input (basic validation, assuming numeric values for quantity)
    if (!is_numeric($quantity) || $quantity <= 0) {
        $error = "Invalid quantity. Please enter a valid quantity.";
    } else {
        // Check if sufficient stock is available
        $livestock = $conn->query("SELECT * FROM livestock WHERE id = $livestock_id")->fetch_assoc();
        if ($livestock['stock'] < $quantity) {
            $error = "Insufficient stock available for {$livestock['name']}. Available stock: {$livestock['stock']}.";
        } else {
            // Calculate total price
            $price = $livestock['price'];
            $total_price = $quantity * $price;

            // Insert order into database
            $sql = "INSERT INTO orders (user_id, livestock_id, quantity, total_price, payment_method, status, order_date) 
                    VALUES ($user_id, $livestock_id, $quantity, $total_price, '$payment_method', 'pending', NOW())";

            if ($conn->query($sql)) {
                // Update livestock stock
                $new_stock = $livestock['stock'] - $quantity;
                $conn->query("UPDATE livestock SET stock = $new_stock WHERE id = $livestock_id");

                // Redirect to view orders page
                header('Location: view_orders.php');
                exit();
            } else {
                $error = "Error occurred while placing order. Please try again.";
            }
        }
    }
}

// Fetch available livestock for dropdown
$livestock_options = '';
$result = $conn->query("SELECT * FROM livestock WHERE stock > 0");
while ($row = $result->fetch_assoc()) {
    $livestock_options .= "<option value='{$row['id']}'>{$row['name']} (Price: {$row['price']} / Stock: {$row['stock']})</option>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Place Order</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
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
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: slideInUp 1s ease; /* Slide-in animation */
        }
        @keyframes slideInUp {
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
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="number"],
        select {
            width: calc(100% - 22px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        .submit-button {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .submit-button:hover {
            background-color: #45a049;
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        .back-button button {
            padding: 10px 20px;
            background-color: #ccc;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .back-button button:hover {
            background-color: #ddd;
        }
        @media screen and (max-width: 480px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Place Order</h2>
        <form method="post">
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <label for="livestock">Select Livestock:</label>
            <select id="livestock" name="livestock_id">
                <?php echo $livestock_options; ?>
            </select>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required>
            <label for="payment_method">Select Payment Method:</label>
            <select id="payment_method" name="payment_method">
                <option value="Credit Card">Credit Card</option>
                <option value="M-Pesa">M-Pesa</option>
                <option value="Cash">Cash</option>
            </select>
            <input type="submit" value="Place Order" class="submit-button">
        </form>
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
