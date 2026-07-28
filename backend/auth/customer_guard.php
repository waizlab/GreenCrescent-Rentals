<?php
include 'session_init.php';

// Block access if not logged in or not customer
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'customer') {
    die("Access denied. Customers only.");
}
?>
