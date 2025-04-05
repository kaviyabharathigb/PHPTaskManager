<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect if not logged in
    header("Location: login.php");
    exit();
}
?>
