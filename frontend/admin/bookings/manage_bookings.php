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

<div class="container section">
    <h1 class="text-center mb-2">Manage Bookings</h1>

    <div class="table-wrap">
        <table>
            <thead>
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
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <?php
                    $badgeClass = match($row['status']) {
                        'pending' => 'badge-warning',
                        'confirmed' => 'badge-info',
                        'completed' => 'badge-success',
                        'cancelled' => 'badge-alert',
                        default => ''
                    };
                ?>
                <tr>
                    <td class="mono"><?= $row['bid'] ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['make']) ?> <?= htmlspecialchars($row['model']) ?> (<?= htmlspecialchars($row['carreg']) ?>)</td>
                    <td class="mono"><?= $row['start_date'] ?></td>
                    <td class="mono"><?= $row['end_date'] ?></td>
                    <td class="mono"><?= number_format($row['total_fare']) ?> PKR</td>
                    <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($row['status']) ?></span></td>
                    <td class="flex gap-1" style="justify-content:center;">
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=confirm" class="btn btn-ghost btn-inline" title="Confirm" style="padding:6px; color:var(--status-online);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>
                            </a>
                            <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=cancel" class="btn btn-ghost btn-inline" title="Cancel" style="padding:6px; color:var(--status-alert);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                            </a>
                        <?php elseif($row['status'] == 'confirmed'): ?>
                            <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=complete" class="btn btn-ghost btn-inline" title="Complete" style="padding:6px; color:var(--status-online);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                            </a>
                            <a href="update_booking.php?bid=<?= $row['bid'] ?>&action=cancel" class="btn btn-ghost btn-inline" title="Cancel" style="padding:6px; color:var(--status-alert);">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                            </a>
                        <?php else: ?>
                            <span class="text-muted mono">N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>