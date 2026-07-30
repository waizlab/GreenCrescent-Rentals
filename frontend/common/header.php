<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenCrescent Rentals</title>
    <link rel="stylesheet" href="/frontend/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <div class="logo-group">
        <img src="/frontend/images/logo.png" alt="GreenCrescent Rentals logo">
        <h1>GreenCrescent Rentals</h1>
    </div>
    <?php if (isset($_SESSION['uid'])): ?>
        <div class="user-area">
            <span><span class="led led-online"></span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="/backend/users/logout.php" class="btn btn-ghost btn-inline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                Logout
            </a>
        </div>
    <?php endif; ?>
</header>