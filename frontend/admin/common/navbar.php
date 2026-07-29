<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$__current = basename($_SERVER['PHP_SELF']);
$__inCars = strpos($_SERVER['PHP_SELF'], '/admin/cars/') !== false;
$__inBookings = strpos($_SERVER['PHP_SELF'], '/admin/bookings/') !== false;
$__inFares = strpos($_SERVER['PHP_SELF'], '/admin/fares/') !== false;
?>
<nav class="navbar">
    <a href="/GreenCrescent_Rentals/frontend/admin/dashboard.php" class="<?php echo $__current === 'dashboard.php' ? 'active' : ''; ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        Dashboard
    </a>
    <a href="/GreenCrescent_Rentals/frontend/admin/cars/list_cars.php" class="<?php echo $__inCars ? 'active' : ''; ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
        Manage Cars
    </a>
    <a href="/GreenCrescent_Rentals/frontend/admin/bookings/manage_bookings.php" class="<?php echo $__inBookings ? 'active' : ''; ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
        Bookings
    </a>
    <a href="/GreenCrescent_Rentals/frontend/admin/fares/manage_fares.php" class="<?php echo $__inFares ? 'active' : ''; ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
        Rental Fares
    </a>
</nav>