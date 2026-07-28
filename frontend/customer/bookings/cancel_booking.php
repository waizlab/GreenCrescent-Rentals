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

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/navbar.php'; ?>
<div class="dashboard-container">
    <?php if ($success): ?>
        <div class="confirmation-card">
            <h1>✅ Booking Cancelled</h1>
            <p>Hello <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>, your booking has been successfully cancelled.</p>

            <h2>Cancelled Booking Details:</h2>
            <ul>
                <li><strong>Car:</strong> <?= htmlspecialchars($booking['make'].' '.$booking['model']); ?> (<?= htmlspecialchars($booking['type']); ?>)</li>
                <li><strong>Registration No:</strong> <?= htmlspecialchars($booking['carreg']); ?></li>
                <li><strong>Start Date:</strong> <?= htmlspecialchars($booking['start_date']); ?></li>
                <li><strong>End Date:</strong> <?= htmlspecialchars($booking['end_date']); ?></li>
                <li><strong>Total Fare:</strong> PKR <?= number_format($booking['total_fare'],2); ?></li>
            </ul>

            <p>We hope to serve you again in the future. Visit <a href="my_bookings.php">My Bookings</a> to view your other bookings.</p>
        </div>
    <?php else: ?>
        <div class="confirmation-card error">
            <h1>❌ Cancellation Failed</h1>
            <p>Sorry, your booking could not be cancelled. Please try again later.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.dashboard-container {
    max-width: 700px;
    margin: 50px auto;
    padding: 20px;
    font-family: 'Lato', sans-serif;
}

.confirmation-card {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.confirmation-card.error {
    background: #f8d7da;
    color: #721c24;
}

.confirmation-card h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    color: #228B22;
    margin-bottom: 20px;
}

.confirmation-card ul {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
    text-align: left;
}

.confirmation-card ul li {
    margin-bottom: 8px;
    font-size: 16px;
}

.confirmation-card p {
    font-size: 16px;
    line-height: 1.5;
}

.confirmation-card a {
    color: #228B22;
    text-decoration: none;
    font-weight: bold;
}

.confirmation-card a:hover {
    text-decoration: underline;
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
