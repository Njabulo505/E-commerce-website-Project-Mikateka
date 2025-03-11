<?php
session_start();

if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    header('location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="success.css">
</head>
<body>
<header>
    <h1>Mikateka.com</h1>
</header>

<main>
    <div class="success-container">
        <h2>Order Successful!</h2>
        <p>Thank you, <strong><?php echo htmlspecialchars($user_name); ?></strong>, for your purchase. Your order has been placed successfully.</p>
        <p>You will receive a confirmation email shortly.</p>
        <a href="index.php" class="btn">Continue Shopping</a>
    </div>
</main>
</body>
</html>
