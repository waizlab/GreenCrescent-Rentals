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
<div class="dashboard-container">
    <h1>My Bookings</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>🚗 Car</th>
                    <th>📛 Reg</th>
                    <th>📅 Start</th>
                    <th>📅 End</th>
                    <th>💰 Total Fare</th>
                    <th>📌 Status</th>
                    <th>⚙️ Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($b = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($b['make'].' '.$b['model']); ?></td>
                    <td><?= htmlspecialchars($b['carreg']); ?></td>
                    <td><?= htmlspecialchars($b['start_date']); ?></td>
                    <td><?= htmlspecialchars($b['end_date']); ?></td>
                    <td>PKR <?= number_format($b['total_fare'], 2); ?></td>
                    <td>
                        <span class="status-badge <?= strtolower($b['status']); ?>">
                            <?= ucfirst($b['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($b['status'] === 'pending'): ?>
                            <a href="cancel_booking.php?bid=<?= $b['bid']; ?>" class="btn cancel-btn">Cancel</a>
                        <?php else: ?>
                            N/A
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
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    font-family: 'Lato', sans-serif;
}

.dashboard-container h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    color: #228B22;
    text-align: center;
    margin-bottom: 25px;
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

.status-badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: bold;
    font-size: 13px;
    color: white;
    text-transform: capitalize;
}

.status-badge.pending { background: #ffc107; }
.status-badge.confirmed { background: #28a745; }
.status-badge.cancelled { background: #dc3545; }

.btn {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-weight: bold;
    font-size: 14px;
    transition: transform 0.2s, background 0.3s;
}

.cancel-btn {
    background: #dc3545;
}

.cancel-btn:hover {
    background: #a71d2a;
    transform: scale(1.05);
}

@media (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }
    thead tr {
        display: none;
    }
    tbody td {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    tbody td::before {
        content: attr(data-label);
        font-weight: bold;
    }
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
