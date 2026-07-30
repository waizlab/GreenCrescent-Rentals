<?php
include '../../../backend/auth/admin_guard.php';
include '../../../backend/db/db_connect.php';

$cid = $_GET['cid'] ?? 0;
if (!$cid) die("Car ID missing.");

// Fetch existing car
$stmt = $conn->prepare("SELECT * FROM cars WHERE cid = ?");
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
if (!$car) die("Car not found.");

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carreg = trim($_POST['carreg'] ?? '');
    $make   = trim($_POST['make'] ?? '');
    $model  = trim($_POST['model'] ?? '');
    $year   = trim($_POST['year'] ?? '');
    $type   = trim($_POST['type'] ?? '');
    $image  = trim($_POST['image'] ?? '');

    if ($carreg && $make && $model && $year && $type) {
        $update = $conn->prepare("UPDATE cars SET carreg=?, make=?, model=?, year=?, type=?, image=? WHERE cid=?");
        $update->bind_param("sssissi", $carreg, $make, $model, $year, $type, $image, $cid);
        if ($update->execute()) {
            $success = "Car updated successfully!";
            $car = array_merge($car, $_POST); // Update current values
        } else {
            $error = "Failed to update car. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/admin/common/navbar.php'; ?>

<div class="container section">
    <h1 class="text-center mb-2">Edit Car Details</h1>

    <div class="card form-card">
        <?php if($error): ?>
            <div class="alert alert-error"><span class="led led-alert"></span><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><span class="led led-online"></span><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="post" class="form-wide">
            <div class="field">
                <label>Registration Number</label>
                <input type="text" name="carreg" placeholder="ABC-123" value="<?php echo htmlspecialchars($car['carreg']); ?>" required>
            </div>
            <div class="field">
                <label>Make</label>
                <input type="text" name="make" placeholder="Toyota" value="<?php echo htmlspecialchars($car['make']); ?>" required>
            </div>
            <div class="field">
                <label>Model</label>
                <input type="text" name="model" placeholder="Corolla" value="<?php echo htmlspecialchars($car['model']); ?>" required>
            </div>
            <div class="field">
                <label>Year</label>
                <input type="number" name="year" placeholder="2022" value="<?php echo htmlspecialchars($car['year']); ?>" required>
            </div>
            <div class="field">
                <label>Type</label>
                <select name="type" required>
                    <option value="">Select Type</option>
                    <?php
                    $types = ['sedan','crossover','hatchback','suv','truck'];
                    foreach ($types as $t) {
                        $sel = ($t === $car['type']) ? "selected" : "";
                        echo "<option value='$t' $sel>".ucfirst($t)."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="field">
                <label>Image URL</label>
                <input type="text" name="image" placeholder="car_image.png" value="<?php echo htmlspecialchars($car['image']); ?>">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                Update Car
            </button>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>