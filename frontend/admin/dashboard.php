<?php
include '../../backend/auth/admin_guard.php'; // Ensure only admin can access
include '../../backend/db/db_connect.php';     // DB connection
// admin.dashboard.php

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

<div class="container section">

    <h1 class="text-center mb-3">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> <span class="text-muted">(<?php echo htmlspecialchars($_SESSION['role']); ?>)</span></h1>

    <div class="grid grid-stats mb-3">
        <div class="card card-screws text-center">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent); margin:0 auto 12px;"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
            <h3 class="label">Total Cars</h3>
            <p class="mono" style="font-size:1.5rem; font-weight:700; color:var(--text);"><?php echo $totalCars; ?></p>
        </div>
        <div class="card card-screws text-center">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--status-online); margin:0 auto 12px;"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
            <h3 class="label">Available Cars</h3>
            <p class="mono" style="font-size:1.5rem; font-weight:700; color:var(--text);"><?php echo $availableCars; ?></p>
        </div>
        <div class="card card-screws text-center">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent); margin:0 auto 12px;"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            <h3 class="label">Total Bookings</h3>
            <p class="mono" style="font-size:1.5rem; font-weight:700; color:var(--text);"><?php echo $totalBookings; ?></p>
        </div>
        <div class="card card-screws text-center">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent); margin:0 auto 12px;"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
            <h3 class="label">Total Revenue</h3>
            <p class="mono" style="font-size:1.5rem; font-weight:700; color:var(--text);"><?php echo number_format($totalRevenue); ?> PKR</p>
        </div>
    </div>

    <div class="flex flex-center gap-2" style="flex-wrap:wrap;">
        <a href="cars/list_cars.php" class="btn btn-secondary">Manage Cars</a>
        <a href="bookings/view_bookings.php" class="btn btn-secondary">View Bookings</a>
        <a href="fares/manage_fares.php" class="btn btn-secondary">Manage Rental Fares</a>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>