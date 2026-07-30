<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/backend/db/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT uid, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || $password !== $user['password']) {
            $error = "Invalid email or password.";
        } else {
            $_SESSION['uid']  = $user['uid'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: /frontend/admin/dashboard.php");
            } else {
                header("Location: /frontend/customer/dashboard.php");
            }
            exit;
        }
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/header.php'; ?>

<div class="container section">
    <div class="card form-card">
        <h1 class="text-center mb-2">Login</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><span class="led led-alert"></span><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="form-wide">
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
                Login
            </button>
        </form>

        <p class="text-center text-muted mt-2" style="font-size:0.875rem;">
            Don't have an account? <a href="register.php">Register</a>
        </p>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/frontend/common/footer.php'; ?>