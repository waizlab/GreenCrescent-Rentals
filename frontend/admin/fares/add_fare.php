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
        $error = "Select a car and enter a valid price.";
    }
}

// Fetch cars for dropdown
$cars = $conn->query("SELECT cid, make, model FROM cars");
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2"><?= $edit_id ? 'Edit' : 'Add' ?> Rental Fare</h1>

    <div class="card form-card">
        <?php if($error): ?>
            <div class="alert alert-error"><span class="led led-alert"></span><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" class="form-wide">
            <div class="field">
                <label>Car</label>
                <select name="car_id" required>
                    <option value="">Select Car</option>
                    <?php while($car = $cars->fetch_assoc()): ?>
                        <option value="<?= $car['cid'] ?>" <?= ($car['cid']==$edit_car_id)?'selected':'' ?>>
                            <?= htmlspecialchars($car['make']) ?> <?= htmlspecialchars($car['model']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="field">
                <label>Price per Day (PKR)</label>
                <input type="number" name="price_per_day" value="<?= $edit_price ?>" placeholder="Enter price in PKR" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <?php if ($edit_id): ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                    Update Fare
                <?php else: ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Add Fare
                <?php endif; ?>
            </button>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>