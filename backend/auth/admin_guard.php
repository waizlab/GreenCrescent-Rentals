<?php
// Protect admin pages
session_start();

// Redirect to login if not logged in or not admin
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../frontend/login.php");
    exit;
}
