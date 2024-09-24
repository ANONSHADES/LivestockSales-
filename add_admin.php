<?php
include 'db.php';

$username = 'admin';
$password = password_hash('2024', PASSWORD_DEFAULT);
$role = 'admin';

$sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "Admin user added successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
