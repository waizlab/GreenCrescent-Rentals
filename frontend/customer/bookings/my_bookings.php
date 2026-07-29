<?php
include '../../../backend/db/db_connect.php';
include '../../../backend/auth/session_init.php';

if ($_SESSION['role'] !== 'customer') {
    header("Location: /GreenCrescent_Rentals/backend/users/login.php");
    exit;
}

// Fetch bookings of this user
$stmt = $conn->prepare("
    SELECT b.bid, c.carreg, c.make, c.model, b.start_date, b.end_date, b.total_fare, b.status
    FROM bookings b
    JOIN cars c ON b.car_id = c.cid
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
$stmt->bind_param("i", $_SESSION['uid']);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2">My Bookings</h1>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Car</th>
                    <th>Reg</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Total Fare</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($b = $result->fetch_assoc()): ?>
                <?php
                    $badgeClass = match(strtolower($b['status'])) {
                        'pending' => 'badge-warning',
                        'confirmed' => 'badge-info',
                        'completed' => 'badge-success',
                        'cancelled' => 'badge-alert',
                        default => ''
                    };
                ?>
                <tr>
                    <td><?= htmlspecialchars($b['make'].' '.$b['model']); ?></td>
                    <td class="mono"><?= htmlspecialchars($b['carreg']); ?></td>
                    <td class="mono"><?= htmlspecialchars($b['start_date']); ?></td>
                    <td class="mono"><?= htmlspecialchars($b['end_date']); ?></td>
                    <td class="mono">PKR <?= number_format($b['total_fare'], 2); ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($b['status']); ?></span></td>
                    <td>
                        <?php if ($b['status'] === 'pending'): ?>
                            <a href="cancel_booking.php?bid=<?= $b['bid']; ?>" class="btn btn-danger btn-inline" style="padding:6px 14px; font-size:0.75rem;">Cancel</a>
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