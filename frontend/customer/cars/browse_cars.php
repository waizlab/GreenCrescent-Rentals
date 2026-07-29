<?php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../backend/users/login.php");
    exit;
}

include '../../common/header.php';
include '../../common/navbar.php';
include '../../../backend/db/db_connect.php';

// browse_cars.php
$result = $conn->query("
    SELECT c.cid, c.carreg, c.make, c.model, c.image, f.price_per_day
    FROM cars c
    JOIN rental_fares f ON c.cid=f.car_id
    WHERE c.availability='available'
");
?>

<div class="container section">
    <h2 class="text-center mb-3">Available Cars</h2>

    <div class="grid grid-cars">
        <?php while($car = $result->fetch_assoc()): ?>
            <div class="card car-card card-screws">
                <img src="../../images/<?php echo htmlspecialchars($car['image']); ?>"
                     alt="<?php echo htmlspecialchars($car['make'].' '.$car['model']); ?>">
                <h3><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h3>
                <p class="text-muted">Price per day</p>
                <p class="price"><?php echo $car['price_per_day']; ?> PKR</p>
                <a href="../bookings/book_form.php?car_id=<?php echo $car['cid']; ?>">
                    <button class="btn btn-primary" style="width:100%;">Book Now</button>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../../common/footer.php'; ?>