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
<div class="dashboard-container">
    <?php if ($success): ?>
        <div class="confirmation-card">
            <h1>🎉 Booking Confirmed!</h1>
            <p>Thank you, <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>! Your booking has been successfully recorded.</p>
            
            <h2>Booking Details:</h2>
            <ul>
                <li><strong>Car:</strong> <?= htmlspecialchars($car['make'] . ' ' . $car['model']); ?> (<?= htmlspecialchars($car['year']); ?>)</li>
                <li><strong>Registration No:</strong> <?= htmlspecialchars($car['carreg']); ?></li>
                <li><strong>Type:</strong> <?= ucfirst(htmlspecialchars($car['type'])); ?></li>
                <li><strong>Start Date:</strong> <?= htmlspecialchars($start_date); ?></li>
                <li><strong>End Date:</strong> <?= htmlspecialchars($end_date); ?></li>
                <li><strong>Days:</strong> <?= $days; ?> day(s)</li>
                <li><strong>Total Fare:</strong> <?= number_format($total_fare, 2); ?> PKR</li>
            </ul>

            <p>We hope you enjoy your ride! Please visit <a href="../../frontend/customer/dashboard.php">your dashboard</a> for more bookings.</p>
        </div>
    <?php else: ?>
        <div class="confirmation-card error">
            <h1>❌ Booking Failed</h1>
            <p>Sorry, something went wrong. Please try again later.</p>
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
