<?php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../backend/users/login.php");
    exit;
}
// frontend/customer/dashboard.php
?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>

<div class="container section text-center">
    <h1>Customer Dashboard</h1>
    <p class="text-muted mb-3">Welcome, <strong style="color:var(--text);"><?= htmlspecialchars($_SESSION['name']); ?></strong>!</p>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 300px)); justify-content: center;">
        <a href="cars/browse_cars.php" class="card card-screws" style="color:inherit; display:flex; flex-direction:column; align-items:center; gap:12px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent);"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
            <span style="font-weight:700;">Browse Available Cars</span>
        </a>
        <a href="bookings/my_bookings.php" class="card card-screws" style="color:inherit; display:flex; flex-direction:column; align-items:center; gap:12px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--accent);"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
            <span style="font-weight:700;">My Bookings</span>
        </a>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>