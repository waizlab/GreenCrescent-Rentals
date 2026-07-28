<?php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../backend/users/login.php");
    exit;
}

include '../../common/header.php';
include '../../common/navbar.php';
include '../../../backend/db/db_connect.php';

// Fetch available cars with fare
$result = $conn->query("
    SELECT c.cid, c.carreg, c.make, c.model, c.image, f.price_per_day
    FROM cars c
    JOIN rental_fares f ON c.cid=f.car_id
    WHERE c.availability='available'
");
?>

<h2 style="text-align:center; margin-top:20px;">Available Cars</h2>

<div class="car-grid">
    <?php while($car = $result->fetch_assoc()): ?>
        <div class="car-card">
            <img src="../../images/<?php echo htmlspecialchars($car['image']); ?>" 
                 alt="<?php echo htmlspecialchars($car['make'].' '.$car['model']); ?>">
            <h3><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h3>
            <p>Price per day: <?php echo $car['price_per_day']; ?> PKR</p>
            <a href="../bookings/book_form.php?car_id=<?php echo $car['cid']; ?>">
                <button style="background:#228B22; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer;">
                    Book Now
                </button>
            </a>
        </div>
    <?php endwhile; ?>
</div>

<style>
.car-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}

.car-card {
    background:#F5FFFA;
    padding:15px;
    border-radius:6px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
    text-align:center;
    font-family: 'Lato', sans-serif;
}

.car-card img {
    width:100%;
    height:auto;
    border-radius:6px;
}
</style>
<?php include '../../common/footer.php'; ?>
