<?php
include '../../../backend/db/db_connect.php';
include '../../../backend/auth/admin_guard.php';


// Fetch all bookings with customer and car info
$sql = "SELECT b.bid, u.name AS customer_name, u.email, c.carreg, c.make, c.model, 
               b.start_date, b.end_date, b.total_fare, b.status
        FROM bookings b
        JOIN users u ON b.user_id = u.uid
        JOIN cars c ON b.car_id = c.cid
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>

<h2>Manage Bookings</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Car</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Total Fare</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['bid'] ?></td>
        <td><?= $row['customer_name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['make'] ?> <?= $row['model'] ?> (<?= $row['carreg'] ?>)</td>
        <td><?= $row['start_date'] ?></td>
        <td><?= $row['end_date'] ?></td>
        <td><?= $row['total_fare'] ?></td>
        <td><?= $row['status'] ?></td>
        <td>
            <?php if($row['status'] == 'pending'): ?>
                <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=confirm">Confirm</a> |
                <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=cancel">Cancel</a>
            <?php elseif($row['status'] == 'confirmed'): ?>
                <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=complete">Complete</a> |
                <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=cancel">Cancel</a>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
