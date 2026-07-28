<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GreenCrescent Rentals</title>
    <link rel="stylesheet" href="/GreenCrescent_Rentals/frontend/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Lato&display=swap" rel="stylesheet">
</head>
<body>
<header style="background:#3C9D3C; color:white; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
    <div class="logo" style="display:flex; align-items:center;">
        <img src="/GreenCrescent_Rentals/frontend/images/logo.png" 
             alt="Logo" style="height:60px; margin-right:15px;">
        <h1 style="margin:0; font-family:'Montserrat', sans-serif; font-size:28px; color:#F5FFFA; font-weight:bold;">
            GreenCrescent Rentals
        </h1>
    </div>
    <?php if(isset($_SESSION['uid'])): ?>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
            <a href="/GreenCrescent_Rentals/backend/users/logout.php" 
               style="
                   margin-left:15px; 
                   text-decoration:none; 
                   font-weight:bold; 
                   padding:6px 12px; 
                   border-radius:5px; 
                   background:#B2BEB5; 
                   color:white;
                   transition: background 0.3s;
               "
               onmouseover="this.style.background='#2C3E50';" 
               onmouseout="this.style.background='#B2BEB5';">
               Logout
            </a>
        </div>
    <?php endif; ?>
</header>
