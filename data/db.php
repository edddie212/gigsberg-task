<?php

//DB Connection Info
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gigsberg_task";

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Initial connection failed: " . $conn->connect_error);
}

// Check database exists create if not
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== true) {
    die("Error creating database: " . $conn->error);
}
// Close connection
$conn->close();

// Now, connect to the newly created database
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "";
} catch (Exception $e) {
    echo $e->getMessage();
}


// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100),
    Email VARCHAR(100),
    Password VARCHAR(100),
    Birthdate DATE,
    Phone_number VARCHAR(10),
    URL VARCHAR(200)
)";


if ($conn->query($sql) === true) {
    echo "";
} else {
    echo "Error creating table: " . $conn->error;
}



?>
