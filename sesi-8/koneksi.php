<?php
$host = "localhost";
$username = "root";
$password = "new_password";
$dbname = "bootcamp_db3";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully\n";

// $sql_insert_user_data = "INSERT INTO users (name, email) VALUES ('johndoe3', 'johndoe3@example.com')";
// $conn->query($sql_insert_user_data);

// $delete_user_data = "DELETE FROM users WHERE id = 4";
// $conn->query($delete_user_data);