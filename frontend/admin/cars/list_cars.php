<?php
include '../../../backend/auth/admin_guard.php';
include '../../../backend/db/db_connect.php';

// list_cars.php
$carsQuery = $conn->query("SELECT * FROM cars");
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2">Manage Cars</h1>

    <div class="flex" style="justify-content:flex-end; margin-bottom:16px;">
        <a href="add_car.php" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Add New Car
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reg</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($car = $carsQuery->fetch_assoc()): ?>
                <tr>
                    <td class="mono"><?php echo $car['cid']; ?></td>
                    <td><?php echo htmlspecialchars($car['carreg']); ?></td>
                    <td><?php echo htmlspecialchars($car['make']); ?></td>
                    <td><?php echo htmlspecialchars($car['model']); ?></td>
                    <td class="mono"><?php echo $car['year']; ?></td>
                    <td><?php echo htmlspecialchars($car['type']); ?></td>
                    <td>
                        <?php $__ok = $car['availability'] === 'available'; ?>
                        <span class="badge <?php echo $__ok ? 'badge-success' : 'badge-alert'; ?>">
                            <span class="led <?php echo $__ok ? 'led-online' : 'led-alert'; ?>"></span>
                            <?php echo ucfirst($car['availability']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_car.php?cid=<?php echo $car['cid']; ?>" class="btn btn-ghost btn-inline" title="Edit Car" style="padding:6px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                        </a>
                        <a href="delete_car.php?cid=<?php echo $car['cid']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-ghost btn-inline" title="Delete Car" style="padding:6px; color:var(--status-alert);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>