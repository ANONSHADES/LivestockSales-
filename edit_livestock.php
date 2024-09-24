<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM livestock WHERE id = $id";
    $result = $conn->query($sql);
    $livestock = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE livestock SET name='$name', description='$description', price='$price', stock='$stock' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Livestock updated successfully. <a href='manage_livestock.php'>Back to Manage Livestock</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Livestock</title>
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
            max-width: 400px; 
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
    <h2>Edit Livestock</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $livestock['id']; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $livestock['name']; ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required><?php echo $livestock['description']; ?></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $livestock['price']; ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" min="0" value="<?php echo $livestock['stock']; ?>" required>

        <button type="submit">Update Livestock</button>
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

