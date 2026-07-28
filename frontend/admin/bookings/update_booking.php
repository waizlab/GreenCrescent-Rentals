<?php
include '../../../backend/db/db_connect.php';
include '../../../backend/auth/admin_guard.php';

$bid = intval($_GET['bid'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$bid || !in_array($action, ['confirm','cancel','complete'])) {
    die("Invalid request.");
}

// Map action to ENUM value
$statusMap = [
    'confirm'  => 'confirmed',
    'cancel'   => 'cancelled',
    'complete' => 'completed'
];

$status = $statusMap[$action] ?? null;
if (!$status) {
    die("Invalid status action.");
}

// Update booking status
$sql = "UPDATE bookings SET status=? WHERE bid=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $bid);
$stmt->execute();

header("Location: manage_bookings.php");
exit;
