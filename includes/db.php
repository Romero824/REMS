<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'rems_db';

// First, create a connection without selecting a database
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === TRUE) {
    // Select the database
    $conn->select_db($db_name);
    
    // Import the database structure
    $sql_file = file_get_contents(__DIR__ . '/../database.sql');
    $conn->multi_query($sql_file);
    
    // Clear any remaining results
    while ($conn->more_results() && $conn->next_result()) {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    }
} else {
    die("Error creating database: " . $conn->error);
}
?> 