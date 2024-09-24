<?php
include 'db.php';
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Process form submission to filter by month and year
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = $_POST['month'];
    $year = $_POST['year'];

    // Construct SQL query to fetch filtered sales data
    $sql = "SELECT livestock.name, SUM(orders.quantity) AS total_quantity, SUM(orders.quantity * livestock.price) AS total_sales 
            FROM orders 
            JOIN livestock ON orders.livestock_id = livestock.id 
            WHERE MONTH(orders.order_date) = $month AND YEAR(orders.order_date) = $year
            GROUP BY livestock.id";
    
    $result = $conn->query($sql);
    $filtered_data = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filter Sales Reports</title>
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
            max-width: 300px; 
            margin: 0 auto; 
            background-color: #fff; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
        }
        form select, form button { 
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

        /* Print styles */
        @media print {
            body { 
                background-color: white; 
                padding: 0; 
            }
            h2 { 
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
            .no-print { 
                display: none; 
            }
        }
    </style>
</head>
<body>
    <h2>Filter Sales Reports</h2>
    <form method="post">
        <select name="month" required>
            <option value="">Select Month</option>
            <?php
            // Generate options for months
            for ($m = 1; $m <= 12; $m++) {
                $month_name = date("F", mktime(0, 0, 0, $m, 1));
                echo "<option value='$m'>$month_name</option>";
            }
            ?>
        </select>
        <select name="year" required>
            <option value="">Select Year</option>
            <?php
            // Generate options for years, assuming range from current year to 10 years past
            $current_year = date('Y');
            for ($y = $current_year; $y >= $current_year - 10; $y--) {
                echo "<option value='$y'>$y</option>";
            }
            ?>
        </select>
        <button type="submit">Filter Reports</button>
    </form>

    <?php if (isset($filtered_data)): ?>
        <!-- Display filtered sales reports -->
        <table>
            <tr>
                <th>Livestock Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales</th>
            </tr>
            <?php foreach ($filtered_data as $row): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['total_quantity']; ?></td>
                    <td><?php echo number_format($row['total_sales'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <script>
        // Hide unnecessary elements when printing
        function hideElementsOnPrint() {
            var elementsToHide = document.querySelectorAll('.no-print');
            elementsToHide.forEach(function(element) {
                element.style.display = 'none';
            });
        }

        // Call the function to hide elements when printing
        hideElementsOnPrint();
    </script>
</body>
</html>
