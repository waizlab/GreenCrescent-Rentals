<?php
session_start();
include '../../../backend/db/db_connect.php';

// Check if user is logged in
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

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/navbar.php'; ?>
<div class="dashboard-container">
    <h1>Book Car</h1>

    <div class="form-container">
        <h2><?= htmlspecialchars($car['make'] . ' ' . $car['model']); ?> (<?= htmlspecialchars($car['year']); ?>)</h2>
        <p>Type: <?= htmlspecialchars(ucfirst($car['type'])); ?> | Price per day: <strong><?= $car['price_per_day']; ?> PKR</strong></p>

        <form action="book_car.php" method="POST">
            <input type="hidden" name="car_id" value="<?= $car_id; ?>">

            <label>Start Date <span>📅</span></label>
            <input type="date" name="start_date" required>

            <label>End Date <span>📅</span></label>
            <input type="date" name="end_date" required>

            <button type="submit">🚗 Book Now</button>
        </form>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    font-family: 'Lato', sans-serif;
}

.dashboard-container h1 {
    text-align: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    color: #228B22;
    margin-bottom: 25px;
}

.form-container {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.form-container h2 {
    font-size: 22px;
    color: #2E2E2E;
    margin-bottom: 10px;
    text-align: center;
}

.form-container p {
    font-size: 16px;
    margin-bottom: 20px;
    text-align: center;
}

.form-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 14px;
    color: #2E2E2E;
}

.form-container label span {
    margin-left: 6px;
}

.form-container input {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 15px;
    border: 1px solid #B2BEB5;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

.form-container input:focus {
    border-color: #228B22;
    outline: none;
}

.form-container button {
    width: 100%;
    background: #228B22;
    color: white;
    font-weight: bold;
    padding: 12px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.form-container button:hover {
    background: #2C3E50;
    transform: scale(1.02);
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
