<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "INSERT INTO properties (name, location, price, description) 
            VALUES ('$name', '$location', '$price', '$description')";

    if ($conn->query($sql) === TRUE) {
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="post">
    Name: <input type="text" name="name" required><br>
    Location: <input type="text" name="location" required><br>
    Price: <input type="number" name="price" required><br>
    Description: <textarea name="description"></textarea><br>
    <button type="submit">Add Property</button>
</form>
