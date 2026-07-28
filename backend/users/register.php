<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/backend/db/db_connect.php';

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

<?php include $_SERVER['DOCUMENT_ROOT'].'/GreenCrescent_Rentals/frontend/common/header.php'; ?>

<div class="form-container">
    <h2>Register</h2>

    <?php if($error): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success-msg"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Name</label>
        <input type="text" name="name" placeholder="Full Name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="example@mail.com" required>

        <label>Phone</label>
        <input type="text" name="phoneno" placeholder="11 digits" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="********" required>

        <button type="submit">Register</button>
    </form>

    <div class="redirect-msg">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>

<style>
/* Container */
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

/* Error and Success Messages */
.error-msg {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}

.success-msg {
    color: #27ae60;
    text-align: center;
    margin-bottom: 15px;
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
