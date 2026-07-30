<?php
session_start();
include '../../../backend/db/db_connect.php';

// book_form.php
if (!isset($_SESSION['uid'])) {
    header("Location: ../../users/login.php");
    exit;
}

$car_id = $_GET['car_id'] ?? null;
if (!$car_id) {
    die("No car selected.");
}

// Fetch car details including rental fare
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
    die("Car not found.");
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2">Book Car</h1>

    <div class="card form-card">
        <h3 class="text-center"><?= htmlspecialchars($car['make'] . ' ' . $car['model']); ?> (<?= htmlspecialchars($car['year']); ?>)</h3>
        <p class="text-center text-muted mb-3">
            Type: <?= htmlspecialchars(ucfirst($car['type'])); ?> &middot;
            Price per day: <strong class="mono" style="color:var(--accent);"><?= $car['price_per_day']; ?> PKR</strong>
        </p>

        <form action="book_car.php" method="POST">
            <input type="hidden" name="car_id" value="<?= $car_id; ?>">

            <div class="field">
                <label>Start Date</label>
                <input type="date" name="start_date" required>
            </div>
            <div class="field">
                <label>End Date</label>
                <input type="date" name="end_date" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                Book Now
            </button>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>