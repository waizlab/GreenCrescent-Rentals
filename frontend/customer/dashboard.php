<?php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../backend/users/login.php");
    exit;
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>

<div class="dashboard-container">
    <h1>Customer Dashboard</h1>
    <p class="welcome-msg">Welcome, <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>!</p>

    <div class="card-container">
        <?php
        // Define dashboard cards dynamically (same theme color)
        $cards = [
            ['label' => 'Browse Available Cars', 'icon' => '🚗', 'link' => 'cars/browse_cars.php'],
            ['label' => 'My Bookings', 'icon' => '📖', 'link' => 'bookings/my_bookings.php']
        ];

        foreach ($cards as $card):
        ?>
        <a href="<?= $card['link'] ?>" class="dashboard-card">
            <span class="card-icon"><?= $card['icon'] ?></span>
            <span class="card-label"><?= $card['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    font-family: 'Lato', sans-serif;
    text-align: center;
}

.dashboard-container h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 32px;
    color: #228B22;
    margin-bottom: 15px;
}

.welcome-msg {
    font-size: 18px;
    color: #2E2E2E;
    margin-bottom: 30px;
}

/* Dynamic flex container */
.card-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

/* Cards adapt dynamically */
.dashboard-card {
    flex: 1 1 250px; /* Grow and shrink dynamically, min width 250px */
    max-width: 300px;
    background: #228B22;
    color: white;
    border-radius: 12px;
    padding: 25px 20px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, box-shadow 0.3s;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.dashboard-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-icon {
    font-size: 36px;
    margin-bottom: 12px;
}

.card-label {
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dashboard-card {
        flex: 1 1 45%; /* Two cards per row */
    }
}

@media (max-width: 480px) {
    .dashboard-card {
        flex: 1 1 100%; /* Full width cards on mobile */
    }
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
