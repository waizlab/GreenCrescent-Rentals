<?php
include '../../../backend/db/db_connect.php';
include '../../../backend/auth/admin_guard.php';

$edit_id = intval($_GET['edit'] ?? 0);
$edit_price = 0;
$edit_car_id = 0;

if ($edit_id) {
    $stmt = $conn->prepare("SELECT car_id, price_per_day FROM rental_fares WHERE rid=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        $edit_car_id = $result['car_id'];
        $edit_price  = $result['price_per_day'];
    }
}

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = intval($_POST['car_id'] ?? 0);
    $price  = intval($_POST['price_per_day'] ?? 0);

    if ($car_id && $price > 0) {
        if ($edit_id) {
            $stmt = $conn->prepare("UPDATE rental_fares SET car_id=?, price_per_day=? WHERE rid=?");
            $stmt->bind_param("iii", $car_id, $price, $edit_id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO rental_fares (car_id, price_per_day) VALUES (?, ?)");
            $stmt->bind_param("ii", $car_id, $price);
            $stmt->execute();
        }
        header("Location: manage_fares.php");
        exit;
    } else {
        $error = "⚠️ Select a car and enter a valid price.";
    }
}

// Fetch cars for dropdown
$cars = $conn->query("SELECT cid, make, model FROM cars");
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>
<div class="dashboard-container">
    <h1><?= $edit_id ? 'Edit' : 'Add' ?> Rental Fare</h1>

    <div class="form-container">
        <?php if($error): ?>
            <div class="form-alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Car <span>🚗</span></label>
            <select name="car_id" required>
                <option value="">Select Car</option>
                <?php while($car = $cars->fetch_assoc()): ?>
                    <option value="<?= $car['cid'] ?>" <?= ($car['cid']==$edit_car_id)?'selected':'' ?>>
                        <?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Price per Day <span>💰</span></label>
            <input type="number" name="price_per_day" value="<?= $edit_price ?>" placeholder="Enter price in PKR" required>

            <button type="submit"><?= $edit_id ? '✏️ Update' : '➕ Add' ?> Fare</button>
        </form>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 500px;
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

.form-container {
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

.form-container label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 14px;
    color: #2E2E2E;
}

.form-container label span {
    margin-left: 6px;
}

.form-container input,
.form-container select {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 15px;
    border: 1px solid #B2BEB5;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

.form-container input:focus,
.form-container select:focus {
    border-color: #228B22;
    outline: none;
}

.form-container button {
    width: 100%;
    background: #228B22;
    color: white;
    font-weight: bold;
    padding: 12px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.form-container button:hover {
    background: #2C3E50;
    transform: scale(1.02);
}

.form-alert {
    padding: 10px 15px;
    margin-bottom: 15px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
}

.form-alert.error {
    background: #f8d7da;
    color: #721c24;
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
