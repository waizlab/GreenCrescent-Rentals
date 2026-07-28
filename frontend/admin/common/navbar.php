<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav style="background:#F5FFFA; padding:15px; display:flex; justify-content:center; gap:20px; box-shadow:0 2px 6px rgba(0,0,0,0.1); border-radius:8px; margin:20px auto; max-width:900px; font-family:'Lato', sans-serif;">
    <a href="/GreenCrescent_Rentals/frontend/admin/dashboard.php" style="text-decoration:none; color:#228B22; font-weight:bold;">🏠 Dashboard</a>
    <a href="/GreenCrescent_Rentals/frontend/admin/cars/list_cars.php" style="text-decoration:none; color:#228B22; font-weight:bold;">🚗 Manage Cars</a>
    <a href="/GreenCrescent_Rentals/frontend/admin/bookings/manage_bookings.php" style="text-decoration:none; color:#228B22; font-weight:bold;">📑 Bookings</a>
    <a href="/GreenCrescent_Rentals/frontend/admin/fares/manage_fares.php" style="text-decoration:none; color:#228B22; font-weight:bold;">💰 Rental Fares</a>
</nav>
