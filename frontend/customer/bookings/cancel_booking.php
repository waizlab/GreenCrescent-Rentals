<?php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'customer') {
    die("Access denied.");
}

include '../../../backend/db/db_connect.php';

$bid = intval($_GET['bid'] ?? 0);
if (!$bid) die("Invalid booking ID");

// Fetch booking details before cancelling
$stmt = $conn->prepare("
    SELECT b.bid, c.make, c.model, c.carreg, c.type, b.start_date, b.end_date, b.total_fare, b.status
    FROM bookings b
    JOIN cars c ON b.car_id = c.cid
    WHERE b.bid=? AND b.user_id=?
");
$stmt->bind_param("ii", $bid, $_SESSION['uid']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

// Update status to cancelled
$update = $conn->prepare("UPDATE bookings SET status='cancelled' WHERE bid=? AND user_id=?");
$update->bind_param("ii", $bid, $_SESSION['uid']);
$success = $update->execute();
$update->close();
$conn->close();
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/navbar.php'; ?>

<div class="container section">
    <?php if ($success): ?>
        <div class="card confirmation-card">
            <div class="confirmation-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
            </div>
            <h1>Booking Cancelled</h1>
            <p>Hello <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>, your booking has been successfully cancelled.</p>

            <h3 class="text-center mt-2">Cancelled Booking Details</h3>
            <ul class="confirmation-list">
                <li><strong>Car:</strong> <?= htmlspecialchars($booking['make'].' '.$booking['model']); ?> (<?= htmlspecialchars($booking['type']); ?>)</li>
                <li><strong>Registration No:</strong> <?= htmlspecialchars($booking['carreg']); ?></li>
                <li><strong>Start Date:</strong> <?= htmlspecialchars($booking['start_date']); ?></li>
                <li><strong>End Date:</strong> <?= htmlspecialchars($booking['end_date']); ?></li>
                <li><strong>Total Fare:</strong> <span class="mono" style="color:var(--accent); font-weight:700;">PKR <?= number_format($booking['total_fare'],2); ?></span></li>
            </ul>

            <p>We hope to serve you again in the future. Visit <a href="my_bookings.php">My Bookings</a> to view your other bookings.</p>
        </div>
    <?php else: ?>
        <div class="card confirmation-card error">
            <div class="confirmation-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
            </div>
            <h1>Cancellation Failed</h1>
            <p>Sorry, your booking could not be cancelled. Please try again later.</p>
        </div>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>