<?php
include '../../../backend/auth/admin_guard.php';
include '../../../backend/db/db_connect.php';

// Fetch all cars
$carsQuery = $conn->query("SELECT * FROM cars");
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>


<div class="dashboard-container">
    <h1>Manage Cars</h1>

    <div class="add-car-btn-container">
        <a href="add_car.php" class="add-car-btn">➕ Add New Car</a>
    </div>

    <div class="cars-table-container">
        <table class="cars-table">
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
                    <td><?php echo $car['cid']; ?></td>
                    <td><?php echo htmlspecialchars($car['carreg']); ?></td>
                    <td><?php echo htmlspecialchars($car['make']); ?></td>
                    <td><?php echo htmlspecialchars($car['model']); ?></td>
                    <td><?php echo $car['year']; ?></td>
                    <td><?php echo htmlspecialchars($car['type']); ?></td>
                    <td>
                        <?php 
                        $availColor = $car['availability'] === 'available' ? '#27ae60' : '#c0392b';
                        ?>
                        <span class="status-label" style="background:<?php echo $availColor; ?>;">
                            <?php echo ucfirst($car['availability']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_car.php?cid=<?php echo $car['cid']; ?>" class="action-btn edit" title="Edit Car">✏️</a>
                        <a href="delete_car.php?cid=<?php echo $car['cid']; ?>" onclick="return confirm('Are you sure?')" class="action-btn delete" title="Delete Car">🗑️</a>
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
    margin-bottom: 25px;
}

.add-car-btn-container {
    text-align: right;
    margin-bottom: 15px;
}

.add-car-btn {
    background: #228B22;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: transform 0.2s, background 0.3s;
}

.add-car-btn:hover {
    background: #2C3E50;
    transform: scale(1.05);
}

.cars-table-container {
    overflow-x: auto;
}

.cars-table {
    width: 100%;
    border-collapse: collapse;
    background: #F5FFFA;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.cars-table th, .cars-table td {
    padding: 12px 15px;
    text-align: center;
}

.cars-table th {
    background: #228B22;
    color: white;
    font-family: 'Montserrat', sans-serif;
}

.cars-table tr:nth-child(even) {
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
    margin: 0 4px;
    padding: 6px 10px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    transition: transform 0.2s;
}

.action-btn.edit { background: #2980b9; }
.action-btn.delete { background: #c0392b; }

.action-btn:hover {
    transform: scale(1.1);
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
