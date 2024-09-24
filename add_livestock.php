<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Check if the livestock already exists
    $check_sql = "SELECT * FROM livestock WHERE name = '$name'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo "Error: Livestock with the same name already exists.";
    } else {
        // Insert the new livestock
        $sql = "INSERT INTO livestock (name, description, price, stock) VALUES ('$name', '$description', '$price', '$stock')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Livestock added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Livestock</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f7f7f7; 
            padding: 20px; 
        }
        h2 { 
            color: #333; 
            text-align: center; 
            margin-bottom: 20px; 
        }
        form { 
            margin: 0 auto; 
            max-width: 300px; 
            background-color: #fff; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
        }
        form input, form textarea, form button { 
            display: block; 
            margin-bottom: 10px; 
            padding: 10px; 
            width: calc(100% - 20px); 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-size: 14px; 
        }
        form button { 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        form button:hover { 
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
        }
        .back-button button:hover { 
            background-color: #ddd; 
        }
    </style>
</head>
<body>
    <h2>Add Livestock</h2>
    <form method="POST" action="">
        Name: <input type="text" name="name" required><br>
        Description: <textarea name="description" required></textarea><br>
        Price: <input type="number" name="price" step="0.01" required><br>
        Stock: <input type="number" name="stock" required><br>
        <button type="submit">Add Livestock</button>
    </form>

    <div class="back-button">
        <button onclick="goBack()">Back</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
