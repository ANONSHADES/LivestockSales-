<?php
include 'db.php';
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch sales data
$sql = "SELECT livestock.name, SUM(orders.quantity) AS total_quantity, SUM(orders.quantity * livestock.price) AS total_sales 
        FROM orders 
        JOIN livestock ON orders.livestock_id = livestock.id 
        GROUP BY livestock.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Reports</title>
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
        .print-button { 
            text-align: center; 
            margin-top: 20px; 
        }
        .print-button button { 
            padding: 10px 20px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
        }
        .print-button button:hover { 
            background-color: #45a049; 
        }
        .filter-form { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .filter-form select { 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            font-size: 14px; 
        }
        .filter-form button { 
            padding: 8px 20px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
            margin-left: 10px; 
        }
        .filter-form button:hover { 
            background-color: #45a049; 
        }
    </style>
</head>
<body>
    <h2>Sales Reports</h2>

    <!-- Filter form for month and year selection -->
    <div class="filter-form">
        <label for="month">Select Month:</label>
        <select id="month">
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>

        <label for="year">Select Year:</label>
        <select id="year">
            <?php 
            // Generate options for years from current year back to 2010
            $current_year = date('Y');
            for ($i = $current_year; $i >= 2010; $i--) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select>

        <button onclick="filterReports()">Apply Filter</button>
    </div>

    <!-- Sales reports table -->
    <table>
        <tr>
            <th>Livestock Name</th>
            <th>Total Quantity Sold</th>
            <th>Total Sales</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['total_quantity']; ?></td>
            <td><?php echo number_format($row['total_sales'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Print button -->
    <div class="print-button">
        <button onclick="printReports()">Print Reports</button>
    </div>

    <!-- Back button -->
    <div class="back-button">
        <button onclick="goBack()">Back</button>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function printReports() {
            window.print();
        }

        function filterReports() {
            var month = document.getElementById('month').value;
            var year = document.getElementById('year').value;
            var url = 'filter_reports.php?month=' + month + '&year=' + year;
            window.location.href = url;
        }
    </script>
</body>
</html>


