<?php
session_start();
include '../../../backend/db/db_connect.php';

if (!isset($_SESSION['uid'])) {
    die("You must be logged in to book a car.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Collect input
$user_id    = $_SESSION['uid'];
$car_id     = $_POST['car_id'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date   = $_POST['end_date'] ?? null;

if (!$car_id || !$start_date || !$end_date) {
    die("All fields are required.");
}

// Validate dates
if (strtotime($end_date) < strtotime($start_date)) {
    die("End date must be after start date.");
}

// Fetch car details and fare
$stmt = $conn->prepare("
    SELECT c.carreg, c.make, c.model, c.year, c.type, f.price_per_day
    FROM cars c
    JOIN rental_fares f ON c.cid = f.car_id
    WHERE c.cid = ?
");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    die("Car details not found.");
}

// Calculate total fare
$days = (strtotime($end_date) - strtotime($start_date)) / (60*60*24);
$days = max(1, $days); // minimum 1 day
$total_fare = (float)$car['price_per_day'] * $days;

// Insert booking
$stmt_insert = $conn->prepare("
    INSERT INTO bookings (user_id, car_id, start_date, end_date, total_fare)
    VALUES (?, ?, ?, ?, ?)
");
$stmt_insert->bind_param("iissd", $user_id, $car_id, $start_date, $end_date, $total_fare);

$success = $stmt_insert->execute();
$stmt_insert->close();
$conn->close();
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/navbar.php'; ?>

<div class="container section">
    <?php if ($success): ?>
        <div class="card confirmation-card">
            <div class="confirmation-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
            </div>
            <h1>Booking Confirmed!</h1>
            <p>Thank you, <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>! Your booking has been successfully recorded.</p>

            <h3 class="text-center mt-2">Booking Details</h3>
            <ul class="confirmation-list">
                <li><strong>Car:</strong> <?= htmlspecialchars($car['make'] . ' ' . $car['model']); ?> (<?= htmlspecialchars($car['year']); ?>)</li>
                <li><strong>Registration No:</strong> <?= htmlspecialchars($car['carreg']); ?></li>
                <li><strong>Type:</strong> <?= ucfirst(htmlspecialchars($car['type'])); ?></li>
                <li><strong>Start Date:</strong> <?= htmlspecialchars($start_date); ?></li>
                <li><strong>End Date:</strong> <?= htmlspecialchars($end_date); ?></li>
                <li><strong>Days:</strong> <?= $days; ?> day(s)</li>
                <li><strong>Total Fare:</strong> <span class="mono" style="color:var(--accent); font-weight:700;"><?= number_format($total_fare, 2); ?> PKR</span></li>
            </ul>

            <p>We hope you enjoy your ride! Please visit <a href="../../frontend/customer/dashboard.php">your dashboard</a> for more bookings.</p>
        </div>
    <?php else: ?>
        <div class="card confirmation-card error">
            <div class="confirmation-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
            </div>
            <h1>Booking Failed</h1>
            <p>Sorry, something went wrong. Please try again later.</p>
        </div>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>