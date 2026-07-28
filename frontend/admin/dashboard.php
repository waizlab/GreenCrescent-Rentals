<?php
include '../../backend/auth/admin_guard.php'; // Ensure only admin can access
include '../../backend/db/db_connect.php';     // DB connection

// Fetch statistics for dashboard
$totalCarsQuery = $conn->query("SELECT COUNT(*) as total FROM cars");
$totalCars = $totalCarsQuery->fetch_assoc()['total'];

$availableCarsQuery = $conn->query("SELECT COUNT(*) as available FROM cars WHERE availability='available'");
$availableCars = $availableCarsQuery->fetch_assoc()['available'];

$totalBookingsQuery = $conn->query("SELECT COUNT(*) as total FROM bookings");
$totalBookings = $totalBookingsQuery->fetch_assoc()['total'];

$totalRevenueQuery = $conn->query("SELECT SUM(total_fare) as revenue FROM bookings WHERE status='completed'");
$totalRevenue = $totalRevenueQuery->fetch_assoc()['revenue'] ?? 0;
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>


<div class="dashboard-container">

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</h1>

    <div class="cards-container">
        <div class="stat-card">
            <div class="icon">🚗</div>
            <h3>Total Cars</h3>
            <p><?php echo $totalCars; ?></p>
        </div>
        <div class="stat-card">
            <div class="icon">✅</div>
            <h3>Available Cars</h3>
            <p><?php echo $availableCars; ?></p>
        </div>
        <div class="stat-card">
            <div class="icon">📄</div>
            <h3>Total Bookings</h3>
            <p><?php echo $totalBookings; ?></p>
        </div>
        <div class="stat-card">
            <div class="icon">💰</div>
            <h3>Total Revenue</h3>
            <p><?php echo number_format($totalRevenue); ?> PKR</p>
        </div>
    </div>

    <div class="links-container">
        <a href="cars/list_cars.php" class="dashboard-btn">Manage Cars</a>
        <a href="bookings/view_bookings.php" class="dashboard-btn">View Bookings</a>
        <a href="fares/manage_fares.php" class="dashboard-btn">Manage Rental Fares</a>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    font-family: 'Lato', sans-serif;
}

.dashboard-container h1 {
    text-align: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 32px;
    color: #228B22;
    margin-bottom: 40px;
}

/* Cards */
.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: #F5FFFA;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.stat-card .icon {
    font-size: 36px;
    margin-bottom: 15px;
}

.stat-card h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    color: #2E2E2E;
    margin-bottom: 10px;
}

.stat-card p {
    font-size: 24px;
    font-weight: bold;
    color: #228B22;
}

/* Dashboard buttons */
.links-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.dashboard-btn {
    padding: 12px 20px;
    background: #228B22;
    color: white;
    font-weight: bold;
    text-decoration: none;
    border-radius: 8px;
    transition: background 0.3s, transform 0.2s;
    text-align: center;
    min-width: 180px;
}

.dashboard-btn:hover {
    background: #2C3E50;
    transform: translateY(-3px);
}

/* Responsive */
@media screen and (max-width: 600px) {
    .cards-container {
        grid-template-columns: 1fr;
    }
    .dashboard-btn {
        width: 100%;
    }
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
