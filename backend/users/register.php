<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/backend/db/db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phoneno'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $phone === '' || $password === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
        $error = "Phone must be 11 digits.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $stmt = $conn->prepare("SELECT uid FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name,email,password,phoneno,role) VALUES (?,?,?,?, 'customer')");
            $stmt->bind_param("ssss", $name, $email, $password, $phone);
            if ($stmt->execute()) {
                $success = "Registration successful. You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>

<div class="container section">
    <div class="card form-card">
        <h1 class="text-center mb-2">Register</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><span class="led led-alert"></span><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><span class="led led-online"></span><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="form-wide">
            <div class="field">
                <label>Name</label>
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="example@mail.com" required>
            </div>
            <div class="field">
                <label>Phone</label>
                <input type="text" name="phoneno" placeholder="11 digits" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="********" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Register
            </button>
        </form>

        <p class="text-center text-muted mt-2" style="font-size:0.875rem;">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>