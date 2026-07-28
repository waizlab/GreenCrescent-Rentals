<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/backend/db/db_connect.php';

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
                header("Location: /GreenCrescent_Rentals/frontend/admin/dashboard.php");
            } else {
                header("Location: /GreenCrescent_Rentals/frontend/customer/dashboard.php");
            }
            exit;
        }
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>

<div class="form-container">
    <h2>Login</h2>

    <?php if(!empty($error)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
    </form>

    <div class="redirect-msg">
        Don't have an account? <a href="register.php">Register</a>
    </div>
</div>

<style>
/* Form container */
.form-container {
    width: 90%;
    max-width: 400px;
    margin: 5vh auto;
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    font-family: 'Lato', sans-serif;
}

/* Heading */
.form-container h2 {
    text-align: center;
    color: #228B22;
    margin-bottom: 25px;
    font-family: 'Montserrat', sans-serif;
}

/* Labels */
.form-container label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    font-size: 14px;
}

/* Inputs */
.form-container input {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 12px;
    border: 1px solid #B2BEB5;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}

/* Button */
.form-container button {
    width: 100%;
    background: #228B22;
    color: white;
    font-weight: bold;
    padding: 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.3s;
}

.form-container button:hover {
    background: #2C3E50;
}

/* Error messages */
.error-msg {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}

/* Redirect text */
.redirect-msg {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.redirect-msg a {
    color: #228B22;
    font-weight: bold;
    text-decoration: none;
}

/* Responsive */
@media screen and (max-width: 480px) {
    .form-container {
        padding: 20px;
    }
}
</style>

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/footer.php'; ?>
