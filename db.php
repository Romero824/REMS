<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'real_estate_db';

// Create connection with error handling
try {
    $conn = new mysqli($host, $user, $password, $db_name);
    
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    
    // Set charset to utf8mb4
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die('Database connection error: ' . $e->getMessage());
}
?>
