<?php
include '../../../backend/auth/admin_guard.php';
include '../../../backend/db/db_connect.php';

// Fetch all bookings using the view
$bookings = $conn->query("SELECT * FROM booking_summary");
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>


<div class="dashboard-container">
    <h1>All Bookings</h1>

    <div class="bookings-table-container">
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Car</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Total Fare</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($b = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $b['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($b['email']); ?></td>
                    <td><?php echo htmlspecialchars($b['carreg'].' | '.$b['make'].' '.$b['model']); ?></td>
                    <td><?php echo $b['start_date']; ?></td>
                    <td><?php echo $b['end_date']; ?></td>
                    <td><?php echo number_format($b['total_fare']); ?> PKR</td>
                    <td>
                        <?php 
                        $statusColor = match($b['status']) {
                            'pending' => '#f39c12',
                            'confirmed' => '#2980b9',
                            'completed' => '#27ae60',
                            'cancelled' => '#c0392b',
                            default => '#7f8c8d'
                        };
                        ?>
                        <span class="status-label" style="background:<?php echo $statusColor; ?>;">
                            <?php echo ucfirst($b['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if($b['status'] === 'pending'): ?>
                            <a href="update_booking.php?bid=<?php echo $b['booking_id']; ?>&action=confirm" class="action-btn confirm" title="Confirm Booking">✔️</a>
                            <a href="update_booking.php?bid=<?php echo $b['booking_id']; ?>&action=cancel" class="action-btn cancel" title="Cancel Booking">❌</a>
                        <?php elseif($b['status'] === 'confirmed'): ?>
                            <a href="update_booking.php?bid=<?php echo $b['booking_id']; ?>&action=complete" class="action-btn complete" title="Mark Complete">✅</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
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
    margin-bottom: 30px;
}

.bookings-table-container {
    overflow-x: auto;
}

.bookings-table {
    width: 100%;
    border-collapse: collapse;
    background: #F5FFFA;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.bookings-table th, .bookings-table td {
    padding: 12px 15px;
    text-align: center;
}

.bookings-table th {
    background: #228B22;
    color: white;
    font-family: 'Montserrat', sans-serif;
}

.bookings-table tr:nth-child(even) {
    background: #eafaf1;
}

.status-label {
    display: inline-block;
    padding: 5px 10px;
    color: white;
    border-radius: 6px;
    font-weight: bold;
    font-size: 13px;
}

.action-btn {
    display: inline-block;
    margin: 0 3px;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    transition: transform 0.2s;
}

.action-btn.confirm { background: #2980b9; }
.action-btn.cancel { background: #c0392b; }
.action-btn.complete { background: #27ae60; }

.action-btn:hover {
    transform: scale(1.1);
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
