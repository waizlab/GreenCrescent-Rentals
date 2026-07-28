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
            $success = "✅ Car updated successfully!";
            $car = array_merge($car, $_POST); // Update current values
        } else {
            $error = "❌ Failed to update car. Please try again.";
        }
    } else {
        $error = "⚠️ All fields are required.";
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/admin/common/navbar.php'; ?>

<div class="dashboard-container">
    <h1>Edit Car Details</h1>

    <div class="form-container">
        <?php if($error): ?>
            <div class="form-alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="form-alert success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Registration Number <span>📛</span></label>
            <input type="text" name="carreg" placeholder="ABC-123" value="<?php echo htmlspecialchars($car['carreg']); ?>" required>

            <label>Make <span>🏭</span></label>
            <input type="text" name="make" placeholder="Toyota" value="<?php echo htmlspecialchars($car['make']); ?>" required>

            <label>Model <span>🚗</span></label>
            <input type="text" name="model" placeholder="Corolla" value="<?php echo htmlspecialchars($car['model']); ?>" required>

            <label>Year <span>📅</span></label>
            <input type="number" name="year" placeholder="2022" value="<?php echo htmlspecialchars($car['year']); ?>" required>

            <label>Type <span>🚙</span></label>
            <select name="type" required>
                <option value="">Select Type</option>
                <?php
                $types = ['sedan','crossover','hatchback','suv','truck'];
                foreach ($types as $t) {
                    $sel = ($t === $car['type']) ? "selected" : "";
                    echo "<option value='$t' $sel>$t</option>";
                }
                ?>
            </select>

            <label>Image URL <span>🖼️</span></label>
            <input type="text" name="image" placeholder="car_image.png" value="<?php echo htmlspecialchars($car['image']); ?>">

            <button type="submit">✏️ Update Car</button>
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

.form-alert.success {
    background: #d4edda;
    color: #155724;
}

.form-alert.error {
    background: #f8d7da;
    color: #721c24;
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
