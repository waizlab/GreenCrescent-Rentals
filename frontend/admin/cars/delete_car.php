<?php
include '../../../backend/auth/admin_guard.php';
include '../../../backend/db/db_connect.php';

$cid = $_GET['cid'] ?? 0;
if (!$cid) die("Car ID missing.");

$stmt = $conn->prepare("DELETE FROM cars WHERE cid = ?");
$stmt->bind_param("i", $cid);
if ($stmt->execute()) {
    header("Location: list_cars.php"); // redirect back to car list
    exit;
} else {
    die("Failed to delete car.");
}
