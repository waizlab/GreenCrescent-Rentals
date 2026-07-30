<?php
include '../../../backend/db/db_connect.php';
include '../../../backend/auth/admin_guard.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $rid = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM rental_fares WHERE rid=?");
    $stmt->bind_param("i", $rid);
    $stmt->execute();
    header("Location: manage_fares.php");
    exit;
}

// Fetch all fares with car info
$sql = "SELECT f.rid, c.make, c.model, f.price_per_day
        FROM rental_fares f
        JOIN cars c ON f.car_id = c.cid
        ORDER BY c.make, c.model";
$result = $conn->query($sql);
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/admin/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2">Manage Rental Fares</h1>

    <div class="flex" style="justify-content:flex-end; margin-bottom:16px;">
        <a href="add_fare.php" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add New Fare
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Car</th>
                    <th>Price per Day</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['make']) ?> <?= htmlspecialchars($row['model']) ?></td>
        <td class="mono">PKR <?= number_format($row['price_per_day'], 2) ?></td>
        <td class="flex gap-1" style="justify-content:center;">
            <a href="add_fare.php?edit=<?= $row['rid'] ?>" class="btn btn-ghost btn-inline" title="Edit Fare" style="padding:6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
            </a>
            <a href="manage_fares.php?delete=<?= $row['rid'] ?>" class="btn btn-ghost btn-inline" title="Delete Fare" onclick="return confirm('Delete this fare?')" style="padding:6px; color:var(--status-alert);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>
        </table>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>