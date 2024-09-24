<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM livestock WHERE id = $delete_id";
    $conn->query($sql);
}

$sql = "SELECT * FROM livestock";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Livestock</title>
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
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background-color: #fff; 
            border: 1px solid #ddd; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
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
            background-color: #4CAF50; 
            border-radius: 4px; 
        }
        .actions a.delete { 
            background-color: #f44336; 
        }
        .actions a:hover { 
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
    <h2>Manage Livestock</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td class="actions">
                <a href="edit_livestock.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="manage_livestock.php?delete_id=<?php echo $row['id']; ?>" class="delete">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

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
