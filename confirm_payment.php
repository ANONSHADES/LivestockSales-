<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $sql = "UPDATE orders SET status='fulfilled' WHERE id = $order_id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: print_receipt.php?order_id=$order_id");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


