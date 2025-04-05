<?php
$host = "localhost";
$user = "root";  // Default user
$pass = "";      // Leave empty for default setup
$db = "task_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
