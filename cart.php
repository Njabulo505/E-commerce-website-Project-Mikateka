<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    header('location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'];

// Handle remove item action
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $sql = "DELETE FROM cart WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $item_id, $user_name);
    $stmt->execute();
    $stmt->close();
}

// Query to get the cart items for the logged-in user
$sql = "SELECT * FROM cart WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Fetch user's order history
$sql = "SELECT * FROM orders WHERE username = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $user_name);
$stmt->execute();
$result = $stmt->get_result();
$order_history = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
<section id="header">
    <h1 class="logo">Mikateka.com</h1>
    <ul id="nbar">
        <li><a href="index.php">HOME</a></li>
        <li><a class="active" href="shop.php">SHOP</a></li>
        <li><a href="about.html">ABOUT US</a></li>
        <li><a href="contact.html">CONTACT</a></li>
    </ul>
</section>
<div class="form-element">
    <h1>Hello <strong><?php echo $_SESSION["user_name"]; ?></strong></h1>
</div>
<section id="cart">
    <h1>Your Cart</h1>
    <ul>
        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): ?>
                <li>
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                    <span>R<?php echo htmlspecialchars($item['price']); ?></span>
                    <span>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></span>
                    <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="remove-btn">Remove</a>
                </li>
                <?php $total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Your cart is empty.</li>
        <?php endif; ?>
    </ul>
    <?php if (!empty($cart_items)): ?>
        <div class="total">
            <p>Total: R<?php echo number_format($total, 2); ?></p>
        </div>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
</section>

<section id="order-history">
    <h1>Your Order History</h1>
    <ul>
        <?php if (!empty($order_history)): ?>
            <?php foreach ($order_history as $order): ?>
                <li>
                    <span>Order ID: <?php echo htmlspecialchars($order['id']); ?></span>
                    <span>Date: <?php echo htmlspecialchars($order['order_date']); ?></span>
                    <span>Total: R<?php echo htmlspecialchars($order['total']); ?></span>
                    <span>Status: <?php echo htmlspecialchars($order['status']); ?></span>
                    <!--  <a href="order_details.php?id=<?php echo $order['id']; ?>">View Details</a>-->
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>You have no order history.</li>
        <?php endif; ?>
    </ul>
</section>

<p><a href="logout.php" role="button" aria-pressed="true" style="align-items:center">Log Out</a></p>
</body>
</html>
