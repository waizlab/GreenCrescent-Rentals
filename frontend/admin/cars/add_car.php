<?php
include '../../../backend/auth/admin_guard.php';
include '../../../backend/db/db_connect.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carreg = trim($_POST['carreg'] ?? '');
    $make   = trim($_POST['make'] ?? '');
    $model  = trim($_POST['model'] ?? '');
    $year   = trim($_POST['year'] ?? '');
    $type   = trim($_POST['type'] ?? '');
    $image  = trim($_POST['image'] ?? '');

    if ($carreg && $make && $model && $year && $type) {
        $stmt = $conn->prepare("INSERT INTO cars (carreg, make, model, year, type, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $carreg, $make, $model, $year, $type, $image);
        if ($stmt->execute()) {
            $success = "Car added successfully!";
        } else {
            $error = "Error adding car. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/admin/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2">Add New Car</h1>

    <div class="card form-card">
        <?php if ($error): ?>
            <div class="alert alert-error">
                <span class="led led-alert"></span><?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">
                <span class="led led-online"></span><?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-wide">
            <div class="field">
                <label>Registration Number</label>
                <input type="text" name="carreg" placeholder="ABC-123" required>
            </div>
            <div class="field">
                <label>Make</label>
                <input type="text" name="make" placeholder="Toyota" required>
            </div>
            <div class="field">
                <label>Model</label>
                <input type="text" name="model" placeholder="Corolla" required>
            </div>
            <div class="field">
                <label>Year</label>
                <input type="number" name="year" placeholder="2022" required>
            </div>
            <div class="field">
                <label>Type</label>
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="sedan">Sedan</option>
                    <option value="crossover">Crossover</option>
                    <option value="hatchback">Hatchback</option>
                    <option value="suv">SUV</option>
                    <option value="truck">Truck</option>
                </select>
            </div>
            <div class="field">
                <label>Image URL</label>
                <input type="text" name="image" placeholder="car_image.png">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add Car
            </button>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>