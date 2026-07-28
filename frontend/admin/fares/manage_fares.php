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

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>

<div class="dashboard-container">
    <h1>Manage Rental Fares</h1>

    <div class="table-actions">
        <a href="add_fare.php" class="btn add-btn">➕ Add New Fare</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>🚗 Car</th>
                    <th>💰 Price per Day</th>
                    <th>⚙️ Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['make']) ?> <?= htmlspecialchars($row['model']) ?></td>
        <td>PKR <?= number_format($row['price_per_day'], 2) ?></td>
        <td>
            <a href="add_fare.php?edit=<?= $row['rid'] ?>" class="btn edit-btn">✏️ Edit</a>
            <a href="manage_fares.php?delete=<?= $row['rid'] ?>" class="btn delete-btn" onclick="return confirm('Delete this fare?')">🗑️ Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>

        </table>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    font-family: 'Lato', sans-serif;
}

.dashboard-container h1 {
    text-align: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    color: #228B22;
    margin-bottom: 25px;
}

.table-actions {
    text-align: right;
    margin-bottom: 15px;
}

.btn {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: transform 0.2s, background 0.3s;
}

.add-btn {
    background: #228B22;
    color: white;
}

.add-btn:hover {
    background: #2C3E50;
    transform: scale(1.05);
}

.edit-btn {
    background: #007BFF;
    color: white;
    margin-right: 5px;
}

.edit-btn:hover {
    background: #0056b3;
    transform: scale(1.05);
}

.delete-btn {
    background: #DC3545;
    color: white;
}

.delete-btn:hover {
    background: #a71d2a;
    transform: scale(1.05);
}

.table-container {
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

thead th {
    text-align: left;
    padding: 12px;
    background: #f0f0f0;
    color: #2E2E2E;
}

tbody td {
    padding: 12px;
    border-bottom: 1px solid #e0e0e0;
}

tbody tr:hover {
    background: #f9f9f9;
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
